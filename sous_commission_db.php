<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); 
	  
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

$colname_rsCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = sprintf("SELECT * FROM commissions WHERE commission_id = %s", GetSQLValueString($colname_rsCommission, "int"));
$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
$row_rsCommission = mysql_fetch_assoc($rsCommission);
$totalRows_rsCommission = mysql_num_rows($rsCommission);

  $countID = MinmapDB::getInstance()->get_count_sous_commission_id($_GET['comID']);
  $localite_lib = MinmapDB::getInstance()->get_localite_lib_by_localite_id($row_rsCommission['localite_id']);
  //$structure_lib = MinmapDB::getInstance()->get_structure_lib_by_structure_id($row_rsCommission['structure_id']);
  $champ = 'structure_lib';
  $structure_lib = MinmapDB::getInstance()->get_structure_lib_by_structure_id($champ, $row_rsCommission['structure_id']); 
  $type_commission_lib = MinmapDB::getInstance()->get_type_commission_lib_by_commission_id($row_rsCommission['type_commission_id']);
  $nature_lib = MinmapDB::getInstance()->get_nature_lib_by_nature_id($row_rsCommission['nature_id']);
  $Number = $countID + 1;
  $date = date('Y-m-d H:i:s');

$lib_sous_commission = "Sous commission d'analyse des offres N° " . $Number . " - issue de la " . $row_rsCommission['commission_lib'];

$insertSQL = sprintf("INSERT INTO commissions (localite_id, type_commission_id, nature_id, structure_id, commission_lib, commission_parent, membre_insert, dateCreation,  display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_rsCommission['localite_id'], "int"),
                       GetSQLValueString(6, "int"),
                       GetSQLValueString(9, "int"),
                       GetSQLValueString($row_rsCommission['structure_id'], "int"),
                       GetSQLValueString($lib_sous_commission, "text"),
                       GetSQLValueString($row_rsCommission['commission_id'], "int"),
                       GetSQLValueString(0, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString(1, "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "show_commissions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];

  header(sprintf("Location: %s", $insertGoTo));
}

mysql_free_result($rsCommission);
?>
