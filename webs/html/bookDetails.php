<?php
require_once 'SystemFrame.php';


/**
 * @throws Exception
 */
function query(){
    $book = isset($_POST['book'])? htmlspecialchars($_POST['book']) : '';

    if($book !='') {
        echo json_encode($book);
    }
}


