<!DOCTYPE HTML>
<html>

<?php
require_once 'SystemFrame.php';

session_start();

/**
 * @throws Exception
 */
function checkReserve(){

    $NUM = $_SESSION["numofReserved"];
    for($i = 0; $i < $NUM; $i++)
    {
        $reserved[$i] = $_SESSION["Reserved"][$i];
    }

    echo "<script language='javascript' type='text/javascript'>";
    echo "var numofReserved = " . json_encode($NUM) . ";";
    echo "var reserved = " . json_encode($reserved) . ";";
    echo "</script>";

}

checkReserve();

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
</head>

<body>
	<div class="page-header">
	    <h1 style="color:white">我的图书
	    </h1>
	</div>

	<div class="divbase panel panel-info">
				<div class="btn-group">
					<a href="../index.html" class="loggedin2">
						<button type="button" class="btn btn-primary">退出登录</button>
					</a>
				</div>



		<p style="font-size:large">您目前已经预约了 <span id="numofReserved"></span> 本书。<br>

            <br><br><br><br>

            <a id="0" href="detailsofBook.html"></a><br><br>
            <a id="1" href="detailsofBook.html"></a><br><br>
            <a id="2" href="detailsofBook.html"></a><br><br>
            <a id="3" href="detailsofBook.html"></a><br><br>
            <a id="4" href="detailsofBook.html"></a><br><br>
            <a id="5" href="detailsofBook.html"></a><br><br>
            <a id="6" href="detailsofBook.html"></a><br><br>
            <a id="7" href="detailsofBook.html"></a><br><br>
            <a id="8" href="detailsofBook.html"></a><br><br>
            <a id="9" href="detailsofBook.html"></a><br><br>
            <a id="10" href="detailsofBook.html"></a><br><br>
            <a id="11" href="detailsofBook.html"></a><br><br>
            <a id="12" href="detailsofBook.html"></a><br><br>
            <a id="13" href="detailsofBook.html"></a><br><br>
            <a id="14" href="detailsofBook.html"></a><br><br>
            <a id="15" href="detailsofBook.html"></a><br><br>
            <a id="16" href="detailsofBook.html"></a><br><br>
            <a id="17" href="detailsofBook.html"></a><br><br>
            <a id="18" href="detailsofBook.html"></a><br><br>
            <a id="19" href="detailsofBook.html"></a><br><br>
            <a id="20" href="detailsofBook.html"></a><br><br>

        </p>

        <script>

            function getJsonLength(json){
                var jsonLength=0;
                for (var i in json) {
                    jsonLength++;
                }
                return jsonLength;
            }

            var numOfReserves = getJsonLength(result);

            document.getElementById("numofReserved").innerHTML=numofReserved;
            for(var i = 0; i < numofReserves; i++){
                if(reserved[i].docID < 10) var plac1 = "0000000"; else plac1 = "000000";
                //if(reserved[i].authors !== null) var plac2 = "著"; else plac2 = "";
                if(reserved[i].source) var plac3 = "来源："; else plac3 = "";
                document.getElementById(i).innerHTML= plac1 + reserved[i].docID + " 《"
                    + reserved[i].title + "》 " + reserved[i].authors  + " "
                    + reserved[i].publisher + " " + plac3 + reserved[i].source;
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
