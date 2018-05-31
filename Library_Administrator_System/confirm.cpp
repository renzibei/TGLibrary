#include "confirm.h"
#include "ui_confirm.h"
#include <QMessageBox>

Confirm::Confirm(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::Confirm)
{
    ui->setupUi(this);
    this->setAttribute(Qt::WA_DeleteOnClose);
    connect(ui->Confirm_Return_bt, SIGNAL(clicked()), this, SLOT(close()));
}

Confirm::~Confirm()
{
    delete ui;
}
