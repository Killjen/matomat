#ifndef SERIALLISTENER_H
#define SERIALLISTENER_H

#include "dbentry.h"
#include <QThread>
#include <QTimer>
#include <iostream>
#include <QtSerialPort/QSerialPort>
#include <QDebug>
#include <QtSql>

struct dbEntry;

class SerialListener :  public QObject
{
    Q_OBJECT
public:
    explicit SerialListener(QMutex* timerLock, QSerialPort *serialPort, QObject *parent = 0);
    ~SerialListener();

signals:
    void changeToIntroductionMenu();
    void changeToBuyMenu(dbEntry* p_entry);

private slots:
    void startCompletionTimer(QString id);


private:

    QMutex* p_timerLock;

    QString _currentID;



};
#endif // SERIALLISTENER_H
