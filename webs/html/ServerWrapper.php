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

    const ip = "0";
    const port = 8333;


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

    public function beginListen()
    {


        $count = 0;

        do{
            if (($msgsock = socket_accept($this->socket)) < 0) {
                echo "socket_accept() failed: reason: " . socket_strerror($msgsock) . "\n";
                break;
            } else {

                //发到客户端
                $msg ="测试成功！\n";
                socket_write($msgsock, $msg, strlen($msg));

                echo "测试成功了啊\n";
                $buf = socket_read($msgsock,8192);


                $talkback = "收到的信息:$buf\n";
                echo $talkback;

                if(++$count >= 5){
                    break;
                };


            }
            //echo $buf;
            socket_close($msgsock);
        }while(true);
    }

}