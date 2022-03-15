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
$value = md5(1);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET["action"])) && ($_GET["action"] == "desactive")) {
 MinmapDB::getInstance()->update_desactive($_GET['map'], $_GET['mapid'], $_GET['col'], $date, $_GET['recordID']);		   
 $ModificationType = 'Desactivation de l\'identifaint numero :'.$_GET['recordID'].' dans le fichier';
 $updateGoTo = $_GET['page'];
 
} elseif ((isset($_GET["action"])) && ($_GET["action"] == "init")) {
 MinmapDB::getInstance()->reinit_password($_GET['map'], $_GET['mapid'], $_GET['col'], $date, $_GET['recordID']);		   
 $ModificationType = 'Reinitilisation du mot de passe de :'.$_GET['recordID'].' dans le fichier';
 $updateGoTo = $_GET['page'];
 
} else {
 MinmapDB::getInstance()->update_actives($_GET['map'], $_GET['mapid'], $_GET['col'], $date, $_GET['recordID']);		   
 $ModificationType = 'Activation de l\'identifaint numero :'.$_GET['recordID'].' dans la demande appurement';
 $updateGoTo = $_GET['page'];	
}

  //$updateGoTo = "sample113.php?comID=".$_GET['comID']."&an=".$_GET['an']."&m1=".$_GET['m1']."&m2=".$_GET['m2'];
 
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
	//$updateGoTo = index.php?value=$value;
  }

  MinmapDB::getInstance()->create_listing_action($_SESSION['MM_UserID'], $updateGoTo, $ModificationType, date('Y-m-d H:i:s'));
  header(sprintf("Location: %s", $updateGoTo));

?>
