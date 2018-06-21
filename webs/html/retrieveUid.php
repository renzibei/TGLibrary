<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/21
 * Time: 15:45
 */

namespace tg;


class retrieveUid extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        $retrieveStr = " uid = '$this->keywords' ";
        return $retrieveStr;
    }

}