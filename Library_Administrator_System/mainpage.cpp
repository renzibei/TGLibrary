#include "mainpage.h"
#include "ui_mainpage.h"
#include "readermanagement.h"
#include "bookmanagement.h"
#include "record.h"
#include "confirm.h"

MainPage::MainPage(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::MainPage)
{
    ui->setupUi(this);
    //this->setAttribute(Qt::WA_DeleteOnClose);
  //  connect(ui->Return_bt, SIGNAL(clicked()),this,SLOT(close()));
  //  QMovie *mainpagemovie = new QMovie(":/Image/sakura_pink1.gif");
    //setMovie(mainpagemovie);
   // mainpagemovie->start();
   // QMovie movie = QMovie(":/Image/sakura_pink1.gif").scaledSize(this->size());
    QPixmap pixmap =  QPixmap(":/Image/sakura_pink1.gif").scaled(this->size());
    QPalette palette(this->palette());
    palette.setBrush(QPalette::Background,QBrush(pixmap));
    this->setPalette(palette);
}

MainPage::~MainPage()
{
    delete ui;
}


void MainPage::on_Book_bt_clicked()
{
  //  this->hide();
    BookManagement *bookmanagement = new BookManagement(this);
 //   bookmanagement->setAttribute(Qt::WA_DeleteOnClose);
    bookmanagement->show();
   // bookmanagement->exec();
  //ne  this->show();
}

void MainPage::on_Return_bt_clicked()
{
    emit sendsignal();
    this->close();
}

void MainPage::on_toolButton_2_clicked()
{
   // this->hide();
    ReaderManagement *readermanagement = new ReaderManagement(this);
    readermanagement->show();
    //readermanagement->exec();
    //this->show();
}

void MainPage::on_toolButton_clicked()
{
    //this->hide();
    Record *record = new Record(this);
    record->show();
    //record->exec();
    //this->show();
}

void MainPage::on_To_Confirm_button_clicked()
{
    //this->hide();
    Confirm *confirm = new Confirm(this);
    confirm->show();
    //confirm->exec();
    //this->show();
}
