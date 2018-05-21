#include "mainpage.h"
#include "ui_mainpage.h"
#include "readermanagement.h"
#include "bookmanagement.h"
#include "record.h"
#include "confirm.h"

MainPage::MainPage(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::MainPage)
{
    ui->setupUi(this);
}

MainPage::~MainPage()
{
    delete ui;
}

void MainPage::on_Return_bt_clicked()
{
    this->close();
}

void MainPage::on_Book_bt_clicked()
{
    BookManagement *bookmanagement= new BookManagement();
    this->close();
    bookmanagement->show();
    bookmanagement->exec();
    this->show();

}
