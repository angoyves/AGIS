<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('db.php');
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
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT * FROM commissions WHERE commission_parent = %s", GetSQLValueString($colname_rsSousCommission, "int"));
$rsSousCommission = mysql_query($query_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);
$totalRows_rsSousCommission = mysql_num_rows($rsSousCommission);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="1" align="center">

<?php $count=0; do { $count++; ?>
  <tr>
    <th width="18" nowrap="nowrap">N°</th>
    <th width="158" nowrap="nowrap">Nom et objet du DAO</th>
    <th nowrap="nowrap">Membre de la sous commission</th>
    <th width="225" nowrap="nowrap">Montant Projet</th>
    <th nowrap="nowrap">Nombre Soumissionnaires</th>
  </tr>
<tr>
    <th colspan="5" nowrap="nowrap"><table align="left">
      <tr>
        <td><a href="#" onclick="<?php popup($link15, "1200", "700") ?>"><img src="img/b_print.png" width="16" height="16" />Version imprimable</a>&nbsp;&nbsp; <a href="<?php echo $link16 . "&valid=mb&comID=" . $_GET['comID'] . "&sCom=".$row_rsSessions_sCom['commission_id'] ?>"><img src="img/b_edit.png" width="16" height="16" /> Modifier</a>&nbsp;&nbsp;<a href="<?php echo $link10 . "&valid=scom" ?>"><img src="img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une session</a>&nbsp;&nbsp;<a href="#"><img src="img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/>Supprimer la session</a></td>
      </tr>
    </table></th>
  </tr>
<tr>
    <td align="center" valign="middle">&nbsp; <a href="detail_sous_commission.php?recordID=<?php echo $row_rsSousCommission['commission_id']; ?>"><?php echo $count; ?>&nbsp; </a><br /></td>
    <td>&nbsp;</td>
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
      <table width="100%" border="1" align="left" id="*">
        <tr>
          <th nowrap="nowrap">Nom et Prenoms</th>
          <th nowrap="nowrap">Fonction</th>
          <th nowrap="nowrap">montant</th>
        </tr>
        <?php do { ?>
        <tr>
          <td><a href="detail_membres_scom.php?recordID=<?php echo $row_rsMembres['commissions_commission_id']; ?>"><?php echo MinmapDB::getInstance()->get_personne_name_by_person_id($row_rsMembres['personnes_personne_id']); ?></a></td>
          <td><?php echo MinmapDB::getInstance()->get_fonction_lib_by_fonction_id($row_rsMembres['fonctions_fonction_id']); ?>&nbsp; </td>
          <td><?php echo $row_rsMembres['montant']; ?>&nbsp; </td>
          </tr>
        <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
    </table></td>
    <td align="center"><?php echo $row_rsSousCommission['montant_cumul']; ?></td>
    <td align="center"><?php echo $row_rsSousCommission['nombre_offre']; ?></td>
  </tr>
    <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
</table>
</body>
</html>
<?php
mysql_free_result($rsSousCommission);

mysql_free_result($rsMembres);
?>
