<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type"  content="text/html"  charset="UTF-8">


<title>菜鸟教程(runoob.com)</title>
</head>
<body>
<?php

    /**
     * @param $type
     * @param $message
     * @param $file
     * @param $line
     * @throws Exception
     */
    function errorHandle($type, $message, $file, $line)
    {
        \tg\SystemFrame::log_info("type" . $type . "message" . $message . "file" . $file . "line" . $line);
    }

/**
 * @throws Exception
 */
    function testUser()
    {
        require_once 'html/UserData.php';
        $user = new \tg\User('nevermore1', '123456', 'shuaige', '201701063');
        \tg\SystemFrame::userData()->addAccount($user);
        $oldUser = \tg\SystemFrame::userData()->userList();
        var_dump($oldUser);

    }

/**
 * @throws Exception
 */
    function testDoc()
    {
        $doc = new \tg\Document('',"haha", array("秘密"), 'Book', "Chinese",
            array("Computer"), "中国教育", array("www.baidu.com"), "他改变了中国", "嘻嘻");
        \tg\SystemFrame::docData()->addDocument($doc);
    }

    /**
     * @throws Exception
     */
    function testSomething()
    {
        require_once 'html/SystemFrame.php';
        require_once 'html/Document.php';
        try {
            \tg\SystemFrame::log_info("begin to init");
            \tg\SystemFrame::instance()->initServer();
            for($i = 1; $i < 2; $i++) {
                testUser();
            }
            \tg\SystemFrame::log_info("finish");
        } catch (Exception $e) {
            \tg\SystemFrame::log_info($e->getMessage() . " Line" . $e->getLine());
            throw $e;
        }
    }

    set_error_handler('errorHandle');
	echo "begin <br />";

    testSomething();




	echo "finish";
?>
</body>
</html>