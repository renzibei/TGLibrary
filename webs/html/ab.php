<?php
header("Content-type:text/html;charset=utf-8");
$username = $_POST['username'];
$age = $_POST['age'];
$job = $_POST['job'];
$json_arr = array("username"=>$username,"age"=>$age,"job"=>$job);
$json_obj = json_encode($json_arr);
echo $json_obj;
?>
