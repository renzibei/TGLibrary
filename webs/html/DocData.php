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
        $getDocIdSql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($getDocIdSql);
        if($result === false)
            throw new \Exception("Fail to get Doc Id from Table " . $conn->error, errorCode\QueryTableError);
        $row = $result->fetch_row();
        $document->setDocID($row[0]);
        $document->updateData();


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
     * @param $row
     * @throws \Exception
     */
    protected function getDocumentFromRow($row)
    {
        $docType = $row['typeId'];
        switch ($docType) {
            case docTypeArray['Book']:
                $doc = new Book($row['title'], '', '', $row['publicationYear'], '', $row['publisher'], '', $row['source'], $row['description'],
                    '', $row['format'], $row['docId']);

        }
        $doc->setAuthors($this->getAuthorsFromDocId($row['docId']), 2);
        $doc->setUrls($this->getUrlsFromDocId($row['docId']), 2);


    }


    /**
     * @param $docId
     * @return bool
     * @throws \Exception
     */
    public static function getDocument($docId)
    {
        $querySql = "SELECT * FROM " . \tg\systemConfig\config['docTable'] . " WHERE docId = $docId";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($querySql);
        if(!$result === false)
            throw new \Exception("Fail to query doc " . $conn->error, errorCode\QueryTableError);
        if($result->num_rows < 0)
            return false;
        else {
            $row = $result->fetch_assoc();
            //$document = new Document($docId, $row['title'], $row[]);

        }


    }




}