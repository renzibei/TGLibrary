#include "bookmanagement.h"
#include "ui_bookmanagement.h"

BookManagement::BookManagement(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::BookManagement)
{
    ui->setupUi(this);
}

BookManagement::~BookManagement()
{
    delete ui;
}

void BookManagement::on_pushButton_4_clicked()
{
    this->close();
}
