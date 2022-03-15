<?php require_once('Connections/MyFileConnect.php'); ?>
<?php
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

if ((isset($_GET['comID'])) && ($_GET['comID'] != "") && (isset($_GET['pID']) && ($_GET['pID'] != "") && ($_GET['fID'] != "") && ($_GET['fID'] != "") && ($_GET['aID'] != "") && ($_GET['aID'] != "") && ($_GET['mID'] != "") && ($_GET['mID'] != ""))) {
  $deleteSQL = sprintf("DELETE FROM sessions WHERE membres_personnes_personne_id=%s AND membres_fonctions_fonction_id=%s AND membres_commissions_commission_id=%s
					   AND annee=%s AND mois=%s",
                       GetSQLValueString($_GET['pID'], "int"),
					   GetSQLValueString($_GET['fID'], "int"),
					   GetSQLValueString($_GET['comID'], "int"),
					   GetSQLValueString($_GET['aID'], "int"),
					   GetSQLValueString($_GET['mID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($deleteSQL, $MyFileConnect) or die(mysql_error());
}

if ((isset($_GET['comID'])) && ($_GET['comID'] != "") && (isset($_GET['pID']) && ($_GET['pID'] != "") && ($_GET['fID'] != "") && ($_GET['fID'] != ""))) {
  $deleteSQL = sprintf("DELETE FROM membres WHERE personnes_personne_id=%s AND fonctions_fonction_id=%s AND commissions_commission_id=%s",
                       GetSQLValueString($_GET['pID'], "int"),
					   GetSQLValueString($_GET['fID'], "int"),
					   GetSQLValueString($_GET['comID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($deleteSQL, $MyFileConnect) or die(mysql_error());

  $deleteGoTo = "search_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}


?>
