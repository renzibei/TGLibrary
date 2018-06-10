<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/5
 * Time: 22:08
 */

namespace tg;

require_once 'Account.php';

class Admin extends Account
{
    /**
     * Admin constructor.
     * @param $username
     * @param $password
     * @param $name
     * @param $userID
     */
    public function __construct($username, $password, $name = '', $userID = NULL)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userID = $userID;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return null
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * @throws \Exception
     */
    public function updateData()
    {
        if($this->isInDatabase()) {
            $conn = SystemFrame::instance()->getConnection();
            $updateUserSql = "UPDATE " . systemConfig\config['adminTable'] . " SET ";
            if(isset($this->username))
                $updateUserSql .= "username = '$this->username' ";
            if(isset($this->password))
                $updateUserSql .= ", password = '$this->password'";
            if(isset($this->name))
                $updateUserSql .= ", name = '$this->name'";
            $updateUserSql .= "WHERE userId = $this->userID";
            $result = $conn->query($updateUserSql);
            if($result === false)
                throw new \Exception("Fail to update admin Table " . $conn->error, errorCode\UpdateTableError);
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    protected function isInDatabase()
    {
        $conn = SystemFrame::instance()->getConnection();
        if(isset($this->docID)) {
            $queryUserSql = "SELECT * FROM " . systemConfig\config['adminTable'] . " WHERE adminId = $this->userID";
            $result = $conn->query($queryUserSql);
            if($result === false)
                throw new \Exception("Fail to query Admin from table " . systemConfig\config['adminTable'] . $conn->error, errorCode\QueryTableError);
            if($result->num_rows > 0)
                return true;
            else return false;
        }
        else return false;
    }
}