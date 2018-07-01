#include "record.h"
#include "ui_record.h"
#include <QMessageBox>
#include "webio.h"

Record::Record(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Record)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);
    recordsocket = WebIO::getSocket();//new QTcpSocket;
    //QObject::connect(recordsocket, &QTcpSocket::readyRead, this, &Record::socket_Read_Data);



    /*
    hostaddress.setAddress(QString("35.194.106.246"));
    recordsocket->connectToHost(hostaddress,8333);

    if(!recordsocket->waitForConnected(3000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    this->close();
    }
    */
}

Record::~Record()
{
    delete ui;

}

void Record::on_pushButton_clicked()
{
    if(ui->Input_Edit1->text()=="")
    {
        QMessageBox::warning(this, tr("错误"), tr("只用输一个参数还不满足人家的需求~"));
        return;
    }



    recordjson.insert("jsontype","12");
    if(ui->Searchway->currentText()=="书目名称")
    recordjson.insert("recordtype", "1");
    else if(ui->Searchway->currentText()=="书目ID")
    recordjson.insert("recordtype", "2");
    else if(ui->Searchway->currentText()=="读者姓名")
    recordjson.insert("recordtype", "3");
    else if(ui->Searchway->currentText()=="读者ID")
    recordjson.insert("recordtype", "4");

    recordjson.insert("information", ui->Input_Edit1->text());

    QJsonDocument jsondoc;
    jsondoc.setObject(recordjson);
    bytearray = jsondoc.toJson(QJsonDocument::Compact);
    //booksocket->write( std::to_string(bytearray.size()).c_str() );
    //recordsocket->write(bytearray);
    WebIO::Singleton()->sendMessage(bytearray, this, SLOT(socket_Read_Data()));

}

void Record::socket_Read_Data()
{

    QByteArray getbuffer;
   // getbuffer = WebIO::Singleton()->readJsonDocument();//recordsocket->readAll();

    QJsonDocument getdocument = WebIO::Singleton()->readJsonDocument();//QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();

    QJsonValue jsontypevalue = rootobj.value("jsontype");

    QJsonValue confirmvalue = rootobj.value("confirmtype");
    int index = confirmvalue.toInt();


    if(index == 2)
    {
        QMessageBox::warning(this, tr("错误"), tr("终端出现错误，请检查网络设置!"));
        return;

    }

    if(index == 1)
    {
        QMessageBox::information(this, tr("错误"), tr("没有查询到相关记录！"));
    }


    QJsonValue documentvalue = rootobj.value("documents");
    QJsonArray bookarray = documentvalue.toArray();

    for(int i = 0; i<bookarray.size(); i++)
    {
        //QJsonValue booknumber = bookarray.at(i);似乎完全多余并没有必要wazawaza走这一步
        QJsonObject iteratorobject = bookarray.at(i).toObject();

        QJsonValue titlevalue = iteratorobject.value("title");
        QJsonValue authorvalue = iteratorobject.value("authors");
        QJsonValue publishervalue = iteratorobject.value("publisher");
        QJsonValue docIDvalue = iteratorobject.value("docID");
        QJsonValue namevalue = iteratorobject.value("name");
        QJsonValue userIDvalue = iteratorobject.value("userID");
        QJsonValue beginDatevalue = iteratorobject.value("beginDate");
        QJsonValue returnDatevalue = iteratorobject.value("returnDate");
        QJsonValue usernamevalue = iteratorobject.value("username");


        ui->tableWidget->setItem(i,0,new QTableWidgetItem(titlevalue.toString()));
        ui->tableWidget->setItem(i,1,new QTableWidgetItem(docIDvalue.toString()));
        ui->tableWidget->setItem(i,2,new QTableWidgetItem(namevalue.toString()));
        ui->tableWidget->setItem(i,3,new QTableWidgetItem(usernamevalue.toString()));
        ui->tableWidget->setItem(i,4,new QTableWidgetItem(userIDvalue.toString()));
        ui->tableWidget->setItem(i,5,new QTableWidgetItem(beginDatevalue.toString()));
        ui->tableWidget->setItem(i,6,new QTableWidgetItem(returnDatevalue.toString()));

    }


}

void Record::on_Record_Return_bt_clicked()
{
    this->close();
}
