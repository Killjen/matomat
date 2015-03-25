#include "guicontroller.h"
#include <QApplication>
#include <QImageReader>
#define PORTNAME "/dev/ttyACM0"

int createDBConnection();

int main(int argc, char *argv[])
{
    QApplication a(argc, argv);
    QSerialPort *serialPort = new QSerialPort();
    //Specify device manually
    serialPort->setPortName(PORTNAME);
    serialPort->setBaudRate(QSerialPort::Baud9600);
    GUIController *controller = new GUIController(serialPort);
    if (!serialPort->open(QIODevice::ReadOnly)) {
        qDebug() << "Failed to open port, error: " + serialPort->errorString();
        return 1;
    }
    qDebug() << QImageReader::supportedImageFormats() ;
    //connect to DB here, since all the other classes also need to have access to the DB
    createDBConnection();

    controller->displayIntroductionMenu();

    int r = a.exec();
    //never forgetti: close database
    QSqlDatabase::database().close();
    return r;
}

int createDBConnection(){
    QSqlDatabase db = QSqlDatabase::addDatabase("QMYSQL");
    db.setHostName("localhost");
    db.setDatabaseName("matomat");
    db.setUserName("matomat");
    db.setPassword("matomat94");
    if(!db.open()){
        qDebug() << "Database could not be opened";
        return 0;
    } else {
        return 1;
    }

}
