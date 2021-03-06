#include "bookmanagement.h"
#include "ui_bookmanagement.h"

#include "bookoperation.h"
#include "webio.h"

#include <QMessageBox>
#include <QString>

BookManagement::BookManagement(QWidget *parent) :
    QDialog(parent),
    ui(new Ui::BookManagement)
{
    ui->setupUi(this);

    this->setAttribute(Qt::WA_DeleteOnClose);

    connect(ui->BookM_Return_bt, SIGNAL(clicked()), this, SLOT(close()));

    ui->tableWidget->setSelectionBehavior(QAbstractItemView::SelectRows);

    booksocket = WebIO::getSocket();//new QTcpSocket;


}

BookManagement::~BookManagement()
{

    booksocket->disconnectFromHost();
      delete ui;
}

void BookManagement::on_AddBook_Bt_clicked()
{
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this);
    bookoperation1->operationtype = 3;
    bookoperation1->defaultchousen();

    bookoperation1->show();
    bookoperation1->exec();
    this->show();
}





void BookManagement::on_Delete_Book_clicked()
{


    if(ui->tableWidget->rowCount() == 0)
    {
        QMessageBox::warning(this, tr("错误"), tr("请搜索书目，之后单击书目所在行，然后删除！"));
        return;
    }

    int rownumber = ui->tableWidget->currentRow();

    QJsonObject onloadbookjson;

    onloadbookjson.insert("jsontype","3");
 //此处有问题
    onloadbookjson.insert("docID",ui->tableWidget->item(rownumber,3)->text());

    QJsonDocument jsondoc;
    jsondoc.setObject(onloadbookjson);
    bytearray = jsondoc.toJson(QJsonDocument::Compact);
   // booksocket->write( std::to_string(bytearray.size()).c_str() );

    WebIO::Singleton()->sendMessage(bytearray, this, SLOT(socket_Read_Data()));

    //booksocket->write(bytearray);

}



void BookManagement::on_detailed_information_clicked()
{
    if(ui->tableWidget->rowCount()==0)
    {
    QMessageBox::warning(this, tr("错误"), tr("请搜索到书籍后点击书籍所在行进行查询"));
    return;
    }
    else
    {
   int rownumber = ui->tableWidget->currentRow();
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this);
    bookoperation1->operationtype = 0;
    bookoperation1->defaultchousen();

    bookoperation1->booktransferobject = counterpartobject[rownumber];
    bookoperation1->writeinformation();
    bookoperation1->show();
    bookoperation1->exec();
    this->show();
    }
}

void BookManagement::on_Modify_Book_clicked()
{
    if(ui->tableWidget->rowCount()==0)
    {
    QMessageBox::warning(this, tr("错误"), tr("请搜索到书籍后点击书籍所在行进行查询"));
    return;
    }
    else
    {
    int rownumber = ui->tableWidget->currentRow();
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this);
    bookoperation1->operationtype = 1;
    bookoperation1->booktransferobject = counterpartobject[rownumber];
    bookoperation1->writeinformation();
    bookoperation1->show();
    bookoperation1->exec();
    this->show();
    this->getadvancedresult();

    }
}

void BookManagement::on_advancedsearch_clicked()
{
    int rownumber = ui->tableWidget->currentRow();
    this->hide();
    bookoperation *bookoperation1 = new bookoperation(this);
    bookoperation1->operationtype = 2;
    bookoperation1->defaultchousen();
    bookoperation1->show();
    bookoperation1->exec();

    this->show();
    this->getadvancedresult();

}

