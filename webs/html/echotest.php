<?php
require_once 'SystemFrame.php';
\tg\SystemFrame::instance()->initServer();
$result = \tg\SystemFrame::docData()->queryDoc(array((new \tg\retrieveTitle("中国"))->And()));
var_dump($result);
echo json_encode($result);