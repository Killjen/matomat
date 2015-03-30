#ifndef INTRODUCTIONMENU_H
#define INTRODUCTIONMENU_H

#include <QMainWindow>
#include "dbentry.h"

namespace Ui {
class IntroductionMenu;
}

class IntroductionMenu : public QMainWindow
{
    Q_OBJECT

public:
    explicit IntroductionMenu(QWidget *parent = 0);
    ~IntroductionMenu();
    void showUnknownMessage();

private:
    Ui::IntroductionMenu *ui;
};

#endif // INTRODUCTIONMENU_H
