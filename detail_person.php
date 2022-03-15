<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_DetailRS1 = 10;
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
$query_DetailRS1 = sprintf("SELECT * FROM personnes  WHERE personne_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
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

$maxRows_rsRIB = 10;
$pageNum_rsRIB = 0;
if (isset($_GET['pageNum_rsRIB'])) {
  $pageNum_rsRIB = $_GET['pageNum_rsRIB'];
}
$startRow_rsRIB = $pageNum_rsRIB * $maxRows_rsRIB;

$colname_rsRIB = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsRIB = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRIB = sprintf("SELECT rib.personne_id, rib.agence_code, agence_lib, rib.banque_code, banque_lib, numero_compte, cle FROM rib, personnes, agences, banques WHERE personnes.personne_id = rib.personne_id AND rib.agence_code = agences.agence_code AND rib.banque_code = banques.banque_code AND personnes.personne_id = %s", GetSQLValueString($colname_rsRIB, "int"));
$query_limit_rsRIB = sprintf("%s LIMIT %d, %d", $query_rsRIB, $startRow_rsRIB, $maxRows_rsRIB);
$rsRIB = mysql_query($query_limit_rsRIB, $MyFileConnect) or die(mysql_error());
$row_rsRIB = mysql_fetch_assoc($rsRIB);

if (isset($_GET['totalRows_rsRIB'])) {
  $totalRows_rsRIB = $_GET['totalRows_rsRIB'];
} else {
  $all_rsRIB = mysql_query($query_rsRIB);
  $totalRows_rsRIB = mysql_num_rows($all_rsRIB);
}
$totalPages_rsRIB = ceil($totalRows_rsRIB/$maxRows_rsRIB)-1;

$colname_rsShowBanque = "-1";
if (isset($row_rsRIB['banque_code'])) {
  $colname_rsShowBanque = $row_rsRIB['banque_code'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowBanque = sprintf("SELECT banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsShowBanque, "text"));
$rsShowBanque = mysql_query($query_rsShowBanque, $MyFileConnect) or die(mysql_error());
$row_rsShowBanque = mysql_fetch_assoc($rsShowBanque);
$totalRows_rsShowBanque = mysql_num_rows($rsShowBanque);

$colname_rsShowAgence = "-1";
if (isset($row_rsRIB['agence_code'])) {
  $colname_rsShowAgence = $row_rsRIB['agence_code'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowAgence = sprintf("SELECT agence_lib FROM agences WHERE agence_code = %s", GetSQLValueString($colname_rsShowAgence, "text"));
$rsShowAgence = mysql_query($query_rsShowAgence, $MyFileConnect) or die(mysql_error());
$row_rsShowAgence = mysql_fetch_assoc($rsShowAgence);
$totalRows_rsShowAgence = mysql_num_rows($rsShowAgence);

$queryString_rsRIB = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsRIB") == false && 
        stristr($param, "totalRows_rsRIB") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsRIB = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsRIB = sprintf("&totalRows_rsRIB=%d%s", $totalRows_rsRIB, $queryString_rsRIB);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="50%" border="1" class="std2">
  <tr>
    <th align="right">ID :</th>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php if (isset($row_rsRIB['personne_matricule']) && $row_rsRIB['personne_matricule'] != 'xxxxxx-x' ) { ?>
  <tr>
    <th align="right">MATRICULE :</th>
    <td><?php echo $row_rsRIB['personne_matricule']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php } ?>
  <tr>
    <th align="right" nowrap="nowrap"> BANQUE :</th>
    <td nowrap="nowrap"><?php echo $row_rsRIB['banque_code']; ?></td>
    <th nowrap="nowrap">AGENCE :</th>
    <td nowrap="nowrap"><?php echo $row_rsRIB['agence_code'];?></td>
  </tr>
  <tr>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap"><?php echo $row_rsShowBanque['banque_lib']; ?></td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap"><?php echo $row_rsShowAgence['agence_lib']?></td>
  </tr>
  <tr>
    <th align="right" nowrap="nowrap">NUMERO COMPTE :</th>
    <td nowrap="nowrap"><?php echo $row_rsRIB['numero_compte']; ?></td>
    <th nowrap="nowrap">CLE :</th>
    <td nowrap="nowrap"><?php echo $row_rsRIB['cle']; ?></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);

mysql_free_result($rsRIB);

mysql_free_result($rsShowBanque);

mysql_free_result($rsShowAgence);
?>