#ifndef ADDNEWREADER_H
#define ADDNEWREADER_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

namespace Ui {
class Addnewreader;
}

class Addnewreader : public QDialog
{
    Q_OBJECT

public:
    explicit Addnewreader(QWidget *parent = 0);
    ~Addnewreader();

private slots:
    void on_pushButton_2_clicked();

    void on_pushButton_clicked();

    void socket_Read_Data();

private:
    Ui::Addnewreader *ui;
    QTcpSocket *addreadersocket;
    QByteArray bytearray;
    QJsonObject addreaderjson;
    QHostAddress hostaddress;
};

#endif // ADDNEWREADER_H
