#-------------------------------------------------
#
# Project created by QtCreator 2015-03-15T14:12:21
#
#-------------------------------------------------

QT       += core gui

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = Matomat
TEMPLATE = app


SOURCES += main.cpp\
        introductionmenu.cpp \
    buymenu.cpp \
    completionmenu.cpp \
    guicontroller.cpp \
    unknownidform.cpp \
    softServo.c

HEADERS  += introductionmenu.h \
    buymenu.h \
    completionmenu.h \
    dbentry.h \
    guicontroller.h \
    unknownidform.h \
    wiringPi.h \
    softServo.h

FORMS    += introductionmenu.ui \
    buymenu.ui \
    completionmenu.ui \
    unknownidform.ui

CONFIG+= serialport
QT += sql


unix:!macx:!symbian: LIBS += -L$$PWD/../../../../usr/lib/ -lwiringPi

INCLUDEPATH += $$PWD/../../../../usr/include
DEPENDPATH += $$PWD/../../../../usr/include
