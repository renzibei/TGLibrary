<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html" charset="utf-8">
<link rel="stylesheet" type="text/css" href="../css/background.css">
<link rel="stylesheet" type="text/css" href="../css/divbase.css">
<link rel="stylesheet" type="text/css" href="../css/loginbtn.css">
<link rel="stylesheet" type="text/css" href="../css/loggedinbtn.css">
<link rel="stylesheet" type="text/css" href="../css/aside.css">
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<script src="../bootstrap/js/bootstrap.min.js"></script>

</head>

<body>
<?php
session_start();
require_once "SystemFrame.php";

$_GET['loggedOut'] = isset($_GET['loggedOut'])? $_GET['loggedOut'] : false;
$thisbook = null;
$realBooks = array();
$select = $_GET['select'];

if($_GET['loggedOut']){
    if(isset($_SESSION['USER']))
        unset($_SESSION['USER']);
    if(isset($_SESSION['ID']))
        unset($_SESSION['ID']);
}

/**
 * @throws Exception
 */
function retrieve(){
    global $thisbook, $realBooks, $select;
    \tg\SystemFrame::log_info($select);
    $result = json_decode($_SESSION['RESULT']);
    $thisbook = $result[$select];
    $thisbookinfo = json_encode($thisbook);
    \tg\SystemFrame::log_info($thisbookinfo);
    $realBooks = $thisbook->realBooks;
    $realBooksinfo = json_encode($realBooks);
    \tg\SystemFrame::log_info($realBooksinfo);
}
retrieve();
?>
	<div class="page-header">
			<h1 style="color:white">图书详情
			</h1>
	</div>
	<div class="divbase panel panel-info">
		<div class="btn-group">

            <div style="font-size:large;"><?php
                if(isset($_SESSION['USER']))
                    echo "您好，" . $_SESSION['ID'] . "！"; ?>
            </div>
            <?php
            if(isset($_SESSION['USER'])) {
                echo "<a href=\"mylib.php\" class=\"loggedin1\">".
                    "<button type=\"button\" class=\"btn btn-info\">我的图书</button></a>";

                echo "<a href=\"detailsofBook.php?loggedOut=true&select=$select\" class=\"loggedin2\">".
                    "<button type=\"button\" class=\"btn btn-primary\" style=\"position:relative; left:60px;\">退出登录</button></a>";
            }

            else echo "<a href=\"login.php\" class=\"loginbtn\">" .
                "<button type=\"button\" class=\"btn btn-primary\">我要登录</button></a>";
            ?>
		</div>

		<h1><?php echo $thisbook->title;?></h1>

		<img src="../images/daolun2.jpg" width="240" height="300"/>

		<div class="aside">
			<br>
			<h2 class="white" style="font-size:x-large">基本信息</h2>
			<p id="details" style="font-size:large">
                DocId: <span id="docid"><?php echo $thisbook->docID;?></span><br />
                作者：<span id="authors"> <?php echo $thisbook->authors;?></span><br />
				出版社：<span id="publisher"><?php echo $thisbook->publisher;?></span><br />
                发行年：<span id="publisher"><?php echo $thisbook->publicationYear;?></span><br />
                类型: <span id="format"><?php echo $thisbook->format;?></span><br />
                语言：<span id="language"><?php echo $thisbook->language;?></span><br />
            </p>
		</div>

		<br><br>
		<span style="position:relative; top:-260px">
			<h3 class="white" style="font-size:x-large">内容简介</h3>
			<!--<p> 《算法导论》自第一版出版以来，已经成为世界范围内广泛使用的大学教材和专业人员的标准参考手册。本书全面论述了算法的内容，从一定深度上涵盖了算法的诸多方面，同时其讲授和分析方法又兼顾了各个层次读者的接受能力。各章内容自成体系，可作为独立单元学习。所有算法都用英文和伪码描述，使具备初步编程经验的人也可读懂。全书讲解通俗易懂，且不失深度和数学上的严谨性。第二版增加了新的章节，如算法作用、概率分析与随机算法、线性编程等，几乎对第一版的各个部分都作了大量修订。
本书深入浅出，全面地介绍了计算机算法。对每一个算法的分析既易于理解又十分有趣，并保持了数学严谨性。本书的设计目标全面，适用于多种用途。涵盖的内容有：算法在计算中的作用，概率分析和随机算法的介绍。本书专门讨论了线性规划，介绍了动态规划的两个应用，随机化和线性规划技术的近似算法等，还有有关递归求解、快速排序中用到的划分方法与期望线性时间顺序统计算法，以及对贪心算法元素的讨论。本书还介绍了对强连通子图算法正确性的证明，对哈密顿回路和子集求和问题的NP完全性的证明等内容。全书提供了900多个练习题和思考题以及叙述较为详细的实例研究。
本书内容丰富，对本科生的数据结构课程和研究生的算法课程都是很实用的教材。本书在读者的职业生涯中，也是一本案头的数学参考书或工程实践手册。</p>-->

  		<span class="text-size:x-large"><?php echo $thisbook->description;?></span>


			<br><br>
			<h3 class="white" style="font-size:x-large">目录</h3>
			<?php
  		echo $thisbook->contents;
			?>

            <h3>在架书籍</h3>
        <table class="table table-hover">
		<caption></caption>
		<thead>
		    <tr>
			    <th>索书号</th>
			    <th>所在馆</th>
			    <th>状态</th>
			    <th>借阅</th>
		    </tr>
		</thead>
		<tbody>
		<?php
        /*$book = $_SESSION['thisbook'];
        $RealBooks = $book->getBooks();
        if(gettype($RealBooks) === "array"){
            $NUM = sizeof($RealBooks);
            for($i = 0; $i < $NUM; $i++){
                $ISBN = $RealBooks[$i]->getIsbn();
                $place = $RealBooks[$i]->getPlace();
                $onshelf = $RealBooks[$i]->isOnShelf();
                echo "<tr><br>";
                echo "<td>$ISBN</td><br>";
                echo "<td>$place</td><br>";
                if($onshelf) echo "<td>在架</td><br>";
                else echo "<td>暂不在架</td><br>";
                echo "<td>" .
                "<a href=\"reserveSuccess.php\">" .
				"<button type=\"button\" class=\"btn btn-success\">我要借阅</button>" .
			    "</a>";
                echo "</tr>";
            }
        }*/
        ?>
		</tbody>
	    </table>
			<br><br><br><br>
            <?php
            echo "<a href=\"searchResults.php?OldPage=true\">" .
				"<button type=\"button\" class=\"btn btn-primary btn-lg center-block\">返回</button></a>";
            ?>

		</span>
	</div>
	<br><br><br><br>

</body>
</html>
