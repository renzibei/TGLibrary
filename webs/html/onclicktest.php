<html>
<input type="button" onclick="click(this.value)" value="点我">
<span id="MIT">
</html>



<p><?php
$a=1;
if($_GET['a']){
    $a += 2;
}
echo $a;
?>
</p>

<script type="text/javascript">
    function click(obj){
        location.href="onclicktest.php?a="+obj;
        document.getElementById("MIT").innerHTML = $a;
    }
</script>