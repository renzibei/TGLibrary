#include "record.h"
#include "ui_record.h"
#include <QMessageBox>

Record::Record(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Record)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);
    connect(ui->Record_Return_bt, SIGNAL(clicked()), this, SLOT(close()));
}

Record::~Record()
{
    delete ui;
}

void Record::on_pushButton_clicked()
{
    if(ui->Input_Edit1->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("只用输一个参数还不满足人家的需求~"));

}
