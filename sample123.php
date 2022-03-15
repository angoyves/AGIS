<?php 
	  require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');
	  
	  
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<?php echo "sample123.php?comID=". $_GET['comID'] . "&amp;menuID=" . $_GET['menuID'] . "&amp;an=" . $_GET['an'] . "&amp;m1=" . $_GET['m1']. "&amp;m2=" .  $_GET['m2']; ?>
</BR>
<?php 
$date = date('Y-m-d H:i:s');
echo $ref_dossier = MinmapDB::getInstance()->get_ref_dossier($_POST['ref_dossier'])." pour = ". $_POST['ref_dossier']; 


echo GetSQLValueString($_POST['ref_dossier'], "text")."-".GetSQLValueString($_POST['motif_saisie'], "text")."-".GetSQLValueString($date, "date")."-".GetSQLValueString(1, "text")."-".GetSQLValueString($_POST['user_id'], "int");

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && ($ref_dossier != $_POST['ref_dossier'])) {

$insertSQL = sprintf("INSERT INTO ordre_virements (ref_dossier, motif_saisie, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ref_dossier'], "text"),
                       GetSQLValueString($_POST['motif_saisie'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect);
}
?>
</body>
</html>