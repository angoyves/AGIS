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
$query_DetailRS1 = sprintf("SELECT personnes.personne_nom, personnes.personne_prenom, sessions.* FROM sessions, personnes  WHERE personne_nom = %s", GetSQLValueString($colname_DetailRS1, "text"));
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
<title>Document sans titre</title>
</head>

<body>

<table border="1" align="center">
  <tr>
    <td>membres_personnes_personne_id</td>
    <td><?php echo $row_DetailRS1['membres_personnes_personne_id']; ?></td>
  </tr>
  <tr>
    <td>personne_nom</td>
    <td><?php echo $row_DetailRS1['personne_nom']; ?></td>
  </tr>
  <tr>
    <td>personne_prenom</td>
    <td><?php echo $row_DetailRS1['personne_prenom']; ?></td>
  </tr>
  <tr>
    <td>membres_commissions_commission_id</td>
    <td><?php echo $row_DetailRS1['membres_commissions_commission_id']; ?></td>
  </tr>
  <tr>
    <td>membres_fonctions_fonction_id</td>
    <td><?php echo $row_DetailRS1['membres_fonctions_fonction_id']; ?></td>
  </tr>
  <tr>
    <td>dossiers_dossier_id</td>
    <td><?php echo $row_DetailRS1['dossiers_dossier_id']; ?></td>
  </tr>
  <tr>
    <td>nombre_dossier</td>
    <td><?php echo $row_DetailRS1['nombre_dossier']; ?></td>
  </tr>
  <tr>
    <td>Nombre_jour</td>
    <td><?php echo $row_DetailRS1['Nombre_jour']; ?></td>
  </tr>
  <tr>
    <td>etat_appur</td>
    <td><?php echo $row_DetailRS1['etat_appur']; ?></td>
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
    <td>dateCreation</td>
    <td><?php echo $row_DetailRS1['dateCreation']; ?></td>
  </tr>
  <tr>
    <td>dateUpdate</td>
    <td><?php echo $row_DetailRS1['dateUpdate']; ?></td>
  </tr>
  <tr>
    <td>display</td>
    <td><?php echo $row_DetailRS1['display']; ?></td>
  </tr>
  <tr>
    <td>user_id</td>
    <td><?php echo $row_DetailRS1['user_id']; ?></td>
  </tr>
  <tr>
    <td>etat_validation</td>
    <td><?php echo $row_DetailRS1['etat_validation']; ?></td>
  </tr>
  <tr>
    <td>userValidate</td>
    <td><?php echo $row_DetailRS1['userValidate']; ?></td>
  </tr>
  <tr>
    <td>userControlate</td>
    <td><?php echo $row_DetailRS1['userControlate']; ?></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>