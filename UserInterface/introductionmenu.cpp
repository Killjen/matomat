#include "introductionmenu.h"
#include "ui_introductionmenu.h"

IntroductionMenu::IntroductionMenu(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::IntroductionMenu)
{
    ui->setupUi(this);
}

IntroductionMenu::~IntroductionMenu()
{
    delete ui;
}

