#include "unknownidform.h"
#include "ui_unknownidform.h"

UnknownIDForm::UnknownIDForm(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::UnknownIDForm)
{
    ui->setupUi(this);
}

UnknownIDForm::~UnknownIDForm()
{
    delete ui;
}

void UnknownIDForm::on_okButton_clicked()
{
    emit changeToIntroductionMenu();
}
