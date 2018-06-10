<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/9
 * Time: 09:06
 */

namespace tg;

require_once 'Book.php';

class RealBook extends Book
{
    protected $_bookId;
    protected $_book;
    protected $_callNum;
    protected $_version;
    protected $_isOnShelf;
    protected $_place;

    /**
     * @param $book
     */
    public function setBook($book)
    {
        $this->_book = $book;
    }
    //protected $_isbn;

    /**
     * RealBook constructor.
     * @param Book $book
     * @param $callNum
     * @param int $version
     * @param bool $isOnShelf
     * @param null $place
     * @param null $bookId
     * @throws \Exception
     */
    public function __construct(Book &$book, $callNum, $version = 1, $isOnShelf = true, $place = NULL,  $bookId = NULL)
    {
        $this->_book = $book;
        $this->_callNum = $callNum;
        $this->_version = $version;
        $this->_isOnShelf = $isOnShelf;
        $this->_place = $place;
        $this->_bookId = $bookId;
        $this->updateData();
    }

    /**
     * @param mixed $bookId
     */
    public function setBookId($bookId)
    {
        $this->_bookId = $bookId;
    }

    /**
     * @return null
     */
    public function getBookId()
    {
        return $this->_bookId;
    }



    public function updateData()
    {
        if($this->isInDatabase()) {
            $conn = SystemFrame::instance()->getConnection();
            $updateBookSql = "UPDATE " . systemConfig\config['bookTable'] . " SET ";
            if(!empty($this->_callNum)) {
                $updateBookSql .= " callNumber = '$this->_callNum' ";
            }
            if(!empty($this->_book)) {
                $updateBookSql .= ", docId =  " . $this->_book->getDocId();
            }
            if(isset($this->_version)) {
                $updateBookSql .= " , version = $this->_version ";
            }
            if(isset($this->_isOnShelf)) {
                $updateBookSql .= " , isOnShelf = $this->_isOnShelf ";
            }
            if(!empty($this->_place)) {
                $insertPlaceSql = " INSERT INTO " . systemConfig\config['placeTable'] . " ( placeName ) SELECT * FROM ( SELECT '$this->_place' ) AS tmp WHERE NOT EXISTS ( SELECT placeId FROM  " . systemConfig\config['placeTable'] . " WHERE placeName LIKE '$this->_place' ) ";
                $insertResult = $conn->query($insertPlaceSql);
                if($insertResult === false)
                    throw new \Exception("Fail to insert place " . $conn->error, errorCode\InsertIntoTableError);
                $updateBookSql .= ", placeId = ( SELECT placeId FROM  ". systemConfig\config['placeTable'] .  " WHERE placeName LIKE '$this->_place' )";
            }
            $updateBookSql .= " WHERE bookId = $this->_bookId ";

            $result = $conn->query($updateBookSql);
            if($result === false)
                throw new \Exception("Fail to update Book " . $conn->error, errorCode\UpdateTableError);


        }
    }


    public function getDocID()
    {
        return $this->_book->getDocID();
    }

    public function getTitle()
    {
        return $this->_book->getTitle();
    }

    public function getPublisher()
    {
        return $this->_book->getPublisher();
    }

    public function getDescription()
    {
        return $this->_book->getDescription();
    }

    public function getLanguage()
    {
        return $this->_book->getLanguage();
    }

    protected function isInDatabase()
    {
        if(isset($this->_bookId)) {
            $conn = SystemFrame::instance()->getConnection();
            $queryBookSql = "SELECT bookId FROM " . systemConfig\config['bookTable'] . " WHERE bookId = $this->_bookId";
            $result = $conn->query($queryBookSql);
            if($result === false)
                throw new \Exception("Fail to query from table " . systemConfig\config['bookTable'] . $conn->error, errorCode\QueryTableError);
            if($result->num_rows > 0)
                return true;
            else return false;
        }
        else return false;
    }


}