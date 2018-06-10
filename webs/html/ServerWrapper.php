<?php
/**
 * Created by PhpStorm.
 * User: fanqu
 * Date: 2018/6/11
 * Time: 03:30
 */

namespace tg;


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
    protected static function getReturnPackage($status = 0)
    {
        $returnArr = ['jsontype' => 0, 'confirmtype' => $status];
        return json_encode($returnArr);
    }

    public function getPackageType()
    {

    }

    protected function dealWithPackage($buffer)
    {
        $json = json_decode($buffer, TRUE);
        if(!isset($json['jsontype']))
            echo "no json type";
        else {
            $jsonType = $json['jsontype'];
            if($jsonType == 1) {
                if(SystemFrame::adminData()->queryFromUsername($json['adminname'])->getUsername() == $json['password']) {
                    $msg = self::getReturnPackage(0);
                }
                else {
                    $msg = self::getReturnPackage(1);
                }
                socket_write($this->sockRe, $msg, strlen($msg));
            }
        }
    }

    public function beginListen()
    {


        $count = 0;

        do{
            if (($this->sockRe = socket_accept($this->socket)) < 0) {
                echo "socket_accept() failed: reason: " . socket_strerror($this->sockRe) . "\n";
                break;
            } else {

                //发到客户端
                $msg ="测试成功！\n";
                //socket_write($this->sockRe, $msg, strlen($msg));

                echo "测试成功了啊\n";
                $buf = socket_read($this->sockRe,65535);
                $this->dealWithPackage($buf);

                $talkback = "收到的信息:$buf\n";
                echo $talkback;

                if(++$count >= 5){
                    break;
                };


            }
            //echo $buf;
            socket_close($this->sockRe);
        }while(true);
    }

}