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

$colYear_rsAppurements = $_GET['aID'];
if (isset($_POST['Year'])) {
  $colYear_rsAppurements = $_POST['Year'];
}
$colCommission_rsAppurements = $_GET['comID'];
if (isset($_GET['comID'])) {
  $colCommission_rsAppurements = $_GET['comID'];
}
$colMonth1_rsAppurements = $_GET['mID1'];
if (isset($_POST['Month1'])) {
  $colMonth1_rsAppurements = $_POST['Month1'];
}
$colMonth2_rsAppurements = $_GET['mID2'];
if (isset($_POST['Month2'])) {
  $colMonth2_rsAppurements = $_POST['Month2'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAppurements = sprintf("SELECT concat(rib.agence_cle, rib.numero_compte, rib.cle) as num_cpte, appurements.rib_beneficiaire, appurements.ref_dossier, personnes.personne_nom, personnes.personne_prenom, appurements.nom_beneficiaire, membres.montant as montant1, sum(sessions.nombre_jour) as nbre_jour, sum(membres.montant*sessions.nombre_jour) as somme, appurements.montant, (sum(membres.montant*sessions.nombre_jour)- appurements.montant) as reste_a_payer FROM personnes, rib, membres, sessions, appurements WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = membres.personnes_personne_id AND personnes.personne_id = sessions.membres_personnes_personne_id AND concat(rib.agence_cle, rib.numero_compte, rib.cle) = appurements.rib_beneficiaire AND sessions.mois between %s AND %s AND sessions.annee = %s AND sessions.membres_commissions_commission_id = %s GROUP BY personnes.personne_id", GetSQLValueString($colMonth1_rsAppurements, "text"),GetSQLValueString($colMonth2_rsAppurements, "text"),GetSQLValueString($colYear_rsAppurements, "text"),GetSQLValueString($colCommission_rsAppurements, "int"));
$rsAppurements = mysql_query($query_rsAppurements, $MyFileConnect) or die(mysql_error());
$row_rsAppurements = mysql_fetch_assoc($rsAppurements);
$totalRows_rsAppurements = mysql_num_rows($rsAppurements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<table width="200" border="1" summary="Effectuer des filtres sur le tableau">
  <tr>
    <th scope="row">&nbsp;</th>
    <td>debut:</td>
    <td>Fin:</td>
    <td>&nbsp;</td>
    <td>Année</td>
  </tr>
  <tr>
    <th scope="row">Periode (Mois):</th>
    <td><select name="Month1" id="Month1">
      <option value="01">Janvier</option>
      <option value="02">Fevrier</option>
      <option value="03">Mars</option>
      <option value="04">Avril</option>
      <option value="05">Mai</option>
      <option value="06">Juin</option>
      <option value="07">Juillet</option>
      <option value="08">Aout</option>
      <option value="09">Septembre</option>
      <option value="10">Octobre</option>
      <option value="11">Novembre</option>
      <option value="12">Decembre</option>
    </select></td>
    <td align="center">
        <select name="Month2" id="Month2">
          <option value="01" <?php if (!(strcmp(01, 12))) {echo "selected=\"selected\"";} ?>>Janvier</option>
          <option value="02" <?php if (!(strcmp(02, 12))) {echo "selected=\"selected\"";} ?>>Fevrier</option>
          <option value="03" <?php if (!(strcmp(03, 12))) {echo "selected=\"selected\"";} ?>>Mars</option>
          <option value="04" <?php if (!(strcmp(04, 12))) {echo "selected=\"selected\"";} ?>>Avril</option>
          <option value="05" <?php if (!(strcmp(05, 12))) {echo "selected=\"selected\"";} ?>>Mai</option>
          <option value="06" <?php if (!(strcmp(06, 12))) {echo "selected=\"selected\"";} ?>>Juin</option>
          <option value="07" <?php if (!(strcmp(07, 12))) {echo "selected=\"selected\"";} ?>>Juillet</option>
          <option value="08" <?php if (!(strcmp(08, 12))) {echo "selected=\"selected\"";} ?>>Aout</option>
          <option value="09" <?php if (!(strcmp(09, 12))) {echo "selected=\"selected\"";} ?>>Septembre</option>
          <option value="10" <?php if (!(strcmp(10, 12))) {echo "selected=\"selected\"";} ?>>Octobre</option>
          <option value="11" <?php if (!(strcmp(11, 12))) {echo "selected=\"selected\"";} ?>>Novembre</option>
          <option value="12" <?php if (!(strcmp(12, 12))) {echo "selected=\"selected\"";} ?>>Decembre</option>
        </select>
      </select>
    </td>
    <td>&nbsp;</td>
    <td><label>
      <select name="Year" id="Year">
        <option value="2014">2014</option>
        <option value="2015">2015</option>
        <option value="2016">2016</option>
        <option value="2017">2017</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><label>
      <input type="submit" name="button" id="button" value="Envoyer" />
    </label></td>
  </tr>
</table></form>
<?php echo $_POST['Month1'] . " - " . $_POST['Month2'] . " - " . $_POST['Year']; ?>  
<table border="1" align="center">
  <tr>
    <th>RIB</th>
    <th>RIB B</th>
    <th>NOM</th>
    <th>NOM B</th>
    <th>REFERENCE</th>
    <th>MONTANT</th>
    <th>SESSIONS</th>
    <th>SOMME</th>
    <th>MONTANT</th>
    <th>RESTE A PAYER</th>
  </tr>
  <?php do { ?>
    <tr>
      <td align="right"><a href="detail_appurement.php?recordID=<?php echo $row_rsAppurements['num_cpte']; ?>"> <?php echo $row_rsAppurements['num_cpte']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsAppurements['rib_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['personne_nom']. " " . $row_rsAppurements['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['nom_beneficiaire']; ?>&nbsp; </td>
      <td align="right"><a href="detail_ov.php?ovID=<?php echo $row_rsAppurements['ref_dossier']; ?>"><?php echo $row_rsAppurements['ref_dossier']; ?></a>&nbsp;</td>
      <td align="right"><?php echo number_format($row_rsAppurements['montant1'],0,' ',' '); ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['nbre_jour'],0,' ',' '); ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['somme'],0,' ',' '); ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['montant'],0,' ',' '); ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['reste_a_payer'],0,' ',' '); ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsAppurements = mysql_fetch_assoc($rsAppurements)); ?>
</table>
<br />
<?php echo $totalRows_rsAppurements ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsAppurements);
?>
