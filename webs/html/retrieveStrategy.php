<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/7
 * Time: 14:04
 */

namespace tg;


abstract class retrieveStrategy
{
    protected $keywords;

    public function __construct($keywords)
    {
        $this->keywords = $keywords;
    }

    abstract protected function &sonRetrieveStr();

    public function &And()
    {
        $retrieveStrategy = " AND " . $this->sonRetrieveStr();
        return $retrieveStrategy;
    }

    public function &Or()
    {
        $retrieveStrategy = " OR " . $this->sonRetrieveStr();
        return $retrieveStrategy;
    }

    public function &Not()
    {
        $retrieveStrategy = " NOT " . $this->sonRetrieveStr();
        return $retrieveStrategy;
    }

    public function &Normal()
    {
        return $this->sonRetrieveStr();
    }
}


