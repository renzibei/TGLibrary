<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/5
 * Time: 20:32
 */

namespace tg;

require_once 'AccountData.php';
require_once 'User.php';

class UserData extends AccountData
{
    public function __construct()
    {
        $this->accountTable = systemConfig\config['userTable'];
    }

    protected function &getAccountFromRow($row)
    {
        $user = new User($row['username'], $row['password'], $row['name'], $row['uid'], $row['userId']);
        return $user;
    }



    /**
     * @param $account
     * @throws \Exception
     */
    public function addAccount(Account &$account)
    {
        $conn = SystemFrame::instance()->getConnection();
        $insertSql = "INSERT INTO " . systemConfig\config['userTable'] . "(username, password, uid, name) 
                                                    VALUES ('" .$account->getUsername() . "', '" . $account->getPassword() ."',
                                                     '" . $account->getUid() . "', '" . $account->getName() . "')";
        $result = $conn->query($insertSql);
        if($result === false) {
            if ($conn->errno === errorCode\DuplicateUsernameOrUid)
                throw new \Exception("Duplicate Username Or Uid Input ", errorCode\DuplicateUsernameOrUid);
            else
                throw new \Exception("Fail to insert an user " . $conn->error, errorCode\InsertIntoTableError);
        }
        $getUserIdSql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($getUserIdSql);
        if($result === false)
            throw new \Exception("Fail to get User Id from Table " . $conn->error, errorCode\QueryTableError);
        $row = $result->fetch_row();
        $account->setUserID($row[0]);
    }
}







