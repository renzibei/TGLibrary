<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type"  content="text/html"  charset="UTF-8">


<title>TGLibrary</title>
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
        $admin = new \tg\Admin('nevermore', '123456789','最帅的人');
        \tg\SystemFrame::adminData()->addAccount($admin);
        //$adminList = \tg\SystemFrame::adminData()->userList();
        //var_dump($adminList);
    }


/**
 * @throws Exception
 */
    function testUser()
    {
        require_once 'html/UserData.php';
        require_once 'html/SystemFrame.php';
        $user = new \tg\User('nevermore1', '123456', 'shuaige', '201701063');
        \tg\SystemFrame::userData()->addAccount($user);
        $oldUser = \tg\SystemFrame::userData()->userList();
        var_dump($oldUser);

    }



    class testClass {
        public $aa;

        public function __construct($a)
        {
            $this->aa = $a;
        }


        function __print()
        {
            echo get_called_class(). $this->aa . "<br />";
        }
    }

    class testNew extends testClass {

    }


    function testNewClass()
    {
        $str = "testClass";
        $a = new $str(2);
        //$a->aa = 1;
        $a->print();
    }

/**
 * @throws Exception
 */
    function testBook()
    {
        \tg\SystemFrame::log_info("begin to test Book");
	    require_once 'html/Book.php';
        require_once 'html/DocData.php';
        require_once 'html/retrieveSet.php';
        require_once 'html/RealBook.php';
        \tg\SystemFrame::log_info("finish load include");
        $book = new \tg\Book('鞋狗', "[美]菲尔-奈特", '中文', 2016, array("纪实", "励志"), "北京联合出版公司", array("ib.tsinghua.edu.cn"), "book.douban.com", "懦夫从不启程，弱者死于途中", "9787550284463", "页数:410,开本16");
        //$book = new \tg\Book('他改变了中国', array('长者'), 'Chinese', 1998, array("哲学", "膜蛤"), "人民出版社", array('www.baidu.com'), 'www.baidu.com', "不要总想搞个大新闻", '2332332', '精装');
        \tg\SystemFrame::log_info("new a book");
	    \tg\SystemFrame::docData()->addDocument($book);
	    \tg\SystemFrame::log_info("add book");
        //$book2 = \tg\SystemFrame::docData()->getDocument(1);
        //$books = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveTitle('他改变了中国'))->And(), (new \tg\retrieveAuthor('长者'))->And(),
               //                                         (new \tg\retrieveISBN('2332333'))->Or(), (new \tg\retrieveSubject('膜蛤'))->Not()));
        $books2 = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveTitle('他改变了中国'))->And()));
        if(count($books2) > 0) {
            $book1 = $books2[0];
            //var_dump($book1);
            $realBook1 = new \tg\RealBook($book1, 'k87.7', 1, true, '法学图书馆');
            //$realBook2 = new \tg\RealBook($book2, 'k909e.2', 2, true, '逸夫馆');
            $book1->addRealBook($realBook1);
            //$book2->addRealBook($realBook2);
            $realBooks = $book1->getBooks();
            //var_dump($book1);
            //var_dump($realBooks);
        }
	    \tg\SystemFrame::log_info("query books");
       // var_dump($books);

    }


    /**
     * @throws Exception
     */
    function testJournal()
    {
        require_once 'html/Journal.php';
        require_once 'html/DocData.php';

        $journal1 = new \tg\Journal('Nature', array('吴冠中', "王"), 'English', 1999, "科学",
                '科学出版社', array("www.sina.com", "www.nature.com"), "Nature Database", "少年你渴望力量吗",
                "ae86", '1*1小开本');
        \tg\SystemFrame::docData()->addDocument($journal1);
        $journals = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveAuthor('王'))->And(), (new \tg\retrieveISSN("ae986"))->And()));
        var_dump($journals);
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
            \tg\SystemFrame::log_info("finish init");
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

    //testBook();




	echo "finish";
?>
</body>
</html>
