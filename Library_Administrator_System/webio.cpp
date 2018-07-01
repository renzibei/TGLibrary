#include "webio.h"

WebIO* WebIO::instance = nullptr;
QTcpSocket* WebIO::socket = nullptr;


WebIO::WebIO(QObject *parent) : QObject(parent)
{
    //this->window = nullptr;
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
    int leftBytes = len, tempLen;
    QByteArray tempBuffer, result;
    char *tempStr;
    int i = 0;
   // if(leftBytes == 4681)
    //    leftBytes = 5000;

    while(leftBytes > 0) {
        /*
        if(WebIO::getSocket()->waitForReadyRead(30000) == false) {
            qDebug() << WebIO::getSocket()->state();
            qDebug() << WebIO::getSocket()->error();
            qDebug() << "超时";
            continue;
            //return -1;
        }
        */
        //tempBuffer = this->socket->read(leftBytes);
        tempStr = new char[leftBytes+1];
        memset(tempStr, 0, leftBytes + 1);
        tempLen = WebIO::getSocket()->read(tempStr, leftBytes);
        leftBytes -= tempLen;
        result.append(tempStr);
        if(leftBytes > 0) {
            if(WebIO::getSocket()->waitForReadyRead(30000) == false)
                qDebug() << "接受超时";
        }
        i++;
        if(i > 1000) {
            qDebug() << WebIO::getSocket()->errorString();
            return -1;

        }
    }
    buffer = std::move(result);
    qDebug() << buffer;
    return buffer.length();
}

int WebIO::sendMessage(const QByteArray& message, const QObject* window , const char* member)
{
    if(window == nullptr) {
        qDebug() << "window is null";
        return -1;
    }
    qDebug() << "state" << this->socket->state();
    if(WebIO::getSocket()->waitForConnected(30000) == 0)
        qDebug() << "Cannot connected";
    else {
        disconnect(this->socket, 0, 0, 0);
        connect(this->socket, SIGNAL(readyRead()), window, member);
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
                    //socket->connectToHost(QHostAddress("35.194.106.246"), 8333);
                }
            }

            leftBytes -= tempLen;
        }
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


