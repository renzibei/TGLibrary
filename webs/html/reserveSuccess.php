<!DOCTYPE html>
<html>

<?php
require_once 'SystemFrame.php';

session_start();



?>



<head>
<meta http-equiv="content-type"; content="text/html"; charset="utf-8">
<title>TGDD.com</title>
<link rel="stylesheet" type="text/css" href="../css/background.css">
<link rel="stylesheet" type="text/css" href="../css/divbase.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">


<script src="../bootstrap/js/bootstrap.min.js"></script>
    <script src="../bootstrap/js/jquery-3.3.1.min.js"></script>
</head>

<body>
	<div class="page-header">
        <h1 style="color:yellow"><b>借阅成功！</b>
			</h1>
	</div>
	<div class="divbase panel panel-success">

		<br><br><br>
		<p style="text-align:center; font-size:large">去<a href="mylib.php" target="_blank">我的图书馆</a>查看所有在借书籍<br>要不要再看看其他您可能感兴趣的书籍？</p>
		<br><br><br><br>

		<div class="row">
    	<div class="col-sm-6 col-md-3">
         <div class="thumbnail" style="width:180px; height:400px">
            <img src="../images/zidongji.jpeg"
             alt="形式语言"  width="240" height="300">
            <div class="caption"><br>
                <h3>形式语言</h3>
                <p>
                    <a href="#" class="btn btn-success" role="button">
                        详情戳我
                    </a>
                </p>
            </div>
         </div>
    	</div>
			<div class="col-sm-6 col-md-3">
         <div class="thumbnail" style="width:180px; height:400px">
            <img src="../images/database2.jpeg"
             alt="数据库原理"  width="240" height="300">
            <div class="caption">
                <h3>数据库原理</h3>
                <p>
                    <a href="#" class="btn btn-success" role="button">
                        详情戳我
                    </a>
                </p>
            </div>
         </div>
    	</div>
			<div class="col-sm-6 col-md-3">
         <div class="thumbnail" style="width:180px; height:400px">
            <img src="../images/mojisuan.jpeg " width="240" height="300"
             alt="计算理论导论">
            <div class="caption">
                <h3>计算理论导论</h3>
                <p>
                    <a href="#" class="btn btn-success" role="button">
                        详情戳我
                    </a>
                </p>
            </div>
         </div>
    	</div>
			<div class="col-sm-6 col-md-3">
         <div class="thumbnail" style="width:180px; height:400px;">
            <img src="../images/abstract.jpeg" width="240" height="300"
             alt="抽象代数">
            <div class="caption">
                <h3>抽象代数</h3>
                <p>
                    <a href="#" class="btn btn-success" role="button">
                        详情戳我
                    </a>
                </p>
            </div>
         </div>
    </div>
	</div><br>
	<br><br><br><br>
	<a href="detailsofBook.php">
		<button type="button" class="btn btn-primary btn-lg center-block">返回</button>
	</a>
</body>
</html>
