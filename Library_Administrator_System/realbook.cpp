#include "realbook.h"
#include "ui_realbook.h"
#include "webio.h"

RealBook::RealBook(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::RealBook)
{
    ui->setupUi(this);
    realbooksocket = WebIO::getSocket();//new QTcpSocket;

    QObject::connect(realbooksocket, &QTcpSocket::readyRead, this, &RealBook::socket_Read_Data);
    this->setAttribute(Qt::WA_DeleteOnClose);

    hostaddress.setAddress(QString("35.194.106.246"));
    realbooksocket->connectToHost(hostaddress,8333);

    if(!realbooksocket->waitForConnected(3000))
    {
    QMessageBox::warning(this, tr("错误"), tr("未能连接到服务器，请检查网络设置！"));
    this->close();
    }


}

RealBook::~RealBook()
{
    delete ui;
}

void RealBook::on_backbutton_clicked()
{
    this->close();
}

void RealBook::on_Addrealbook_clicked()
{


    if(ui->PlaceEdit->text()=="")
    {
    QMessageBox::warning(this, tr("错误"), tr("没有位置怎么能够称为实体书呢……"));
    return;
    }

    realbookjson.insert("_place", ui->PlaceEdit->text());
    realbookjson.insert("_version", ui->VersionEdit->text());
    realbookjson.insert("_bookId", virtualbookobject.value("docID").toString());
    realbookjson.insert("jsontype", "4");

    QJsonDocument jsondoc;
    jsondoc.setObject(realbookjson);
    bytearray = jsondoc.toJson(QJsonDocument::Compact);
    //booksocket->write( std::to_string(bytearray.size()).c_str() );
    realbooksocket->write(bytearray);


}

void RealBook::socket_Read_Data()
{
    QByteArray getbuffer;
    //getbuffer = WebIO::Singleton()->readJsonDocument();//realbooksocket->readAll();

    QJsonDocument getdocument = WebIO::Singleton()->readJsonDocument();//QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();

    QJsonValue confirmvalue = rootobj.value("confirmtype");

    int index = confirmvalue.toInt();
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
