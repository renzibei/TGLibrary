<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/8
 * Time: 21:09
 */

namespace tg;

require_once 'User.php';



class BorrowRecord
{
    protected $user;

    protected $document;
    protected $beginDate;
    protected $dueDate;
    protected $returnDate;
    protected $isReturned;


}