<!DOCTYPE HTML>
<html>

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


<?php
require_once 'SystemFrame.php';

session_start();

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



<body>
	<div class="page-header">
	    <h1 style="color:white">踢馆大队の学术搜索平台
	    </h1>
	</div>

	<div class="divbase panel panel-info">
				<div class="btn-group">
					<a href="mylib.php" class="loggedin1">　
						<button type="button" class="btn btn-info">我的图书</button>　　　
					</a>
					<a href="../index.html" class="loggedin2">　
						<button type="button" class="btn btn-primary">退出登录</button>
					</a>
				</div>

        <h3><span style="font-size:x-large"><a href="#" style=" font-weight:bold; color:#233333;" target="_self">海量资源</a>  <a href="#" target="_self">纸本图书</a>  <a href="#" target="_self">期刊杂志</a>  <a href="#" target="_self">学术论文</a> <a href="#" target="_self">在馆图书</a>

        <form action="searchResults.php" method="post"> <input type="radio" name="searchtype" value="bookname" /><span style="text-align:center; font-size:x-large" class="white">按书名</span>  <input type="radio" name="searchtype" value="pressname" /><span style="text-align:center; font-size:x-large" class="white">按出版社</span>  <input type="radio" name="searchtype" value="authorname" /><span style="text-align:center; font-size:x-large" class="white">按作者</span>
        <input type="text" name="bookname"> <input type="submit" value="搜索">

        </form>
                <script>
                function doPost(url, struct) {  // to:提交动作（action）,struct:参数
                    var myForm = document.createElement("form");
                    myForm.method = "post";
                    myForm.action = url;
                    myForm.style.display = "none";

                    var myInput = document.createElement("input");
                    myInput.setAttribute("name", "thisbook");  // 为input对象设置name
                    myInput.setAttribute("value", struct);  // 为input对象设置value
                    myForm.appendChild(myInput);

                    document.body.appendChild(myForm);
                    myForm.submit();
                    document.body.removeChild(myForm);  // 提交后移除创建的form
                }
            </script>

		<p style="font-size:large">我们为您找到了 <span id="numofResults"></span> 条结果。<br>
			<br><br><br><br>
            <?php
                for($i = 0; $i < 20; $i++){
                    echo "<a id=\"$i\" href=\"javascript:doPost(\"detailsofBook.php\", $result[$i]);\"></a>";
                    echo "<br><br>";
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
                //if(result[i].authors !== null) var plac2 = "著"; else plac2 = "";
                if(result[i].source) var plac3 = "来源："; else plac3 = "";
                document.getElementById(i).innerHTML= plac1 + result[i].docID + " 《"
					+ result[i].title + "》 " + result[i].authors  + " "
					+ result[i].publisher + " " + plac3 + result[i].source;
			}
            for(var i = numofResults; i <= 20; i++)
                document.getElementById(i).innerHTML = "";

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
