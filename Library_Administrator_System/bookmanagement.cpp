#include "bookmanagement.h"
#include "ui_bookmanagement.h"

#include "bookoperation.h"

#include <QMessageBox>

BookManagement::BookManagement(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::BookManagement)
{
    ui->setupUi(this);
    connect(ui->BookM_Return_bt, SIGNAL(clicked()), this, SLOT(close()));
}

BookManagement::~BookManagement()
{
    delete ui;
}

void BookManagement::on_AddBook_Bt_clicked()
{
    this->hide();
    bookoperation *bookoperation2 = new bookoperation(this);
    bookoperation2->show();
    bookoperation2->exec();
    this->show();




 /*   if(ui->Author_Edit->text()==""||ui->Book_Edit->text()==""||ui->Index_Edit->text()==""||ui->Publisher_Edit->text()==""||ui->Original_Booknum_Edit->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("新增书目参数不足，请您检查！"));
 等待大神添加数据库判断操作
    else if()
    QMessageBox::warning(this, tr("似乎出错了……"), tr("未能连接至数据库，请您检查！"));
    else if()
    QMessageBox::warning(this, tr("似乎出错了……"), tr("这本书已经存在于数据库！"));
*/
}

void BookManagement::on_Delete_Book_clicked()
{
    if(ui->Author_Edit->text()==""&& ui->Book_Edit->text()==""&&ui->Index_Edit->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("请使用书名，作者或编号来删除书目！"));
   /*等待大神添加数据库判断操作
    else if()
    QMessageBox::warning(this, tr("似乎出错了……"), tr("未能连接至数据库，请您检查！"));
    else if()
    QMessageBox::warning(this, tr("似乎出错了……"), tr("数据库中不存在这本书！"));
    */
}


void BookManagement::on_pushButton_clicked()
{

}

void BookManagement::on_detailed_information_clicked()
{
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this, 1);
    bookoperation1->show();
    bookoperation1->exec();
    this->show();
}

void BookManagement::on_Modify_Book_clicked()
{
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this, 2);
    bookoperation1->show();
    bookoperation1->exec();
    this->show();
}

void BookManagement::on_advancedsearch_clicked()
{
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this, 2);
    bookoperation1->show();
    bookoperation1->exec();
    this->show();

}
