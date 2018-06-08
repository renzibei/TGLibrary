<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/7
 * Time: 14:10
 */

namespace tg;

require_once 'retrieveStrategy.php';

class retrieveTitle extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        $returnStr = " title = $this->keywords ";
        return $returnStr;
    }

}