<?php

function customError($errno, $errstr)
 { 
 echo "<b>Error:</b> [$errno] $errstr";
 }

//set error handler
set_error_handler("customError");

//echo dirname(__DIR__) . "<br />";
$userFilePath =  dirname(__DIR__) . '/dbusers/dbadmin';
//echo $userFilePath . "<br />";
if(!file_exists($userFilePath))
 {
	echo("File not found");
 }
else
 {
 //$file=fopen($userFilePath,"r");
 //echo "存在" . "<br />";
 	$userFile = fopen($userFilePath, "r"); 
 //$userFile = fopen('./html/test1.html', 'r');
	if($userFile != false) {
		echo "读取成功" . '<br />';
		$db_username = 'library_admin';//fgets($userFile);
		$db_username2 = fgets($userFile);
		$db_passwd = 'tiguandaduihenshuai'; //gets($userFile);
		$db_passwd2 = fgets($userFile);
		$db_server =  'localhost'; //fgets($userFile);
		$db_server2 =  fgets($userFile);
		//echo 'haha';
		echo '比较' . strcmp($db_server, $db_server2) . ' '  . strcmp($db_username, $db_username2) . ' ' . strcmp($db_passwd, $db_passwd2) . '<br />';
		echo 'Length' . strlen($db_server) . " " . strlen($db_server2) . "<br />";
		echo 'db_username: ' . $db_username . ' ' . $db_username2 .  '<br />' . 'db_server: ' . $db_server . ' ' . $db_server2  .'<br />' . 'db_passwd: ' . $db_passwd .' ' . $db_passwd2 . "<br />";
		$conn = new mysqli($db_server, $db_username2, $db_passwd);
		if($conn->connect_error) {
			die('连接失败：' . $coon->connect_error);
		}
		else echo '连接成功' . '<br />';
	}
	else echo"读取失败";
 }

/*
$servername = "localhost";
$username = "library_admin";
$password = "tiguandaduihenshuai";
 
// 创建连接
$conn = new mysqli($servername, $username, $password);
 
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
echo "连接成功";
*/
?>