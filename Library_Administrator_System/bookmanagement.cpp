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

    ui->tableWidget->setSelectionBehavior(QAbstractItemView::SelectRows);

    QObject::connect(booksocket, &QTcpSocket::readyRead, this, &BookManagement::socket_Read_Data);

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
}





void BookManagement::on_Delete_Book_clicked()
{
    int rownumber = ui->tableWidget->currentRow();

    hostaddress.setAddress(QString("35.194.106.246"));
    booksocket->connectToHost(hostaddress,8333);

    if(!booksocket->waitForConnected(10000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    return;
    }

    bookjson.insert("jsontype","3");
    bookjson.insert("docID",ui->tableWidget->item(rownumber,3)->text());

    QJsonDocument jsondoc;
    jsondoc.setObject(bookjson);
    bytearray = jsondoc.toJson(QJsonDocument::Compact);
    booksocket->write( std::to_string(bytearray.size()).c_str() );
    booksocket->write(bytearray);


     /*  if(ui->Author_Edit->text()==""&& ui->Book_Edit->text()==""&&ui->Index_Edit->text()=="")
       QMessageBox::warning(this, tr("似乎出错了……"), tr("请使用书名，作者或编号来删除书目！"));
      等待大神添加数据库判断操作
       else if()
       QMessageBox::warning(this, tr("似乎出错了……"), tr("未能连接至数据库，请您检查！"));
       else if()
       QMessageBox::warning(this, tr("似乎出错了……"), tr("数据库中不存在这本书！"));
       */
}



void BookManagement::on_detailed_information_clicked()
{
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this);
    bookoperation1->show();
    bookoperation1->exec();
    this->show();
}

void BookManagement::on_Modify_Book_clicked()
{
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this);
    bookoperation1->show();
    bookoperation1->exec();
    this->show();
}

void BookManagement::on_advancedsearch_clicked()
{
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this);
    bookoperation1->show();
    bookoperation1->exec();
    this->show();

}

void BookManagement::on_SearchBook_clicked()
{
    if(ui->Book_Edit->text()=="")
    QMessageBox::warning(this, tr("错误"), tr("请输入"));
    else
    {
       // QHostAddress hostaddress;
        hostaddress.setAddress(QString("35.194.106.246"));
        booksocket->connectToHost(hostaddress,8333);

        if(!booksocket->waitForConnected(10000))
        {
        QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
        return;
        }

        bookjson.insert("jsontype","5");
        bookjson.insert("keywords",ui->Book_Edit->text());

        QJsonDocument jsondoc;
        jsondoc.setObject(bookjson);
        bytearray = jsondoc.toJson(QJsonDocument::Compact);
        booksocket->write( std::to_string(bytearray.size()).c_str() );
        booksocket->write(bytearray);
}
}
void BookManagement::socket_Read_Data()
{
    QByteArray getbuffer;
    getbuffer = booksocket->readAll();

    QJsonDocument getdocument = QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();
    QJsonValue jsontypevalue = rootobj.value("documents");
    QJsonValue confirmvalue = rootobj.value("confirmtype");

    int index = jsontypevalue.toInt();

    if(index == 1)
    {
        QMessageBox::warning(this, tr("错误"), tr("终端出现错误，请检查网络设置!"));
        return;
    }


    QJsonArray bookarray = jsontypevalue.toArray();
    ui->tableWidget->setRowCount(bookarray.size());

    for(int i = 0; i<bookarray.size(); i++)
    {
        QJsonValue booknumber = bookarray.at(i);
        QJsonObject iteratorobject = booknumber.toObject();

        QJsonValue titlevalue = iteratorobject.value("title");
        QJsonValue authorvalue = iteratorobject.value("authors");
        QJsonValue publishervalue = iteratorobject.value("publisher");
        QJsonValue docIDvalue = iteratorobject.value("docID");

        ui->tableWidget->setItem(i,0,new QTableWidgetItem(titlevalue.toString()));
        ui->tableWidget->setItem(i,1,new QTableWidgetItem(authorvalue.toString()));
        ui->tableWidget->setItem(i,2,new QTableWidgetItem(publishervalue.toString()));
        ui->tableWidget->setItem(i,3,new QTableWidgetItem(docIDvalue.toString()));

    }
}



