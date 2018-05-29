#ifndef BOOKMANAGEMENT_H
#define BOOKMANAGEMENT_H

#include <QDialog>

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

private:
    Ui::BookManagement *ui;
};

#endif // BOOKMANAGEMENT_H
