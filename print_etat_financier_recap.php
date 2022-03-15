<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php');?>
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

$colname4_rsSessionsCommite = "-1";
if (isset($_GET['typID'])) {
  $colname4_rsSessionsCommite = $_GET['typID'];
}
$colname2_rsSessionsCommite = "-1";
if (isset($_GET['mID2'])) {
  $colname2_rsSessionsCommite = $_GET['mID2'];
}
$colname3_rsSessionsCommite = "-1";
if (isset($_GET['aID'])) {
  $colname3_rsSessionsCommite = $_GET['aID'];
}
$colname1_rsSessionsCommite = "-1";
if (isset($_GET['mID1'])) {
  $colname1_rsSessionsCommite = $_GET['mID1'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionsCommite = sprintf("SELECT distinct(membres_commissions_commission_id), commission_lib, type_commission_lib, localite_lib, taux
FROM commissions, sessions, localites, type_commissions WHERE membres_commissions_commission_id = commission_id AND commissions.localite_id = localites.localite_id AND commissions.type_commission_id = type_commissions.type_commission_id AND mois between %s AND %s AND annee = %s AND commissions.type_commission_id = %s", GetSQLValueString($colname1_rsSessionsCommite, "text"),GetSQLValueString($colname2_rsSessionsCommite, "text"),GetSQLValueString($colname3_rsSessionsCommite, "text"),GetSQLValueString($colname4_rsSessionsCommite, "text"));
$rsSessionsCommite = mysql_query($query_rsSessionsCommite, $MyFileConnect) or die(mysql_error());
$row_rsSessionsCommite = mysql_fetch_assoc($rsSessionsCommite);
$totalRows_rsSessionsCommite = mysql_num_rows($rsSessionsCommite);

$colname5_rsSousCommission = "-1";
if (isset($_GET['aID'])) {
  $colname5_rsSousCommission = $_GET['aID'];
}
$colname6_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname6_rsSousCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom, banque_code, agence_code, numero_compte, cle,    membres_commissions_commission_id,  membres_fonctions_fonction_id, fonction_lib,    lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee,   montant,(nombre_jour * montant) as total					FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, rib						 WHERE sessions.mois = mois.mois_id			 AND sessions.membres_personnes_personne_id = personnes.personne_id		 AND sessions.membres_commissions_commission_id = commissions.commission_id	 AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id			 AND sessions.membres_personnes_personne_id = membres.personnes_personne_id						 AND commissions.nature_id = natures.nature_id			 AND commissions.type_commission_id = type_commissions.type_commission_id AND personnes.personne_id = rib.personne_id					AND commissions.localite_id = localites.localite_id   AND annee = %s AND commission_parent = %s GROUP BY membres_personnes_personne_id", GetSQLValueString($colname5_rsSousCommission, "text"),GetSQLValueString($colname6_rsSousCommission, "int"));
$rsSousCommission = mysql_query($query_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);
$totalRows_rsSousCommission = mysql_num_rows($rsSousCommission);



mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois1 = "SELECT * FROM mois";
$rsMois1 = mysql_query($query_rsMois1, $MyFileConnect) or die(mysql_error());
$row_rsMois1 = mysql_fetch_assoc($rsMois1);
$totalRows_rsMois1 = mysql_num_rows($rsMois1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois2 = "SELECT * FROM mois";
$rsMois2 = mysql_query($query_rsMois2, $MyFileConnect) or die(mysql_error());
$row_rsMois2 = mysql_fetch_assoc($rsMois2);
$totalRows_rsMois2 = mysql_num_rows($rsMois2);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1'";
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
<table width="60%" border="0" align="center">
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
    <td colspan="3" align="center"><strong class="welcome">ETAT RECAPITULATIF DES <?php echo strtoupper($row_rsSessionsCommite['type_commission_lib']); ?> DE PASSATION DES MARCHES</strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">PERIODE ALLANT DE <strong><?php echo strtoupper(MinmapDB::getInstance()->get_month_by_month_id($_GET['mID1'])); ?></strong> A <strong><?php echo strtoupper(MinmapDB::getInstance()->get_month_by_month_id($_GET['mID2'])); ?></strong> <?php echo $_GET['aID'] ?></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
<?php if ($totalRows_rsSessionsCommite > 0) { // Show if recordset not empty ?>
  <table border="1" align="center" class="print">
    <tr>
      <th nowrap="nowrap">N°</th>
      <th nowrap="nowrap">Commission</th>
      <th nowrap="nowrap">Localite</th>
      <?php $counter = $_GET['mID1']; do {  
    switch ($counter) {
    case 2:
      $counter = '02';
      break; 
    case 3:
      $counter = '03';
      break; 
    case 4:
      $counter = '04';
      break; 
    case 5:
      $counter = '05';
      break; 
    case 6:
      $counter = '06';
      break; 
    case 7:
      $counter = '07';
      break; 
    case 8:
      $counter = '08';
      break;
    case 9:
      $counter = '09';
      break; 	  
	}
	?>
      <th nowrap="nowrap"><?php echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities($row_rsSessionsCommite['commission_lib']); ?></td>
        <td nowrap="nowrap"><?php echo $row_rsSessionsCommite['localite_lib']; ?></td>
        <?php $counter = $_GET['mID1'];  $total_montant = 0; do {    
		switch ($counter) {
		case 2:
		  $counter = '02';
		  break; 
		case 3:
		  $counter = '03';
		  break; 
		case 4:
		  $counter = '04';
		  break; 
		case 5:
		  $counter = '05';
		  break; 
		case 6:
		  $counter = '06';
		  break; 
		case 7:
		  $counter = '07';
		  break; 
		case 8:
		  $counter = '08';
		  break;
		case 9:
		  $counter = '09';
		  break; 	  
		}
	  
	  ?>
        <td align="right" nowrap="nowrap">
		<?php 
		$colname_rsCountIndemnit2 = "-1";
		if (isset($row_rsSessionsCommite['membres_commissions_commission_id'])) {
		  $colname_rsCountIndemnity = $row_rsSessionsCommite['membres_commissions_commission_id'];
		}
		$colname_rsCountIndemnity1 = "-1";
		if (isset($counter)) {
		  $colname_rsCountIndemnity1 = $counter;
		}
		$colname_rsCountIndemnity2 = "-1";
		if (isset($_GET['aID'])) {
		  $colname_rsCountIndemnity2 = $_GET['aID'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsCountIndemnity = sprintf("SELECT sum(nombre_jour) as number_day FROM sessions WHERE membres_personnes_personne_id = %s
		AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s",  GetSQLValueString(2129, "int"), GetSQLValueString($colname_rsCountIndemnity, "int"),GetSQLValueString($colname_rsCountIndemnity1, "text"),GetSQLValueString($colname_rsCountIndemnity2, "text"));
		$rsCountIndemnity = mysql_query($query_rsCountIndemnity, $MyFileConnect) or die(mysql_error());
		$row_rsCountIndemnity = mysql_fetch_assoc($rsCountIndemnity);
		$totalRows_rsCountIndemnity = mysql_num_rows($rsCountIndemnity);
		
		$montant_session = $row_rsCountIndemnity['number_day'] * $row_rsSessionsCommite['taux'];
		$total_montant = $total_montant + $montant_session;
		echo number_format($montant_session,0,' ',' ');
		
		?>&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessionsCommite['taux'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($total_montant,0,' ',' '); $somme = $somme + $total_montant; ?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsSessionsCommite = mysql_fetch_assoc($rsSessionsCommite)); ?>
<tr>
      <td colspan="3" nowrap="nowrap">&nbsp;</td>
      <?php $counter = $_GET['mID1']; do {  
    switch ($counter) {
    case 2:
      $counter = '02';
      break; 
    case 3:
      $counter = '03';
      break; 
    case 4:
      $counter = '04';
      break; 
    case 5:
      $counter = '05';
      break; 
    case 6:
      $counter = '06';
      break; 
    case 7:
      $counter = '07';
      break; 
    case 8:
      $counter = '08';
      break;
    case 9:
      $counter = '09';
      break; 	  
	}
	?>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td nowrap="nowrap"><strong>Total</strong></td>
      <td align="right" nowrap="nowrap"><strong><?php echo number_format($somme,0,' ',' '); ?></strong></td>
  </tr>
  </table>
  <?php } // Show if recordset not empty ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table>

</p>
</body>
</html>
<?php
mysql_free_result($rsSessionsCommite);

mysql_free_result($rsSessionsCommite);

mysql_free_result($rsSousCommission);

mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($rsAnnee);

mysql_free_result($rsTypeCommission);
?>
