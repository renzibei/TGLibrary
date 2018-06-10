#ifndef WELCOMEPAGE_H
#define WELCOMEPAGE_H

#include <QMainWindow>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

namespace Ui {
class WelcomePage;
}

class WelcomePage : public QMainWindow
{
    Q_OBJECT

public:
    explicit WelcomePage(QWidget *parent = 0);
    ~WelcomePage();

private slots:
    void on_En_Bt_clicked();
    void on_Ex_Bt_clicked();
    void reshow();

    void socket_Read_Data();

private:
    Ui::WelcomePage *ui;
    QTcpSocket *loginsocket;
    QByteArray bytearray;
    QJsonObject loginjson;
    QHostAddress hostaddress;
};

#endif // WELCOMEPAGE_H
