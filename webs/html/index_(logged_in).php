<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html" charset="utf-8">
	<title>TGDD.com</title>
	<link rel="stylesheet" type="text/css" href="../css/background.css">
	<link rel="stylesheet" type="text/css" href="../css/indexdiv.css">
	<link rel="stylesheet" type="text/css" href="../css/alink.css">
	<link rel="stylesheet" type="text/css" href="../css/loggedinbtn.css">
	<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<script src="../bootstrap/js/jquery-3.3.1.min.js"></script>
	<script src="../bootstrap/js/bootstrap.min.js"></script>
</head>

<body>

<?php
    require_once 'SystemFrame.php';

    $user = null;

    /**
    * @throws Exception
    */
    function checklogin()
    {
        $ID = isset($_POST['']) ? htmlspecialchars($_POST['ID']) : '';
        $password = isset($_POST['']) ? htmlspecialchars($_POST['password']) : '';
        \tg\SystemFrame::log_info($ID);
        \tg\SystemFrame::log_info($password);
        $user = \tg\SystemFrame::userData()->queryFromUsername($ID);
        \tg\SystemFrame::log_info("kjkkjkkjkkjkkjk");
        $usertype = gettype($user);
        \tg\SystemFrame::log_info($usertype);
        /*
        if ($user === false){
            \tg\SystemFrame::log_info("False user!");
        }
        else{
            \tg\SystemFrame::log_info($user);
            $actualPwd = $user->getPassword();
            \tg\SystemFrame::log_info($actualPwd);
        }

        if ($actualPwd != $password) {
            $url = "loginFailure.html";
            echo "<script language='javascript' type='text/javascript'>";
            echo "window.location.href='$url'";
            echo "</script>";
        }*/
    }
    checklogin();
?>

	<div class="page-header">
	    <h1 style="color:white">踢馆大队の学术搜索平台
	    </h1>
	</div>

	<div class="indexdiv panel panel-info">
			<div class="btn-group">
				<a href="mylib.php" class="loggedin1">
					<button type="button" class="btn btn-info">我的图书</button>　　　
				</a>
				<a href="../index.html" class="loggedin2">
					<button type="button" class="btn btn-primary">退出登录</button>
				</a>
			<div>
	</div>
	<h3><span style="font-size:x-large"><a href="#" style=" font-weight:bold; color:#233333;" target="_self">海量资源</a>  <a href="#" target="_self">纸本图书</a>  <a href="#" target="_self">期刊杂志</a>  <a href="#" target="_self">学术论文</a> <a href="#" target="_self">在馆图书</a>

<form action="searchResults.php" method="post"><input type="radio" name="searchtype" value="bookname" /><span style="text-align:center; font-size:x-large">按书名</span>  <input type="radio" name="searchtype" value="pressname" /><span style="text-align:center; font-size:x-large">按出版社</span>  <input type="radio" name="searchtype" value="authorname" /><span style="text-align:center; font-size:x-large">按作者</span>
	<div class="form-group">
		<input type="text" class="form-control col-lg" name="keywords"
				 placeholder="请输入搜索关键字"/>
	<br><br><br><br><br>
		<input type="submit" class="btn btn-info btn-lg center-block" value="搜索" />

	</div>
</form>
</body>
