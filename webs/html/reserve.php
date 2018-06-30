<?php
  if($numofAvailable)
  {
    $numofAvailable -= 1;
    Header("Location: reserveSuccess.php");
  }
  else {
    Header("Location: reserveFailure.html"); 
  }
?>
