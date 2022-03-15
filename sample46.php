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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE type_commission_id between 1 AND 4";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1" align="center" class="print">
  <tr>
    <th nowrap="nowrap">ID</th>
    <th nowrap="nowrap">Type</th>
    <th nowrap="nowrap">Details</th>
    <th nowrap="nowrap">Total</th>
  </tr>
  <?php $SousTotal = 0; $Total = 0; do { ?>
    <tr align="right">
      <td valign="top"><a href="sample47.php?recordID=<?php echo $row_rsTypeCommission['type_commission_id']; ?>"> <?php echo $row_rsTypeCommission['type_commission_id']; ?>&nbsp; </a></td>
      <td align="left" valign="top"><?php echo $row_rsTypeCommission['type_commission_lib']; ?>&nbsp; </td>
      <td valign="top">
		<?php 
        $colname_rsCommissions = "-1";
        if (isset($row_rsTypeCommission['type_commission_id'])) {
          $colname_rsCommissions = $row_rsTypeCommission['type_commission_id'];
        }
        mysql_select_db($database_MyFileConnect, $MyFileConnect);
        $query_rsCommissions = sprintf("SELECT commission_id, commission_parent, type_commission_id, commission_lib FROM commissions WHERE type_commission_id = %s", GetSQLValueString($colname_rsCommissions, "int"));
        $rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
        $row_rsCommissions = mysql_fetch_assoc($rsCommissions);
        $totalRows_rsCommissions = mysql_num_rows($rsCommissions); ?>
      
        <table border="1">
          <tr>
            <th nowrap="nowrap">ID</th>
            <th nowrap="nowrap">Libelle</th>
            <th nowrap="nowrap">Commission</th>
            <th nowrap="nowrap">Sous Commissions</th>
          </tr>
          <?php $Montant_com = 0; $Montant_SCom = 0; $SousTotal1 = 0; $SousTotal2 = 0; do { ?>
            <tr>
              <td valign="middle"><a href="sample48.php?recordID=<?php echo $row_rsCommissions['commission_id']; ?>"> <?php echo $row_rsCommissions['commission_id']; ?>&nbsp; </a></td>
              <td><?php echo $row_rsCommissions['commission_lib']; ?>&nbsp;</td>
              <td align="right"><?php $Montant_com = MinmapDB::getInstance()->get_montant_commission($row_rsCommissions['commission_id']);
			  				 echo number_format($Montant_com,0,' ',' ');
			  				 $SousTotal1 = $SousTotal1 + $Montant_com; ?>&nbsp;</td>
              <td align="right"><?php $Montant_SCom = MinmapDB::getInstance()->get_montant_sous_commission($row_rsCommissions['commission_id']);
			  				 echo number_format($Montant_SCom,0,' ',' ');
			  				 $SousTotal2 = $SousTotal2 + $Montant_SCom; ?></td>
            </tr>
            <?php } while ($row_rsCommissions = mysql_fetch_assoc($rsCommissions)); ?>
      </table></td>
      <td><?php $SousTotal = $SousTotal1 + $SousTotal2; echo number_format($SousTotal,0,' ',' '); 
	  							$Total = $Total + $SousTotal; ?>&nbsp;</td>
    </tr>
    <?php } while ($row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission)); ?>
    <tr>
      <td align="center" valign="top">&nbsp;</td>
      <td align="center" valign="top">&nbsp;</td>
      <td align="right"><strong>Total</strong></td>
      <td align="right"><?php echo number_format($Total,0,' ',' '); ?>&nbsp;</td>
    </tr>
</table>
<br />
<?php echo $totalRows_rsTypeCommission ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsTypeCommission);

mysql_free_result($rsCommissions);
?>
