#ifndef READERMANAGEMENT_H
#define READERMANAGEMENT_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

#include <QVector>

namespace Ui {
class ReaderManagement;
}

class ReaderManagement : public QDialog
{
    Q_OBJECT

public:
    explicit ReaderManagement(QWidget *parent = 0);
    ~ReaderManagement();

    QVector<QJsonObject> counterpartjson;
    int typenumber = 0;

private slots:
    void on_pushButton_2_clicked();

    void on_pushButton_4_clicked();

    void socket_Read_Data();

    void on_Modifieduser_clicked();

    void on_delete_hito_clicked();

private:
    Ui::ReaderManagement *ui;
    QTcpSocket *readersocket;
    QByteArray bytearray;
    QJsonObject readerjson;
    QHostAddress hostaddress;

    QJsonObject transferobject;
};

#endif // READERMANAGEMENT_H
