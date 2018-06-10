 <?php
     header('Content-Type:text/html; charset=gb2312');//使用gb2312编码，使中文不会变成乱码
     $backValue=$_POST['trans_data'];
     echo $backValue."+后台返回";
 ?>
