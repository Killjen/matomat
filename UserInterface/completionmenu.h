#ifndef COMPLETIONMENU_H
#define COMPLETIONMENU_H

#include <QWidget>
#include "dbentry.h"
#include <QtSql>

namespace Ui {
class CompletionMenu;
}

class CompletionMenu : public QWidget
{
    Q_OBJECT

public:
    explicit CompletionMenu(QWidget *parent = 0);
    ~CompletionMenu();
    void setUpCompletionMenu(QMutex* p_lock, dbEntry* p_entry, int buttonID);

private:
    Ui::CompletionMenu *ui;
};

#endif // COMPLETIONMENU_H
