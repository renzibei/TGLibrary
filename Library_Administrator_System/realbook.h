#ifndef REALBOOK_H
#define REALBOOK_H

#include <QDialog>

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

#include <QMessageBox>

#include <QString>

namespace Ui {
class RealBook;
}

class RealBook : public QDialog
{
    Q_OBJECT

public:
    explicit RealBook(QWidget *parent = 0);
    ~RealBook();

    QJsonObject virtualbookobject ;



private slots:
    void on_backbutton_clicked();

    void on_Addrealbook_clicked();

    void socket_Read_Data();


private:
    Ui::RealBook *ui;
    QTcpSocket *realbooksocket;
    QByteArray bytearray;
    QJsonObject realbookjson;
    QHostAddress hostaddress;
};

#endif // REALBOOK_H
