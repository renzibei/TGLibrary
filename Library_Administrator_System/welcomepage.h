#ifndef WELCOMEPAGE_H
#define WELCOMEPAGE_H

#include <QMainWindow>
#include "mainpage.h" //For windows shifted

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

private:
    Ui::WelcomePage *ui;
  //  MainPage mainpage;
};

#endif // WELCOMEPAGE_H
