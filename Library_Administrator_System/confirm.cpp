#include "confirm.h"
#include "ui_confirm.h"
#include <QMessageBox>

Confirm::Confirm(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Confirm)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);
    connect(ui->Confirm_Return_bt, SIGNAL(clicked()), this, SLOT(close()));

    ui->tableWidget->setSelectionBehavior(QAbstractItemView::SelectRows);

    confirmsocket = new QTcpSocket;
    QObject::connect(confirmsocket, &QTcpSocket::readyRead, this, &Confirm::socket_Read_Data);

}

Confirm::~Confirm()
{
    delete ui;
}

void Confirm::on_update_record_clicked()
{
    hostaddress.setAddress(QString("35.194.106.246"));
    confirmsocket->connectToHost(hostaddress,8333);

    if(!confirmsocket->waitForConnected(10000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    return;
    }


    QJsonObject transferobject;
    transferobject.insert("jsontype","16");

    QJsonDocument jsondoc;
    jsondoc.setObject(transferobject);
    bytearray = jsondoc.toJson(QJsonDocument::Compact);
    //confirmsocket->write( std::to_string(bytearray.size()).c_str() );
    confirmsocket->write(bytearray);
}



void Confirm::on_AcceptRequest_clicked()
{
    int rownumber = ui->tableWidget->currentRow();
    hostaddress.setAddress(QString("35.194.106.246"));
    confirmsocket->connectToHost(hostaddress,8333);

    if(!confirmsocket->waitForConnected(10000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    return;
    }

    QJsonObject transferobject;
    transferobject.insert("jsontype","17");
    transferobject.insert("requestID", ui->tableWidget->item(rownumber,4)->text());
    transferobject.insert("isagreed", "1");

    QJsonDocument jsondoc;
    jsondoc.setObject(transferobject);
    bytearray = jsondoc.toJson(QJsonDocument::Compact);
    //confirmsocket->write( std::to_string(bytearray.size()).c_str() );
    confirmsocket->write(bytearray);

}

void Confirm::on_KotowariRecord_clicked()
{
    int rownumber = ui->tableWidget->currentRow();
    hostaddress.setAddress(QString("35.194.106.246"));
    confirmsocket->connectToHost(hostaddress,8333);

    if(!confirmsocket->waitForConnected(10000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    return;
    }

    QJsonObject transferobject;
    transferobject.insert("jsontype","17");
    transferobject.insert("requestID", ui->tableWidget->item(rownumber,4)->text());
    transferobject.insert("isagreed", "0");

    QJsonDocument jsondoc;
    jsondoc.setObject(transferobject);
    bytearray = jsondoc.toJson(QJsonDocument::Compact);
    //confirmsocket->write( std::to_string(bytearray.size()).c_str() );
    confirmsocket->write(bytearray);
}


void Confirm::socket_Read_Data()
{
    QByteArray getbuffer;
    getbuffer = confirmsocket->readAll();

    QJsonDocument getdocument = QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();

    if(rootobj.value("jsontype").toString()=="16")
    {
    QJsonValue jsontypevalue = rootobj.value("requestrecords");



    QJsonArray recordarray = jsontypevalue.toArray();
    ui->tableWidget->setRowCount(recordarray.size());

    for(int i = 0; i<recordarray.size(); i++)
    {
        QJsonValue recordnumber = recordarray.at(i);
        QJsonObject iteratorobject = recordnumber.toObject();

        QJsonValue titlevalue = iteratorobject.value("title");
        QJsonValue namevalue = iteratorobject.value("name");
        QJsonValue usernamevalue = iteratorobject.value("username");
        QJsonValue begindatevalue = iteratorobject.value("begindate");
        QJsonValue requestIDvalue = iteratorobject.value("requestID");

        ui->tableWidget->setItem(i,0,new QTableWidgetItem(namevalue.toString()));
        ui->tableWidget->setItem(i,1,new QTableWidgetItem(usernamevalue.toString()));
        ui->tableWidget->setItem(i,2,new QTableWidgetItem(titlevalue.toString()));
        ui->tableWidget->setItem(i,3,new QTableWidgetItem(begindatevalue.toString()));
        ui->tableWidget->setItem(i,4,new QTableWidgetItem(requestIDvalue.toString()));

    }
    }
    else if (rootobj.value("jsontype").toString()=="17")
    {
        if(rootobj.value("confirmtype").toString()=="1")
        QMessageBox::information(this, tr("提示"),tr("操作成功"));
        else if(rootobj.value("confirmtype").toString()=="0")
        QMessageBox::warning(this, tr("错误"), tr("审核未生效，请检查网络"));
    }

}
