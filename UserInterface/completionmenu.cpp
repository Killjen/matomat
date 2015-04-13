#include "completionmenu.h"
#include "ui_completionmenu.h"

CompletionMenu::CompletionMenu(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::CompletionMenu)
{
    ui->setupUi(this);
    showFullScreen();
}

CompletionMenu::~CompletionMenu()
{
    delete ui;
}

void CompletionMenu::setUpCompletionMenu(QMutex* p_lock, dbEntry *p_entry, int buttonID){
    //buying process needs to be atomic, so the user doesnt get changed midway
    p_lock->lock();

    //determine what the user wants to buy exactly
    QSqlQuery query1;
    query1.exec("SELECT Price, ArticleName FROM stock WHERE ArticleID=" + QString::number(buttonID) + ";");
    if(query1.size()!=1){
        qDebug() << "there has to be some bug/race condition with this article";
        return;
    }
    query1.next();

    float price = query1.value(0).toFloat();
    qDebug() << "price is " + QString::number(price);

    //conduct actual purchase
    QSqlQuery query2;
    query2.exec("UPDATE users SET Balance = Balance - " + QString::number(price) + " WHERE UserID='" + p_entry->userID + "'");
    if (query2.numRowsAffected() <= 0){
        qDebug() << "failed to update balance";
    }

    //update transactions table
    QSqlQuery query3;
    QString strTime = QDateTime::currentDateTime().toString("yyyy-MM-dd hh:mm:ss");
    QString str =  "INSERT INTO transactions (Username, ArticleID, ArticleName, Payed, Time) "
                   "VALUES ('" + p_entry->username + "', " + QString::number(buttonID) + ", '"
                   + query1.value(1).toString() +"', " + QString::number(price) + ", '" + strTime + "')";
    query3.exec(str);
    //may fail bc feature not available
    if (query3.numRowsAffected()==0){
        qDebug() << "failed to update transaction log";
    }

    //update stock table
    QSqlQuery query4;
    query4.exec("UPDATE stock SET Quantity = Quantity - 1 WHERE ArticleID='" + QString::number(buttonID) + "'");
    if (query4.numRowsAffected() <= 0){
        qDebug() << "failed to update stock";
    }

    //servo control goes here


    softServoWrite(0, 200);
    delay(1000);
    softServoWrite(0,0);
    delay(1000);
    softServoWrite(0,200);


    p_lock->unlock();
}
