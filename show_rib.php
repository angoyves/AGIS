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
    <th width="220" align="left">ID</th>
    <td width="441"><?php echo $row_DetailRS1['personne_id']; ?></td>
  </tr>
  <tr>
    <th align="left">AGENCE CLE</th>
    <td><?php echo $row_DetailRS1['agence_cle']; ?></td>
  </tr>
  <tr>
    <th align="left">CODE BANQUE</th>
    <td><?php echo $row_DetailRS1['banque_code']; ?></td>
  </tr>
  <tr>
    <th align="left">CODE AGENCE</th>
    <td><?php echo $row_DetailRS1['agence_code']; ?></td>
  </tr>
  <tr>
    <th align="left">N° COMPTE</th>
    <td><?php echo $row_DetailRS1['numero_compte']; ?></td>
  </tr>
  <tr>
    <th align="left">CLE</th>
    <td><?php echo $row_DetailRS1['cle']; ?></td>
  </tr>
  <tr>
    <td colspan="2"><a href="upd_rib.php?recordID=<?php echo $_GET['recordID'] ?>"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/>Mettre à jour</a></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>