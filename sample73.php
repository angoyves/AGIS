<?php
	  require_once('includes/db.php');
	  
	  //if (isset($_GET['recordID'])){ 
	  MinmapDB::getInstance()->update_comment($_GET['recordID'], date('Y-m-d H:i:s'));
	 // }
	$updateGoTo = "sample51.php"; 
	if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
	  
?>