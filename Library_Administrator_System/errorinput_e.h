#ifndef ERRORINPUT_E_H
#define ERRORINPUT_E_H

#include <QDialog>

namespace Ui {
class ErrorInput_E;
}

class ErrorInput_E : public QDialog
{
    Q_OBJECT

public:
    explicit ErrorInput_E(QWidget *parent = 0);
    ~ErrorInput_E();

private:
    Ui::ErrorInput_E *ui;
};

#endif // ERRORINPUT_E_H
