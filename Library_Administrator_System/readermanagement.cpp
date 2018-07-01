#include "readermanagement.h"
#include "ui_readermanagement.h"
#include "addnewreader.h"
#include <QDesktopWidget>
#include <QMessageBox>
#include "webio.h"

ReaderManagement::ReaderManagement(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::ReaderManagement)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);
    connect(ui->Readerm_Return_bt, SIGNAL(clicked()), this, SLOT(close()));

    ui->tableWidget->setSelectionBehavior(QAbstractItemView::SelectRows);

    readersocket = WebIO::getSocket();//new QTcpSocket();
    //QObject::connect(readersocket, &QTcpSocket::readyRead, this, &ReaderManagement::socket_Read_Data);

    // QHostAddress hostaddress;
    /*
     hostaddress.setAddress(QString("35.194.106.246"));
     readersocket->connectToHost(hostaddress,8333);

     if(!readersocket->waitForConnected(3000))
     {
     QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    this->close();     }
    */

}

ReaderManagement::~ReaderManagement()
{
    delete ui;
    readersocket->disconnectFromHost();
}

void ReaderManagement::on_pushButton_2_clicked()
{
    Addnewreader *addnewreader = new Addnewreader(this);
    addnewreader->move((QApplication::desktop()->width() - addnewreader->width()) / 2,
                     (QApplication::desktop()->height() - addnewreader->height()) / 2);
    addnewreader->show();
}


void ReaderManagement::on_pushButton_4_clicked()
{
    if(ui->Input_Edit->text()=="")
    QMessageBox::warning(this, tr("错误"), tr("请输入"));
    else
    {
        if(ui->comboBox->currentText()=="读者姓名"||ui->comboBox->currentText()=="读者用户名"||ui->comboBox->currentText()=="读者工作证号")
        {
           readerjson.insert("jsontype", "9");

        if(ui->comboBox->currentText()=="读者姓名")
            readerjson.insert("name",ui->Input_Edit->text());
        if(ui->comboBox->currentText()=="读者用户名")
            readerjson.insert("username",ui->Input_Edit->text());
        if(ui->comboBox->currentText()=="读者工作证号")
            readerjson.insert("uid",ui->Input_Edit->text());

            readerjson.insert("usertype","0");
        }
        else
        {
            readerjson.insert("jsontype", "9");
            if(ui->comboBox->currentText()=="管理员姓名")
                readerjson.insert("name",ui->Input_Edit->text());
            if(ui->comboBox->currentText()=="管理员用户名")
                readerjson.insert("username",ui->Input_Edit->text());

            readerjson.insert("usertype","1");
        }


        QJsonDocument sendjson;
        sendjson.setObject(readerjson);
        bytearray = sendjson.toJson(QJsonDocument::Compact);
        //readersocket->write( std::to_string(bytearray.size()).c_str() );
        WebIO::Singleton()->sendMessage(bytearray, this,  SLOT(socket_Read_Data()));
        //readersocket->write(bytearray);
}
}

void ReaderManagement::on_Modifieduser_clicked()
{
    int rownumber =  ui->tableWidget->currentRow();


    Addnewreader *addnewreader = new Addnewreader(this);
    if(ui->comboBox->currentText()=="读者姓名" || ui->comboBox->currentText()=="读者用户名" || ui->comboBox->currentText()=="读者工作证号" )
    addnewreader->operationtype = 1;
    else
    addnewreader->operationtype = 2;

    addnewreader->SendData(counterpartjson[rownumber]);

    addnewreader->move((QApplication::desktop()->width() - addnewreader->width()) / 2,
                     (QApplication::desktop()->height() - addnewreader->height()) / 2);
    addnewreader->show();



}

void ReaderManagement::socket_Read_Data()
{
    QByteArray getbuffer;
    //getbuffer = WebIO::Singleton()->readJsonDocument();//readersocket->readAll();

    QJsonDocument getdocument = WebIO::Singleton()->readJsonDocument();//QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();
    transferobject = rootobj;

    QJsonValue jsontypevalue = rootobj.value("confirmtype");
    int index = jsontypevalue.toInt();


    if(rootobj.value("documents").toArray().size()==0)
    {
        QMessageBox::warning(this, tr("错误"), tr("没有找到相关信息！"));
        return;
    }

    QJsonArray informationarray = rootobj.value("documents").toArray();
    int informationnumber = informationarray.size();

    for(int i = 0; i<informationnumber; i++)
    {
    QJsonObject iteratorobject = informationarray.at(i).toObject();

    counterpartjson.push_back(iteratorobject);

    //qDebug() << iteratorobject;

    QJsonValue usernamevalue = iteratorobject.value("username");
    QJsonValue namevalue = iteratorobject.value("name");
    QJsonValue IDvalue = iteratorobject.value("userID");
    //qDebug() << IDvalue;
  //  QJsonValue usertypevalue = iteratorobject.value("usertype");
    QJsonValue uidvalue = iteratorobject.value("uid");
    ui->tableWidget->setItem(i,0,new QTableWidgetItem(usernamevalue.toString()));
    ui->tableWidget->setItem(i,1,new QTableWidgetItem(namevalue.toString()));
    //qDebug() << IDvalue.toInt();
    ui->tableWidget->setItem(i,2,new QTableWidgetItem(IDvalue.toString()));
    ui->tableWidget->setItem(i,3,new QTableWidgetItem(uidvalue.toString()));
    }
}

void ReaderManagement::on_delete_hito_clicked()
{
    //创建json
    int rownumber = ui->tableWidget->currentRow();
    if(ui->tableWidget->item(rownumber,2) == 0)
    {
        QMessageBox::warning(this, tr("错误"), tr("请搜索后，选中记录再删除！"));
        return;
    }
    QJsonObject deletebookvalue;
    deletebookvalue.insert("jsontype","15");
    deletebookvalue.insert("userID", ui->tableWidget->item(rownumber,2)->text());
    if(ui->comboBox->currentText()=="读者姓名" || ui->comboBox->currentText()=="读者用户名" || ui->comboBox->currentText()=="读者工作证号" )
    deletebookvalue.insert("usertype","0");
    else
    deletebookvalue.insert("usertype","1");

    //  连接服务器
    //QHostAddress hostaddress;
    /*
     hostaddress.setAddress(QString("35.194.106.246"));
     readersocket->connectToHost(hostaddress,8333);

     if(!readersocket->waitForConnected(10000))
     {
     QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
     return;
     }
     */

    // 上传服务器
     QJsonDocument sendjson;
     sendjson.setObject(deletebookvalue);
     bytearray = sendjson.toJson(QJsonDocument::Compact);
     //readersocket->write( std::to_string(bytearray.size()).c_str() );
     WebIO::Singleton()->sendMessage(bytearray, this,  SLOT(socket_Read_Data()));
     //readersocket->write(bytearray);
}
