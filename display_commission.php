<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
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
$DATE = date('Y-m-d H:i:s');
$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$count = 0; do { $count++;

$countID = MinmapDB::getInstance()->get_localite_id_by_count_id($count, 2);

if ($countID) {
  //$countID = MinmapDB::getInstance()->get_count_id_by_localite_id($_POST['localite_id'], $_POST['type_commission_id'], $_POST['nature_id']);
  $localite_lib = MinmapDB::getInstance()->get_localite_lib_by_localite_id($countID);
  $commission_lib =  "Commission Departementale de Passation de Marche de (du) " . $localite_lib;


$insertSQL = sprintf("INSERT INTO commissions (commission_id, localite_id, type_commission_id, nature_id, commission_parent, commission_lib, dateCreation, structure_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($countID, "int"),
                       GetSQLValueString(4, "int"),
                       GetSQLValueString(9, "int"),
					   GetSQLValueString($_POST['commission_parent'], "int"),
					   GetSQLValueString($commission_lib, "text"),
					   GetSQLValueString($DATE, "date"),
					   GetSQLValueString(426, "int"));


  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
 
 }
  
} while ($count<79);

  echo "<script type='".text/javascript."'>
			alert('Commissions chargés avec succès!')
		</script>";

 $insertGoTo = "show_commissions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
   $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
?>