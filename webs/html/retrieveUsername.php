<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/21
 * Time: 15:44
 */

namespace tg;


class retrieveUsername extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        $retrieveStr = " username = '$this->keywords' ";
        return $retrieveStr;
    }

}