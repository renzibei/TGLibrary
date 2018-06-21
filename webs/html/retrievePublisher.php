<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/8
 * Time: 20:45
 */

namespace tg;


class retrievePublisher extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        $returnStr = " publisher LIKE '%$this->keywords%' ";
        return $returnStr;
    }

}