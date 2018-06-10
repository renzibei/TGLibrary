<!DOCTYPE HTML>
<html>

<?php
require_once 'SystemFrame.php';


/**
 * @throws Exception
 */
function query(){
    $searchtype = isset($_POST['searchtype'])? htmlspecialchars($_POST['searchtype']) : 'bookname';
    $keywords = $_POST['keywords'];
    $result =null;
    if($searchtype =='bookname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveTitle($keywords))->And()));
    } else if($searchtype =='authorname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveAuthor($keywords))->And()));
    } else if($searchtype =='pressname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrievePublisher($keywords))->And()));
    }
    if($result == "") {
        $url = "searchFailure.html";
        echo "<script language='javascript' type='text/javascript'>";
        echo "javascript:window.location.href='$url'";
        echo "</script>";
    }
    else {
        //$url = "searchResults.html";
        echo "<script language='javascript' type='text/javascript'>";
        echo "var result = " . json_encode($result) . ";";
       // echo " javascript:window.location.href='$url' ";
        echo "</script>";
    }
}

query();

?>

<head>
<meta charset="utf-8">
<title>TGDD.com</title>
<link rel="stylesheet" type="text/css" href="../css/background.css">
<link rel="stylesheet" type="text/css" href="../css/divbase.css">
<link rel="stylesheet" type="text/css" href="../css/alink.css">
<link rel="stylesheet" type="text/css" href="../css/loggedinbtn.css">
<link rel="stylesheet" type="text/css" href="../css/flippage.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script src="../bootstrap/js/jquery-3.3.1.min.js"></script>
</head>

<body>
	<div class="page-header">
	    <h1 style="color:white">踢馆大队の学术搜索平台
	    </h1>
	</div>

	<div class="divbase panel panel-info">
				<div class="btn-group">
					<a href="mylib.html" class="loggedin1">
						<button type="button" class="btn btn-info">我的图书</button>　　　
					</a>
					<a href="index.html" class="loggedin2">
						<button type="button" class="btn btn-primary">退出登录</button>
					</a>
				</div>

<h3><span style="font-size:x-large"><a href="/css/" target="_self">海量资源</a>  <a href="/css/" target="_self">纸本图书</a>  <a href="/css/" target="_self">电子图书</a>  <a href="/css/" target="_self">纸本期刊</a>  <a href="/css/" target="_self">电子期刊</a>  <a href="/css/" target="_self">音像制品</a>  <a href="/css/" target="_self">古籍</a></span></h3>

<form action="search.php" method="post"> <input type="radio" name="searchtype" value="bookname" /><span style="text-align:center; font-size:x-large" class="white">按书名</span>  <input type="radio" name="searchtype" value="pressname" /><span style="text-align:center; font-size:x-large" class="white">按出版社</span>  <input type="radio" name="searchtype" value="authorname" /><span style="text-align:center; font-size:x-large" class="white">按作者</span>
<input type="text" name="bookname"> <input type="submit" value="搜索">

</form>

		<p style="font-size:large">我们为您找到了 <span id="numofResults"></span> 条结果。<br>
			<br><br><br><br>
		<a id="0" href="detailsofBook.html"></a><br><br>
		<a id="1" href="detailsofBook.html"></a><br><br>
		<a id="2" href="detailsofBook.html"></a><br><br>
		<a id="3" href="detailsofBook.html"></a><br><br>
		<a id="4" href="detailsofBook.html"></a><br><br>
		</p>

		<script>
			//var result = "search.php";
            // result= {
            //     "numofResults":"3",
            //     "books": [
            //         { "docid":"666666", "title": "线性代数", "info":[ "跪" ] },
            //         { "docid":"555555", "title": "微积分", "info":[ "凉凉", "学霸请客" ] },
            //         { "docid":"444444", "title": "Oops", "info":[ "瞿神万能", "杨神大巨" ] }
            //     ]
            // };
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
                document.getElementById(i).innerHTML=result[i].docID + " "
					+ result[i].title + " " + result[i].authors + " "
					+ result[i].publisher + " " + result[i].source;
			}

		</script>




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

	</div>
</body>

</html>