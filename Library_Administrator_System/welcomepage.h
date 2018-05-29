#ifndef WELCOMEPAGE_H
#define WELCOMEPAGE_H

#include <QMainWindow>
#include <QMovie>

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

private:
    Ui::WelcomePage *ui;
};

#endif // WELCOMEPAGE_H
