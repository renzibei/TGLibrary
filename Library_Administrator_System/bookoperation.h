#ifndef bookoperation_H
#define bookoperation_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>
#include <QString>
#include <QMessageBox>

//#include "realbook.h"


namespace Ui {
class bookoperation;
}

class bookoperation : public QDialog
{
    Q_OBJECT

public:
    explicit bookoperation(QWidget *parent = 0);
    ~bookoperation();

    //用于窗口传值的重要变量
     QJsonObject booktransferobject; //可能需要变成指针
     int operationtype = 0;
     void writeinformation();
     QJsonArray sonadvance;

private slots:

    void on_add_Books_clicked();

    void on_Back_Button_clicked();

    void socket_Read_Data();

    void on_AddRealBooks_clicked();

private:
    Ui::bookoperation *ui;

    QTcpSocket *bookoperationsocket;
    QByteArray bytearray;
    QJsonObject bookoperationjson;
    QHostAddress hostaddress;
};

#endif // bookoperation_H
