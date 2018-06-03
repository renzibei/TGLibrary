<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type"  content="text/html"  charset="UTF-8">


<title>菜鸟教程(runoob.com)</title>
</head>
<body>
<?php

    function testSomething()
    {
        require_once 'html/SystemFrame.php';
        require_once 'html/Document.php';
        try {
            \tg\SystemFrame::log_info("begin to init");
            \tg\SystemFrame::instance()->initServer();
            \tg\SystemFrame::log_info("finish");
            $doc = new \tg\Document("haha", array("秘密"), 'Book', "Chinese",
                array("Computer"), "中国教育", array("www.baidu.com"), "他改变了中国", "嘻嘻");
            \tg\SystemFrame::instance()->docData()->addDocument($doc);
        } catch (Exception $e) {
            \tg\SystemFrame::log_info($e->getMessage() . " Line" . $e->getLine());
            throw $e;
        }
    }
	echo "begin <br />";

    testSomething();




	echo "finish";
?>
</body>
</html>