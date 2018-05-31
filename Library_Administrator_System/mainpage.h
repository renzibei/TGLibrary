#ifndef MAINPAGE_H
#define MAINPAGE_H

#include <QDialog>
#include <QMovie>

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
    void on_Book_bt_clicked();

    void on_Return_bt_clicked();

    void on_toolButton_2_clicked();

    void on_toolButton_clicked();

    void on_To_Confirm_button_clicked();

signals:
    void sendsignal();

private:
    Ui::MainPage *ui;
};

#endif // MAINPAGE_H
