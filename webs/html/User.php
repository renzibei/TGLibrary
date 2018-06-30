<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/5
 * Time: 13:20
 */


namespace tg;

require_once 'Account.php';

class User extends Account
{


    protected $uid;

    /**
     * User constructor.
     * @param $username
     * @param $password
     * @param string $name
     * @param null $uid
     * @param null $userID
     */
    public function __construct($username, $password, $name = '', $uid  = NULL,$userID = NULL)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userID = $userID;
        $this->name = $name;
        $this->uid = $uid;
    }


    /**
     * @return bool
     * @throws \Exception
     */
    protected function isInDatabase()
    {
        $conn = SystemFrame::instance()->getConnection();
        if(isset($this->docID)) {
            $queryUserSql = "SELECT * FROM " . systemConfig\config['userTable'] . " WHERE userId = $this->userID";
            $result = $conn->query($queryUserSql);
            if($result === false)
                throw new \Exception("Fail to query User from table " . systemConfig\config['userTable'] . $conn->error, errorCode\QueryTableError);
            if($result->num_rows > 0)
                return true;
            else return false;
        }
        else return false;
    }

    /**
     * @throws \Exception
     */
    public function updateData()
    {
        if($this->isInDatabase()) {
            $conn = SystemFrame::instance()->getConnection();
            $updateUserSql = "UPDATE " . systemConfig\config['userTable'] . " SET ";
            if(isset($this->username))
                $updateUserSql .= "username = '$this->username' ";
            if(isset($this->password))
                $updateUserSql .= ", password = '$this->password'";
            if(isset($this->name))
                $updateUserSql .= ", name = '$this->name'";
            if(isset($this->uid))
                $updateUserSql .= ", uid = '$this->uid'";
            $updateUserSql .= "WHERE userId = $this->userID";
            $result = $conn->query($updateUserSql);
            if($result === false)
                throw new \Exception("Fail to update user Table " . $conn->error, errorCode\UpdateTableError);
        }
    }


    /**
     * @throws \Exception
     */
    public function updateUsername()
    {
        $conn = SystemFrame::instance()->getConnection();
        if($this->isInDatabase()) {
            //$updateUserSql = "UPDATE " . systemConfig\config['userTable']
        }
    }

    public function updatePassword()
    {

    }
    public function updateUserID()
    {

    }

    public function updateName()
    {

    }


    public function getUserID()
    {
        return $this->userID;
    }

    public function getUid()
    {
        return $this->uid;
    }


    /**
     * @param $uid
     * @throws \Exception
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        $this->updateData();
    }


}