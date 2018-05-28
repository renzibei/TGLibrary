<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type"; content="text/html"; charset="utf-8">
<title>TGDD.com</title>
<style>
a:link {color:white; text-decoration:none;}      /* 未访问链接*/
a:visited {color:white; text-decoration:none;}  /* 已访问链接 */
a:hover {color:white; text-decoration: underline;}  /* 鼠标移动到链接上 */
a:active {color:white; text-decoration: underline;}  /* 鼠标点击时 */

body
{
	background-image:url("Dreamy Grassland under the Sun.jpg");
}

h1
{
	position:relative;
	left:150px;
	color:orange;
}

h3
{
	color:white;
}

.white
{
	color:white;
}

.img1
{
	border-style:groove;
	border-color:green;
	border-width:10px;
	position: absolute;
	left:0px;
  top:0px;
	z-index: -1;
}

p
{
	font-family:"Times New Roman";
	color:white;
	font-size:20px;
	white-space:nowrap;
}
</style>
</head>

<body>
<img class="img1" src="logo.jpeg" width="100" height="100" position="left top"/>
<h1>图书详情</h1>

<img class="img2" src="book.jpeg" width="240" height="300" position="left"/>
<h3 class="white">基本信息</h3>
<?php
  echo "书名：" . $bookname . PHP_EOL
			."出版社：" . $pressname . PHP_EOL
			."页数：" . $numofPages . PHP_EOL
			."ISBN：" .$ISBN . PHP_EOL
			."剩余库存：" .$numofAvailable . "本可借阅" .PHP_EOL;
?>

<br><br>
<h3 class="white">内容简介</h3>
<?php
  echo $summary;
?>

<br><br>
<h3 class="white">目录</h3>
<?php
  echo $contents;
?>

<br><br><br><br>
<form action="login.html" method="post">
<input type="submit" value="返回">
