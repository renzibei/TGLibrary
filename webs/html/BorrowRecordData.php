<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/10
 * Time: 11:17
 */

namespace tg;

require_once 'BorrowRecord.php';

class BorrowRecordData
{





    /**
     * @param BorrowRecord $borrowRecord
     * @throws \Exception
     */
    public function addBorrowRequest(BorrowRecord &$borrowRecord)
    {
        $insertBorrowRequestSql = "INSERT INTO " . systemConfig\config['borrowRequest'] . " ( userId ) VALUES ( " . $borrowRecord->getUser()->getUserId() . " )";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($insertBorrowRequestSql);
        if($result === false) {
            throw new \Exception("Fail to insert borrowRecord " . $conn->error, errorCode\InsertIntoTableError);
        }
        $getRecordIdSql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($getRecordIdSql);
        if($result === false)
            throw new \Exception("Fail to get request Id from Table " . $conn->error, errorCode\QueryTableError);
        $row = $result->fetch_row();
        $borrowRecord->setRequestId($row[0]);
        $borrowRecord->updateData();

    }

    /**
     * @param $requestId
     * @param null $recordId
     * @return bool|BorrowRecord
     * @throws \Exception
     */
    public function getBorrowRecord($requestId, $recordId = null)
    {
        $requestSql = "SELECT * FROM " . systemConfig\config['borrowRequest'] . " WHERE requestId = $requestId";
        $conn = SystemFrame::instance()->getConnection();
        $requestResult = $conn->query($requestSql);
        if($requestResult === false)
            throw new \Exception("Fail to query requestRecord " . $conn->error, errorCode\QueryTableError);
        $listLength = $requestResult->num_rows;

        if($row = $requestResult->fetch_assoc()) {
            $user = SystemFrame::userData()->queryFromId($row['userId']);
            $book = SystemFrame::docData()->getDocument($row['docId']);
            $realBook = SystemFrame::docData()->getRealBook($row['bookId']);

            $borrowRecord = new BorrowRecord($user, $realBook, $book, NULL,
                $row['isAnswered'], $row['allow'], NULL, NULL, $row['requestTime'], $row['answerTime'], NULL);


            if(!empty($recordId)) {
                $recordSql = "SELECT * FROM " . systemConfig\config['borrowRecord'] . " WHERE recordId = $recordId";
                $recordResult = $conn->query($recordSql);
                if ($recordResult === false)
                    throw new \Exception("Fail to query borrowRecord" . $conn->error, errorCode\QueryTableError);

                $row = $recordResult->fetch_assoc();
                $borrowRecord->setRecordId($row['recordId']);
                $borrowRecord->setIsReturned($row['returned']);
                $borrowRecord->setBeginDate($row['beginDate']);
                $borrowRecord->setDueDate($row['dueDate']);
                $borrowRecord->setReturnDate($row['returnDate']);
            }
            return $borrowRecord;
        }
        return false;
    }




    /**
     * @return array
     * @throws \Exception
     */
    public function getBorrowRecordList()
    {
        $requestSql = "SELECT * FROM " . systemConfig\config['borrowRequest'];
        $conn = SystemFrame::instance()->getConnection();
        $requestResult = $conn->query($requestSql);
        if($requestResult === false)
            throw new \Exception("Fail to query requestRecord " . $conn->error, errorCode\QueryTableError);
        $listLength = $requestResult->num_rows;
        $borrowRecordList = array();
        while($row = $requestResult->fetch_assoc()) {
            $user = SystemFrame::userData()->queryFromId($row['userId']);
            $book = SystemFrame::docData()->getDocument($row['docId']);
            $realBook = SystemFrame::docData()->getRealBook($row['bookId']);

            $borrowRecord = new BorrowRecord($user, $realBook, $book, NULL,
                            $row['isAnswered'], $row['allow'], NULL, NULL, $row['requestTime'], $row['answerTime'], NULL);
            $borrowRecordList[] = $borrowRecord;
        }
        $recordSql = "SELECT * FROM " . systemConfig\config['borrowRecord'];
        $recordResult = $conn->query($recordSql);
        if($recordResult === false)
            throw new \Exception("Fail to query borrowRecord" . $conn->error, errorCode\QueryTableError);
        if($recordResult->num_rows != $listLength)
            throw new \Exception("记录出现了偏差", errorCode\BorrowRecordNotEqualRequest);
        for($i = 0; $i < $listLength; ++$i) {
            $row = $recordResult->fetch_assoc();
            $borrowRecordList[$i]->setRecordId($row['recordId']);
            $borrowRecordList[$i]->setIsReturned($row['returned']);
            $borrowRecordList[$i]->setBeginDate($row['beginDate']);
            $borrowRecordList[$i]->setDueDate($row['dueDate']);
            $borrowRecordList[$i]->setReturnDate($row['returnDate']);
        }
        return $borrowRecordList;
    }

}