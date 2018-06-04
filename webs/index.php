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
    function testSomething()
    {
        require_once 'html/SystemFrame.php';
        require_once 'html/Document.php';
        try {
            \tg\SystemFrame::log_info("begin to init");
            \tg\SystemFrame::instance()->initServer();
            $doc = new \tg\Document("haha", array("秘密"), 'Book', "Chinese",
                array("Computer"), "中国教育", array("www.baidu.com"), "他改变了中国", "嘻嘻");
            \tg\SystemFrame::docData()->addDocument($doc);
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