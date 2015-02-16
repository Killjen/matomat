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
    guicontroller.cpp

HEADERS  += introductionmenu.h \
    buymenu.h \
    completionmenu.h \
    dbentry.h \
    guicontroller.h

FORMS    += introductionmenu.ui \
    buymenu.ui \
    completionmenu.ui

CONFIG+= serialport
QT += sql
