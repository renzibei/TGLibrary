#ifndef CONFIRM_H
#define CONFIRM_H

#include <QDialog>

namespace Ui {
class Confirm;
}

class Confirm : public QDialog
{
    Q_OBJECT

public:
    explicit Confirm(QWidget *parent = 0);
    ~Confirm();

private:
    Ui::Confirm *ui;
};

#endif // CONFIRM_H
