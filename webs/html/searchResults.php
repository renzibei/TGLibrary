<!DOCTYPE HTML>
<html>

<head>
    <meta charset="utf-8">
    <title>TGDD.com</title>
    <link rel="stylesheet" type="text/css" href="../css/background.css">
    <link rel="stylesheet" type="text/css" href="../css/divbase.css">
    <link rel="stylesheet" type="text/css" href="../css/alink.css">
    <link rel="stylesheet" type="text/css" href="../css/loginbtn.css">
    <link rel="stylesheet" type="text/css" href="../css/loggedinbtn.css">
    <link rel="stylesheet" type="text/css" href="../css/flippage.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../bootstrap/js/jquery-3.3.1.min.js"></script>
</head>


<?php
require_once 'SystemFrame.php';
session_start();

$result = null;
$_GET['loggedOut'] = isset($_GET['loggedOut'])? $_GET['loggedOut'] : false;

/**
 * @throws Exception
 */
function query(){
    $searchtype = isset($_POST['searchtype'])? htmlspecialchars($_POST['searchtype']) : 'bookname';
    $keywords = isset($_POST['keywords'])? htmlspecialchars($_POST['keywords']) : '';
    global $result;
    if($searchtype =='bookname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveTitle($keywords))->And()));
    } else if($searchtype =='authorname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveAuthor($keywords))->And()));
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveAuthor($keywords))->And()));
    } else if($searchtype =='pressname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrievePublisher($keywords))->And()));
    }
    if(sizeof($result) === 0) {
        $url = "searchFailure.html";
        echo "<script language='javascript' type='text/javascript'>";
        echo "javascript:window.location.href='$url'";
        echo "</script>";
    }
    else {
        echo "<script language='javascript' type='text/javascript'>";
        echo "var result = " . json_encode($result) . ";";
        echo "</script>";
    }
}

query();

/**
 * @throws Exception
 */
function logintest(){
    $b = isset($_SESSION['USER']);
    \tg\SystemFrame::log_info("In searchResults.php: logged=");
    \tg\SystemFrame::log_info($b);
}
logintest();

if($_GET['loggedOut']){
    if(isset($_SESSION['USER']))
        unset($_SESSION['USER']);
    if(isset($_SESSION['ID']))
        unset($_SESSION['ID']);
}

?>

<body>
	<div class="page-header">
	    <h1 style="color:white">踢馆大队の学术搜索平台
	    </h1>
	</div>

	<div class="divbase panel panel-info">
				<div class="btn-group">
                    <div style="font-size:large; "><?php
                        if(isset($_SESSION['USER']))
                            echo "您好，" . $_SESSION['ID'] . "！"; ?>
                    </div>
                    <?php
                    if(isset($_SESSION['USER'])) {
                        echo "<a href=\"mylib.php\" class=\"loggedin1\">".
				        "<button type=\"button\" class=\"btn btn-info\">我的图书</button></a>";

                        echo "<a href=\"../index.php\" class=\"loggedin2\">".
						"<button type=\"button\" class=\"btn btn-primary\" style=\"position:relative; left:60px;\">退出登录</button></a>";
                        }

                    else echo "<a href=\"login.php\" class=\"loginbtn\">" .
				        "<button type=\"button\" class=\"btn btn-primary\">我要登录</button></a>";
                    ?>
				</div>

        <h3><span style="font-size:x-large"><a href="#" style=" font-weight:bold; color:#233333;" target="_self">海量资源</a>  <a href="#" target="_self">纸本图书</a>  <a href="#" target="_self">期刊杂志</a>  <a href="#" target="_self">学术论文</a> <a href="#" target="_self">在馆图书</a></span></h3>

        <form action="searchResults.php" method="post"> <input type="radio" name="searchtype" value="bookname" <?php echo("checked");?>/><span style="text-align:center; font-size:x-large" class="white">按书名</span>  <input type="radio" name="searchtype" value="pressname" /><span style="text-align:center; font-size:x-large" class="white">按出版社</span>  <input type="radio" name="searchtype" value="authorname" /><span style="text-align:center; font-size:x-large" class="white">按作者</span>
        <input type="text" class="form-control col-lg" name="keywords"><input type="submit" value="搜索">

        </form>

		<p style="font-size:large">我们为您找到了 <span id="numofResults"></span> 条结果。<br>
			<br>
            <?php
                $_GET['select'] = "";
                $_SESSION['RESULT'] = json_encode($result);
                $NUM = sizeof($result);
                for($i = 0; $i < $NUM; $i++) {
                    echo "<a id=\"$i\" href=\"detailsofBook.php?select=$i\"></a>";
                    echo "<br>";
                    }
                    echo "<br><br><br><br><br><br>";
            ?>
		</p>

		<script>

            function getJsonLength(json){
                var jsonLength=0;
                for (var i in json) {
                    jsonLength++;
                }
                return jsonLength;
            }

            var numOfResults = getJsonLength(result);

            document.getElementById("numofResults").innerHTML=numOfResults;
            for(var i = 0; i < numOfResults; i++){
                if(result[i].docID < 10) var plac1 = "0000000"; else plac1 = "000000";
                if(result[i].source) var plac3 = "来源："; else plac3 = "";
                document.getElementById(i).innerHTML = plac1 + result[i].docID + " 《"
					+ result[i].title + "》 " + result[i].authors  + " "
					+ result[i].publisher + " " + plac3 + result[i].source;
			}
		</script>



<!--
	<div class="flippage">
	<ul class="pagination">
    <li><a href="#">&laquo;</a></li>
    <li class="active"><a href="#">1</a></li>
    <li class="disabled"><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#">&raquo;</a></li>
	</ul>
	</div>
-->

	</div>
</body>

</html>
