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

    protected function writeLength($msg)
    {
        require_once 'int_helper.php';
        $len = strlen($msg);
        $sendMsg = int_helper::uint32($len, true);
        socket_write($this->sockRe, $sendMsg, 4);
    }

    /**
     * @param int $status
     * @param int $jsonType
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

    protected function readJson()
    {
        $len = $this->readMsgLength();
        $leftLen = $len;
        $msg = "";
        while($leftLen > 0) {
            $tempBuffer = socket_read($this->sockRe, $leftLen);
            if($tempBuffer === false) {
                self::echoMessag(socket_strerror(socket_last_error()));
            }
            $leftLen -= strlen($tempBuffer);
            $msg.= $tempBuffer;

        }
        if(!empty($msg))
            return $msg;
        else return false;
    }

    /**
     * @param $returnValue
     * @param int $jsonType
     */
    protected function sendReturnPackage($returnValue, $jsonType = 0)
    {
        $msg = self::getReturnPackage($returnValue, $jsonType);
        $this->sendSimpleMessage($msg);
        //socket_write($this->sockRe, $msg, strlen($msg));
        //self::echoMessage($msg);
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
        $this->writeLength($msg);
        socket_write($this->sockRe, $msg, strlen($msg));
        self::echoMessage($msg);
    }

    protected static function echoMessage($msg)
    {
       // debug_print_backtrace();
        echo "发送的信息:长度 " . strlen($msg) . " "  . $msg . PHP_EOL;
    }

    protected function sendAccounts($returnValue, array $accounts, $jsonType = 0)
    {
        $returnPackage = self::getReturnPackage($returnValue, $jsonType);
        $returnPackage = array_merge(json_decode($returnPackage, TRUE), ['documents' => $accounts]);
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
            echo "no json type" . PHP_EOL;
            throw new \Exception("No json type detected ");
        }
        else {
            $jsonType = $json['jsontype'];
            try {
                if ($jsonType == self::messageType['loginRequest']) {
                    $admin = SystemFrame::adminData()->queryFromUsername($json['adminname']);
                    if (!empty($admin) && $admin->getPassword() == $json['password']) {
                        //$msg = self::getReturnPackage(0);
                    } else {
                        throw new \Exception("No username or wrong password ", errorCode\AccountWrongError);
                        //$msg = self::getReturnPackage(1);
                    }
                    $this->sendReturnPackage(0, $jsonType);
                    //socket_write($this->sockRe, $msg, strlen($msg));
                } else if ($jsonType == self::messageType['addBookRequest']) {
                    try {
                        SystemFrame::docData()->addDocument(new Book($json['title'], $json['authors'], $json['languages'], $json["publicationYear"],
                            $json['subjects'], $json['publisher'], $json['urls'], $json['source'], $json['description'], $json['ISBNs'], '', NULL));
                        $this->sendReturnPackage(0, $jsonType);
                    } catch (\Exception $e) {
                        //SystemFrame::log_info("Fail to add book " . $e->getMessage() . " Line " . $e->getLine());
                        //$this->sendReturnPackage(1);
                        throw $e;
                    }
                } else if ($jsonType == self::messageType['deleteBookRequest']) {
                    SystemFrame::docData()->deleteDoc($json['docID']);
                    $this->sendReturnPackage(0, $jsonType);
                } else if ($jsonType == self::messageType['addRealBookRequest']) {
                    $doc = SystemFrame::docData()->getDocument($json['_bookId']);
                    $realBook = new RealBook($doc, null, $json['_version'], TRUE, $json['_place']);
                    $doc->addRealBook($realBook);
                    $this->sendReturnPackage(0, $jsonType);
                } else if ($jsonType == self::messageType['normalQueryBook']) {
                    require_once 'retrieveSimple.php';
                    $docs = SystemFrame::docData()->queryDoc(array((new retrieveSimple($json['keywords']))->And()));

                    $this->sendDocuments(0, $docs, $jsonType);

                } else if ($jsonType == self::messageType['advancedQueryBook']) {
                    require_once 'retrieveSet.php';
                    $queryStrategy = array();
                    if(isset($json['title']))
                        $queryStrategy[] = (new retrieveTitle($json['title']))->And();
                    if(isset($json['authors']))
                        $queryStrategy[] = (new retrieveAuthor($json['authors']))->And();
                    if(isset($json['publisher']))
                        $queryStrategy[] = (new retrievePublisher($json['publisher']))->And();
                    if(isset($json['publicationYear']))
                        $queryStrategy[] = (new retrievePublicationDate($json['publicationYear']))->And();
                    $docs = SystemFrame::docData()->queryDoc($queryStrategy);
                    $this->sendDocuments(0, $docs, $jsonType);


                } else if ($jsonType == self::messageType['addUserRequest']) {
                    $newUser = new User($json['username'], $json['password'], $json['name'], $json['userID']);
                    SystemFrame::userData()->addAccount($newUser);
                    $this->sendReturnPackage(0, $jsonType);
                } else if ($jsonType == self::messageType['queryUserRequest']) {
                    require_once 'retrieveSet.php';
                    $retrieveConditions = array();
                    if (isset($json['name']))
                        $retrieveConditions[] = (new retrieveName($json['name']))->And();
                    if (isset($json['username']))
                        $retrieveConditions[] = (new retrieveUsername($json['username']))->And();
                    if (isset($json['uid']))
                        $retrieveConditions[] = (new retrieveUid($json['uid']))->And();
                    if ($json['usertype'] == 0)
                        $accountList = SystemFrame::userData()->queryAccount($retrieveConditions);
                    else if ($json['usertype'] == 1)
                        $accountList = SystemFrame::adminData()->queryAccount($retrieveConditions);
                    else throw new \Exception("No userType appointed ", errorCode\TableTypeError);
                    $this->sendAccounts(0, $accountList);
                } else if ($jsonType == self::messageType['addAdminRequest']) {
                    SystemFrame::adminData()->addAccount(new Admin($json['username'], $json['password'], $json['name']));
                    $this->sendReturnPackage(0, $jsonType);

                } else if ($jsonType == self::messageType['queryAdminRequest']) {


                }
            } catch (\Exception $e) {
                echo $e->getMessage() . " Line " . $e->getLine() .  PHP_EOL;
                SystemFrame::log_info($e->getMessage() . " Line " . $e->getLine() );
                if($e->getCode() == errorCode\DuplicateUsernameOrUid) {
                    $this->sendReturnPackage(3, $jsonType);
                }
                else {
                    $this->sendReturnPackage(1, $jsonType);
                }
            }


        }
    }

    protected function readMsgLength()
    {
        require_once 'int_helper.php';
        $buffer = socket_read($this->sockRe, 4);
        //echo "buffer length $buffer";
        $len = int_helper::uInt32($buffer, true);
        return $len;
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

                $buf = $this->readJson();
                //$buf = socket_read($this->sockRe,65535);
                try {
                    $talkback = "收到的信息:$buf\n";
                    echo $talkback . PHP_EOL;
                    $this->dealWithPackage($buf);
                    //echo "测试成功了啊\n" ;


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