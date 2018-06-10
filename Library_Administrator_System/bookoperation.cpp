#include "bookoperation.h"
#include "ui_bookoperation.h"

bookoperation::bookoperation(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::bookoperation)
{

    ui->setupUi(this);
    ui->bookinformation->setColumnWidth(1,600);
    ui->bookinformation->setItem(0,0,Qt::AlignLeft);
    ui->bookinformation->resizeColumnsToContents();
    ui->bookinformation->resizeRowsToContents();
    ui->bookinformation->verticalHeader()->setVisible(false);
    ui->bookinformation->setAlternatingRowColors(true);


}

bookoperation::bookoperation(QWidget *parent, int i) : bookoperation(QWidget *parent)
{
    if(i==1)
    ui->bookinformation->setEditTriggers(QAbstractItemView::NoEditTriggers);


}

bookoperation::~bookoperation()
{
    delete ui;
}
