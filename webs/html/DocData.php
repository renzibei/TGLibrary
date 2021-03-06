<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/3
 * Time: 15:15
 */

namespace tg;

require_once 'Document.php';
require_once dirname(dirname(__DIR__)) . '/dbusers/dbadmin.php';
require_once 'retrieveSet.php';
require_once 'Book.php';
require_once 'Journal.php';
require_once 'User.php';



class DocData
{
    /**
     * @param Document $document
     * @throws \Exception
     */
    public function addDocument(Document &$document)
    {
        $insertSql = "INSERT INTO " . systemConfig\config['docTable'] . " ( title ) VALUES " . "( '" . $document->getTitle() . "' ) ";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($insertSql);
        if($result === false)
            throw new \Exception("Fail to insert DocId into Table " . $conn->error,  errorCode\InsertIntoTableError);
        SystemFrame::log_info("finish insert title");
        $getDocIdSql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($getDocIdSql);
        SystemFrame::log_info("finish query id");
        if($result === false)
            throw new \Exception("Fail to get Doc Id from Table " . $conn->error, errorCode\QueryTableError);
        $row = $result->fetch_row();
        $document->setDocID($row[0]);
        $document->updateData();


    }

    /**
     * @param $docId
     * @return bool|Book
     * @throws \Exception
     */
    public function &getDocument($docId)
    {
        $querySql = "SELECT * FROM " . \tg\systemConfig\config['docTable'] . " WHERE docId = $docId";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($querySql);
        if($result === false)
            throw new \Exception("Fail to query doc " . $conn->error, errorCode\QueryTableError);
        if($result->num_rows < 0)
            return false;
        else {
            $row = $result->fetch_assoc();
            $document = &$this->getDocumentFromRow($row);
            return $document;
            //$document = new Document($docId, $row['title'], $row[]);

        }


    }

    /**
     * @param $bookId
     * @return bool|RealBook
     * @throws \Exception
     */
    public function &getRealBook($bookId)
    {
        $queryBookSql = " SELECT * FROM " . systemConfig\config['bookTable'] . " WHERE docId = $bookId ";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($queryBookSql);
        if ($result === false)
            throw new \Exception("Fail to query real book " . $conn->error, errorCode\QueryTableError);
        if($row = $result->fetch_assoc()) {
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
            $book = SystemFrame::docData()->getDocument($row['docId']);
            $realBook = new RealBook($book, $row['callNumber'], $row['version'], $row['isOnShelf'], $bookPlaceStr, $row['bookId']);
        }
        else return false;
        return $realBook;
    }

    /**
     * @param array $retrieveList
     * @return array
     * @throws \Exception
     */
    public function &queryDoc(array $retrieveList)
    {
        $querySql = "SELECT * FROM " . systemConfig\config['docTable'] . " WHERE TRUE ";
        foreach ($retrieveList as $condition) {
            $querySql .= $condition;
        }
        //echo $querySql ." <br />";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($querySql);
        $docList = array();
        while($row = $result->fetch_assoc()) {
            $docList[] = &$this->getDocumentFromRow($row);
        }
        return $docList;

    }

    /**
     * @param $docId
     * @throws \Exception
     */
    public function deleteDoc($docId) {
        $query = "DELETE FROM " . systemConfig\config['docTable'] . " WHERE docId = $docId";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($query);
        if($result === false)
            throw new \Exception(" Fail to delete doc $docId , $conn->error", errorCode\DeleteFromTableError);

    }



    /**
     * @param $docId
     * @return array
     * @throws \Exception
     */
    protected function &getAuthorsFromDocId($docId)
    {
        $conn = SystemFrame::instance()->getConnection();
        $queryAuthorSql = "SELECT name FROM " . systemConfig\config['authorTable'] .
                            " WHERE authorId IN ( SELECT authorId FROM " . systemConfig\config['writingTable'] . " WHERE docId = $docId) ";
        $result = $conn->query($queryAuthorSql);
        if($result === false)
            throw new \Exception("Fail to query author " . $conn->error , errorCode\QueryTableError);
        $authors = array();
        while( $row = $result->fetch_assoc() ) {
            $authors[] = $row['name'];
        }
        return $authors;

    }



