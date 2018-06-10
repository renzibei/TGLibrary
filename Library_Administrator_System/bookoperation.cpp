#include "bookoperation.h"
#include "ui_bookoperation.h"

bookoperation::bookoperation(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::bookoperation)
{

    ui->setupUi(this);
    ui->bookinformation->setColumnWidth(1,600);
    //ui->bookinformation->setItem(0,0,Qt::AlignLeft);
    ui->bookinformation->resizeColumnsToContents();
    ui->bookinformation->resizeRowsToContents();
    ui->bookinformation->verticalHeader()->setVisible(false);
    ui->bookinformation->setAlternatingRowColors(true);


}

/*
 * bookoperation::bookoperation(QWidget *parent, int i) : bookoperation(QWidget *parent)
{
    if(i==1)
    ui->bookinformation->setEditTriggers(QAbstractItemView::NoEditTriggers);


}*/

bookoperation::~bookoperation()
{
    delete ui;
}

void bookoperation::on_pushButton_2_clicked()
{
    this->close();
}

void bookoperation::on_add_Books_clicked()
{
    hostaddress.setAddress(QString("35.194.106.246"));
    bookoperationsocket->connectToHost(hostaddress,8333);

    if(!bookoperationsocket->waitForConnected(10000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    return;
    }

    bookoperationjson.insert("jsontype","2");

    if(ui->bookinformation->item(0,0)->text()==""||ui->bookinformation->item(1,0)->text()==""||ui->bookinformation->item(2,0)->text()=="")
    {
        QMessageBox::warning(this, tr("错误"), tr("基础参数不足，请至少输入书名，作者，出版社！"));
        return;
    }
    bookoperationjson.insert("title", ui->bookinformation->item(0,0)->text());
    bookoperationjson.insert("authors", ui->bookinformation->item(1,0)->text());
    bookoperationjson.insert("publisher", ui->bookinformation->item(2,0)->text());
    bookoperationjson.insert("source", ui->bookinformation->item(4,0)->text());
    bookoperationjson.insert("urls", ui->bookinformation->item(5,0)->text());
    bookoperationjson.insert("languages", ui->bookinformation->item(6,0)->text());
    bookoperationjson.insert("subjects", ui->bookinformation->item(7,0)->text());
    bookoperationjson.insert("description", ui->bookinformation->item(8,0)->text());

            QJsonDocument jsondoc;
            jsondoc.setObject(bookoperationjson);
            bytearray = jsondoc.toJson(QJsonDocument::Compact);
            bookoperationsocket->write( std::to_string(bytearray.size()).c_str() );
            bookoperationsocket->write(bytearray);
}
