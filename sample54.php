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
$colname_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsSousCommission = $_GET['comID'];
}
$colname1_rsSousCommission = "-1";
if (isset($_GET['mID'])) {
  $colname1_rsSousCommission = $_GET['mID'];
}
$colname2_rsSousCommission = "-1";
if (isset($_GET['aID'])) {
  $colname2_rsSousCommission = $_GET['aID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT distinct(commission_id), montant_cumul, nombre_offre FROM commissions, sessions WHERE sessions.membres_commissions_commission_id = commissions.commission_id  AND commission_parent = %s AND mois = %s AND annee = %s", GetSQLValueString($colname_rsSousCommission, "int"),GetSQLValueString($colname1_rsSousCommission, "int"),GetSQLValueString($colname2_rsSousCommission, "int"));
$rsSousCommission = mysql_query($query_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);
$totalRows_rsSousCommission = mysql_num_rows($rsSousCommission);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="80%" border="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="869" height="46" align="center" valign="bottom">&nbsp;</td>
    <td width="376"><a href="#" onclick="window.print()" title="Imprimer cette page">Imprimer<img src="images/img/b_print.png" alt="print" width="16" height="16" hspace="2" vspace="2" border="0" align="absmiddle" /></a>&nbsp;</td>
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
    <td colspan="3" align="center"><strong class="welcome"><?php echo strtoupper(MinmapDB::getInstance()->get_commission_lib_by_commission_id($_GET['comID']));
	//echo htmlentities(strtoupper($row_rsSessions['commission_lib'])); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">BILAN du mois de <strong>JANVIER
      <?php //echo strtoupper($row_rsJourMois['lib_mois'] . "/" . $_POST['txtYear']); ?>
    </strong> à <strong>DECEMBRE <?php echo  $_POST['txtYear']; ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left"><table width="100%" border="1" align="center" class="print">
      <?php $count=0; $Montant_global=0; do { $count++; ?>
      <tr>
        <th width="18" nowrap="nowrap">N&deg;</th>
        <th width="158" align="center" nowrap="nowrap">Nom et objet du DAO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th nowrap="nowrap">Membre de la sous commission</th>
        <th width="225" align="center" nowrap="nowrap">Montant Projet</th>
        <th nowrap="nowrap">Soumissionnaires</th>
      </tr>
      <tr>
        <td align="center" valign="middle">&nbsp; <a href="detail_sous_commission.php?recordID=<?php echo $row_rsSousCommission['commission_id']; ?>"><?php echo $row_rsSousCommission['commission_id']; ?>
          <?php //echo $count; ?>
        </a><br /></td>
        <td><?php $com_ID = $row_rsSousCommission['commission_id'];
	  		echo htmlentities(MinmapDB::getInstance()->get_dossier_ref_by_value($com_ID, $_GET['mID'], $_GET['aID'])); ?>
          &nbsp;</td>
        <td valign="top"><?php
	  	$colname_rsMembres = "-1";
		if (isset($row_rsSousCommission['commission_id'])) {
		  $colname_rsMembres = $row_rsSousCommission['commission_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsMembres = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s", GetSQLValueString($colname_rsMembres, "int"));
		$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
		$row_rsMembres = mysql_fetch_assoc($rsMembres);
		$totalRows_rsMembres = mysql_num_rows($rsMembres);
	?>
          <table width="100%" align="left" id="*">
            <tr>
              <th nowrap="nowrap">Nom et Prenoms</th>
              <th nowrap="nowrap">Fonction</th>
              <th nowrap="nowrap">Montant</th>
            </tr>
            <?php do { ?>
            <?php           
				$showGoToPersonnes2 = "upd_rib.php?recordID=". $row_rsMembres['personnes_personne_id'];
				?>
            <tr>
              <td nowrap="nowrap"><?php echo strtoupper(MinmapDB::getInstance()->get_personne_name_by_person_id($row_rsMembres['personnes_personne_id'])); ?>
                </td>
              <td nowrap="nowrap"><?php echo strtoupper(MinmapDB::getInstance()->get_fonction_lib_by_fonction_id($row_rsMembres['fonctions_fonction_id'])); ?>&nbsp; </td>
              <td align="right" nowrap="nowrap"><?php echo number_format($row_rsMembres['montant'],0,' ',' '); $Montant_global = $Montant_global + $row_rsMembres['montant'];?>&nbsp; </td>
            </tr>
            <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
          </table></td>
        <td align="center"><strong><?php echo number_format($row_rsSousCommission['montant_cumul'],0,' ',' ');  ?> F CFA</strong></td>
        <td align="center"><strong><?php echo $row_rsSousCommission['nombre_offre']; ?></strong></td>
      </tr>
      <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
      <tr>
        <td width="18" nowrap="nowrap">&nbsp;</td>
        <td width="158" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <th width="225" align="right" nowrap="nowrap">Sous Total :</th>
        <th align="right" nowrap="nowrap"><?php echo number_format($Montant_global,0,' ',' '); ?> F CFA&nbsp;</th>
      </tr>
    </table> &nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsSousCommission);
?>
