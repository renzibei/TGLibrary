<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/21
 * Time: 15:41
 */

namespace tg;


class retrieveName extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        $retrieveStr = " name = $this->keywords ";
        return $retrieveStr;
    }

}