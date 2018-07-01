<!DOCTYPE HTML>
<html>

<head>
<meta charset="utf-8">
<title>TGDD.com</title>
<link rel="stylesheet" type="text/css" href="../css/background.css">
<link rel="stylesheet" type="text/css" href="../css/logindiv.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<script src="../bootstrap/js/bootstrap.min.js"></script>
</head>

<body>

<div class="page-header">
    <h1 style="color:white">登录页面
    </h1>
</div>

<br><br>
<div class="logindiv panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">登录</h3>
	</div>

	<div class="panel-body">
		<form action="index_(logged_in).php" method="post">
			<div class="form-group">
				<br><br>
				<label for="ID">学号/工号/用户名</label>
				<input type="text" class="form-control" name="ID" placeholder="请输入学生ID/教工ID/用户名">
			</div>
			<div class="form-group">
				<label for="password">密码</label>
				<input type="password" class="form-control" name="password" placeholder="请输入密码">
			</div>
			<br><br>
            <input type="submit" class="btn btn-info btn-lg center-block" value="登录" />
            <a href="../index.php">
                <button type="button" class="btn btn-default" style="position:relative; left:107px; top:15px;">取消</button>
            </a>
        </form>

	</div>
</div>

</body>

</html>
