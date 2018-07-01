<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/8
 * Time: 21:09
 */

namespace tg;

require_once 'User.php';



class BorrowRecord
{
    protected $user;
    protected $recordId;
    protected $realBook;
    protected $document;
    protected $beginDate;
    protected $requestId;
    protected $dueDate;
    protected $returnDate;
    protected $isReturned;
    protected $requestTime;
    protected $answerTime;
    protected $isAnswered;
    protected $isAllowed;


    public function __construct(User &$user, RealBook &$realBook, Book &$document, $isReturned = false,
                                $isAnswered = false, $isAllowed = false , $beginDate = NULL, $dueDate = NULL, $requestTime = NULL
                                , $answerTime = NULL, $returnDate = NULL)
    {
        $this->user = $user;
        $this->realBook = $realBook;
        $this->document = $document;
        $this->isReturned = $isReturned;
        $this->isAllowed = $isAnswered;
        $this->isAllowed = $isAllowed;
        $this->beginDate = $beginDate;
        $this->dueDate = $dueDate;
        $this->requestTime = $requestTime;
        $this->answerTime = $answerTime;
        $this->returnDate = $returnDate;
    }

    /**
     * @throws \Exception
     */
    public function updateData()
    {
        if($this->isInRequestTable() && !empty($this->requestId)) {
            $updateRequestSql = "UPDATE " . systemConfig\config['borrowRequest'] . " SET ";
            if (!empty(!$this->realBook)) {
                $updateRequestSql .= ( " bookId = " . $this->realBook->getBookId() );
            }
            if (!empty(!$this->document)) {
                $updateRequestSql .= ( " , docId = " . $this->document->getDocID() );
            }
            if (isset($this->isAnswered)) {
                $updateRequestSql .= " , isAnswered =  $this->isAllowed ";
                if(isset($this->isAllowed))
                    $updateRequestSql .= " , allow = $this->isAllowed ";
                $updateRequestSql .= " , answerTime = NOW()";
                if(!empty($this->answerTime))
                    $updateRequestSql .= ", answerTime = $this->answerTime ";
            }
            $updateRequestSql .= " WHERE requestId = $this->requestId ";
            $conn = SystemFrame::instance()->getConnection();
            $requestResult = $conn->query($updateRequestSql);
            if($requestResult === false)
                throw new \Exception("Fail to Update request Table" . $conn->error, errorCode\UpdateTableError);
        }
        if($this->isInRecordTable() && !empty($this->recordId)) {
            $updateRecordSql = "UPDATE " . systemConfig\config['borrowRecord'] . " SET ";
            if(!empty($this->user)) {
                $updateRecordSql .= " userId = " . $this->user->getUserID();
            }
            if(isset($this->isReturned)) {
                $updateRecordSql .= " , returned = $this->isReturned ";
                if($this->isReturned === true) {
                    $updateRecordSql .= ", returnDate = NOW() ";
                }
            }
            $updateRecordSql .= " WHERE recordId = $this->recordId ";
            $conn = SystemFrame::instance()->getConnection();
            $recordResult = $conn->query($updateRecordSql);
            if($recordResult === false)
                throw new \Exception("Fail to update record Sql ", errorCode\UpdateTableError);


        }

    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param RealBook $realBook
     */
    public function setRealBook(RealBook $realBook)
    {
        $this->realBook = $realBook;
    }

    /**
     * @param Book $document
     */
    public function setDocument(Book $document)
    {
        $this->document = $document;
    }

    /**
     * @param null $beginDate
     */
    public function setBeginDate($beginDate)
    {
        $this->beginDate = $beginDate;
    }

    /**
     * @param null $dueDate
     */
    public function setDueDate($dueDate)
    {
        $this->dueDate = $dueDate;
    }

    /**
     * @param null $returnDate
     */
    public function setReturnDate($returnDate)
    {
        $this->returnDate = $returnDate;
    }

    /**
     * @param bool $isReturned
     */
    public function setIsReturned(bool $isReturned)
    {
        $this->isReturned = $isReturned;
    }

    /**
     * @param null $requestTime
     */
    public function setRequestTime($requestTime)
    {
        $this->requestTime = $requestTime;
    }

    /**
     * @param null $answerTime
     */
    public function setAnswerTime($answerTime)
    {
        $this->answerTime = $answerTime;
    }

    /**
     * @param $isAnswered
     * @throws \Exception
     */
    public function setIsAnswered($isAnswered)
    {
        $this->isAnswered = $isAnswered;
        $this->updateData();
    }




    /**
     * @throws \Exception
     */
    public function returnBook()
    {
        $this->isReturned = true;
        $this->updateData();
    }

    /**
     * @param bool $ans
     * @throws \Exception
     */
    public function Answer($ans = TRUE)
    {
        $this->isAllowed = $ans;
        if($ans === TRUE) {
            $insertIntoBorrowRecord = "INSERT INTO " . systemConfig\config['borrowRecord'] . " ( userId, bookId, docId,  returnDate ) VALUES ( "
                                    . $this->user->getUserID() . " , "  . $this->realBook->getBookId() . " , " . $this->document->getDocID() ." , DATE_ADD( NOW() , INTERVAL 3 MONTH) )";
            $conn = SystemFrame::instance()->getConnection();
            $insertResult = $conn->query($insertIntoBorrowRecord);
            if($insertResult === false)
                throw new \Exception("Fail to insert into borrowRecord" .$conn->error , errorCode\InsertIntoTableError);
            $getRecordIdSql = "SELECT LAST_INSERT_ID()";
            $result = $conn->query($getRecordIdSql);
            if($result === false)
                throw new \Exception("Fail to get record Id from Table " . $conn->error, errorCode\QueryTableError);
            $row = $result->fetch_assoc();
            $this->setRecordId($row[0]);
            $queryTimeSql = "SELECT beginDate,dueDate FROM " . systemConfig\config['borrowRecord'] . " WHERE recordId = $this->recordId";
            $timeResult = $conn->query($queryTimeSql);
            if($timeResult === false || $timeResult->num_rows === 0)
                throw new \Exception("Fail to get record Time " . $conn->error, errorCode\QueryTableError);
            if($row = $timeResult->fetch_assoc()) {
                $this->beginDate = $row['beginDate'];
                $this->dueDate = $row['dueDate'];
            }
            $this->answerTime = date('Y-m-d H:i:s');
            $this->isAnswered = 1;
            $this->isAllowed = 1;
            $this->updateData();
        }
    }

    /**
     * @param mixed $recordId
     */
    public function setRecordId($recordId): void
    {
        $this->recordId = $recordId;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function isInRequestTable()
    {

        if(isset($this->requestId)) {
            $conn = SystemFrame::instance()->getConnection();
            $queryRequestSql = "SELECT requestId FROM " . systemConfig\config['borrowRequest'] . " WHERE requestId = $this->requestId";
            $result = $conn->query($queryRequestSql);
            if($result === false)
                throw new \Exception("Fail to query Request Id from table " . systemConfig\config['borrowRequest'] . $conn->error, errorCode\QueryTableError);
            if($result->num_rows > 0)
                return true;
            else return false;
        }
        else return false;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function isInRecordTable()
    {
        if(isset($this->recordId)) {
            $conn = SystemFrame::instance()->getConnection();
            $queryRecordSql = "SELECT recordId FROM " . systemConfig\config['borrowRecord'] . " WHERE recordId = $this->recordId";
            $result = $conn->query($queryRecordSql);
            if($result === false)
                throw new \Exception("Fail to query Record Id from table " . systemConfig\config['borrowRecord'] . $conn->error, errorCode\QueryTableError);
            if($result->num_rows > 0)
                return true;
            else return false;
        }
        else return false;
    }



}