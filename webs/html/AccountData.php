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
        $query = "SELECT * FROM $this->accountTable WHERE userId = $Id";
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