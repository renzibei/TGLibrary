<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/5
 * Time: 14:03
 */

namespace tg;


abstract class AccountData
{
    protected $accountTable;


    abstract protected function &getAccountFromRow($row);

    /**
     * @return array
     * @throws \Exception
     */
    public function &userList()
    {
        $conn = SystemFrame::instance()->getConnection();
        $querySql = "SELECT * FROM $this->accountTable";
        $result = $conn->query($querySql);
        if($result === false)
            throw new \Exception("Fail to query account list " . $conn->error, errorCode\QueryTableError);
        /*
        if($result->num_rows === 0)
            return false;
        else {
        */
            $accounts = array();
            while($row = $result->fetch_assoc()) {
                $account = &$this->getAccountFromRow($row);
                $accounts[] = $account;
            }
            return $accounts;
       // }
    }

    /**
     * @param $name
     * @return array
     * @throws \Exception
     */
    public function &queryFromName($name)
    {
        $conn = SystemFrame::instance()->getConnection();
        $querySql = "SELECT * FROM $this->accountTable WHERE name = '$name'";
        $result = $conn->query($querySql);
        $accounts = array();
        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $account = &$this->getAccountFromRow($row);
                $accounts[] = $account;
            }
        }
        return $accounts;
    }

    /**
     * @param null $username
     * @param null $name
     * @param null $userId
     * @param null $uid
     * @return array
     * @throws \Exception
     */
    public function &multiQuery($username = NULL, $name = NULL, $userId = NULL ,$uid = NULL)
    {
        $querySql = "SELECT * FROM $this->accountTable WHERE TRUE ";
        if(!empty($username)) {
            $querySql .= " AND username = '$username' ";
        }
        if(!empty($name)) {
            $querySql .= " AND name LIKE '%$name%' ";
        }
        if(!empty($userId)) {
            if($this->accountTable === systemConfig\config['userTable'])
                $querySql .= " AND userId = $userId ";
            else if($this->accountTable === systemConfig\config['adminTable'])
                $querySql .= " AND adminId = $userId";
        }
        if(!empty($uid)) {
            if($this->accountTable === systemConfig\config['userTable'])
                $querySql .= " AND uid = $uid";
        }
        $conn= SystemFrame::instance()->getConnection();
        $result = $conn->query($querySql);
        if($result === false)
            throw new \Exception("Fail to query user from $this->accountTable " . $conn->error, errorCode\QueryTableError);
        $returnAccounts = array();
        while($row = $result->fetch_assoc()) {
            $account = &$this->getAccountFromRow($row);
            $returnAccounts[] = $account;
        }
        return $returnAccounts;

    }

    /**
     * @param array $retrieveList
     * @return array
     * @throws \Exception
     */
    public function &queryAccount(array $retrieveList)
    {
        $querySql = "SELECT * FROM " . $this->accountTable . " WHERE TRUE ";
        foreach ($retrieveList as $condition) {
            $querySql .= $condition;
        }
        //echo $querySql ." <br />";
        $conn = SystemFrame::instance()->getConnection();
        $result = $conn->query($querySql);
        if($result === false)
            throw new \Exception("Fail to query Account From " . $this->accountTable . " " . $conn->error . " Sql: $querySql " , errorCode\QueryTableError);
        $accountList = array();
        while($row = $result->fetch_assoc()) {
            $accountList[] = &$this->getAccountFromRow($row);
        }
        return $accountList;

    }


    /**
     * @param $username
     * @return bool/Account
     * @throws \Exception
     */
    public function &queryFromUsername($username)
    {
        $conn = SystemFrame::instance()->getConnection();
        $query = "SELECT * FROM $this->accountTable WHERE username = '$username'";
        $result = $conn->query($query);
        if($result->num_rows === 0)
            return false;
        else {
            $row = $result->fetch_assoc();
            $account = &$this->getAccountFromRow($row);
            return $account;
        }

    }

    /**
     * @param $Id
     * @return bool/Account
     * @throws \Exception
     */
    public function queryFromId($Id)
    {
        $conn = SystemFrame::instance()->getConnection();
        $query = "SELECT * FROM $this->accountTable " ;
        if($this->accountTable === systemConfig\config['adminTable'])
            $query .= " WHERE adminId = $Id";
        else if($this->accountTable === systemConfig\config['userTable'])
            $query .= " WHERE userId = $Id";
        $result = $conn->query($query);
        if($result->num_rows === 0)
            return false;
        else {
            $row = $result->fetch_assoc();
            $account = &$this->getAccountFromRow($row);
            return $account;
        }
    }

    abstract public function addAccount(Account &$account);

    /**
     * @param $uuid
     * @param int $deleteType
     * @throws \Exception
     */
    public function deleteAccount($uuid, $deleteType = 1)
    {
        if($deleteType != 1 && $deleteType != 2)
            throw new \Exception("Wrong format to use deleteAccount ", errorCode\FunctionParameterError);
        $conn = SystemFrame::instance()->getConnection();
        $deleteSql = "DELETE FROM $this->accountTable WHERE ";
        if($deleteType === 1)
            $deleteSql .= " username = '$uuid' " ;
        else $deleteSql .= " uid = $uuid";
        if($conn->query($deleteSql) === false)
            throw new \Exception("Fail to delete account " . $conn->error, errorCode\DeleteFromTableError);
    }




}