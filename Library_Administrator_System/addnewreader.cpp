#include "addnewreader.h"
#include "ui_addnewreader.h"
#include <QMessageBox>

Addnewreader::Addnewreader(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Addnewreader)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);

    addreadersocket = new QTcpSocket();
    QObject::connect(addreadersocket, &QTcpSocket::readyRead, this, &Addnewreader::socket_Read_Data);
}

Addnewreader::~Addnewreader()
{
    delete ui;
}

void Addnewreader::on_pushButton_2_clicked()
{
    this->close();
}

void Addnewreader::on_pushButton_clicked()
{

    if(ui->name->text()==""||ui->peoplenumber->text()==""||ui->Cardnumber->text()==""||ui->password->text()=="")
    QMessageBox::warning(this, tr("错误"), tr("请输入全部参数以添加读者"));
    else
    {
       // QHostAddress hostaddress;
        hostaddress.setAddress(QString("35.194.106.246"));
        addreadersocket->connectToHost(hostaddress,8333);

        if(!addreadersocket->waitForConnected(10000))
        {
        QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
        return;
        }

        addreaderjson.insert("jsontype", "8");


        addreaderjson.insert("name",ui->name->text());
        addreaderjson.insert("username",ui->Cardnumber->text());
        addreaderjson.insert("userID", ui->peoplenumber->text());
        addreaderjson.insert("password", ui->password->text());



        QJsonDocument sendjson;
        sendjson.setObject(addreaderjson);
        bytearray = sendjson.toJson(QJsonDocument::Compact);
        addreadersocket->write( std::to_string(bytearray.size()).c_str() );
        addreadersocket->write(bytearray);
}

}


void Addnewreader::socket_Read_Data()
{
    QByteArray getbuffer;
    getbuffer = addreadersocket->readAll();

    QJsonDocument getdocument = QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();
    QJsonValue jsontypevalue = rootobj.value("confirmtype");
    int index = jsontypevalue.toInt();

    if(index == 0)
    {
        QMessageBox::information(this, tr(""), tr("添加成功"));
    }
    else if(index == 1)
    {
        QMessageBox::warning(this, tr("错误"), tr("终端出现错误，请检查网络设置!"));
    }
}
