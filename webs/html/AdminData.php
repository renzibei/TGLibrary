<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/5
 * Time: 22:21
 */

namespace tg;

require_once 'AccountData.php';
require_once 'Admin.php';

class AdminData extends AccountData
{


    /**
     * AdminData constructor.
     */
    public function __construct()
    {
        $this->accountTable = systemConfig\config['adminTable'];
    }

    protected function &getAccountFromRow($row)
    {
        $admin = new Admin($row['username'], $row['password'], $row['name'], $row['userId']);
        return $admin;
    }

    /**
     * @param Account $account
     * @throws \Exception
     */
    public function addAccount(Account &$account)
    {
        $conn = SystemFrame::instance()->getConnection();
        $insertSql = "INSERT INTO " . systemConfig\config['adminTable'] . "(username, password, name) 
                                                    VALUES ('" .$account->getUsername() . "', '" . $account->getPassword() ."',
                                                     '" . $account->getName() . "')";
        $result = $conn->query($insertSql);
        if($result === false) {
            if ($conn->errno === errorCode\DuplicateUsernameOrUid)
                throw new \Exception("Duplicate Username Or Uid Input " . $conn->error, errorCode\DuplicateUsernameOrUid);
            else
                throw new \Exception("Fail to insert an admin " . $conn->error, errorCode\InsertIntoTableError);
        }
        $getUserIdSql = "SELECT LAST_INSERT_ID()";
        $result = $conn->query($getUserIdSql);
        if($result === false)
            throw new \Exception("Fail to get Admin Id from Table " . $conn->error, errorCode\QueryTableError);
        $row = $result->fetch_row();
        $account->setUserID($row[0]);
    }

}