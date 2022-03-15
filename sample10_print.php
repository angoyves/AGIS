<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php');?>
<?php

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsSessions = 10;
$pageNum_rsSessions = 0;
if (isset($_GET['pageNum_rsSessions'])) {
  $pageNum_rsSessions = $_GET['pageNum_rsSessions'];
}
$startRow_rsSessions = $pageNum_rsSessions * $maxRows_rsSessions;

$colname1_rsSessionsCommite = "-1";
if (isset($_GET['comID'])) {
  $colname1_rsSessionsCommite = $_GET['comID'];
}
$colname2_rsSessionsCommite = "01";
if (isset($_GET['mID1'])) {
  $colname2_rsSessionsCommite = $_GET['mID1'];
}
$colname3_rsSessionsCommite = "12";
if (isset($_GET['mID2'])) {
  $colname3_rsSessionsCommite = $_GET['mID2'];
}
$colname4_rsSessionsCommite = "-1";
if (isset($_GET['aID'])) {
  $colname4_rsSessionsCommite = $_GET['aID'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personne_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jours, jour, mois, annee, montant, (sum(nombre_jour) * montant) as total FROM commissions, membres, personnes,  fonctions, sessions WHERE membres.commissions_commission_id = commissions.commission_id AND membres.personnes_personne_id = personnes.personne_id AND membres.fonctions_fonction_id = fonctions.fonction_id AND sessions.membres_commissions_commission_id = membres.commissions_commission_id AND sessions.membres_personnes_personne_id = membres.personnes_personne_id AND membres.fonctions_fonction_id <> 4 AND membres.fonctions_fonction_id <> 5 AND membres.fonctions_fonction_id <> 40 
AND membres_commissions_commission_id = %s 
AND mois BETWEEN %s AND %s 
AND annee = %s 
GROUP BY personne_id
ORDER BY annee, mois, fonctions.fonction_id", GetSQLValueString($colname1_rsSessionsCommite, "int"),GetSQLValueString($colname2_rsSessionsCommite, "text"),GetSQLValueString($colname3_rsSessionsCommite, "text"),GetSQLValueString($colname4_rsSessionsCommite, "text"));
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
ORDER BY annee, mois, fonctions.fonction_id ", GetSQLValueString($colname2_rsSessionsCommite, "text"),GetSQLValueString($colname3_rsSessionsCommite, "text"),GetSQLValueString($colname4_rsSessionsCommite, "text"), GetSQLValueString($colname1_rsSessionsCommite, "int"));
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
$query_rsRepresentant = sprintf("SELECT personnes.personne_id, personne_nom, personne_prenom, banques.banque_code, agences.agence_code, numero_compte, cle, commission_id,  commission_lib, fonctions.fonction_id, fonction_lib, code_structure, lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee,   montant,(nombre_jour * montant) as total
FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, banques, agences, rib, structures
WHERE sessions.mois = mois.mois_id
AND sessions.membres_personnes_personne_id = personnes.personne_id
AND personnes.structure_id = structures.structure_id
AND sessions.membres_commissions_commission_id = commissions.commission_id
AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.nature_id = natures.nature_id
AND commissions.type_commission_id = type_commissions.type_commission_id
AND personnes.personne_id = rib.personne_id
AND commissions.localite_id = localites.localite_id  																																																																						AND mois BETWEEN %s AND %s																																																																															AND annee = %s																																																																												AND commissions.commission_id = %s	
AND fonctions.fonction_id = 40	
GROUP BY personnes.personne_id
ORDER BY annee, mois, fonctions.fonction_id ", GetSQLValueString($colname2_rsSessionsCommite, "text"),GetSQLValueString($colname3_rsSessionsCommite, "text"),GetSQLValueString($colname4_rsSessionsCommite, "text"), GetSQLValueString($colname1_rsSessionsCommite, "int"));
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

$queryString_rsSessions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSessions") == false && 
        stristr($param, "totalRows_rsSessions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSessions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSessions = sprintf("&totalRows_rsSessions=%d%s", $totalRows_rsSessions, $queryString_rsSessions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0" align="center">
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
    <td colspan="3" align="center"><strong class="welcome"><?php echo htmlentities(strtoupper($row_rsSessions['commission_lib'])); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left">
<h1>Etat commission</h1>
<table border="1" align="center" class="print">
  <tr>
    <th>N°</th>
    <th>Nom et Prenom</th>
    <th>Fonction</th>
    <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter); ?>
    <th nowrap="nowrap"><?php echo GetValueMonth($counter); //ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id(GetValueMonth($counter)));?>&nbsp;</th>
    <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
    <th>Nbre de Jours</th>
    <th>Taux</th>
    <th>Montant</th>
  </tr>
  <?php $compter = 0; $somme2 = 0; do { $compter++; ?>
    <tr>
      <td nowrap="nowrap"><?php echo $compter; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo strtoupper(htmlentities($row_rsSessions['personne_nom'])); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
      <?php $counter = $_GET['mID1'];  $total_montant = 0; do {   GetValueMonth($counter);  ?>
      <td align="right" nowrap="nowrap">
      <?php 
		$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($_GET['comID'], $row_rsSessions['personne_id'], $counter, $_GET['aID']);
		echo $somme2 = GetSomme($count, $somme2, $nombre_jours);
		//echo $somme;
		//echo $count;
				/*if (isset($nombre_jours))
				{ echo $nombre_jours; }
				else
				{echo 0;}*/
				//echo "commission_id : " . $row_rsSessionsCommite['commission_id']. "personne_id : " . $row_rsSessionsCommite['personne_id'] . " mois : " . $count . " annee : " . $_GET['aID'] . "</BR>";
					
			?>
        
        &nbsp;</td>
      <?php $counter++; } while ($counter <= $_GET['mID2']) ?>
      <td align="right" nowrap="nowrap"><?php echo $row_rsSessions['nombre_jours']; //$nbr_jours = MinmapDB::getInstance()->get_somme_nombre_jour_by_values(1, $row_rsSessions['personne_id'], 1, 12, 2014); ?>&nbsp;   </td>
      <td align="right" nowrap="nowrap">&nbsp; <?php echo number_format($row_rsSessions['montant'],0,' ',' ')?>&nbsp; </td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['total'],0,' ',' '); $somme1 = $somme1 + $row_rsSessions['total']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
<tr>
    <td colspan="3" nowrap="nowrap"></td>
    <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter); ?>
    <td nowrap="nowrap"><?php //echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</td>
    <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap"><strong>Sous total sessions</strong></td>
    <td nowrap="nowrap"><?php echo number_format($somme1,0,' ',' '); ?></td>
  </tr>
</table>
<br /></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
<?php if ($totalRows_rsSousCommission > 0) { // Show if recordset not empty ?>
<h1>Etat sous commission</h1>
<table border="1" align="center" class="print">
    <tr>
      <th nowrap="nowrap">N°</th>
      <th nowrap="nowrap">Nom et Prenom</th>
      <th nowrap="nowrap">Fonction</th>
      <?php $counter = $_GET['mID1']; do { GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php echo GetValueMonth($counter); // echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter)); ?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities(strtoupper($row_rsSousCommission['personne_nom'] . " " . $row_rsSousCommission['personne_prenom'])); ?></td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsSousCommission['fonction_lib']); ?>&nbsp;</td>
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
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSousCommission['total'],0,' ',' '); 
	  $somme = $somme + $row_rsSousCommission['total']; 
	  $somme2 = $somme2 + $row_rsSousCommission['total'];?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
<tr>
      <td colspan="3" nowrap="nowrap">&nbsp;</td>
      <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter);	?>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td nowrap="nowrap">S<strong>ous total sessions</strong></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($somme2,0,' ',' '); ?></td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
<?php if ($totalRows_rsRepresentant > 0) { // Show if recordset not empty ?>
<h1>Representant maitre d'ouvrage</h1>
<table border="1" align="center" class="print">
    <tr>
      <th nowrap="nowrap">N°</th>
      <th nowrap="nowrap">Nom et Prenom</th>
      <th nowrap="nowrap">Fonction</th>
      <?php $counter = $_GET['mID1']; do { GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php echo GetValueMonth($counter); //ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id(GetValueMonth($counter)));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <th nowrap="nowrap">Nbre de Jours</th>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities(strtoupper($row_rsRepresentant['personne_nom'] . " " . $row_rsRepresentant['personne_prenom'])); ?></td>
        <td nowrap="nowrap"><?php echo ucfirst($row_rsRepresentant['code_structure']); ?>&nbsp;&nbsp;</td>
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
      <td align="right" nowrap="nowrap"><?php //echo number_format($row_rsRepresentant['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsRepresentant['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsRepresentant['total'],0,' ',' '); 
	  $somme = $somme + $row_rsRepresentant['total'];
	  $somme3 = $somme3 + $row_rsRepresentant['total'];?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsRepresentant = mysql_fetch_assoc($rsRepresentant)); ?>
<tr>
      <td colspan="3" nowrap="nowrap">&nbsp;</td>
      <?php $counter = $_GET['mID1']; do {  GetValueMonth($counter); ?>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $counter++;} while ($counter <= $_GET['mID2']) ?>
      <td nowrap="nowrap"></td>
      <td nowrap="nowrap"><strong>Sous total sessions</strong></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($somme3,0,' ',' '); ?></td>
  </tr>
</table>
<?php } // Show if recordset not empty ?>
    &nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">
    <h1>Total</h1>
    <table border="0" align="center" class="print">
      <tr>
        <td>Total des representants des maitres d'ouvrages</td>
        <td align="right"><strong><?php echo number_format($somme3,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
<tr>
        <td>Total de la Sous Commission</td>
        <td align="right"><strong><?php echo number_format(($somme2),0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
      <tr>
        <td>Total de la Commission</td>
        <td align="right"><strong><?php echo number_format(($somme1),0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
      <tr>
        <td><!--Billetage (1%)--></td>
        <td align="right"><strong>
          <?php  //echo number_format(($somme*1/100),0,' ',' '); ?>
          &nbsp;</strong></td>
      </tr>
      <tr>
        <th>Total G&eacute;n&eacute;ral</th>
        <th align="right"><strong>
          <?php  echo number_format($somme1 + $somme2 + $somme3 ,0,' ',' '); ?>
          &nbsp;</strong></th>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsSessions);

mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($rsAnnee);
?>
