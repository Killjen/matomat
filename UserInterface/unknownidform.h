#ifndef UNKNOWNIDFORM_H
#define UNKNOWNIDFORM_H

#include <QWidget>

namespace Ui {
class UnknownIDForm;
}

class UnknownIDForm : public QWidget
{
    Q_OBJECT

public:
    explicit UnknownIDForm(QWidget *parent = 0);
    ~UnknownIDForm();

signals:
    void changeToIntroductionMenu();

private slots:
    void on_okButton_clicked();

private:
    Ui::UnknownIDForm *ui;
};

#endif // UNKNOWNIDFORM_H
