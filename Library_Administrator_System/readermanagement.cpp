#include "readermanagement.h"
#include "ui_readermanagement.h"
#include "addnewreader.h"
#include <QDesktopWidget>
#include <QMessageBox>

ReaderManagement::ReaderManagement(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::ReaderManagement)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);
    connect(ui->Readerm_Return_bt, SIGNAL(clicked()), this, SLOT(close()));
}

ReaderManagement::~ReaderManagement()
{
    delete ui;
}

void ReaderManagement::on_pushButton_2_clicked()
{
    Addnewreader *addnewreader = new Addnewreader(this);
    addnewreader->move((QApplication::desktop()->width() - addnewreader->width()) / 2,
                     (QApplication::desktop()->height() - addnewreader->height()) / 2);
    addnewreader->show();
}

void ReaderManagement::on_pushButton_3_clicked()
{
    if(ui->Input_Edit->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("只用输一个参数还不满足人家的需求~"));
     /*等待大神添加数据库判断操作
    else if()
    QMessageBox::warning(this, tr("似乎出错了……"), tr("数据库中不存在这名读者！"));
    */
}

void ReaderManagement::on_pushButton_4_clicked()
{
    if(ui->Input_Edit->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("只用输一个参数还不满足人家的需求~"));
   // else if()
}
