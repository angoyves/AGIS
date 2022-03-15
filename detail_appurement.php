<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
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

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_DetailRS1 = sprintf("SELECT concat(rib.agence_cle, rib.numero_compte, rib.cle) as num_cpte, appurements.rib_beneficiaire, personnes.personne_nom, appurements.nom_beneficiaire, membres.montant, sessions.nombre_jour, sum(membres.montant*sessions.nombre_jour) as somme, appurements.montant, (sum(membres.montant*sessions.nombre_jour)- appurements.montant) as reste_a_payer FROM personnes, rib, membres, sessions, appurements  WHERE appurements.rib_beneficiaire = %s", GetSQLValueString($colname_DetailRS1, "text"));
$DetailRS1 = mysql_query($query_DetailRS1, $MyFileConnect) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>

<table border="1" align="center">
  <tr>
    <td>num_cpte</td>
    <td><?php echo $row_DetailRS1['num_cpte']; ?></td>
  </tr>
  <tr>
    <td>rib_beneficiaire</td>
    <td><?php echo $row_DetailRS1['rib_beneficiaire']; ?></td>
  </tr>
  <tr>
    <td>personne_nom</td>
    <td><?php echo $row_DetailRS1['personne_nom']; ?></td>
  </tr>
  <tr>
    <td>nom_beneficiaire</td>
    <td><?php echo $row_DetailRS1['nom_beneficiaire']; ?></td>
  </tr>
  <tr>
    <td>montant</td>
    <td><?php echo $row_DetailRS1['montant']; ?></td>
  </tr>
  <tr>
    <td>nombre_jour</td>
    <td><?php echo $row_DetailRS1['nombre_jour']; ?></td>
  </tr>
  <tr>
    <td>somme</td>
    <td><?php echo $row_DetailRS1['somme']; ?></td>
  </tr>
  <tr>
    <td>montant</td>
    <td><?php echo $row_DetailRS1['montant']; ?></td>
  </tr>
  <tr>
    <td>reste_a_payer</td>
    <td><?php echo $row_DetailRS1['reste_a_payer']; ?></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>