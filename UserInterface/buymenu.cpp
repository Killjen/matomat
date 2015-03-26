#include "buymenu.h"
#include "ui_buymenu.h"

BuyMenu::BuyMenu(QWidget *parent) :
    QWidget(parent),
    ui(new Ui::BuyMenu)
{
    ui->setupUi(this);
}

BuyMenu::~BuyMenu()
{
    delete ui;
}

void BuyMenu::setUpBuyMenu(dbEntry* p_entry){
    //clean up old labelList to-do:FIND A WAY TO REMOVE STUFF FROM THE GRIDLAYOUT
    for (int i = 0; i < _buttonList.size(); ++i) {
        this->ui->gridLayout->removeWidget(_buttonList[i]);
        this->ui->gridLayout->removeWidget(_labelList[i]);
        delete _buttonList[i];
        delete _labelList[i];
    }
    _buttonList.clear();
    _labelList.clear();

    //set up account info on top right
    this->ui->username->setText(p_entry->username);
    this->ui->balance->setText(QString::number((p_entry->balance))+ QString::fromUtf8(" €"));


    //set up all purchasable articles as buttons (by accessing the stock table in our db)
    QSqlQuery query;
    query.exec("SELECT ArticleName, Price, ArticleID, LogoPath  FROM stock;");
    if(query.size()==0){
        qDebug() << "Query returned empty: empty Stock table";
        return;
    }
    int i=0;
    while(query.next()){
        qDebug() << "adding button to buymenu";
        _buttonList.append(new QPushButton(query.value(0).toString()));
        _labelList.append(new QLabel(query.value(1).toString() + QString::fromUtf8(" €") ));
        _buttonList[i]->setObjectName(query.value(2).toString());
        if(query.value(3).toString() != ""){
            /*QPixmap pixmap("/var/www/"+query.value(3).toString());
            qDebug() << pixmap;
            QIcon ButtonIcon(pixmap);
            _buttonList[i]->setIcon(ButtonIcon);
            _buttonList[i]->setIconSize(pixmap.rect().size());*/
            //to-do: check what happens, if there is a wrong filename
            _buttonList[i]->setIcon(QIcon("/var/www/" + query.value(3).toString()));
       }
        //grey out buttons if balance is too low
        if(query.value(1).toFloat() > p_entry->balance){
            _buttonList[i]->setEnabled(false);
        }
        ++i;
    }
    fillLayout();
}

void BuyMenu::fillLayout(){
    for (int i = 0; i < _buttonList.size(); ++i) {
        this->ui->gridLayout->addWidget(_buttonList[i],i,0);
        this->ui->gridLayout->addWidget(_labelList[i],i,1);
        connect(_buttonList[i], SIGNAL(clicked()), this, SLOT(emitDisplayCompletionMenu()));
    }
}

void BuyMenu::emitDisplayCompletionMenu(){
    emit changeToCompletionMenu(sender()->objectName().toInt());
}
