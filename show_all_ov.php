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

$colname_rsAppurements = "-1";
if (isset($_REQUEST['txtSearch'])) {
  $colname_rsAppurements = $_REQUEST['txtSearch'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE (motif LIKE %s OR nom_beneficiaire LIKE %s OR rib_beneficiaire LIKE %s OR ref_dossier LIKE %s ) ORDER BY nom_beneficiaire ASC", GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text"));
$rsAppurements = mysql_query($query_rsAppurements, $MyFileConnect) or die(mysql_error());
$row_rsAppurements = mysql_fetch_assoc($rsAppurements);
$totalRows_rsAppurements = mysql_num_rows($rsAppurements);

          
	$showGoTo = "upd_all_ov.php?recordID=";
	  /*if (isset($_SERVER['QUERY_STRING'])) {
		$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
		$showGoTo .= $_SERVER['QUERY_STRING'];
	  }*/


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
AFFICHER LES ORDRES DE VIREMENTS<br />
<a href="#" onclick="<?php popup("insappurements.php", "610", "350");?>">Ajouter un Ordre de Virement</a><br />
<table align="center">
  <tr>
    <td colspan="12">Rechercher par Motif, Nom, RIB, ou Reference dossier 
      <form id="form1" name="form1" method="post" action="">
        <input type="text" name="txtSearch" id="txtSearch" value="<?php echo $_REQUEST['txtSearch']; ?>" />
        <input type="submit" name="button" id="button" value="Envoyer" />
    </form></td>
  </tr>
</table>
<?php echo 'Resultat de la recherche pour : <strong>' . $_REQUEST['txtSearch'] . '</strong><br />'; ?><br />
<?php if (isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup']==1 || $_SESSION['MM_UserGroup']==8)) { 
	$link2 = 'print_all_ov.php?txtSearch='. $colname_rsAppurements;
?>
	  
      <a href="" onClick="<?php popup($link2, "1200", "700"); ?>">Print recap<img src="images/img/b_print.png" width="16" height="16" border="0" /></a>
      <?php } ?>
<table border="1" align="center">
<?php if ($totalRows_rsAppurements > 0) { // Show if recordset not empty ?>
  <tr>
    <th nowrap="nowrap">ID</th>
    <th nowrap="nowrap">COMISSION ID</th>
    <th nowrap="nowrap">NUMERO</th>
    <th nowrap="nowrap">RIB BENEFICIAIRE</th>
    <th nowrap="nowrap">NOM BENEFICIAIRE</th>
    <th nowrap="nowrap">REFERENCE </th>
    <th nowrap="nowrap">MOTIF </th>
    <th nowrap="nowrap">MONTANT</th>
    <th nowrap="nowrap">DEBUT</th>
    <th nowrap="nowrap">FIN</th>
    <th nowrap="nowrap">ANNEE</th>
    <th nowrap="nowrap">ACTIF</th>
  </tr>
  <?php $montant_total = 0; do { ?>
    <tr>
      <td><a href="#" onclick="<?php popup($showGoTo.$row_rsAppurements['appurement_id'], "610", "300");?>"> <?php echo $row_rsAppurements['appurement_id']; ?><img src="/images/img/b_edit.png" width="16" height="16" /></a>&nbsp;</td>
      <td><?php echo $row_rsAppurements['commission_id']; ?>&nbsp;</td>
      <td><?php echo $row_rsAppurements['num_virement']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['rib_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['nom_beneficiaire']; ?>&nbsp; </td>
      <td><a href="show_ref_dossier.php?recordID=<?php echo $row_rsAppurements['ref_dossier']; ?>"><?php echo $row_rsAppurements['ref_dossier']; ?></a>&nbsp; </td>
      <td><?php echo $row_rsAppurements['motif']; ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['montant'],0,' ',' ');; $montant_total = $row_rsAppurements['montant'] + $montant_total; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['periode_debut']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['periode_fin']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['annee']; ?>&nbsp; </td>
      <td><?php //echo $row_rsAppurements['display']; ?>&nbsp; 
      
      
      
      
      <?php if (isset($row_rsAppurements['display']) && $row_rsAppurements['display'] == '1') { ?>
        <a href="change_display.php?recID=<?php echo $row_rsAppurements['appurement_id']; ?>&map=appurements&mapid=appurement_id&display=0&menuID=<?php echo $_GET['menuID']; ?>&txtSearch=<?php echo $_REQUEST['txtSearch']; ?>"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } else { ?>
        <a href="change_display.php?recID=<?php echo $row_rsAppurements['appurement_id']; ?>&map=appurements&mapid=appurement_id&display=1&menuID=<?php echo $_GET['menuID']; ?>&txtSearch=<?php echo $_REQUEST['txtSearch']; ?>"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php } ?>
      </td>
    </tr>
    <?php } while ($row_rsAppurements = mysql_fetch_assoc($rsAppurements)); ?>
  <tr>
    <th colspan="7" nowrap="nowrap">TOTAL</th>
    <th align="right" nowrap="nowrap"><?php echo number_format(MinmapDB::getInstance()->get_montant_ov($_REQUEST['txtSearch']),0,' ',' '); ?>&nbsp;</th>
    <th nowrap="nowrap">&nbsp;</th>
    <th nowrap="nowrap">&nbsp;</th>
    <th nowrap="nowrap">&nbsp;</th>
    <th nowrap="nowrap">&nbsp;</th>
  </tr>
  <?php } // Show if recordset not empty ?>
</table>
<br />
<?php echo $totalRows_rsAppurements ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsAppurements);
?>
