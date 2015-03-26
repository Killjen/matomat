#include "guicontroller.h"

GUIController::GUIController(QSerialPort* serialPort, QObject *parent) :
    p_lock(new QMutex),
    QObject(parent),
    p_introMenu(new IntroductionMenu()),
    p_buyMenu(new BuyMenu()),
    p_compMenu(new CompletionMenu()),
    p_entry(new dbEntry()),
    _serialPort(serialPort),
    p_buyTimer(new QTimer()),
    p_compTimer(new QTimer())
{


    connect(_serialPort,        SIGNAL(readyRead()),                        this,   SLOT(handleReadyRead()));
    connect(p_buyTimer,         SIGNAL(timeout()),                          this,   SLOT(handleBuyTimeout()));
    connect(p_compTimer,        SIGNAL(timeout()),                          this,   SLOT(handleCompTimeout()));
    connect(_serialPort,        SIGNAL(error(QSerialPort::SerialPortError)),this,   SLOT(handleError(QSerialPort::SerialPortError)));
    connect(this->p_buyMenu,    SIGNAL(changeToCompletionMenu(int)),        this,   SLOT(displayCompletionMenu(int)));
    p_entry->balance    = 0;
    p_entry->userID     = "";
    p_entry->username   = "";

    //set up timer for completion menu and buy menu
    p_buyTimer->setSingleShot(true);
    p_buyTimer->setInterval(10000);
    p_compTimer->setSingleShot(true);
    p_compTimer->setInterval(5000);

}

GUIController::~GUIController()
{
    _serialPort->close();
    delete p_introMenu;
    delete p_buyMenu;
    delete p_compMenu;
    delete p_entry;
    delete p_buyTimer;
    delete p_compTimer;
}

void GUIController::displayIntroductionMenu(){
    //clear data of previous user
    this->p_entry->username     =   "";
    this->p_entry->balance      =   0;
    this->p_entry->userID       =   "";
    p_introMenu->showFullScreen();
    p_buyMenu->hide();
    p_compMenu->hide();
    return;
}

//note that all of this is locked (through handleReadyRead)
void GUIController::displayBuyMenu(){
    //to-do: need to make sure that race-conditions are excluded: especially for the timer
    p_buyMenu->setUpBuyMenu(p_entry);

    //set up and start timer
    p_buyTimer->setObjectName(p_entry->userID);
    p_buyTimer->start();
    p_buyMenu->showFullScreen();
    p_introMenu->hide();
    p_compMenu->hide();
}

void GUIController::displayCompletionMenu(int buttonID){
    p_compMenu->setUpCompletionMenu(p_lock, p_entry, buttonID);

    //set up and start timer
    p_lock->lock();
        p_compTimer->setObjectName(p_entry->userID);
        p_compTimer->start();
    p_lock->unlock();

    p_compMenu->showFullScreen();
    p_introMenu->hide();
    p_buyMenu->hide();

}


void GUIController::handleReadyRead(){
    qDebug() << "Data is ready to be read\n";
    _result.append( _serialPort->readAll());
    qDebug() << _result;

    QRegExp rx(" ([0-9A-F]{8}) ");
    int pos = rx.indexIn(_result);
    if(pos == -1){
        return;
    }
    QString newID = rx.cap(1);
    qDebug() <<"newID: "<< newID;
    _result.clear();

    //dont change anything, if ID doesn't change
    if (p_entry->userID == newID){
        return;
    }

    //lock so there are no race conditions with timeouts and menu changes
    qDebug() << "lockreadyread";
    p_lock->lock();
        p_buyTimer->stop();
        p_compTimer->stop();
        //our current ID has changed to another known ID to-do: race condition (user1 buys, user2 changes ID and has to pay)
        p_entry->userID = newID;


    //get the corresponding DB entry if necessary (-> if the new ID is different from the current one)
    getDbEntry(newID);
    //ID was unknown to our System
    if(p_entry->username == ""){
        qDebug() << "unknown ID, no entry was returned";
    }else {
        displayBuyMenu();
    }
    p_lock->unlock();

}


void GUIController::handleBuyTimeout(){
    //timeout is useful if and only if the user hasn't changed (oldID is stored in objectName property) to-do: needs to be locked
    p_lock->lock();
        qDebug() << "buy menu timeout";

        //only reset if we are still in the buy menu and the user hasn't changed
        if(p_entry->userID == p_buyTimer->objectName() && p_buyMenu->isVisible()){
            qDebug() << "buy menu timeout carried out";

            displayIntroductionMenu();
        }
    p_lock->unlock();
}

void GUIController::handleCompTimeout(){
    //timeout is useful if and only if the user hasn't changed (oldID is stored in objectName property) to-do: needs to be locked
    p_lock->lock();
        qDebug() << "completion menu timeout username: ";

        //only reset if we the user hasn't changed
        if(p_entry->userID == p_compTimer->objectName()){
            qDebug() << "comp menu timeout carried out";

            displayIntroductionMenu();
        }
    p_lock->unlock();
}

void GUIController::handleError(QSerialPort::SerialPortError error){
    qDebug() << "There was some sort of error concerning the serial port:"<< _serialPort->errorString();
}

void GUIController::getDbEntry(QString newID){
    QSqlQuery query1;
    query1.exec("SELECT * FROM users where UserID='" + newID + "' ;");
    qDebug() << "size: " << query1.size();
    //react to unknown IDs (insert into unknown log) (query.size may not be available depending on db driver)
    if(query1.size() <= 0){
        query1.finish();
        QSqlQuery query2;
        qDebug() << "This ID is unknown, adding entry to unknown ID log";

        QDateTime rawTime = QDateTime::currentDateTime();

        //maybe need to convert parameter to QString here(or change the :
        QString strTime = rawTime.toString("yyyy-MM-dd hh:mm:ss");
        qDebug() << "insert unknown ID into log";
        query2.exec("INSERT INTO log (RFID, Time) "
                     "VALUES ('"+ newID + "', '"+ strTime +"' );");
        //may fail bc feature not available
        if (query2.numRowsAffected() == 0){
            qDebug() << "failed to update the unknown id log";
        }else{
            QMessageBox msgBox;
            msgBox.setText("Die ID Ihrer Karte ist (noch) unbekannt.\n Wenden Sie sich bitte an den Administrator um ihre Karte zu registrieren.");
            msgBox.exec();
        }
        //reset currentID and display IntroductionMenu since ID is unknown
        p_entry->username = "";
        displayIntroductionMenu();

    } else {
        query1.next();
        p_entry->username   = query1.value(0).toString();
        p_entry->balance    = query1.value(1).toFloat();
        p_entry->userID     = query1.value(2).toString();

        qDebug() << "current user has been updated";
    }


}
