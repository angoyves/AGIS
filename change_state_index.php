<?php require_once('Connections/MyFileConnect.php'); ?>
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
  $updateGoTo = "new_membres.php";
}
if ((isset($_GET["action"])) && ($_GET["action"] == "move_com")) {
  $updateSQL = sprintf("UPDATE " . $_GET['map'] . " SET add_commission='0', dateUpdate=%s WHERE " . $_GET['mapid'] . "=%s",
					   GetSQLValueString($date, "date"),
                       GetSQLValueString($_GET['recordID'], "int"));
  $updateGoTo = "new_membres.php";
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
