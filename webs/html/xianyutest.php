<?php

namespace tg;

require_once 'UserData.php';
require_once 'User.php';
require_once 'SystemFrame.php';


try{
    
/*
    $jyx = new User('MensEtManus', "Since1861");

    SystemFrame::userData()->addAccount($jyx); */

    echo SystemFrame::userData()->queryFromUsername('MensEtManus')->getUsername() . PHP_EOL;


}

catch (\Exception $e){
    echo "An exception has occurred";
}

?>
