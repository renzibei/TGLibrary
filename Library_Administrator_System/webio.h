#ifndef WEBIO_H
#define WEBIO_H

#include <QObject>
#include <QtNetwork>
#include <QJsonDocument>

class WebIO : public QObject
{
    Q_OBJECT
protected:
    static QTcpSocket *socket;
    static WebIO *instance;
    //QDataStream *dataStream;
    int getIntFromBuffer(const QByteArray &buffer);
    int readIntoBuffer(QByteArray& buffer, int len);
    //QObject *window;

public:
    explicit WebIO(QObject *parent = nullptr);
    static WebIO* Singleton() {
        if(WebIO::instance == nullptr)
            WebIO::instance = new WebIO;
        return WebIO::instance;
    }
    static QTcpSocket *getSocket() {
        if(WebIO::socket == nullptr) {
            WebIO::socket = new QTcpSocket;
            WebIO::socket->connectToHost(QHostAddress("35.194.106.246"), 8333);
            if(WebIO::socket->waitForConnected(30000) == false) {
                qDebug() << "链接超时";
            }
            else {
                qDebug() << "链接成功";
            }
        }
        if(WebIO::socket->waitForConnected(30000) == false) {
            WebIO::socket->connectToHost(QHostAddress("35.194.106.246"), 8333);
            if(WebIO::socket->waitForConnected(30000) == false) {
                qDebug() << "链接错误";
            }
        }
        return WebIO::socket;
    };
    int sendMessage(const QByteArray& message, const QObject* window, const char* member);
    QJsonDocument readJsonDocument();

signals:

public slots:
};

#endif // WEBIO_H
