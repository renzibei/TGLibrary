#ifndef MAINPAGE_H
#define MAINPAGE_H

#include <QDialog>

namespace Ui {
class MainPage;
}

class MainPage : public QDialog
{
    Q_OBJECT

public:
    explicit MainPage(QWidget *parent = 0);
    ~MainPage();

private slots:
    void on_Return_bt_clicked();

    void on_Book_bt_clicked();

private:
    Ui::MainPage *ui;
};

#endif // MAINPAGE_H
