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
        \tg\SystemFrame::log_info(" type " . $type . " message " . $message . " file " . $file . " line " . $line);
    }

    /**
     * @throws Exception
     */
    function testAdmin()
    {
        require_once 'html/AdminData.php';
        $admin = new \tg\Admin('nevermore1', '123456','最帅的人');
        \tg\SystemFrame::adminData()->addAccount($admin);
        $adminList = \tg\SystemFrame::adminData()->userList();
        var_dump($adminList);
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
    function testBook()
    {
        require_once 'html/Book.php';
        require_once 'html/DocData.php';
        $book = new \tg\Book('他改变了中国', array('长着'), 'Chinese', 1998,
                array("哲学", "膜蛤"), "人民出版社", array('www.baidu.com'), 'www.baidu.com', "不要总想搞个大新闻", '2332332', '精装');
        \tg\SystemFrame::docData()->addDocument($book);

    }

/**
 * @throws Exception
 */
    function testDoc()
    {
       /* $doc = new \tg\Document('',"haha", array("秘密"), 'Book', "Chinese",
            array("Computer"), "中国教育", array("www.baidu.com"), "他改变了中国", "嘻嘻");
        \tg\SystemFrame::docData()->addDocument($doc);
       */
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
                testBook();
            }
            \tg\SystemFrame::log_info("finish");
        } catch (Exception $e) {
            \tg\SystemFrame::log_info($e->getMessage() . " Line " . $e->getLine());
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