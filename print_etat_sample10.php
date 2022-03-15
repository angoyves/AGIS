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

$colname1_rsSessions = "-1";
if (isset($_GET['comID'])) {
  $colname1_rsSessions = $_GET['comID'];
}
$colname2_rsSessions = "-1";
if (isset($_GET['mID1'])) {
  $colname2_rsSessions = $_GET['mID1'];
}
$colname3_rsSessions = "-1";
if (isset($_GET['mID2'])) {
  $colname3_rsSessions = $_GET['mID2'];
}
$colname4_rsSessions = "-1";
if (isset($_GET['aID'])) {
  $colname4_rsSessions = $_GET['aID'];
}
$colname5_rsSessions = "-1";
if (isset($_GET['typID'])) {
  $colname5_rsSessions = $_GET['typID'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personne_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jours, jour, mois, annee, montant, (sum(nombre_jour) * montant) as total FROM commissions, membres, personnes,  fonctions, sessions WHERE membres.commissions_commission_id = commissions.commission_id AND membres.personnes_personne_id = personnes.personne_id AND membres.fonctions_fonction_id = fonctions.fonction_id AND sessions.membres_commissions_commission_id = membres.commissions_commission_id AND sessions.membres_personnes_personne_id = membres.personnes_personne_id 																																										AND membres.fonctions_fonction_id <> 4 
AND membres.fonctions_fonction_id <> 5 
AND membres.fonctions_fonction_id <> 40 
AND membres_commissions_commission_id = %s 
AND mois BETWEEN %s AND %s 
AND annee = %s 
GROUP BY personne_id
ORDER BY annee, mois, fonctions.fonction_id", GetSQLValueString($colname1_rsSessions, "int"),GetSQLValueString($colname2_rsSessions, "text"),GetSQLValueString($colname3_rsSessions, "text"),GetSQLValueString($colname4_rsSessions, "text"));
$query_limit_rsSessions = sprintf("%s LIMIT %d, %d", $query_rsSessions, $startRow_rsSessions, $maxRows_rsSessions);
$rsSessions = mysql_query($query_limit_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);

if (isset($_GET['totalRows_rsSessions'])) {
  $totalRows_rsSessions = $_GET['totalRows_rsSessions'];
} else {
  $all_rsSessions = mysql_query($query_rsSessions);
  $totalRows_rsSessions = mysql_num_rows($all_rsSessions);
}
$totalPages_rsSessions = ceil($totalRows_rsSessions/$maxRows_rsSessions)-1;


mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT personnes.personne_id, personne_nom, personne_prenom, banques.banque_code, agences.agence_code, numero_compte, cle, commission_id,  commission_lib, fonctions.fonction_id, fonction_lib, lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee,   montant,(nombre_jour * montant) as total
FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, banques, agences, rib
WHERE sessions.mois = mois.mois_id
AND sessions.membres_personnes_personne_id = personnes.personne_id
AND sessions.membres_commissions_commission_id = commissions.commission_id
AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.nature_id = natures.nature_id
AND commissions.type_commission_id = type_commissions.type_commission_id
AND personnes.personne_id = rib.personne_id
AND commissions.localite_id = localites.localite_id  																																																																						AND mois BETWEEN %s AND %s																																																																															AND annee = %s																																																																												AND commission_parent = %s	
AND fonctions.fonction_id <> 41	
GROUP BY personnes.personne_id
ORDER BY annee, mois, fonctions.fonction_id ", GetSQLValueString($colname2_rsSessions, "text"),GetSQLValueString($colname3_rsSessions, "text"),GetSQLValueString($colname4_rsSessions, "text"), GetSQLValueString($colname1_rsSessions, "int"));
$rsSousCommission = mysql_query($query_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);
$totalRows_rsSousCommission = mysql_num_rows($rsSousCommission);

$colname5_rsSousCommission = "-1";
if (isset($_GET['aID'])) {
  $colname5_rsSousCommission = $_GET['aID'];
}
$colname6_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname6_rsSousCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRepresentant = sprintf("SELECT personnes.personne_id, personne_nom, personne_prenom, banques.banque_code, agences.agence_code, numero_compte, cle, commission_id,  commission_lib, fonctions.fonction_id, fonction_lib, lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee,   montant,(nombre_jour * montant) as total
FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, banques, agences, rib
WHERE sessions.mois = mois.mois_id
AND sessions.membres_personnes_personne_id = personnes.personne_id
AND sessions.membres_commissions_commission_id = commissions.commission_id
AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.nature_id = natures.nature_id
AND commissions.type_commission_id = type_commissions.type_commission_id
AND personnes.personne_id = rib.personne_id
AND commissions.localite_id = localites.localite_id  																																																																						AND mois BETWEEN %s AND %s																																																																															AND annee = %s																																																																												AND commissions.commission_id = %s	
AND fonctions.fonction_id = 40	
GROUP BY personnes.personne_id
ORDER BY annee, mois, fonctions.fonction_id ", GetSQLValueString($colname2_rsSessions, "text"),GetSQLValueString($colname3_rsSessions, "text"),GetSQLValueString($colname4_rsSessions, "text"), GetSQLValueString($colname1_rsSessions, "int"));
$rsRepresentant = mysql_query($query_rsRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsRepresentant = mysql_fetch_assoc($rsRepresentant);
$totalRows_rsRepresentant = mysql_num_rows($rsRepresentant);



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
    <td colspan="3" align="center"><strong class="welcome"><?php echo htmlentities(strtoupper($row_rsSessions['type_commission_lib']) . " DE " . strtoupper($row_rsSessions['lib_nature']) . " DU " .strtoupper($row_rsSessions['localite_lib'])); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
<?php if ($totalRows_rsSessions > 0) { // Show if recordset not empty ?>
  <table border="1" align="center" class="print">
    <tr>
      <th>ID</th>
      <th>Nom et Prenom</th>
      <th>Fonction</th>
      <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <th>Jours</th>
      <th>Montant</th>
      <th>Total</th>
    </tr>
    <?php $count = 0;  do { $count++; ?>
    <tr>
      <td nowrap="nowrap"><?php echo $row_rsSessions['personne_id']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo strtoupper($row_rsSessions['personne_nom']); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['fonction_lib']; ?>&nbsp; </td>
      <?php $counter = $_GET['mID1'];  $total_montant = 0; do {   GetValueMonth($counter);  ?>
      <td align="right" nowrap="nowrap"><?php 
		$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($_GET['comID'], $row_rsSessions['personne_id'], $counter, $_GET['aID']);
		echo $somme2 = GetSomme($count, $somme2, $nombre_jours);
		//echo $somme;
		//echo $count;
				/*if (isset($nombre_jours))
				{ echo $nombre_jours; }
				else
				{echo 0;}*/
				//echo "commission_id : " . $row_rsSessions['commission_id']. "personne_id : " . $row_rsSessions['personne_id'] . " mois : " . $count . " annee : " . $_GET['aID'] . "</BR>";
					
			?>
        &nbsp;</td>
      <?php $counter++; } while ($counter <= $_GET['mID2']) ?>
      <td align="right" nowrap="nowrap"><?php echo $row_rsSessions['nombre_jours']; //$nbr_jours = MinmapDB::getInstance()->get_somme_nombre_jour_by_values(1, $row_rsSessions['personne_id'], 1, 12, 2014); ?>&nbsp; </td>
      <td align="right" nowrap="nowrap">&nbsp; <?php echo number_format($row_rsSessions['montant'],0,' ',' ')?>&nbsp; </td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['total'],0,' ',' '); $somme = $somme + $row_rsSessions['total']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
    <tr>
      <th colspan="3"></th>
      <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php //echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>
        &nbsp;</th>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <th>&nbsp;</th>
      <th>S<strong>ous total sessions</strong></th>
      <th><?php echo number_format($somme,0,' ',' '); ?></th>
    </tr>
  </table>
    <?php } // Show if recordset not empty ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
<?php if ($totalRows_rsSousCommission > 0) { // Show if recordset not empty ?>
<table border="1" align="center" class="print">
    <tr>
      <th nowrap="nowrap">N°</th>
      <th nowrap="nowrap">Nom et Prenom</th>
      <th nowrap="nowrap">Fonction</th>
      <th nowrap="nowrap">Numero compte</th>
      <?php $counter = $_GET['mID1']; do { GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities(strtoupper($row_rsSousCommission['personne_nom'] . " " . $row_rsSousCommission['personne_prenom'])); ?></td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsSousCommission['fonction_lib']); ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsSousCommission['numero_compte']; ?>&nbsp;</td>
        <?php $counter = $_GET['mID1'];  $total_montant = 0; do {   ?>
        <td align="right" nowrap="nowrap">
		<?php 
		$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSousCommission['commission_id'], $row_rsSousCommission['personne_id'], $counter, $_GET['aID']);
				if (isset($nombre_jours))
				{ echo $nombre_jours; }
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsSousCommission['commission_id']. "personne_id : " . $row_rsSousCommission['personne_id'] . " mois : " . $counter . " annee : " . $_GET['aID'] . "</BR>";
					
			?>&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSousCommission['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSousCommission['total'],0,' ',' '); $somme = $somme + $row_rsSousCommission['total']; ?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
<tr>
      <td colspan="4">&nbsp;</td>
      <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter);	?>
      <td>&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td>S<strong>ous total sessions</strong></td>
      <td align="right"><?php echo number_format($somme,0,' ',' '); ?></td>
  </tr>
  </table>
<br />
<?php echo $totalRows_rsSousCommission ?> Enregistrements Total
<?php } // Show if recordset not empty ?>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
<?php if ($totalRows_rsRepresentant > 0) { // Show if recordset not empty ?>
<table border="1" align="center" class="print">
    <tr>
      <th nowrap="nowrap">N°</th>
      <th nowrap="nowrap">Nom et Prenom</th>
      <th nowrap="nowrap">Fonction</th>
      <th nowrap="nowrap">Numero compte</th>
      <?php $counter = $_GET['mID1']; do { GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities(strtoupper($row_rsRepresentant['personne_nom'] . " " . $row_rsRepresentant['personne_prenom'])); ?></td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsRepresentant['fonction_lib']); ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsRepresentant['numero_compte']; ?>&nbsp;</td>
        <?php $counter = $_GET['mID1'];  $total_montant = 0; do {   ?>
        <td align="right" nowrap="nowrap">
		<?php 
		$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsRepresentant['commission_id'], $row_rsRepresentant['personne_id'], $counter, $_GET['aID']);
				if (isset($nombre_jours))
				{ echo $nombre_jours; }
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsRepresentant['commission_id']. "personne_id : " . $row_rsRepresentant['personne_id'] . " mois : " . $counter . " annee : " . $_GET['aID'] . "</BR>";
					
			?>&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsRepresentant['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsRepresentant['total'],0,' ',' '); $somme = $somme + $row_rsRepresentant['total']; ?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsRepresentant = mysql_fetch_assoc($rsRepresentant)); ?>
<tr>
      <td colspan="4" nowrap="nowrap">&nbsp;</td>
      <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter); ?>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td nowrap="nowrap">S<strong>ous total sessions</strong></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($somme,0,' ',' '); ?></td>
  </tr>
  </table>

<br />
<?php echo $totalRows_rsRepresentant ?> Enregistrements Total
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
mysql_free_result($rsSessions);

mysql_free_result($rsSessions);

mysql_free_result($rsSousCommission);

mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($rsAnnee);

mysql_free_result($rsTypeCommission);
?>
