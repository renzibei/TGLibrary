<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html" charset="utf-8">
<title>TGDD.com</title>
<link rel="stylesheet" type="text/css" href="css/background.css">
<link rel="stylesheet" type="text/css" href="css/indexdiv.css">
<link rel="stylesheet" type="text/css" href="css/alink.css">
<link rel="stylesheet" type="text/css" href="css/loginbtn.css">

<link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">

<script src="./bootstrap/js/bootstrap.min.js"></script>
</head>

<body>
<?php
session_start();
require_once "html/SystemFrame.php";
if(isset($_SESSION['USER']))
    unset($_SESSION['USER']);
if(isset($_SESSION['ID']))
    unset($_SESSION['ID']);

/**
 * @throws Exception
 */

?>
	<div class="page-header">
	    <h1 style="color:white">踢馆大队の学术搜索平台
	    </h1>
	</div>

	<div class="indexdiv panel panel-info">

		<div class="loginbtn">
			<a href="html/login.php">
				<button type="button" class="btn btn-primary">我要登录</button>
			</a>
		</div>
        <h3><span style="font-size:x-large"><a href="#" style=" font-weight:bold; color:#233333;" target="_self">海量资源</a>  <a href="#" target="_self">纸本图书</a>  <a href="#" target="_self">期刊杂志</a>  <a href="#" target="_self">学术论文</a> <a href="#" target="_self">在馆图书</a></span></h3>

        <form action="html/searchResults.php" method="post"><input type="radio" name="searchtype" value="bookname" <?php echo("checked");?>/><span style="text-align:center; font-size:x-large" class="white">按书名</span>  <input type="radio" name="searchtype" value="pressname" /><span style="text-align:center; font-size:x-large" class="white">按出版社</span>  <input type="radio" name="searchtype" value="authorname" /><span style="text-align:center; font-size:x-large" class="white">按作者</span>
			<div class="form-group">
				<input type="text" class="form-control col-lg" name="keywords"
					   placeholder="请输入搜索关键字">
				<br>
                <a style="text-decoration: underline; position: relative; left: 610px; font-size: large"  href="html/highlevelSearch.php">高级检索</a>
                <br><br><br><br>
				<input type="submit" class="btn btn-info btn-lg center-block" value="搜索" />
			</div>
		</form>

	</div>
</body>