    /**
     * @param $docId
     * @return array
     * @throws \Exception
     */
    protected function &getUrlsFromDocId($docId)
    {
        $conn = SystemFrame::instance()->getConnection();
        $queryUrlSql = "SELECT url FROM " . systemConfig\config['urlTable'] . " WHERE docId = $docId ";
        $result = $conn->query($queryUrlSql);
        if($result === false)
            throw new \Exception("Fail to query url " . $conn->error, errorCode\QueryTableError);
        $urls = array();
        while($row = $result->fetch_assoc()) {
            $urls[] = $row['url'];
        }
        return $urls;
    }

    /**
     * @param $docId
     * @return bool
     * @throws \Exception
     */
    protected function getLanguageFromDocId($docId)
    {
        $conn = SystemFrame::instance()->getConnection();
        $queryLanguageSql = "SELECT lanName FROM " . systemConfig\config['languageTable'] . " WHERE languageId = ( SELECT languageId FROM  " . systemConfig\config['docTable']  . " WHERE docId = $docId )";
        $result = $conn->query($queryLanguageSql);
        if($result === false)
            throw new \Exception("Fail to query language " . $conn->error, errorCode\QueryTableError);
        if($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['lanName'];
        }
        else
            return false;

    }

    /**
     * @param $docId
     * @return array
     * @throws \Exception
     */
    protected function &getSubjectsFromDocId($docId)
    {
        $conn = SystemFrame::instance()->getConnection();
        $querySubjectSql = "SELECT subjectName FROM " . systemConfig\config['subjectTable'] .
            " WHERE subjectId IN ( SELECT subjectId FROM " . systemConfig\config['subjectRecord'] . " WHERE docId = $docId) ";
        $result = $conn->query($querySubjectSql);
        if($result === false)
            throw new \Exception("Fail to query subject " . $conn->error , errorCode\QueryTableError);
        $subjects = array();
        while( $row = $result->fetch_assoc() ) {
            $subjects[] = $row['subjectName'];
        }
        return $subjects;
    }

    /**
     * @param $docId
     * @param string $identifierType
     * @return array
     * @throws \Exception
     */
    protected function &getIdentifierFromDocId($docId, $identifierType = "ISBN")
    {
        $conn = SystemFrame::instance()->getConnection();
        $queryISBNSql = "SELECT identifierNum FROM " . systemConfig\config['identifierTable'] . " WHERE docId = $docId AND identifierType = " .  identifierTypeArray[$identifierType];
        $result = $conn->query($queryISBNSql);
        if($result === false)
            throw new \Exception("Fail to query identifier " . $conn->error, errorCode\QueryTableError);
        $ISBNs = array();
        while($row = $result->fetch_assoc()) {
            $ISBNs[] = $row['identifierNum'];
        }
        return $ISBNs;
    }

    /**
     * @param $row
     * @return Book
     * @throws \Exception
     */
    protected function &getDocumentFromRow($row)
    {
        $docType = $row['typeId'];
        $docId = $row['docId'];
        switch ($docType) {
            case docTypeArray['Book']:
                $doc = new Book($row['title'], $this->getAuthorsFromDocId($docId), $this->getLanguageFromDocId($docId),
                    $row['publicationYear'], $this->getSubjectsFromDocId($docId), $row['publisher'], $this->getUrlsFromDocId($docId),
                    $row['source'], $row['description'], $this->getIdentifierFromDocId($docId, "ISBN"), $row['format'], $docId );
                break;
            case docTypeArray['Journal']:
                $doc = new Journal($row['title'], $this->getAuthorsFromDocId($docId), $this->getLanguageFromDocId($docId),
                    $row['publicationYear'], $this->getSubjectsFromDocId($docId), $row['publisher'], $this->getUrlsFromDocId($docId),
                    $row['source'], $row['description'], $this->getIdentifierFromDocId($docId, "ISSN"), $row['format'], $docId );
                break;
        }

        return $doc;



    }







}
