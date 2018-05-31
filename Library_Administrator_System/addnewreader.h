#ifndef ADDNEWREADER_H
#define ADDNEWREADER_H

#include <QDialog>

namespace Ui {
class Addnewreader;
}

class Addnewreader : public QDialog
{
    Q_OBJECT

public:
    explicit Addnewreader(QWidget *parent = 0);
    ~Addnewreader();

private slots:
    void on_pushButton_2_clicked();

    void on_pushButton_clicked();

private:
    Ui::Addnewreader *ui;
};

#endif // ADDNEWREADER_H
