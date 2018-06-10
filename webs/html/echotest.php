<?php
require_once 'SystemFrame.php';
\tg\SystemFrame::instance()->initServer();
$result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveTitle("中国"))->And()));
//var_dump($result[0]);
//$temp = $result[0]->jsonSerialize();
//var_dump($temp);
//var_dump($result[0]->jsonSerialize());
echo json_encode($result);
var_dump(json_last_error_msg());