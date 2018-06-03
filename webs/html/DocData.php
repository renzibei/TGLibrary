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
    function addDocument(Document &$document)
    {
        $insertSql = "INSERT INTO " . systemConfig\config['docTable'] . " ( title ) VALUES " . "( '" . $document->getTitle() . "' ) ";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($insertSql);
        if($result === false)
            throw new \Exception("Fail to insert DocId into Table " . $conn->error,  errorCode\InsertIntoTableError);
        $document->updateData();


    }

}