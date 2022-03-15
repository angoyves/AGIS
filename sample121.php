<?php require_once('Connections/MyFileConnect.php'); ?><?php
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

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_DetailRS1 = sprintf("SELECT * FROM appurements  WHERE num_virement = %s", GetSQLValueString($colname_DetailRS1, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $MyFileConnect) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>

<table border="1" align="center">
  <tr>
    <th>num_virement</th>
    <td><?php echo $row_DetailRS1['num_virement']; ?></td>
  </tr>
  <tr>
    <th>commission_id</th>
    <td><?php echo $row_DetailRS1['commission_id']; ?></td>
  </tr>
  <tr>
    <th>personne_id</th>
    <td><?php echo $row_DetailRS1['personne_id']; ?></td>
  </tr>
  <tr>
    <th>fonction_id</th>
    <td><?php echo $row_DetailRS1['fonction_id']; ?></td>
  </tr>
  <tr>
    <th>ref_dossier</th>
    <td><?php echo $row_DetailRS1['ref_dossier']; ?></td>
  </tr>
  <tr>
    <th>nombre_jour</th>
    <td><?php echo $row_DetailRS1['nombre_jour']; ?></td>
  </tr>
  <tr>
    <th>etat_appur</th>
    <td><?php echo $row_DetailRS1['etat_appur']; ?></td>
  </tr>
  <tr>
    <th>periode_debut</th>
    <td><?php echo $row_DetailRS1['periode_debut']; ?></td>
  </tr>
  <tr>
    <th>periode_fin</th>
    <td><?php echo $row_DetailRS1['periode_fin']; ?></td>
  </tr>
  <tr>
    <th>annee</th>
    <td><?php echo $row_DetailRS1['annee']; ?></td>
  </tr>
  <tr>
    <th>dateCreation</th>
    <td><?php echo $row_DetailRS1['dateCreation']; ?></td>
  </tr>
  <tr>
    <th>dateUpdate</th>
    <td><?php echo $row_DetailRS1['dateUpdate']; ?></td>
  </tr>
  <tr>
    <th>display</th>
    <td><?php echo $row_DetailRS1['display']; ?></td>
  </tr>
  <tr>
    <th>user_id</th>
    <td><?php echo $row_DetailRS1['user_id']; ?></td>
  </tr>
  <tr>
    <th>etat_validation</th>
    <td><?php echo $row_DetailRS1['etat_validation']; ?></td>
  </tr>
  <tr>
    <th>userValidate</th>
    <td><?php echo $row_DetailRS1['userValidate']; ?></td>
  </tr>
  <tr>
    <th>userControlate</th>
    <td><?php echo $row_DetailRS1['userControlate']; ?></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>