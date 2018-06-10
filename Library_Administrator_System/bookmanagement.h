#ifndef BOOKMANAGEMENT_H
#define BOOKMANAGEMENT_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

namespace Ui {
class BookManagement;
}

class BookManagement : public QDialog
{
    Q_OBJECT

public:
    explicit BookManagement(QWidget *parent = 0);
    ~BookManagement();

private slots:
    void on_AddBook_Bt_clicked();

    void on_Delete_Book_clicked();

    void on_pushButton_clicked();

    void on_detailed_information_clicked();

    void on_Modify_Book_clicked();

    void on_advancedsearch_clicked();

private:
    Ui::BookManagement *ui;
};

#endif // BOOKMANAGEMENT_H
