#include "bookmanagement.h"
#include "ui_bookmanagement.h"

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
    if(ui->Author_Edit->text()==""||ui->Book_Edit->text()==""||ui->Index_Edit->text()==""||ui->Publisher_Edit->text()==""||ui->Original_Booknum_Edit->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("新增书目参数不足，请您检查！"));
 /*等待大神添加数据库判断操作
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
    if(ui->Author_Edit->text()==""&& ui->Book_Edit->text()==""&&ui->Index_Edit->text()==""&&ui->Publisher_Edit->text()==""&&ui->Original_Booknum_Edit->text()==""&& ui->Remain_Booknum_Edit->text()=="")
    QMessageBox::warning(this, tr("似乎出错了……"), tr("请输入至少一个参数用于查询！"));
}
