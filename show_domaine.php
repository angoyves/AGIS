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

$maxRows_DetailRS1 = 20;
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
$query_DetailRS1 = sprintf("SELECT * FROM personnes, rib, domaine_expert, localite_expert  WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = domaine_expert.personne_id AND personnes.personne_id = localite_expert.personne_id AND personnes.personne_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
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

$colname_rsPersonne = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsPersonne = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonne = sprintf("SELECT personne_nom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsPersonne, "int"));
$rsPersonne = mysql_query($query_rsPersonne, $MyFileConnect) or die(mysql_error());
$row_rsPersonne = mysql_fetch_assoc($rsPersonne);
$totalRows_rsPersonne = mysql_num_rows($rsPersonne);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="677" border="1" align="center" class="std2">
  <tr>
    <td colspan="2" align="left"><h1>Domaine de compétence de <?php echo $row_rsPersonne['personne_nom']; ?></h1>      &nbsp;</td>
  </tr>
  <tr>
    <th width="220" align="left">AG</th>
    <td width="441"><?php echo $row_DetailRS1['AG']; ?></td>
  </tr>
  <tr>
    <th align="left">Services et prestations Intellectuelles</th>
    <td><?php echo $row_DetailRS1['SPI']; ?></td>
  </tr>
  <tr>
    <th align="left">Batiments et Travaux Publics</th>
    <td><?php echo $row_DetailRS1['BAT']; ?></td>
  </tr>
  <tr>
    <th align="left">RTES</th>
    <td><?php echo $row_DetailRS1['RTES']; ?></td>
  </tr>
  <tr>
    <th align="left">Autres Services</th>
    <td><?php echo $row_DetailRS1['AI']; ?></td>
  </tr>
  <tr>
    <th align="left">SCENT</th>
    <td><?php echo $row_DetailRS1['SCENT']; ?></td>
  </tr>
  <tr>
    <td colspan="2"><a href="upd_domaine.php?recordID=<?php echo $_GET['recordID'] ?>">Mettre à jour</a></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);

mysql_free_result($rsPersonne);
?>