#include "bookoperation.h"
#include "ui_bookoperation.h"

#include "bookoperation.h"
#include "bookmanagement.h"
#include "realbook.h"
#include "webio.h"

#include <QHeaderView>
#include <QString>

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

    bookoperationsocket = WebIO::getSocket();//new QTcpSocket;

    //QObject::connect(bookoperationsocket, &QTcpSocket::readyRead, this, &bookoperation::socket_Read_Data);

    //hostaddress.setAddress(QString("35.194.106.246"));
    /*
    bookoperationsocket->connectToHost(hostaddress,8333);

    if(!bookoperationsocket->waitForConnected(3000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    this->close();
    }
    */

}

/*
 * bookoperation::bookoperation(QWidget *parent, int i) : bookoperation(QWidget *parent)
{
    if(i==1)
    ui->bookinformation->setEditTriggers(QAbstractItemView::NoEditTriggers);


}*/

bookoperation::~bookoperation()
{

    bookoperationsocket->disconnectFromHost();
    delete ui;
}



void bookoperation::on_add_Books_clicked()
{


    if (operationtype == 0)
{

    ui->bookinformation->setEditTriggers(QAbstractItemView::NoEditTriggers);
    return;

}
    if(operationtype == 1)
    {
    bookoperationjson.insert("jsontype","13");
    bookoperationjson.insert("docID", booktransferobject.value("docID").toString());


    }
    if(operationtype == 3)
    {
    bookoperationjson.insert("jsontype","2");
    }

    if(operationtype == 2)
    {
    bookoperationjson.insert("jsontype","6");
    }

    if(operationtype == 3 || operationtype == 1)
    {
    if(ui->bookinformation->item(0,0)==0||ui->bookinformation->item(1,0)==0||ui->bookinformation->item(2,0)==0)
    {
        QMessageBox::warning(this, tr("错误"), tr("基础参数不足，请至少输入书名，作者，出版社！"));
        return;
    }
    bookoperationjson.insert("title", ui->bookinformation->item(0,0)->text());
    bookoperationjson.insert("authors", ui->bookinformation->item(1,0)->text());
    bookoperationjson.insert("publisher", ui->bookinformation->item(2,0)->text());
    }

    if(operationtype == 2)
    {
        if(ui->bookinformation->item(0,0)!=0)
        bookoperationjson.insert("title", ui->bookinformation->item(0,0)->text());
        if(ui->bookinformation->item(1,0)!=0)
        bookoperationjson.insert("authors", ui->bookinformation->item(1,0)->text());
        if(ui->bookinformation->item(2,0)!=0)
        bookoperationjson.insert("publisher", ui->bookinformation->item(2,0)->text());
    }
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

    //bookoperationsocket->write(bytearray);
    WebIO::Singleton()->sendMessage(bytearray, this,  SLOT(socket_Read_Data()));





void bookoperation::on_Back_Button_clicked()
{
    this->close();
}


void bookoperation::socket_Read_Data()
{
    //jikken

  //  QByteArray jikken;
   // jikken = bookoperationsocket->r



    QByteArray getbuffer;
    //getbuffer = WebIO::Singleton()->readJsonDocument();//bookoperationsocket->readAll();

    QJsonDocument getdocument = WebIO::Singleton()->readJsonDocument();//QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();
    qDebug() << rootobj;
    QJsonValue jsonvalue = rootobj.value("jsontype");

    if(rootobj.value("jsontype").toString()== "6")
    {
  //      qDebug() << rootobj;
        QJsonArray qwertybookarray = rootobj.value("documents").toArray();

        if(qwertybookarray.size() == 0)
        QMessageBox::warning(this, tr("错误"), tr("未查到相关书籍"));
        else
        {
            BookManagement *asd = (BookManagement*) parent();
            asd->advancetransfer =qwertybookarray;
            this->close();

        }
        return;
    }


    int index =rootobj.value("confirmvalue").toInt();

    if(index == 1)
    {
        QMessageBox::warning(this, tr("错误"), tr("操作未成功，请检查网络设置"));
        return;
    }
    if(index == 0)
    {
        QMessageBox::information(this, tr("成功"), tr("操作成功！"));
        this->close();
        return;
    }


}

void bookoperation::writeinformation()
{
    QJsonArray subjectarray = booktransferobject.value("subjects").toArray();
    QJsonArray authorarray= booktransferobject.value("authors").toArray();
    QJsonArray ISBNarray= booktransferobject.value("ISBNs").toArray();

    QString subjectstring =""; QString authorstring = ""; QString ISBNstring = "";
    for(int i=0; i<subjectarray.size();i++)
    subjectstring = subjectstring +subjectarray.at(i).toString() +"; ";


    for(int j=0; j<authorarray.size(); j++)
    authorstring = authorstring+ authorarray.at(j).toString() + "; ";

    for(int k=0; k<ISBNarray.size();k++)
    ISBNstring = ISBNstring+ ISBNarray.at(k).toString() + "; ";


    //可能是指针 后续再改
    ui->bookinformation->setItem(0,0,new QTableWidgetItem(booktransferobject.value("title").toString()));
    ui->bookinformation->setItem(1,0,new QTableWidgetItem(authorstring.left(authorstring.size()-1)));

    ui->bookinformation->setItem(2,0,new QTableWidgetItem(booktransferobject.value("publisher").toString()));
    ui->bookinformation->setItem(3,0,new QTableWidgetItem(booktransferobject.value("publicationYear").toString()));
    ui->bookinformation->setItem(4,0,new QTableWidgetItem(ISBNstring.left(ISBNstring.size()-1)));
    ui->bookinformation->setItem(5,0,new QTableWidgetItem(booktransferobject.value("source").toString()));
    ui->bookinformation->setItem(6,0,new QTableWidgetItem(booktransferobject.value("urls").toString()));
    ui->bookinformation->setItem(7,0,new QTableWidgetItem(booktransferobject.value("languages").toString()));
    ui->bookinformation->setItem(8,0,new QTableWidgetItem(subjectstring.left(subjectstring.length()-1)));
    ui->bookinformation->setItem(9,0,new QTableWidgetItem(booktransferobject.value("description").toString()));

    QJsonArray realbookarray = booktransferobject.value("realBooks").toArray();
    int realbooknumber = realbookarray.size();
    ui->RealBookInformation->setRowCount(realbooknumber);
    for (int i = 0 ; i<realbooknumber; i++)
    {
        QJsonObject iteratorobject = realbookarray.at(i).toObject();
        ui->RealBookInformation->setItem(i,0,new QTableWidgetItem(iteratorobject.value("_bookId").toString()));
        ui->RealBookInformation->setItem(i,1,new QTableWidgetItem(iteratorobject.value("_place").toString()));
        ui->RealBookInformation->setItem(i,2,new QTableWidgetItem(iteratorobject.value("_version").toString()));
        if (iteratorobject.value("isOnShelf") == true)
        ui->RealBookInformation->setItem(i,3,new QTableWidgetItem("是"));
        else
        ui->RealBookInformation->setItem(i,3,new QTableWidgetItem("否"));



    }


}

void bookoperation::on_AddRealBooks_clicked()
{
//    this->hide();
    if(ui->bookinformation->item(0,0)==0)
    {
        QMessageBox::warning(this, tr("错误"), tr("先有虚拟书目才能添加该书目对应实体书哦~"));

    }
    else{
    RealBook *addrealbook = new RealBook(this);
    addrealbook->virtualbookobject = booktransferobject;
    addrealbook->show();
   // this->show();
    }
}
