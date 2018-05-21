#include "readermanagement.h"
#include "ui_readermanagement.h"

ReaderManagement::ReaderManagement(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::ReaderManagement)
{
    ui->setupUi(this);
}

ReaderManagement::~ReaderManagement()
{
    delete ui;
}
