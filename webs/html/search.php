<?php
    require_once 'SystemFrame.php';


/**
 * @throws Exception
 */
function query(){
    $searchtype = isset($_POST['searchtype'])? htmlspecialchars($_POST['searchtype']) : 'bookname';
    $keywords = $_POST['keywords'];
    $result =null;
    if($searchtype =='bookname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveTitle($keywords))->And()));
    } else if($searchtype =='authorname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveAuthor($keywords))->And()));
    } else if($searchtype =='pressname') {
        $result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrievePublisher($keywords))->And()));
    }
    if($result == "") {
        $url = "searchFailure.html";
        echo "<script language='javascript' type='text/javascript'>";
        echo "javascript:window.location.href='$url'";
        echo "</script>";
    }
    else {
        $url = "searchResults.html";
        echo "<script language='javascript' type='text/javascript'>";
        echo json_encode($result);
        echo " javascript:window.location.href='$url' ";
        echo "</script>";
    }
}

query();

?>