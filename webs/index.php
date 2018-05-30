<!DOCTYPE html>
<html>

<head>
<meta http-equiv="Content-Type"  content="text/html"  charset="UTF-8">

<title>菜鸟教程(runoob.com)</title>
</head>
<body>
<?php
	echo "begin <br />";
	require 'html/install.php';
	SystemFrame::log_info("begin to init");
	SystemFrame::instance()->initServer();
	SystemFrame::log_info("finish");
	echo "finish";
?>
</body>
</html>