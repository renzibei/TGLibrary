#ifndef CONFIRM_H
#define CONFIRM_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

namespace Ui {
class Confirm;
}

class Confirm : public QDialog
{
    Q_OBJECT

public:
    explicit Confirm(QWidget *parent = 0);
    ~Confirm();

private slots:
    void on_update_record_clicked();

    void on_AcceptRequest_clicked();

    void on_KotowariRecord_clicked();

    void socket_Read_Data();

private:
    Ui::Confirm *ui;
    QTcpSocket *confirmsocket;
    QByteArray bytearray;
    QJsonObject confirmjson;
    QHostAddress hostaddress;
};

#endif // CONFIRM_H
