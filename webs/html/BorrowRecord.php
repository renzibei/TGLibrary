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


    public function __construct($user, $realBook, $document, $isReturned = false, $isAnswered = false, $isAllowed = false )
    {
        $this->user = $user;
        $this->realBook = $realBook;
        $this->document = $document;
        $this->isReturned = $isReturned;
        $this->isAllowed = $isAnswered;
        $this->isAllowed = $isAllowed;
    }

    public function updateData()
    {

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



}