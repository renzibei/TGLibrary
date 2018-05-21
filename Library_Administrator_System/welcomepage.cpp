#include "welcomepage.h"
#include "ui_welcomepage.h"

WelcomePage::WelcomePage(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::WelcomePage)
{
    ui->setupUi(this);
    this->resize(QSize(800,600));
}

WelcomePage::~WelcomePage()
{
    delete ui;
}

void WelcomePage::on_En_Bt_clicked()
{
    MainPage *mainpage= new MainPage();
    this->close();
    mainpage->show();
    mainpage->exec();
    this->show();
}

void WelcomePage::on_Ex_Bt_clicked()
{
    this->close();
}
