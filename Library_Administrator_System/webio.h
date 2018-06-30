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

public:
    explicit WebIO(QObject *parent = nullptr);
    static WebIO* Singleton() {
        if(WebIO::instance == nullptr)
            WebIO::instance = new WebIO;
        return WebIO::instance;
    }
    static QTcpSocket *getSocket() {
        if(WebIO::socket == nullptr)
            WebIO::socket = new QTcpSocket;
        return WebIO::instance->socket;
    };
    int sendMessage(const QByteArray& message);
    QJsonDocument readJsonDocument();

signals:

public slots:
};

#endif // WEBIO_H
