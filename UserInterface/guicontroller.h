#ifndef GUICONTROLLER_H
#define GUICONTROLLER_H

#include <QObject>
#include <QMessageBox>
#include <QSerialPort>
#include "introductionmenu.h"
#include "buymenu.h"
#include "completionmenu.h"
#include "unknownidform.h"

class GUIController : public QObject
{
    Q_OBJECT
public:
    explicit GUIController(QSerialPort* serialPort, QObject *parent = 0);
    ~GUIController();

public slots:
    void displayIntroductionMenu();
    void displayBuyMenu();
    //buttonID is also the ID of the corresponding article in the DB
    void displayCompletionMenu(int buttonID);

private slots:
    void handleReadyRead();
    void handleBuyTimeout();
    void handleCompTimeout();
    void handleError(QSerialPort::SerialPortError error);
//possible problems: clicked() signal is sent, handleReadyRead changes user -> wrong transaction
//handleReadyRead gets new user and changesToBuyMenu, timeout reverts to introMenu
//clicked() signal is sent, buytimer timeout happens and changes user -> wrong transaction

    //best solution: the buying process is atomic, the changing of a user (handleReadyRead and timeouts) has to be atomic,
    //               in each atomic block, we have to determine if the block should still be executed
    //               purchasing block: is the user still the same
    //               timeout blocks: buytimer:is user still the same and is buyMenu still visible?
    //                              comptimer: is user still the same
    //               handleReadyRead: always valid, dont do anything if user doesnt change

private:
    dbEntry* p_entry;

    QMutex* p_lock;

    //note that the objectName attribute is used as a temporary storage for the corresponding userID
    QTimer* p_buyTimer;
    QTimer* p_compTimer;


    IntroductionMenu*   p_introMenu;
    BuyMenu*            p_buyMenu;
    CompletionMenu*     p_compMenu;
    UnknownIDForm*      p_unknownForm;
    //members for serial port
    QByteArray      _result;
    QSerialPort*    _serialPort;
    void getDbEntry(QString newID);
};

#endif // GUICONTROLLER_H
