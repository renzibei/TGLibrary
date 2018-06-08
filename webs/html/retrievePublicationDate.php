<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/8
 * Time: 20:47
 */

namespace tg;


class retrievePublicationDate extends retrieveStrategy
{

    protected function &sonRetrieveStr()
    {
        $returnStr = " ( publicationYear > $this->keywords ) ";
        return $returnStr;
    }

}