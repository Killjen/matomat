#include "introductionmenu.h"
#include "ui_introductionmenu.h"

IntroductionMenu::IntroductionMenu(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::IntroductionMenu)
{
    ui->setupUi(this);
<<<<<<< HEAD
    showFullScreen();
=======
>>>>>>> 8406ee65aed88d070cf6766cf398ecb653605d17
}

IntroductionMenu::~IntroductionMenu()
{
    delete ui;
}

void IntroductionMenu::showUnknownMessage(){
    ui->statusBar->showMessage("Unbekannte ID");
}