void BookManagement::on_SearchBook_clicked()
{
    if(ui->Book_Edit->text()=="")
    QMessageBox::warning(this, tr("错误"), tr("请输入"));
    else
    {

        bookjson.insert("jsontype","5");
        bookjson.insert("keywords",ui->Book_Edit->text());

        QJsonDocument jsondoc;
        jsondoc.setObject(bookjson);
        bytearray = jsondoc.toJson(QJsonDocument::Compact);
        //booksocket->write( std::to_string(bytearray.size()).c_str() );

        WebIO::Singleton()->sendMessage(bytearray, this, SLOT(socket_Read_Data()));

        //booksocket->write(bytearray);

}
}
void BookManagement::socket_Read_Data()
{
    QByteArray getbuffer;
    //getbuffer = WebIO::Singleton()->readJsonDocument();//booksocket->readAll();

    QJsonDocument getdocument = WebIO::Singleton()->readJsonDocument();//QJsonDocument::fromJson(getbuffer);
    QJsonObject rootobj = getdocument.object();
    qDebug() << rootobj;
    QJsonValue jsonvalue = rootobj.value("jsontype");

    QString jsonvaluenumber = jsonvalue.toString();

    QJsonValue confirmvalue = rootobj.value("confirmtype");



    if(jsonvaluenumber == "5" )// || jsonvaluenumber == 6

    {
        QJsonValue jsontypevalue = rootobj.value("documents");
        qDebug() << jsontypevalue;

        QString index = confirmvalue.toString();

        if(index == "2")
        {
            QMessageBox::warning(this, tr("错误"), tr("终端出现错误，请检查网络设置!"));
            return;
        }
        if(jsontypevalue.toArray().size()== 0)
        {
            QMessageBox::information(this, tr("错误"), tr("没有查询到相关书籍！"));
            return;
        }

        counterpartobject.clear();
        ui->tableWidget->setRowCount(0);
        ui->tableWidget->clearContents();

        QJsonArray bookarray = jsontypevalue.toArray();

        ui->tableWidget->setRowCount(bookarray.size());

        for(int i = 0; i<bookarray.size(); i++)
        {
            //QJsonValue booknumber = bookarray.at(i);似乎完全多余并没有必要wazawaza走这一步
            QJsonObject iteratorobject = bookarray.at(i).toObject();
            counterpartobject.push_back(iteratorobject);

            QJsonArray authorarray = iteratorobject.value("authors").toArray();
            QString authorstring = "";
            for(int i=0; i<authorarray.size(); i++)
            authorstring = authorstring + authorarray.at(i).toString() + "; ";

            QJsonValue titlevalue = iteratorobject.value("title");
            QJsonValue authorvalue = iteratorobject.value("authors");
            QJsonValue publishervalue = iteratorobject.value("publisher");
            QJsonValue docIDvalue = iteratorobject.value("docID");

            ui->tableWidget->setItem(i,0,new QTableWidgetItem(titlevalue.toString()));
            ui->tableWidget->setItem(i,1,new QTableWidgetItem(authorstring.left(authorstring.size()-2   )));
            ui->tableWidget->setItem(i,2,new QTableWidgetItem(publishervalue.toString()));
            ui->tableWidget->setItem(i,3,new QTableWidgetItem(docIDvalue.toString()));

        }
    }
    else if (jsonvaluenumber == "3")
    {
        int index = confirmvalue.toInt();

        if(index == 2)
        {
            QMessageBox::warning(this, tr("错误"), tr("终端发生错误，请检查网络设置"));
            return;
        }
        if(index == 0)
        {
            QMessageBox::information(this, tr("成功"), tr("删除成功！"));
            ui->tableWidget->removeRow(ui->tableWidget->currentRow());
            return;
        }
    }
}

void BookManagement::getadvancedresult(){
    ui->tableWidget->setRowCount(0);
    ui->tableWidget->clearContents();
    ui->tableWidget->setRowCount(advancetransfer.size());

    for(int i = 0; i<advancetransfer.size(); i++)
    {
        QJsonObject iteratorobject = advancetransfer.at(i).toObject();

        QJsonArray authorarray = iteratorobject.value("authors").toArray();
        QString authorstring = "";
        for(int i=0; i<authorarray.size(); i++)
        authorstring = authorstring +authorarray.at(i).toString() + "; ";

        QJsonValue titlevalue = iteratorobject.value("title");
        QJsonValue authorvalue = iteratorobject.value("authors");
        QJsonValue publishervalue = iteratorobject.value("publisher");
        QJsonValue docIDvalue = iteratorobject.value("docID");

        ui->tableWidget->setItem(i,0,new QTableWidgetItem(titlevalue.toString()));
        ui->tableWidget->setItem(i,1,new QTableWidgetItem(authorstring.left(authorstring.size()-2)));
        ui->tableWidget->setItem(i,2,new QTableWidgetItem(publishervalue.toString()));
        ui->tableWidget->setItem(i,3,new QTableWidgetItem(docIDvalue.toString()));

    }

}


