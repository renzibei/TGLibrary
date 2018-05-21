#include "record.h"
#include "ui_record.h"

Record::Record(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Record)
{
    ui->setupUi(this);
}

Record::~Record()
{
    delete ui;
}
