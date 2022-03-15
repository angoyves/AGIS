<?php require_once('Connections/MyFileConnect.php'); ?>
<?php
	  require_once('includes/db.php');
	  require_once('fonction_db.php');
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
//$query_SelSessions = "SELECT * FROM sessions";
$query_SelSessions = sprintf("SELECT * FROM sessions WHERE membres_personnes_personne_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
$SelSessions = mysql_query($query_SelSessions, $MyFileConnect) or die(mysql_error());
$row_SelSessions = mysql_fetch_assoc($SelSessions);
$totalRows_SelSessions = mysql_num_rows($SelSessions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="0">
  <tr>
    <th align="right" scope="row">Personne ID :</th>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th align="right" scope="row">Nom Personne : </th>
    <td><?php echo strtoupper(MinmapDB2::getInstance()->get_2_col_lib_by_id(personnes, personne_nom, personne_prenom, personne_id, $row_SelSessions['membres_personnes_personne_id'])); ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
    <td>&nbsp;</td>
    <td>&nbsp; </td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <th>Commission</th>
    <th>Fonction</th>
    <th>Dossier traité</th>
    <th>Nombre de dossiers</th>
    <th>Nombre de jour</th>
    <th>Etat appurement</th>
    <th>Mois</th>
    <th>Annee</th>
    <th>Date Enregistrement</th>
    <th>Date mise à jour</th>
    <th>Agent de saisie</th>
    <th>Etat Validation</th>
    <th>Agent Validateur</th>
    <th>Agent Controleur</th>
  </tr>
  <?php do { ?>
    <tr>
      <td> 
      <?php echo MinmapDB2::getInstance()->get_1_col_lib_by_id(commissions, commission_lib, commission_id, $row_SelSessions['membres_commissions_commission_id']); ?></td>
      <td><?php echo strtoupper(MinmapDB2::getInstance()->get_1_col_lib_by_id(fonctions, fonction_lib, fonction_id, $row_SelSessions['membres_fonctions_fonction_id'])); ?>&nbsp; </td>
      <td><?php echo MinmapDB2::getInstance()->get_1_col_lib_by_id(dossiers, dossier_ref, dossier_id, $row_SelSessions['dossiers_dossier_id']); ?>&nbsp; </td>
      <td align="right"><?php echo $row_SelSessions['nombre_dossier']; ?>&nbsp; </td>
      <td align="right"><?php echo $row_SelSessions['Nombre_jour']; ?>&nbsp; </td>
      <td align="right"><?php echo $row_SelSessions['etat_appur']; ?>&nbsp; </td>
      <td><?php echo strtoupper(MinmapDB2::getInstance()->get_1_col_lib_by_id(Mois, lib_mois, mois_id, $row_SelSessions['mois'])); ?>&nbsp; </td>
      <td><?php echo $row_SelSessions['annee']; ?>&nbsp; </td>
      <td><?php echo $row_SelSessions['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_SelSessions['dateUpdate']; ?>&nbsp; </td>
      <td><?php echo strtoupper(MinmapDB2::getInstance()->get_1_col_lib_by_id(users, user_name, user_id, $row_SelSessions['user_id'])); ?>&nbsp; </td>
      <td><?php echo $row_SelSessions['etat_validation']; ?>&nbsp; </td>
      <td><?php echo $row_SelSessions['userValidate']; ?>&nbsp; </td>
      <td><?php echo $row_SelSessions['userControlate']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_SelSessions = mysql_fetch_assoc($SelSessions)); ?>
</table>
<p><br />
  <?php echo $totalRows_SelSessions ?> Enregistrements Total
</p>
<p><a href="sample99.php">Revenir </a></p>
</body>
</html>
<?php
mysql_free_result($SelSessions);
?>
