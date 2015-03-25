#include "completionmenu.h"
#include "ui_completionmenu.h"

CompletionMenu::CompletionMenu(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::CompletionMenu)
{
    ui->setupUi(this);

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
    query1.exec("SELECT * FROM stock WHERE ArticleID=" + QString::number(buttonID) + ";");
    if(query1.size()!=1){
        qDebug() << "there has to be some bug/race condition with this article";
        return;
    }
    query1.next();

    float price = query1.value("Price").toFloat();
    qDebug() << "price is " + QString::number(price);

    //conduct actual purchase
    QSqlQuery query2;
    query2.exec("UPDATE users SET Balance = Balance - " + QString::number(price) + " WHERE UserID='" + p_entry->userID + "'");
    if (query2.numRowsAffected() == 0){
        qDebug() << "failed to update balance";
    }
    //update transactions table to-do: insert datetime correctly
    QSqlQuery query3;
    QString strTime = QDateTime::currentDateTime().toString("yyyy-MM-dd hh:mm:ss");
    QString str =  "INSERT INTO transactions (Username, ArticleID, ArticleName, Payed, Time) "
                   "VALUES ('" + p_entry->username + "', " + QString::number(buttonID) + ", '"
                   + query1.value("ArticleName").toString() +"', " + QString::number(price) + ", '" + strTime + "')";
    query3.exec(str);
    //may fail bc feature not available
    if (query3.numRowsAffected()==0){
        qDebug() << "failed to update transaction log";
    }

    p_lock->unlock();
}
