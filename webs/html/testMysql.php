<?php
/*
function customError($errno, $errstr)
 { 
 echo "<b>Error:</b> [$errno] $errstr";
 }
*/

function getLine($fileHandle)
{
	$tempBuffer = fgets($fileHandle);
	if($tempBuffer == false)
		return false;
	$tempBuffer = substr($tempBuffer, 0, -1);
	return $tempBuffer;
}

//set error handler
/*
set_error_handler("customError");
*/

$userFilePath =  dirname(__DIR__) . '/dbusers/dbadmin';
if(!file_exists($userFilePath))
 {
	echo("File not found");
 }
else
 {
 	$userFile = fopen($userFilePath, "r"); 
	if($userFile != false) {
		echo "读取成功" . '<br />';
		$db_username2 = getLine($userFile);
		$db_passwd2 = getLine($userFile);
		$db_server2 = getLine($userFile);
		$conn = new mysqli($db_server2, $db_username2, $db_passwd2);
		if($conn->connect_error) {
			die('连接失败：' . $coon->connect_error);
		}
		else echo '连接成功' . '<br />';
	}
	else echo"读取失败";
 }

/*
\
 
// 创建连接
$conn = new mysqli($servername, $username, $password);
 
// 检测连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
} 
echo "连接成功";
*/
?>