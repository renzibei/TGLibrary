<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/11
 * Time: 03:30
 */

namespace tg;
require_once 'SystemFrame.php';
require_once 'AdminData.php';

/**
 * Class ServerWrapper
 * @package tg
 */
class ServerWrapper
{
    public $socket;
    protected $sockRe;
    const ip = "0";
    const port = 8333;

    const messageType = [
                            'loginRequest' => 1,
                            'addBookRequest' => 2,
                            'deleteBookRequest' => 3,
                            'addRealBookRequest' => 4,
                            'normalQueryBook' => 5,
                            'advancedQueryBook' => 6,

                            'addUserRequest' => 8,
                            'queryUserRequest' =>9,
                            'addAdminRequest' => 10,
                            'queryAdminRequest' => 11,
                            'queryBorrowRecords' => 12,
                            'modifyBookRequest' => 13,
                            'deleteAccountRequest' => 14,

                        ];

    public function initServer()
    {

        if(($this->socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) < 0) {
            echo "socket_create() 失败的原因是:".socket_strerror($this->socket)."\n";
        }

        if(($ret = socket_bind($this->socket,self::ip,self::port)) < 0) {
            echo "socket_bind() 失败的原因是:".socket_strerror($ret)."\n";
        }

        if(($ret = socket_listen($this->socket,4)) < 0) {
            echo "socket_listen() 失败的原因是:".socket_strerror($ret)."\n";
        }
    }

    /**
     * @param $status
     * @return string
     */
    protected static function getReturnPackage($status = 0, $jsonType = 0)
    {
        $returnArr = ['jsontype' => $jsonType, 'confirmtype' => $status];
        return json_encode($returnArr);
    }

    public function getPackageType()
    {

    }

    protected function sendReturnPackage($returnValue)
    {
        $msg = self::getReturnPackage($returnValue);
        socket_write($this->sockRe, $msg, strlen($msg));
    }

    protected function sendDocuments($returnValue, array $documents, $jsonType = 0)
    {
        $returnPackage = self::getReturnPackage($returnValue, $jsonType);
        $returnPackage = array_merge(json_decode($returnPackage, TRUE), ['recordNumber' => count($documents), 'documents' => $documents]);
        $msg = json_encode($returnPackage);
        $this->sendSimpleMessage($msg);
    }

    protected  function sendSimpleMessage($msg)
    {
        socket_write($this->sockRe, $msg, strlen($msg));
    }

    protected function sendAccounts($returnValue, array $accounts, $jsonType = 0)
    {
        $returnPackage = self::getReturnPackage($returnValue, $jsonType);
        $returnPackage = array_merge(json_decode($returnPackage, TRUE), ['accounts' => $accounts]);
        $this->sendSimpleMessage(json_encode($returnPackage));
    }

    /**
     * @param $buffer
     * @throws \Exception
     */
    protected function dealWithPackage($buffer)
    {
        $json = json_decode($buffer, TRUE);
        if(!isset($json['jsontype'])) {
            echo "no json type";
            throw new \Exception("No json type detected ");
        }
        else {
            $jsonType = $json['jsontype'];
            if($jsonType == self::messageType['loginRequest']) {
                $admin = SystemFrame::adminData()->queryFromUsername($json['adminname']);
                if(!empty($admin) && $admin->getPassword() == $json['password']) {
                        //$msg = self::getReturnPackage(0);
                }
                else {
                    throw new \Exception("No username or wrong password ", errorCode\AccountWrongError);
                    //$msg = self::getReturnPackage(1);
                }
                $this->sendReturnPackage(0);
                //socket_write($this->sockRe, $msg, strlen($msg));
            }
            else if($jsonType == self::messageType['addBookRequest']) {
                try {
                    SystemFrame::docData()->addDocument(new Book($json['title'], $json['authors'], $json['languages'], $json["publicationYear"],
                        $json['subjects'], $json['publisher'], $json['urls'], $json['source'], $json['description'], $json['ISBNs'], '', NULL));
                    $this->sendReturnPackage(0);
                } catch (\Exception $e) {
                    //SystemFrame::log_info("Fail to add book " . $e->getMessage() . " Line " . $e->getLine());
                    //$this->sendReturnPackage(1);
                    throw $e;
                }
            }
            else if($jsonType == self::messageType['deleteBookRequest']) {
                SystemFrame::docData()->deleteDoc($json['docID']);
                $this->sendReturnPackage(0);
            }
            else if($jsonType == self::messageType['addRealBookRequest']) {
                $doc = SystemFrame::docData()->getDocument($json['docID']);
                $realBook = new RealBook($doc, $json['callNum'], NULL, TRUE, $json['place']);
                $doc->addRealBook($realBook);
                $this->sendReturnPackage(0);
            }
            else if($jsonType == self::messageType['normalQueryBook']) {
                require_once 'retrieveSimple.php';
                $docs = SystemFrame::docData()->queryDoc(array((new retrieveSimple($json['keywords']))->And()));
                $this->sendDocuments(0, $docs, 5);

            }
            else if($jsonType == self::messageType['advancedQueryBook']) {

            }
            else if($jsonType == self::messageType['addUserRequest']) {
                $newUser = new User($json['username'], $json['password'], $json['name'], $json['userID']);
                SystemFrame::userData()->addAccount($newUser);
                $this->sendReturnPackage(0);
            }
            else if($jsonType == self::messageType['queryUserRequest']) {
                require_once 'retrieveSet.php';
                $retrieveConditions = array();
                if(isset($json['name']))
                    $retrieveConditions[] = (new retrieveName($json['name']))->And();
                if(isset($json['username']))
                    $retrieveConditions[] = (new retrieveUsername($json['username']))->And();
                if(isset($json['uid']))
                    $retrieveConditions[] = (new retrieveUid($json['uid']))->And();
                if($json['usertype'] == 0)
                    $accountList = SystemFrame::userData()->queryAccount($retrieveConditions);
                else if($json['usertype'] == 1)
                    $accountList = SystemFrame::adminData()->queryAccount($retrieveConditions);
                else throw new \Exception("No userType appointed ", errorCode\TableTypeError);
                $this->sendAccounts(0, $accountList);
            }
            else if($jsonType == self::messageType['addAdminRequest']) {
                SystemFrame::adminData()->addAccount(new Admin($json['username'], $json['password'], $json['name']));
                $this->sendReturnPackage(0);

            }
            else if($jsonType == self::messageType['queryAdminRequest']) {
                

            }


        }
    }

    /**
     * @throws \Exception
     */
    public function beginListen()
    {


        $count = 0;

        do{
            if (($this->sockRe = socket_accept($this->socket)) < 0) {
                echo "socket_accept() failed: reason: " . socket_strerror($this->sockRe) . "\n";
                break;
            } else {

                //发到客户端
                //$msg ="测试成功！\n";
                //socket_write($this->sockRe, $msg, strlen($msg));

                echo "测试成功了啊\n" ;
                $buf = socket_read($this->sockRe,65535);
                try {
                    $this->dealWithPackage($buf);

                    $talkback = "收到的信息:$buf\n";
                    echo $talkback;
                } catch (\Exception $e) {
                    echo $e->getMessage() . " Line " . $e->getLine() .  PHP_EOL;
                    SystemFrame::log_info($e->getMessage() . " Line " . $e->getLine() );
                    $this->sendReturnPackage(1);
                }
/*
                if(++$count >= 5){
                    break;
                };
*/

            }
            //echo $buf;
            socket_close($this->sockRe);
        }while(true);
    }

}