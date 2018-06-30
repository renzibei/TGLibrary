<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html" charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/background.css">
<link rel="stylesheet" type="text/css" href="../css/divbase.css">
<link rel="stylesheet" type="text/css" href="../css/loggedinbtn.css">
<link rel="stylesheet" type="text/css" href="../css/aside.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<script src="../bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript">
        $(function(){
        	$.ajax({
            	url: "ajax_php.php",
            	type: "LOAD",

            	error: function(){
                	alert('Error loading XML document');
            	},
            	success: function(data,status){//如果调用php成功
                	alert(unescape(data));//解码，显示汉字
            	}
        	});


        $(function(){
            var book_details;
            book_details = new Array();
                $.ajax({
                    url:'search.php',
                    type:'post',
                    dataType:'json',
                    data:cont,
                    success:function(data){
                        var str = "DocId：" + data.docid + "<br>书名：" + data.title +
                            + "<br>作者：" + data.author + "<br>出版社：" + data.publisher
                        + "<br>发行年：" + data.publicationYear + "<br>语言：" + data.language;
                        $("#result").html(str);
                    }
                });
        });
	</script>
</head>

<body>
<?php session_start(); session_id(SID);?>
	<div class="page-header">
			<h1 style="color:white">图书详情
			</h1>
	</div>
	<div class="divbase panel panel-info">
		<div class="btn-group">
            <div style="font-size:large; position:absolute; right:0; top:15px;"><?php echo "您好，" . $_SESSION['ID'] . "！"; ?></div>
			<a href="mylib.php" class="loggedin1">
				<button type="button" class="btn btn-info">我的图书</button>　　　
			</a>
			<a href="../index.php" class="loggedin2">
				<button type="button" class="btn btn-primary">退出登录</button>
			</a>
		</div>

		<h1>书名</h1>

		<img src="../images/daolun2.jpg" width="240" height="300"/>

		<div class="aside">
			<br>
			<h2 class="white" style="font-size:x-large">基本信息</h2>
			<p id="details" style="font-size:large"> DocId: <span id="docid"></span><br />
				书名：<span id="title"> 算法导论</span><br />作者：<span id="author"> 科曼（Cormen,T.H.）</span><br />
				出版社：<span id="publisher">机械工业出版社</span><br />语言：<span id="language">中文</span><br />
			<a href="reserveSuccess.php">
				<button type="button" class="btn btn-success">我要借阅</button>
			</a>
		</div>

		<br><br>
		<span style="position:relative; top:-260px">
			<h3 class="white" style="font-size:x-large">内容简介</h3>
			<p> 《算法导论》自第一版出版以来，已经成为世界范围内广泛使用的大学教材和专业人员的标准参考手册。本书全面论述了算法的内容，从一定深度上涵盖了算法的诸多方面，同时其讲授和分析方法又兼顾了各个层次读者的接受能力。各章内容自成体系，可作为独立单元学习。所有算法都用英文和伪码描述，使具备初步编程经验的人也可读懂。全书讲解通俗易懂，且不失深度和数学上的严谨性。第二版增加了新的章节，如算法作用、概率分析与随机算法、线性编程等，几乎对第一版的各个部分都作了大量修订。
本书深入浅出，全面地介绍了计算机算法。对每一个算法的分析既易于理解又十分有趣，并保持了数学严谨性。本书的设计目标全面，适用于多种用途。涵盖的内容有：算法在计算中的作用，概率分析和随机算法的介绍。本书专门讨论了线性规划，介绍了动态规划的两个应用，随机化和线性规划技术的近似算法等，还有有关递归求解、快速排序中用到的划分方法与期望线性时间顺序统计算法，以及对贪心算法元素的讨论。本书还介绍了对强连通子图算法正确性的证明，对哈密顿回路和子集求和问题的NP完全性的证明等内容。全书提供了900多个练习题和思考题以及叙述较为详细的实例研究。
本书内容丰富，对本科生的数据结构课程和研究生的算法课程都是很实用的教材。本书在读者的职业生涯中，也是一本案头的数学参考书或工程实践手册。</p>
			<?php
  		echo $summary;
			?>

			<br><br>
			<h3 class="white" style="font-size:x-large">目录</h3>
			<?php
  		echo $contents;
			?>

        <table class="table table-hover">
		<caption>在架书籍</caption>
		<thead>
		    <tr>
			    <th>索书号</th>
			    <th>类型</th>
			    <th>状态</th>
			    <th>借阅</th>
		    </tr>
		</thead>
		<tbody>
		<?php
        $RealBooks = $book.getBooks();
            for($i = 0; $i < getJsonLength(result); $i++){
                echo "<tr> <br>";
                echo "<td>" . "</td>";
                echo "</tr>";
            }


        ?>
		</tbody>
	</table>
			<br><br><br><br>
			<a href="searchResults.php">
				<button type="button" class="btn btn-primary btn-lg center-block">返回</button>
			</a>
		</span>
	</div>
	<br><br><br><br>


	<script>
        var book= {
            "docid":"2333333",
            "title":"他改变了中国",
            "author":"嘻嘻",
			"publisher":"2333出版社",
			"landocument.getElementById("docid").innerHTML=book.docid;guage":"English"
        };

        document.getElementById("title").innerHTML=book.title;
        document.getElementById("author").innerHTML=book.author;
        document.getElementById("publisher").innerHTML=book.publisher;
        document.getElementById("language").innerHTML=book.language;

	</script>


</body>
</html>
