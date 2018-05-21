#include "errorinput_e.h"
#include "ui_errorinput_e.h"

ErrorInput_E::ErrorInput_E(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::ErrorInput_E)
{
    ui->setupUi(this);
}

ErrorInput_E::~ErrorInput_E()
{
    delete ui;
}
