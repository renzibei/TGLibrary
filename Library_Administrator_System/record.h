#ifndef RECORD_H
#define RECORD_H

#include <QDialog>

namespace Ui {
class Record;
}

class Record : public QDialog
{
    Q_OBJECT

public:
    explicit Record(QWidget *parent = 0);
    ~Record();

private:
    Ui::Record *ui;
};

#endif // RECORD_H
