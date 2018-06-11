#include "welcomepage.h"
#include "ui_welcomepage.h"

#include "mainpage.h"
#include <QMessageBox>
#include <QString>

WelcomePage::WelcomePage(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::WelcomePage)
{
    ui->setupUi(this);
    this->resize(QSize(800,600));
    this->setAttribute(Qt::WA_DeleteOnClose);
   // QMovie *welcomemovie = new QMovie(":/Image/sakura_pink1.gif");
   // ui->label->setMovie(welcomemovie);
   // welcomemovie->start();

    loginsocket = new QTcpSocket();
    QObject::connect(loginsocket, &QTcpSocket::readyRead, this, &WelcomePage::socket_Read_Data);

    ui->adminpassword->setEchoMode(QLineEdit::Password);
}

WelcomePage::~WelcomePage()
{
    delete ui;
}

void WelcomePage::on_En_Bt_clicked()
{
    if(ui->adminID->text()==""|| ui->adminpassword->text()=="")
    QMessageBox::warning(this, tr("错误"), tr("请输入账号和密码"));
    else
    {
       // QHostAddress hostaddress;
        hostaddress.setAddress(QString("35.194.106.246"));
        loginsocket->connectToHost(hostaddress,8333);

        if(!loginsocket->waitForConnected(30000))
        {
        QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
        return;
        }

      //  QJsonObject loginjson;
        loginjson.insert("jsontype",1);
        loginjson.insert("adminname", ui->adminID->text());
        loginjson.insert("password", ui->adminpassword->text());

        QJsonDocument sendjson;
        sendjson.setObject(loginjson);
        bytearray = sendjson.toJson(QJsonDocument::Compact);
       // loginsocket->write( std::to_string(bytearray.size()).c_str() );
        loginsocket->write(bytearray);
    }
}


void WelcomePage::socket_Read_Data()
{
    QByteArray getbuffer;
    getbuffer = loginsocket->readAll();

    QJsonDocument getdocument = QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();
    QJsonValue jsontypevalue = rootobj.value("confirmtype");
    int index = jsontypevalue.toInt();

    if(index == 0)
    {
        this->hide();
        MainPage *mainpage = new MainPage(this);
        connect(mainpage,SIGNAL(sendsignal()),this,SLOT(reshow())) ;
        mainpage->show();
    }
    else if(index == 1)
    {
         QMessageBox::warning(this, tr("错误"), tr("账号或密码不正确！"));
    }

}









void WelcomePage::on_Ex_Bt_clicked()
{
    this->close();
}

void WelcomePage::reshow()
{
    this->show();
}

