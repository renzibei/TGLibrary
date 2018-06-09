<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/8
 * Time: 20:14
 */

namespace tg;


class retrieveSubject extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        $returnStr =   " (docId IN (SELECT docId FROM " . systemConfig\config['subjectRecord']
            . " WHERE subjectId IN ( SELECT subjectId FROM " . systemConfig\config['subjectTable']
            . " WHERE subjectName LIKE '%$this->keywords%' ) ) )";
        return $returnStr;
    }

}