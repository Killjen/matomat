#include "seriallistener.h"

SerialListener::SerialListener(QMutex* timerLock, QSerialPort *serialPort, QObject *parent) :
    p_timerLock(timerLock),
     QObject(parent),

{



    //_timer.start(5000);
}

SerialListener::~SerialListener(){

}




void SerialListener::startCompletionTimer(QString id){
    p_timer->setObjectName(id);
    p_timer->start();
}









