#include "webio.h"

WebIO* WebIO::instance = nullptr;
QTcpSocket* WebIO::socket = nullptr;


WebIO::WebIO(QObject *parent) : QObject(parent)
{
    this->socket =  WebIO::getSocket();//new QTcpSocket(this);
    //connect(socket, &QTcpSocket::readyRead, this, &TestClass::slotReadForRead);
    //socket->connectToHost(QHostAddress("35.194.106.246"), 8333);
    //this->dataStream = new QDataStream(this->socket);
    //this->dataStream->setByteOrder(QDataStream::BigEndian);
}

int WebIO::getIntFromBuffer(const QByteArray &buffer)
{
    QDataStream tempStream(buffer);
    tempStream.setByteOrder(QDataStream::BigEndian);
    int x;
    tempStream >> x;
    return x;
}

int WebIO::readIntoBuffer(QByteArray& buffer, int len)
{
    int leftBytes = len;
    QByteArray tempBuffer, result;
    int i = 0;
    while(leftBytes > 0) {
        tempBuffer = this->socket->read(leftBytes);
        leftBytes -= tempBuffer.length();
        result.append(tempBuffer);
        i++;
        if(i > 1000000)
            return -1;
    }
    buffer = std::move(tempBuffer);
    qDebug() << result;
    return buffer.length();
}

int WebIO::sendMessage(const QByteArray& message)
{
    QDataStream dataStream((WebIO::getSocket()));
    dataStream.setByteOrder(QDataStream::BigEndian);
    dataStream << message.length();
    int leftBytes = message.length(), tempLen;
    while(leftBytes > 0) {
        tempLen = this->socket->write(message);
        if(tempLen == -1) {
            qDebug() << "errorCode " << WebIO::getSocket()->error();
            qDebug() << this->socket->errorString();
            if(this->socket->error() == QTcpSocket::UnknownSocketError) {
                socket->connectToHost(QHostAddress("35.194.106.246"), 8333);
            }
        }

        leftBytes -= tempLen;
    }
}

QJsonDocument WebIO::readJsonDocument()
{
    QByteArray byteBuffer = this->socket->read(4);
    int len = this->getIntFromBuffer(byteBuffer);
    int status = this->readIntoBuffer(byteBuffer, len);
    QJsonDocument tempJson;
    if(status > 0)
        tempJson = QJsonDocument::fromJson(byteBuffer);
    return tempJson;
}

