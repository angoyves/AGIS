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

$date = date('Y-m-d H:i:s');
$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user_name']));

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET["action"])) && ($_GET["action"] == "desactive")) {
  $updateSQL = sprintf("UPDATE " . $_GET['map'] . " SET display='0', dateUpdate=%s WHERE " . $_GET['mapid'] . "=%s",
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_GET['recordID'], "int"));
  $updateGoTo = "show_personnes.php";
}
if ((isset($_GET["action"])) && ($_GET["action"] == "active")) {
  $updateSQL = sprintf("UPDATE " . $_GET['map'] . " SET display='1', dateUpdate=%s WHERE " . $_GET['mapid'] . "=%s",
					   GetSQLValueString($date, "date"),
                       GetSQLValueString($_GET['recordID'], "int"));
  $updateGoTo = "show_personnes.php";
}	
if ((isset($_GET["action"])) && ($_GET["action"] == "add_com")) {
  $user_id = (MinmapDB::getInstance()->get_user_id_by_name($_GET['uID']));
  $updateSQL = sprintf("UPDATE " . $_GET['map'] . " SET add_commission='1', dateUpdate=%s, user_id=%s WHERE " . $_GET['mapid'] . "=%s",
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"),
                       GetSQLValueString($_GET['recordID'], "int"));
  if (isset($_GET['page']) && $_GET['page'] == 'rep') {
  $updateGoTo = "new_representant.php";  
	} elseif (isset($_GET['page']) && $_GET['page'] == 'addMbr'){
  $updateGoTo = "add_membre.php";	
	} elseif (isset($_GET['page']) && $_GET['page'] == 'addMbr2'){
  $updateGoTo = "new_membres2.php";
	} else {
  $updateGoTo = "new_membres.php";
	}
}
if ((isset($_GET["action"])) && ($_GET["action"] == "move_com")) {
  $updateSQL = sprintf("UPDATE " . $_GET['map'] . " SET add_commission='0', dateUpdate=%s WHERE " . $_GET['mapid'] . "=%s",
					   GetSQLValueString($date, "date"),
                       GetSQLValueString($_GET['recordID'], "int"));
  if (isset($_GET['page']) && $_GET['page'] == 'rep') {
  $updateGoTo = "new_representant.php";  
	} elseif (isset($_GET['page']) && $_GET['page'] == 'addMbr'){
  $updateGoTo = "add_membre.php";
    } elseif (isset($_GET['page']) && $_GET['page'] == 'addMbr2'){
  $updateGoTo = "new_membres2.php";
	} else {
  $updateGoTo = "new_membres.php";
	}
}
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  //$updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));

mysql_free_result($rsUdpUsers);
?>
