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
    //clean up old labelList
    for (int i = 0; i < _buttonList.size(); ++i) {
        this->ui->buttonLayout->removeWidget(_buttonList[i]);
        this->ui->buttonLayout->removeWidget(_labelList[i]);
        delete _buttonList[i];
        delete _labelList[i];
    }
    _buttonList.clear();
    _labelList.clear();

    //set up account info on top right
    QFont font("Arial", 36);
    this->ui->profile->setFont(font);
    this->ui->profile->setText(p_entry->username + "  |  " + QString::number((p_entry->balance)) + QString::fromUtf8("€"));


    //set up all purchasable articles as buttons (by accessing the stock table in our db)
    QSqlQuery query;
    query.exec("SELECT ArticleName, Price, ArticleID, LogoPath, Quantity  FROM stock;");
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
        _buttonList[i]->setFont(font);
        _labelList[i]->setFont(font);
        _labelList[i]->setAlignment(Qt::AlignCenter);
        _buttonList[i]->setSizePolicy(QSizePolicy::Expanding,QSizePolicy::Expanding);
        _labelList[i]->setSizePolicy(QSizePolicy::Expanding,QSizePolicy::Expanding);

        if(query.value(3).toString() != ""){
            //to-do: check what happens, if there is a wrong filename
            _buttonList[i]->setIcon(QIcon("/var/www/" + query.value(3).toString()));
            _buttonList[i]->setIconSize(QSize(200,200));
       }
        //grey out buttons if balance is too low or article is out of stock
        if((query.value(1).toFloat() > p_entry->balance) or (query.value(4).toFloat()<=0)){
            _buttonList[i]->setEnabled(false);
        }
        ++i;
    }
    fillLayout();
}


void BuyMenu::fillLayout(){
    for (int i = 0; i < _buttonList.size(); ++i) {
        ui->buttonLayout->addWidget(_buttonList[i],i, 0);
        ui->buttonLayout->addWidget(_labelList[i], i, 3);
        connect(_buttonList[i], SIGNAL(clicked()), this, SLOT(emitDisplayCompletionMenu()));
    }
}

void BuyMenu::emitDisplayCompletionMenu(){
    emit changeToCompletionMenu(sender()->objectName().toInt());
}

void BuyMenu::on_backButton_clicked()
{
    emit changeToIntroductionMenu();
}
