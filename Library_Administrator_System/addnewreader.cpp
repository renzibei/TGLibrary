#include "addnewreader.h"
#include "ui_addnewreader.h"
#include <QMessageBox>

Addnewreader::Addnewreader(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Addnewreader)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);
}

Addnewreader::~Addnewreader()
{
    delete ui;
}

void Addnewreader::on_pushButton_2_clicked()
{
    this->close();
}

void Addnewreader::on_pushButton_clicked()
{
    if(ui->Cardnumber->text()==""||ui->name->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("给我们足够的参数，以便更好地满足您的需求~"));
   // else if()
    else
    QMessageBox::information(this, tr(""), tr("添加成功"));
}
