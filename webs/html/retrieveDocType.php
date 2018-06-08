<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/8
 * Time: 20:51
 */

namespace tg;


class retrieveDocType extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        if(in_array($this->keywords,docTypeArray) === false)
            return " FALSE ";
        $returnStr = " typeId = " . docTypeArray[$this->keywords];
        return $returnStr;
    }

}