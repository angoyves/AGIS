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
if (isset($_GET['txt_search'])) {
  $colname_rsAppurements = $_GET['txt_search'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE (motif LIKE %s OR nom_beneficiaire LIKE %s OR rib_beneficiaire LIKE %s OR ref_dossier LIKE %s ) ORDER BY nom_beneficiaire, ref_dossier ASC", GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text"));
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
<title>AGIS::Ordres de Virements</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
<link href="css/v2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="60%" border="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="869" height="46" align="center" valign="bottom">&nbsp;</td>
    <td width="376"><a href="#" title="Imprimer cette page" class="Print_link" onclick="window.print()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
  </tr>
  <tr>
    <td><table>
      <tr>
        <th align="center" nowrap="nowrap">REPUBLIQUE  DU CAMEROUN</BR>
          Paix - Travail - Patrie</BR>
          ------*------</BR>
          PRESIDENCE  DE LA REPUBLIQUE</BR>
          ------*------</BR>
          MINISTERE  DES MARCHES PUBLICS</BR></th>
      </tr>
    </table></td>
    <td width="869" height="48" align="center" valign="bottom">&nbsp;</td>
    <td width="376"><table>
      <tr>
        <th align="center" nowrap="nowrap">REPUBLIC OF CAMEROON</BR>
          Peace - Work - Fatherland</BR>
          ------*------</BR>
          PRESIDENCY  OF THE REPUBLIC</BR>
          ------*------</BR>
          MINISTRY  OF PUBLIC CONTRACTS</th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="157">&nbsp;</td>
    <td align="center" valign="top">ETAT DES COMMISIONS DE PASSATION DES MARCHES</td>
    <td width="376">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><strong class="welcome"><?php echo htmlentities(strtoupper($row_rsSessions['commission_lib'])); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">AFFICHER LES ORDRES DE VIREMENTS</td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">
    <table border="1" align="center" class="print">
      <?php if ($totalRows_rsAppurements > 0) { // Show if recordset not empty ?>
      <tr>
        <th nowrap="nowrap">N° VIREMENT</th>
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
        <td nowrap="nowrap"><?php echo $row_rsAppurements['num_virement']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsAppurements['rib_beneficiaire']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsAppurements['nom_beneficiaire']; ?>&nbsp; </td>
        <td nowrap="nowrap"><a href="show_ref_dossier.php?recordID=<?php echo $row_rsAppurements['ref_dossier']; ?>"><?php echo $row_rsAppurements['ref_dossier']; ?></a>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsAppurements['motif']; ?>&nbsp; </td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsAppurements['montant'],0,' ',' ');; $montant_total = $row_rsAppurements['montant'] + $montant_total; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsAppurements['periode_debut']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsAppurements['periode_fin']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsAppurements['annee']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsAppurements['display']; ?>&nbsp; </td>
      </tr>
      <?php } while ($row_rsAppurements = mysql_fetch_assoc($rsAppurements)); ?>
      <tr>
        <th colspan="5" nowrap="nowrap">TOTAL</th>
        <th align="right" nowrap="nowrap"><?php echo number_format($montant_total,0,' ',' '); ?>&nbsp;</th>
        <th nowrap="nowrap">&nbsp;</th>
        <th nowrap="nowrap">&nbsp;</th>
        <th nowrap="nowrap">&nbsp;</th>
        <th nowrap="nowrap">&nbsp;</th>
      </tr>
      <?php } // Show if recordset not empty ?>
    </table></td>
  </tr>
</table>
<br />
</body>
</html>
<?php
mysql_free_result($rsAppurements);
?>
