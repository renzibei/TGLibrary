<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8">
    <title>TGDD.com</title>
    <link rel="stylesheet" type="text/css" href="../css/background.css">
    <link rel="stylesheet" type="text/css" href="../css/indexdiv.css">
    <link rel="stylesheet" type="text/css" href="../css/alink.css">
    <link rel="stylesheet" type="text/css" href="../css/loginbtn.css">
    <link rel="stylesheet" type="text/css" href="../css/loggedinbtn.css">
    <link rel="stylesheet" type="text/css" href="../css/loggedintext.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <script src="../bootstrap/js/jquery-3.3.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</head>

<body>

<?php
require_once 'SystemFrame.php';
session_start();
$_GET['highlevel'] = "";
?>

<div class="page-header">
    <h1 style="color:white">踢馆大队の学术搜索平台
    </h1>
</div>

<div class="indexdiv panel panel-info">
    <div class="btn-group">
        <div style="font-size:large; position:relative; top:10px;"><?php
            if(isset($_SESSION['USER']))
                echo "　　您好，" . $_SESSION['ID'] . "！"; ?>
        </div>
        <?php
        if(isset($_SESSION['USER'])) {
            echo "<a href=\"mylib.php\" class=\"loggedin1\">".
                "<button type=\"button\" class=\"btn btn-info\">我的图书</button></a>";

            echo "<a href=\"detailsofBook.php?loggedOut=true&select=$select\" class=\"loggedin2\">".
                "<button type=\"button\" class=\"btn btn-primary\" style=\"position:relative; left:50px;\">退出登录</button></a>";
        }

        else echo "<a href=\"login.php\" class=\"loginbtn\">" .
            "<button type=\"button\" class=\"btn btn-primary\">我要登录</button></a>";
        ?>
    </div>

    <h3><span style="font-size:x-large"><a href="#" style="font-weight:bold; color:#233333;" target="_self">海量资源</a>  <a href="#" target="_self">纸本图书</a>  <a href="#" target="_self">期刊杂志</a>  <a href="#" target="_self">学术论文</a> <a href="#" target="_self">在馆图书</a></span></h3>

    <form action="searchResults.php?highlevel=true" method="post">
        <div class="form-group">
            <input type="text" class="form-control col-lg" name="keywords"
                   placeholder="请输入搜索关键字"/>
            <br><br><br><br><br>
            <input type="submit" class="btn btn-info btn-lg center-block" value="搜索" />
        </div>
    </form>
</div>


</body>
