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

$aID = $_REQUEST['aID'];
$comID = $_REQUEST['comID'];
$m1ID = $_REQUEST['m1ID'];
$m2ID = $_REQUEST['m2ID'];

$colname_rsAppurements = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsAppurements = $_GET['recordID'];
}
$colname_rsAppurement = $_GET['txtSearch'];
if (isset($_POST['txtSearch'])) {
  $colname_rsAppurement = $_POST['txtSearch'];
}
$colname_rsAppurement2 = $_GET['txtChamp'];
if (isset($_POST['txtChamp'])) {
  $colname_rsAppurement2 = $_POST['txtChamp'];
}

	$colname_rsAppurement3 = (empty($_POST['txtOrder'])?"nom_beneficiaire":$_POST['txtOrder']);
	$txtOrder = (isset($_GET['txtOrder'])?$_GET['txtOrder']:"motif");
	$txtSearch = (isset($_POST['txtSearch'])?$_POST['txtSearch']:$_GET['txtSearch']);
	$txtChamp = (isset($_POST['txtChamp'])?$_POST['txtChamp']:$_GET['txtChamp']);
	$txtLimit = (isset($_POST['txtLimit'])?$_POST['txtLimit']:$_GET['txtLimit']);
	

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_POST['txtSearch']) || isset($_GET['txtSearch'])){
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE %s LIKE %s ORDER BY %s ASC LIMIT %s", $colname_rsAppurement2, GetSQLValueString("%" . $colname_rsAppurement . "%", "text"), $txtOrder, $txtLimit);
} else {
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE ref_dossier = %s ORDER BY %s ASC", GetSQLValueString($colname_rsAppurements, "text"), $txtOrder);
}
$rsAppurements = mysql_query($query_rsAppurements, $MyFileConnect) or die(mysql_error());
$row_rsAppurements = mysql_fetch_assoc($rsAppurements);
$totalRows_rsAppurements = mysql_num_rows($rsAppurements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AGIS</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table border="0">
    <tr>
    <td><strong>Rechercher Par :</strong></td>
    <td><select name="txtChamp" id="txtChamp">
      <option value="motif" <?php if (!(strcmp("motif", $_POST['txtChamp']))) {echo "selected=\"selected\"";} ?>>Motif virement</option>
      <option value="nom_beneficiaire" <?php if (!(strcmp("nom_beneficiaire", $_POST['txtChamp']))) {echo "selected=\"selected\"";} ?>>Nom beneficiaire</option>
    </select></td>
    <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo $txtSearch; ?>" /></td>
    <td><select name="txt" id="txt">
    </select></td>
    <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
  </tr>
  <tr>
    <td colspan="5"><strong><?php echo $query_rsAppurements; ?></strong></td>
  </tr>
  <tr>
    <td colspan="5" align="right">Nombre d'enregistrements
      <select name="txtLimit" id="txtLimit">
        <option value="10" <?php if (!(strcmp(10, $txtLimit))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="30" <?php if (!(strcmp(30, $txtLimit))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="60" <?php if (!(strcmp(60, $txtLimit))) {echo "selected=\"selected\"";} ?>>60</option>
        <option value="100" <?php if (!(strcmp(100, $txtLimit))) {echo "selected=\"selected\"";} ?>>100</option>
      </select>
&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><?php if (isset($_POST['txtSearch'])) { ?>
      Enregistrement trouv&eacute; pour votre recherche &quot;<strong><?php echo $_POST['txtSearch']; ?></strong>&quot; dans <strong> <?php echo $_POST['txtChamp']; ?></strong>
      <?php } ?></td>
  </tr>
</table>
</form>
<?php
echo '<a href="show_all_ov.php">Retour à l\'ecran precedent</a>'; ?>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>
      <p>
        <?php if (isset($txtOrder) && ($txtOrder == "nom_beneficiaire")){ ?>
        <a href="detail_ov.php?ovID=<?php echo $_GET['ovID'] ?>&txtChamp=<?php echo $txtChamp ?>&txtSearch=<?php echo $txtSearch ?>&amp;txtOrder=num_virement&txtLimit=<?php echo $txtLimit; ?>">N&deg; Virement</a>
        <?php } else { ?>
      N&deg; Virement
      <?php } ?>
      </p>
</th>
    <th>RIB Beneficiaire</th>

    <th><?php if (isset($txtOrder) && ($txtOrder == "num_virement")){ ?>
    <a href="detail_ov.php?ovID=<?php echo $_GET['ovID'] ?>&amp;txtChamp=<?php echo $txtChamp ?>&amp;txtSearch=<?php echo $txtSearch ?>&amp;txtOrder=nom_beneficiaire&txtLimit=<?php echo $txtLimit; ?>">Nom Beneficiaire</a>
      <?php } else { ?>
Nom Beneficiaire
<?php } ?></th>
    <th>Ref Dossier</th>
    <th>Motif Paiement</th>
    <th>Montant</th>
  </tr>
  <?php $counter=0; $i = 0; do { $i++; $counter++;?>
  <?php 
  		//$tatAppurement = MinmapDB::getInstance()->verify_appurement_credentials_2($comID, $row_rsAppurements['rib_beneficiaire'], $m1ID, $m2ID, $aID); 
  		//$etatRib = MinmapDB::getInstance()->verify_rib_credentials($row_rsAppurements['rib_beneficiaire']); 
  ?>
  
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>"> 	
      <td><a href="detail_appurements.php?recordID=<?php echo $row_rsAppurements['appurement_id']; ?>"> <?php echo $i; ?>&nbsp; </a></td>
      <td><?php echo $row_rsAppurements['num_virement']; ?>&nbsp; </td>
      <td><?php echo substr($row_rsAppurements['rib_beneficiaire'],0,5) . '-' . substr($row_rsAppurements['rib_beneficiaire'],5,5) . '-' . substr($row_rsAppurements['rib_beneficiaire'],10,11).'-' . substr($row_rsAppurements['rib_beneficiaire'],21,2); ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['nom_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['ref_dossier']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['motif']; ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['montant'],0,' ',' '); ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsAppurements = mysql_fetch_assoc($rsAppurements)); ?>
    <tr>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>

    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
</table>
<strong><?php echo $totalRows_rsAppurements ?></strong> Enregistrements Total </br><strong>Legende</strong> : 
<strong>S*</strong>= Session Existantante? <strong>RIB</strong> = Releve d'Identit&eacute; Bancaire existante?<br /> 
NB : Cliquez sur<a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a> pour ajouter le RIB </br>
</body>
</html>
<?php
mysql_free_result($rsAppurements);
?>