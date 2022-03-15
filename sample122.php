<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

$date = date('Y-m-d H:i:s');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET["action"])) && ($_GET["action"] == "desactive")) {
 MinmapDB::getInstance()->update_desactive($_GET['map'], $_GET['mapid'], $_GET['col'], $date, $_GET['recordID']);		   
 $ModificationType = 'Desactivation de l\'identifaint numero :'.$_GET['recordID'].' dans le fichier';
 $updateGoTo = $_GET['page'];
 
} else {
 MinmapDB::getInstance()->update_actives($_GET['map'], $_GET['mapid'], $_GET['col'], $date, $_GET['recordID']);		   
 $ModificationType = 'Activation de l\'identifaint numero :'.$_GET['recordID'].' dans la demande appurement';
 $updateGoTo = $_GET['page'];	
}

  //$updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  MinmapDB::getInstance()->create_listing_action($_SESSION['MM_UserID'], $updateGoTo, $ModificationType, date('Y-m-d H:i:s'));
  header(sprintf("Location: %s", $updateGoTo));

?>
