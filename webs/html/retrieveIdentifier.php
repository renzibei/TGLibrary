<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/8
 * Time: 20:20
 */

namespace tg;


class retrieveIdentifier extends retrieveStrategy
{
    protected $identifierType;

    protected function &sonRetrieveStr()
    {
        $returnStr = " ( docId IN ( SELECT docId FROM " . systemConfig\config['identifierTable']
                    . " WHERE identifierNum LIKE '%$this->keywords%' AND identifierType = " . $this->identifierType . " ) ) ";
        return $returnStr;
    }


}



class retrieveISBN extends retrieveIdentifier
{

    /**
     * retrieveISBN constructor.
     * @param $keywords
     */
    public function __construct($keywords)
    {
        parent::__construct($keywords);
        $this->identifierType = identifierTypeArray['ISBN'];
    }
}


class retrieveISSN extends retrieveIdentifier
{

    /**
     * retrieveISSN constructor.
     * @param $keywords
     */
    public function __construct($keywords)
    {
        parent::__construct($keywords);
        $this->identifierType = identifierTypeArray['ISSN'];
    }
}

class retrieveE_ISSN extends retrieveIdentifier
{

    /**
     * retrieveE_ISSN constructor.
     * @param $keywords
     */
    public function __construct($keywords)
    {
        parent::__construct($keywords);
        $this->identifierType = identifierTypeArray['e-ISSN'];
    }
}


class retrieveDoi extends retrieveIdentifier
{

    /**
     * retrieveDoi constructor.
     * @param $keywords
     */
    public function __construct($keywords)
    {
        parent::__construct($keywords);
        $this->identifierType = identifierTypeArray['doi'];
    }
}


