<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/5
 * Time: 00:47
 */

namespace tg;


abstract class Account
{
    protected $username;
    protected $password;
    protected $userID;
    protected $name;




    /*
    public function __construct($username, $password, $name = '', $userID = NULL)
    {
        $this->username = $username;
        $this->password = $password;
        $this->userID = $userID;
        $this->name = $name;
    }
    */

    abstract public function updateData();

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    abstract public function updateUsername();

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    abstract public function updatePassword();

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUserID()
    {
        return $this->userID;
    }

    abstract public function updateUserID();

    /**
     * @param mixed $userID
     */
    public function setUserID($userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    abstract public function updateName();

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    abstract protected function isInDatabase();


}