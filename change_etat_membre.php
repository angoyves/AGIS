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

if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

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
  $updateSQL = sprintf("UPDATE " . $_GET['map'] . " SET add_commission='1', dateUpdate=%s WHERE " . $_GET['mapid'] . "=%s",
					   GetSQLValueString($date, "date"),
                       GetSQLValueString($_GET['recordID'], "int"));
  $updateGoTo = "maj_membres.php";
}
if ((isset($_GET["action"])) && ($_GET["action"] == "move_com")) {
  $updateSQL = sprintf("UPDATE personnes SET add_commission='0', dateUpdate=%s WHERE personne_id =%s",
					   GetSQLValueString($date, "date"),
                       GetSQLValueString($_GET['recordID'], "int"));
  $commission_empty = (MinmapDB::getInstance()->valid_membre_commission($_GET['comID']));
  if ($commission_empty)
  {
  $updatesSQL = sprintf("UPDATE commissions SET membre_insert=0 WHERE commission_id=%s",
                       GetSQLValueString($_GET['comID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Resultes = mysql_query($updatesSQL, $MyFileConnect) or die(mysql_error());
	  }
  $updateGoTo = "add_membre.php";
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
