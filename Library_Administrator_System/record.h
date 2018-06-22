#ifndef RECORD_H
#define RECORD_H

#include <QDialog>


#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

#include <QMessageBox>

namespace Ui {
class Record;
}

class Record : public QDialog
{
    Q_OBJECT

public:
    explicit Record(QWidget *parent = 0);
    ~Record();

private slots:
    void on_pushButton_clicked();

    void socket_Read_Data();

    void on_Record_Return_bt_clicked();

private:
    Ui::Record *ui;

    QTcpSocket *recordsocket;
    QByteArray bytearray;
    QJsonObject recordjson;
    QHostAddress hostaddress;
};

#endif // RECORD_H
