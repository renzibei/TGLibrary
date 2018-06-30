#include "addnewreader.h"
#include "ui_addnewreader.h"
#include "webio.h"
#include <QMessageBox>

Addnewreader::Addnewreader(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Addnewreader)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);

    ui->pushButton->setText("确认");

    addreadersocket = WebIO::getSocket();//new QTcpSocket();
    QObject::connect(addreadersocket, &QTcpSocket::readyRead, this, &Addnewreader::socket_Read_Data);

    hostaddress.setAddress(QString("35.194.106.246"));
    addreadersocket->connectToHost(hostaddress,8333);

    if(!addreadersocket->waitForConnected(3000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    this->close();
    }
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
if(ui->pushButton->text()=="确认")
    {
    if(ui->name->text()==""||ui->peoplenumber->text()==""||ui->Cardnumber->text()==""||ui->password->text()=="")
    QMessageBox::warning(this, tr("错误"), tr("请输入全部参数以添加读者/管理者"));
    else
    {
       // QHostAddress hostaddress;
        if(operationtype == 1)
        {
            addreaderjson.insert("jsontype", "14");
            addreaderjson.insert("usertype", "0");

            addreaderjson.insert("name",ui->name->text());
            addreaderjson.insert("username",ui->peoplenumber->text());
            addreaderjson.insert("userID", ui->Cardnumber->text());
            addreaderjson.insert("password", ui->password->text());

        }
        else if (operationtype == 2)
        {
            addreaderjson.insert("jsontype", "14");
            addreaderjson.insert("usertype", "1");
            addreaderjson.insert("name",ui->name->text());
            addreaderjson.insert("username",ui->peoplenumber->text());
            addreaderjson.insert("userID", ui->Cardnumber->text());
            addreaderjson.insert("password", ui->password->text());

        }

        else if(ui->comboBox->currentText()=="读者")
        {
        addreaderjson.insert("jsontype", "8");

        addreaderjson.insert("name",ui->name->text());
        addreaderjson.insert("username",ui->peoplenumber->text());
        addreaderjson.insert("userID", ui->Cardnumber->text());
        addreaderjson.insert("password", ui->password->text());
        }
        else if(ui->comboBox->currentText()=="管理员")
        {
            addreaderjson.insert("jsontype", "10");

            addreaderjson.insert("name",ui->name->text());
            addreaderjson.insert("username",ui->peoplenumber->text());
           //() addreaderjson.insert("userID", ui->Cardnumber->text());
            addreaderjson.insert("password", ui->password->text());
        }


        QJsonDocument sendjson;
        sendjson.setObject(addreaderjson);
        bytearray = sendjson.toJson(QJsonDocument::Compact);
        //addreadersocket->write( std::to_string(bytearray.size()).c_str() );
        addreadersocket->write(bytearray);
        this->close();
     //   addreaderjson.~QJsonObject();
}
    }
else if (ui->pushButton->text()=="修改")
    {
    if(ui->name->text()==""||ui->peoplenumber->text()==""||ui->Cardnumber->text()==""||ui->password->text()=="")
    QMessageBox::warning(this, tr("错误"), tr("请输入全部参数以修改读者/管理者"));
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

}


}
}


void Addnewreader::socket_Read_Data()
{
    QByteArray getbuffer;
    //getbuffer = addreadersocket->readAll();

    QJsonDocument getdocument =  WebIO::Singleton()->readJsonDocument();//QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();

    QJsonValue jsontypevalue = rootobj.value("confirmtype");
    int index = jsontypevalue.toInt();

    if(index == 0)
    {
        QMessageBox::information(this, tr(""), tr("操作成功"));
        this->close();
    }
    else if(index == 1)
    {
        QMessageBox::warning(this, tr("错误"), tr("终端出现错误，请检查网络设置!"));
    }
}

void Addnewreader::SendData(QJsonObject qjsonpass)
{
    getpassjson = qjsonpass;
    ui->name->setText(getpassjson.value("name").toString());
    ui->peoplenumber->setText(getpassjson.value("username").toString());
    ui->Cardnumber->setText(getpassjson.value("userID").toString());
    ui->password->setText(getpassjson.value("password").toString());
    ui->pushButton->setText("修改");
}
