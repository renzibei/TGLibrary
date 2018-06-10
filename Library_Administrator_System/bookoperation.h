#ifndef bookoperation_H
#define bookoperation_H

#include <QDialog>
#include <QTcpSocket>
#include <QJsonObject>
#include <QJsonDocument>
#include <QtNetwork>

namespace Ui {
class bookoperation;
}

class bookoperation : public QDialog
{
    Q_OBJECT

public:
    explicit bookoperation(QWidget *parent = 0);
    //bookoperation(QWidget *parent = 0, int i =1);
    ~bookoperation();

private:
    Ui::bookoperation *ui;

};

#endif // bookoperation_H
