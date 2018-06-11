#include "bookoperation.h"
#include "ui_bookoperation.h"

#include <QHeaderView>

bookoperation::bookoperation(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::bookoperation)
{

    this->setAttribute(Qt::WA_DeleteOnClose);

    ui->setupUi(this);
    ui->bookinformation->setColumnWidth(0,600);
    //ui->bookinformation->setItem(0,0,Qt::AlignLeft);
//  ui->bookinformation->resizeColumnsToContents();
//  ui->bookinformation->resizeRowsToContents();
//    ui->bookinformation->verticalHeader()->setVisible(false);
    ui->bookinformation->setAlternatingRowColors(true);

    //connect(ui->pushButton_2, SIGNAL(clicked()), this, SLOT(close()));

    bookoperationsocket = new QTcpSocket;

    QObject::connect(bookoperationsocket, &QTcpSocket::readyRead, this, &bookoperation::socket_Read_Data);



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

    if(ui->bookinformation->item(0,0)==0||ui->bookinformation->item(1,0)==0||ui->bookinformation->item(2,0)==0)
    {
        QMessageBox::warning(this, tr("错误"), tr("基础参数不足，请至少输入书名，作者，出版社！"));
        return;
    }
    bookoperationjson.insert("title", ui->bookinformation->item(0,0)->text());
    bookoperationjson.insert("authors", ui->bookinformation->item(1,0)->text());
    bookoperationjson.insert("publisher", ui->bookinformation->item(2,0)->text());
    if(ui->bookinformation->item(3,0)!=0)
    bookoperationjson.insert("publicationYear", ui->bookinformation->item(3,0)->text());
    if(ui->bookinformation->item(4,0)!=0)
    bookoperationjson.insert("ISBNs", ui->bookinformation->item(4,0)->text());
    if(ui->bookinformation->item(5,0)!=0)
    bookoperationjson.insert("source", ui->bookinformation->item(5,0)->text());
    if(ui->bookinformation->item(6,0)!=0)
    bookoperationjson.insert("urls", ui->bookinformation->item(6,0)->text());
    if(ui->bookinformation->item(7,0)!=0)
    bookoperationjson.insert("languages", ui->bookinformation->item(7,0)->text());
    if(ui->bookinformation->item(8,0)!=0)
    bookoperationjson.insert("subjects", ui->bookinformation->item(8,0)->text());
    if(ui->bookinformation->item(9,0)!=0)
    bookoperationjson.insert("description", ui->bookinformation->item(9,0)->text());

            QJsonDocument jsondoc;
            jsondoc.setObject(bookoperationjson);
            bytearray = jsondoc.toJson(QJsonDocument::Compact);
           // bookoperationsocket->write( std::to_string(bytearray.size()).c_str() );
            bookoperationsocket->write(bytearray);
}

void bookoperation::on_Back_Button_clicked()
{
    this->close();
}


void bookoperation::socket_Read_Data()
{
    QByteArray getbuffer;
    getbuffer = bookoperationsocket->readAll();

    QJsonDocument getdocument = QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();

    QJsonValue jsonvalue = rootobj.value("jsontype");
    int index =rootobj.value("confirmvalue").toInt();

    if(index == 1)
    {
        QMessageBox::warning(this, tr("错误"), tr("添加未成功，请检查网络设置"));
        return;
    }
    if(index == 0)
    {
        QMessageBox::information(this, tr("成功"), tr("已删除！"));
        return;
    }


}
