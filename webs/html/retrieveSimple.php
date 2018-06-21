<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/21
 * Time: 14:57
 */

namespace tg;

require_once 'retrieveStrategy.php';

class retrieveSimple extends retrieveStrategy
{
    protected function &sonRetrieveStr()
    {
        require_once 'retrieveTitle.php';
        require_once 'retrieveSubject.php';
        require_once 'retrieveIdentifier.php';
        require_once 'retrieveAuthor.php';
        $returnStr = "(" . (new retrieveSubject($this->keywords))->Normal() . (new retrieveISBN($this->keywords))->Or()
                    . (new retrieveISSN($this->keywords))->Or() . (new retrieveDoi($this->keywords))->Or()
                    . (new retrieveAuthor($this->keywords))->Or() .(new retrieveTitle($this->keywords))->Or() . ")";
        return $returnStr;
    }

}