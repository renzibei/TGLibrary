<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/6
 * Time: 00:03
 */

namespace tg;

require_once 'Document.php';
require_once 'RealBook.php';

class Book extends Document
{

    protected $ISBNs;
    protected $format;
    protected $publicationYear;
    protected $realBooks;


    /**
     * Book constructor.
     * @param $title
     * @param array $authors
     * @param string $language
     * @param null $publicationYear
     * @param array|null $subjects
     * @param string $publisher
     * @param array|null $urls
     * @param string $source
     * @param string $description
     * @param string $ISBNs
     * @param string $format
     * @param null $docId
     * @throws \Exception
     */
    public function __construct($title, $authors , $language = 'Chinese', $publicationYear = NULL, $subjects = null, $publisher = '', $urls = null, $source = '', $description = '', $ISBNs = '', $format = '',  $docId = NULL)
    {

        $this->docType = 'Book';
        $this->title = $title;
        if(!empty($authors)) {
            if (!is_array($authors))
                $this->authors = array($this->authors);
            else
                $this->authors = $authors;
        }
        $this->language = $language;
        $this->publicationYear = $publicationYear;
        if(!empty($subjects)) {
            if(!is_array($subjects))
                $this->subjects = array($subjects);
            else
                $this->subjects = $subjects;
        }
        $this->publisher = $publisher;
        if(!empty($urls)) {
            if(!is_array($urls))
                $this->urls = array($urls);
            else
                $this->urls = $urls;
        }
        $this->source = $source;
        $this->description = $description;
        if(!empty($ISBNs)) {
            if(!is_array($ISBNs))
                $this->ISBNs = array($ISBNs);
            else
                $this->ISBNs = $ISBNs;
        }
        $this->format = $format;
        $this->docID = $docId;
        $this->setRealBooks();
        $this->updateData();
    }

    /**
     * @throws \Exception
     */
    protected function setRealBooks()
    {
        if(isset($this->docID) && $this->isInDatabase()) {
            $queryBookSql = " SELECT * FROM " . systemConfig\config['bookTable'] . " WHERE docId = $this->docID ";
            $conn = SystemFrame::instance()->getConnection();
            $result = $conn->query($queryBookSql);
            if ($result === false)
                throw new \Exception("Fail to query real books " . $conn->error, errorCode\QueryTableError);
            $books = array();
            while ($row = $result->fetch_assoc()) {
                if(!isset($row['placeId']))
                    $bookPlaceStr = NULL;
                else {
                    $getPlaceSql = " SELECT placeName FROM " . systemConfig\config['placeTable'] . " WHERE placeId = " . $row['placeId'];
                    $placeResult = $conn->query($getPlaceSql);
                    if ($placeResult === false)
                        throw new \Exception("Fail to query Place " . $conn->error, errorCode\QueryTableError);
                    if ($placeResult->num_rows === 1) {
                        $bookPlaceRow = $placeResult->fetch_assoc();
                        $bookPlaceStr = $bookPlaceRow['placeName'];
                    } else $bookPlaceStr = NULL;
                }
                $books[] = new RealBook($this, $row['callNumber'], $row['version'], $row['isOnShelf'], $bookPlaceStr, $row['bookId']);
            }
            $this->realBooks = $books;
        }
    }

    public function getBooks()
    {
        return $this->realBooks;
    }

    /**
     * @param RealBook $realBook
     * @throws \Exception
     */
    public function addRealBook(RealBook &$realBook)
    {
        $this->realBooks[] = $realBook;
        $insertBookSql = "INSERT INTO " . systemConfig\config['bookTable'] . " ( docId ) VALUES ($this->docID)";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($insertBookSql);
        if($result === false)
            throw new \Exception("Fail to insert Real Book " . $conn->error, errorCode\InsertIntoTableError);
        $getBookIdSql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($getBookIdSql);
        SystemFrame::log_info("finish query bookId");
        if($result === false)
            throw new \Exception("Fail to get Book Id from Table " . $conn->error, errorCode\QueryTableError);
        $row = $result->fetch_row();
        $realBook->setBookId($row[0]);
        $realBook->updateData();
        $this->realBooks[] = $realBook;

    }

    public function updateData()
    {
        if($this->isInDatabase()) {
            $conn = SystemFrame::instance()->getConnection();
            $updateSql = "UPDATE " . systemConfig\config['docTable'] . " SET ";
            if(!empty($this->title))
                $updateSql .= " title = '$this->title' ";
            if(isset($this->docType))
                $updateSql .= (", typeId = " . \tg\docTypeArray[$this->docType]);
            if(!empty($this->publisher))
                $updateSql .= ", publisher = '$this->publisher' ";
            if(!empty($this->format))
                $updateSql .= ", format = '$this->format' ";
            if(isset($this->publicationYear))
                $updateSql .= ", publicationYear = $this->publicationYear ";
            if(!empty($this->source))
                $updateSql .= ", source = '$this->source' ";
            if(!empty($this->description))
                $updateSql .= ", description = '$this->description' ";
            /*
            if(!empty($this->language))
                $updateSql .= ", languageId =  ( SELECT languageId FROM "
                    . systemConfig\config['languageTable'] . " WHERE lanName = '$this->language' )";
            */
            $result = $conn->query($updateSql);
            if($result === false)
                throw new \Exception("Fail to update Book Data " . $conn->error, errorCode\UpdateTableError);
            if(!empty($this->language))
                $this->updateLanguage();
            if(!empty($this->authors))
                $this->updateAuthor();
            if(!empty($this->subjects))
                $this->updateSubject();
            if(!empty($this->urls))
                $this->updateUrl();
            if(!empty($this->ISBNs))
                $this->updateISBN();

        }
    }

    /**
     * @throws \Exception
     */
    protected function updateISBN()
    {
        if($this->isInDatabase())
            if(!empty($this->ISBNs)) {
                $conn = SystemFrame::instance()->getConnection();
                foreach ($this->ISBNs as $ISBN) {
                    $insertIsbnSql = "INSERT INTO " . \tg\systemConfig\config['identifierTable'] . " ( docId, identifierNum, identifierType ) 
                                    SELECT * FROM ( SELECT $this->docID AS a, '$ISBN' AS b," .  identifierTypeArray["ISBN"] . " AS c ) AS tmp 
                                     WHERE NOT EXISTS (SELECT identifierId FROM " . systemConfig\config['identifierTable'] . " WHERE docId = $this->docID AND identifierNum = '$ISBN' AND identifierType = " . identifierTypeArray["ISBN"]. ")";
                    $result = $conn->query($insertIsbnSql);
                    if($result === false)
                        throw new \Exception("Fail to update ISBN " . $conn->error, errorCode\InsertIntoTableError);
                }
        }
    }



}