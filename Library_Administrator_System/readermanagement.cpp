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

    readersocket = new QTcpSocket();
    QObject::connect(readersocket, &QTcpSocket::readyRead, this, &ReaderManagement::socket_Read_Data);
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
    QMessageBox::warning(this, tr("错误"), tr("请输入"));
    else
    {
       // QHostAddress hostaddress;
        hostaddress.setAddress(QString("35.194.106.246"));
        readersocket->connectToHost(hostaddress,8333);

        if(!readersocket->waitForConnected(10000))
        {
        QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
        return;
        }

        readerjson.insert("jsontype", "9");

        if(ui->comboBox->currentText()=="读者姓名")
            readerjson.insert("name",ui->Input_Edit->text(););
        if(ui->comboBox->currentText()=="读者卡号")
            readerjson.insert("username",ui->Input_Edit->text());
        if(ui->comboBox->currentText()=="工作证号")
            readerjson.insert("userID",ui->Input_Edit->text());


        QJsonDocument sendjson;
        sendjson.setObject(readerjson);
        bytearray = sendjson.toJson(QJsonDocument::Compact);
        readersocket->write( std::to_string(bytearray.size()).c_str() );
        readersocket->write(bytearray);
}
}
