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

$maxRows_DetailRS1 = 40;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_DetailRS1 = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jours, jour, mois, annee, montant, (sum(nombre_jour) * montant) as total, banque_code, agence_code, numero_compte, cle  FROM commissions, membres, personnes,  fonctions, sessions, rib   WHERE commission_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $MyFileConnect) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>

<table border="1" align="center">
  <tr>
    <td>commission_id</td>
    <td><?php echo $row_DetailRS1['commission_id']; ?></td>
  </tr>
  <tr>
    <td>commission_lib</td>
    <td><?php echo $row_DetailRS1['commission_lib']; ?></td>
  </tr>
  <tr>
    <td>personne_id</td>
    <td><?php echo $row_DetailRS1['personne_id']; ?></td>
  </tr>
  <tr>
    <td>personne_nom</td>
    <td><?php echo $row_DetailRS1['personne_nom']; ?></td>
  </tr>
  <tr>
    <td>fonction_lib</td>
    <td><?php echo $row_DetailRS1['fonction_lib']; ?></td>
  </tr>
  <tr>
    <td>nombre_jours</td>
    <td><?php echo $row_DetailRS1['nombre_jours']; ?></td>
  </tr>
  <tr>
    <td>jour</td>
    <td><?php echo $row_DetailRS1['jour']; ?></td>
  </tr>
  <tr>
    <td>mois</td>
    <td><?php echo $row_DetailRS1['mois']; ?></td>
  </tr>
  <tr>
    <td>annee</td>
    <td><?php echo $row_DetailRS1['annee']; ?></td>
  </tr>
  <tr>
    <td>montant</td>
    <td><?php echo $row_DetailRS1['montant']; ?></td>
  </tr>
  <tr>
    <td>total</td>
    <td><?php echo $row_DetailRS1['total']; ?></td>
  </tr>
  <tr>
    <td>banque_code</td>
    <td><?php echo $row_DetailRS1['banque_code']; ?></td>
  </tr>
  <tr>
    <td>agence_code</td>
    <td><?php echo $row_DetailRS1['agence_code']; ?></td>
  </tr>
  <tr>
    <td>numero_compte</td>
    <td><?php echo $row_DetailRS1['numero_compte']; ?></td>
  </tr>
  <tr>
    <td>cle</td>
    <td><?php echo $row_DetailRS1['cle']; ?></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>