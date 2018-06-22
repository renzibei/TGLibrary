#ifndef BOOKMANAGEMENT_H
#define BOOKMANAGEMENT_H

#include <QDialog>

#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QJsonArray>
#include <QVector>

#include <QtNetwork>

#include "bookoperation.h"

namespace Ui {
class BookManagement;
}

class BookManagement : public QDialog
{
    Q_OBJECT

public:
    explicit BookManagement(QWidget *parent = 0);
    ~BookManagement();

    QVector<QJsonObject> counterpartobject;

private slots:
    void on_AddBook_Bt_clicked();

    void on_Delete_Book_clicked();

    void on_detailed_information_clicked();

    void on_Modify_Book_clicked();

    void on_advancedsearch_clicked();

    void socket_Read_Data();

    void on_SearchBook_clicked();

private:
    Ui::BookManagement *ui;
    QTcpSocket *booksocket;
    QByteArray bytearray;
    QJsonObject bookjson;
    QHostAddress hostaddress;
};

#endif // BOOKMANAGEMENT_H
