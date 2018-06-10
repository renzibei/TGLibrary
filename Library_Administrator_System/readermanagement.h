#ifndef READERMANAGEMENT_H
#define READERMANAGEMENT_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

namespace Ui {
class ReaderManagement;
}

class ReaderManagement : public QDialog
{
    Q_OBJECT

public:
    explicit ReaderManagement(QWidget *parent = 0);
    ~ReaderManagement();

private slots:
    void on_pushButton_2_clicked();

    void on_pushButton_3_clicked();

    void on_pushButton_4_clicked();

    void socket_Read_Data();

private:
    Ui::ReaderManagement *ui;
    QTcpSocket *readersocket;
    QByteArray bytearray;
    QJsonObject readerjson;
    QHostAddress hostaddress;
};

#endif // READERMANAGEMENT_H
