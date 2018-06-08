<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/7
 * Time: 14:18
 */

namespace tg;


class retrieveAuthor extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        $returnStr =   " docId IN (SELECT docId FROM " . systemConfig\config['writingTable']
            . " WHERE authorId IN ( SELECT authorId FROM " . systemConfig\config['authorTable']
            . " WHERE name LIKE '%$this->keywords%' ) )";
        return $returnStr;
    }

}