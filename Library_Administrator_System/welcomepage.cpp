#include "welcomepage.h"
#include "ui_welcomepage.h"

#include "mainpage.h"

WelcomePage::WelcomePage(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::WelcomePage)
{
    ui->setupUi(this);
    this->resize(QSize(800,600));
    this->setAttribute(Qt::WA_DeleteOnClose);
    QMovie *welcomemovie = new QMovie(":/Image/sakura_pink1.gif");
    ui->label->setMovie(welcomemovie);
    welcomemovie->start();
}

WelcomePage::~WelcomePage()
{
    delete ui;
}

void WelcomePage::on_En_Bt_clicked()
{
    this->hide();
    MainPage *mainpage = new MainPage(this);
    connect(mainpage,SIGNAL(sendsignal()),this,SLOT(reshow())) ;
    mainpage->show();
}

void WelcomePage::on_Ex_Bt_clicked()
{
    this->close();
}

void WelcomePage::reshow(){
    this->show();
}
