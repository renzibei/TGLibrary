#ifndef READERMANAGEMENT_H
#define READERMANAGEMENT_H

#include <QDialog>

namespace Ui {
class ReaderManagement;
}

class ReaderManagement : public QDialog
{
    Q_OBJECT

public:
    explicit ReaderManagement(QWidget *parent = 0);
    ~ReaderManagement();

private:
    Ui::ReaderManagement *ui;
};

#endif // READERMANAGEMENT_H
