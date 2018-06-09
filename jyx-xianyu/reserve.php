<?php
  if($numofAvailable)
  {
    $numofAvailable -= 1;
    Header("Location: reserveSuccess.html");
  }
  else {
    Header("Location: reserveFailure.html"); 
  }
?>
