#ifndef bookoperation_H
#define bookoperation_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

#include <QMessageBox>


namespace Ui {
class bookoperation;
}

class bookoperation : public QDialog
{
    Q_OBJECT

public:
    explicit bookoperation(QWidget *parent = 0);
  //  bookoperation(QWidget *parent = 0, int i =1);
    ~bookoperation();

private slots:
    void on_pushButton_2_clicked();

    void on_add_Books_clicked();

private:
    Ui::bookoperation *ui;

    QTcpSocket *bookoperationsocket;
    QByteArray bytearray;
    QJsonObject bookoperationjson;
    QHostAddress hostaddress;
};

#endif // bookoperation_H
