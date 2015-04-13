#ifndef BUYMENU_H
#define BUYMENU_H

#include "dbentry.h"
#include <QWidget>
#include <QtSql>
#include <QPushButton>
#include <QList>
#include <QLabel>
#include <QButtonGroup>

namespace Ui {
class BuyMenu;
}

class BuyMenu : public QWidget
{
    Q_OBJECT

public:
    explicit BuyMenu(QWidget *parent = 0);
    ~BuyMenu();
    void setUpBuyMenu(dbEntry* p_entry);


//signal and slot only exist to add the buttonID to our clicked()-signal (
//we need to use "connect" in the BuyMenu-Class, since we connect for every button created at runtime) and we need a signal that we can connect from from the GUIController
signals:
    void changeToCompletionMenu(int buttonID);
    void changeToIntroductionMenu();

private slots:
    void emitDisplayCompletionMenu();
    void on_backButton_clicked();

private:
    Ui::BuyMenu *ui;
    //We are using pointers here since most QObjects can't be copied and therefore not added to a container
    QList<QPushButton*> _buttonList;
    QList<QLabel*>      _labelList;
    QList<bool>         _resizableList;
    bool eventFilter(QObject *, QEvent *);
    void fillLayout();
};

#endif // BUYMENU_H
