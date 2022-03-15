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
if (!isset($_SESSION)) {
  session_start();
}
$dateCreation = date('d-m-Y H:i:s');
MinmapDB::getInstance()->create_etats($_POST['comID'], $_POST['txtMonth1'], $_POST['txtMonth2'], $_POST['txtYear'], $dateCreation, 1, $_SESSION['MM_UserID']);
$etat_id = MinmapDB::getInstance()->get_etat_id_by_date($dateCreation);
?>
<?php

$MM_authorizedUsers = "1,5,6";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False;

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "acces_denied.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
   
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'phpqrcode/temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'phpqrcode/temp/';

    include "phpqrcode/qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'qrcode.png';
    $errorCorrectionLevel = 'M'; 
    $matrixPointSize = 3;
	$codeEtatEdition = 'AGIS-RECAP'.$etat_id.'-'.$_SESSION['MM_UserID'].$_POST['comID'].$_POST['txtMonth1'].$_POST['txtMonth2'].$_POST['txtYear'].date('Y-m-d H:i:s');

    $filename = $PNG_TEMP_DIR.'qrcode'.md5($codeEtatEdition.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
    QRcode::png($codeEtatEdition, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 

?>
<?php
if (trim($_POST['txtYear']) == '')
            die('Année cannot be empty! <a href="sample52.php?comID='. $_POST['comID'] .'">back</a>');

$colname_rsSessions = "-1";
if (isset($_POST['comID'])) {
  $colname_rsSessions = $_POST['comID'];
}
$colname_rsSessions1 = "-1";
if (isset($_POST['txtMonth1'])) {
  $colname_rsSessions1 = $_POST['txtMonth1'];
}
$colname_rsSessions3 = "-1";
if (isset($_POST['txtMonth2'])) {
  $colname_rsSessions3 = $_POST['txtMonth2'];
}
$colname_rsSessions2 = "-1";
if (isset($_POST['txtYear'])) {
  $colname_rsSessions2 = $_POST['txtYear'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte, cle
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND personnes.personne_id = rib.personne_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id BETWEEN 1 AND 3
AND membres_commissions_commission_id = %s 
AND annee = %s 
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_id
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_sCom = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, nombre_jour, annee, montant, sum(nombre_jour * montant) as total
FROM sessions, membres, commissions, personnes
WHERE sessions.membres_commissions_commission_id = membres.commissions_commission_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.commission_id = membres.commissions_commission_id
AND personnes.personne_id = membres.personnes_personne_id
AND personnes.display = '1'
AND commission_parent = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_id
ORDER BY personne_nom, annee, mois", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessions_sCom = mysql_query($query_rsSessions_sCom, $MyFileConnect) or die(mysql_error());
$row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom);
$totalRows_rsSessions_sCom = mysql_num_rows($rsSessions_sCom);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_rep = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND personnes.personne_id = rib.personne_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id = 40
AND membres_commissions_commission_id = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_nom 
ORDER BY personnes.personne_nom",  GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessions_rep = mysql_query($query_rsSessions_rep, $MyFileConnect) or die(mysql_error());
$row_rsSessions_rep = mysql_fetch_assoc(rsSessions_rep);
$totalRows_rsSessions_rep = mysql_num_rows($rsSessions_rep);



$colname_rsCountIndemnit2 = "-1";
if (isset($_POST['comID'])) {
  $colname_rsCountIndemnity = $_POST['comID'];
}
$colname_rsCountIndemnity1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsCountIndemnity1 = $_GET['mID'];
}
$colname_rsCountIndemnity2 = "-1";
if (isset($_POST['txtYear'])) {
  $colname_rsCountIndemnity2 = $_POST['txtYear'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountIndemnity = sprintf("SELECT (sum(nombre_jour) * montant) as total
FROM sessions, membres
WHERE `membres`.personnes_personne_id = `sessions`.`membres_personnes_personne_id`
AND membres_commissions_commission_id = %s 
AND mois between 1 AND 12 
AND annee = %s
AND sessions.membres_personnes_personne_id = %s ", GetSQLValueString($colname_rsCountIndemnity, "int"),GetSQLValueString($colname_rsCountIndemnity2, "text") , GetSQLValueString(2129, "int"));
$rsCountIndemnity = mysql_query($query_rsCountIndemnity, $MyFileConnect) or die(mysql_error());
$row_rsCountIndemnity = mysql_fetch_assoc($rsCountIndemnity);
$totalRows_rsCountIndemnity = mysql_num_rows($rsCountIndemnity);

$colname_rsJourMois = "-1";
if (isset($_GET['mID'])) {
  $colname_rsJourMois = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

$colname_rsCommissions = "-1";
if (isset($_POST['comID'])) {
  $colname_rsCommissions = $_POST['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname_rsMembres = "-1";
if (isset($_POST['comID'])) {
  $colname_rsMembres = $_POST['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id  AND fonctions_fonction_id = fonctions.fonction_id  AND personnes_personne_id = personne_id  AND commissions_commission_id = %s", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsSessionRepresentant = "-1";
if (isset($_POST['comID'])) {
  $colname_rsSessionRepresentant = $_POST['comID'];
}
$colname1_rsSessionRepresentant = "-1";
if (isset($_POST['txtYear'])) {
  $colname1_rsSessionRepresentant = $_POST['txtYear'];
}
$colname2_rsSessionRepresentant = "-1";
if (isset($_GET['mID'])) {
  $colname2_rsSessionRepresentant = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionRepresentant = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, personne_prenom, code_structure, sum(nombre_jour) as nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  sessions, structures 
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND  personnes.structure_id = structures.structure_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id = 40
AND membres_commissions_commission_id = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_nom 
ORDER BY personnes.personne_nom", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessionRepresentant = mysql_query($query_rsSessionRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant);
$totalRows_rsSessionRepresentant = mysql_num_rows($rsSessionRepresentant);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
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
    <td colspan="3" align="center">BILAN du mois de <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($_POST['txtMonth1'])); ?></strong> à <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($_POST['txtMonth2'])); ?> <?php echo  $_POST['txtYear']; ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left">
    <h1>Commissions</h1>
    <table border="1" align="center" class="print">
	<tr>
        <th nowrap="nowrap">N°</th>
        <th nowrap="nowrap">NomPrenom</th>
        <th nowrap="nowrap">Fonction</th>
        <th nowrap="nowrap">RIB</th>
		<?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
        <th nowrap="nowrap"><?php echo GetValueMonth($counter);//ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
        <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
        <th nowrap="nowrap">Total</th>
        <th nowrap="nowrap">Taux</th>
        <th nowrap="nowrap">Montant</th>
        <th nowrap="nowrap">Retenu (<?php $_SESSION['MM_Retenu'] = (MinmapDB::getInstance()->get_retenu($_POST['txtYear'])); 
		echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
        <th nowrap="nowrap">Net à percevoir</th>
      </tr>
      <tr>
        <td colspan="4" align="center" nowrap="nowrap">Nombre de dossiers traités</td>
        <?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?>&nbsp;</td>
        <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?></td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
      </tr>
      <?php $counter=0; $Montant_global1 = 0; $user_rib = null; do  { $counter++; ?>
      <?php           
		$showGoToPersonnes1 = "upd_rib.php?recordID=". $row_rsSessions['personne_id'];
	  ?>
      <tr>
        <td nowrap="nowrap"><?php echo $counter ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">
		<?php echo htmlentities(strtoupper($row_rsSessions['personne_nom'].' '.$row_rsSessions['personne_prenom'])); ?>&nbsp; </a></td>
        <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions['personne_id']);
		if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ } ?></td>
        <?php $count = $_POST['txtMonth1']; $sommer=0; $montant2=0; do  { GetValueMonth($count); ?>
        <td align="right" nowrap="nowrap"><?php 
					//$countDoc = 0;
					//foreach(explode("**", $row_rsSessions['jour']) as $val){
						//$countDoc++; 
						//$val == $count ? (print "1") : $countDoc--; 
						
						
					//}
				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessions['commission_id'], $row_rsSessions['personne_id'], $count, $_POST['txtYear']);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsSessions['commission_id']. "personne_id : " . $row_rsSessions['personne_id'] . " mois : " . $count . " annee : " . $_POST['txtYear'] . "</BR>";
					
			?></td>
        <?php $count++; } while ($count <= $_POST['txtMonth2']) ?>
        <td align="right" nowrap="nowrap"><?php echo $sommer //$row_rsSessions['nombre_jour']; ?></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap">
		<?php 
		$somme = $row_rsSessions['montant']*$sommer; 
		$Montant_global1 = $Montant_global1 + $somme; 
		$montant2=$montant; 
		$montant = $montant + $somme;  //echo number_format(($somme),0,' ',' '); ?><?php echo number_format($somme,0,' ',' '); ?><strong>
          
          </strong></td>
        <td align="right" nowrap="nowrap"><?php echo number_format(($retenu=$somme*$_SESSION['MM_Retenu']),0,' ',' '); ?><?php $retenu_global_1 = $retenu_global_1 + $retenu; ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><strong><?php echo number_format(($net_percu_1 = $somme-$retenu),0,' ',' '); ?></strong><?php $net_global_1 = $net_global_1 + $net_percu_1; ?><?php $somme1 = $somme; $somme = $somme + $row_rsSessions['total']; ?></td>
      </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
<tr>
        <td colspan="4" nowrap="nowrap"> <a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">&nbsp; </a> </td>
        <?php $counter = $_POST['txtMonth1']; $sommer=0; $montant2=0; do  { GetValueMonth($counter); ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <?php $counter++; } while ($counter <= $_POST['txtMonth2']) ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
        <th align="right" nowrap="nowrap"><strong>
          <?php $total = -($montant-($row_rsCountIndemnity['total'])); echo number_format(($Montant_global1),0,' ',' ')//$somme = $somme - $row_rsCountIndemnity['total']; echo number_format($somme,0,' ',' '); ?>
        </strong></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($retenu_global_1,0,' ',' '); ?></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($net_global_1,0,' ',' '); ?></th>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><?php if ($totalRows_rsSessions_sCom > 0) { // Show if recordset not empty ?>
    <h1>Sous Commissions Dépendante</h1>
  <table width="50%" border="1" align="center" class="print">
    <tr>
      <th>ID</th>
      <th>NomPrenom</th>
      <th>Fonction</th>
      <th>RIB</th>
      <?php $counter = $_POST['txtMonth1']; do {   ?>
      <th><?php echo GetValueMonth($counter) ?></th>
      <?php //}while ($counter < 12);?>
      <?php $counter++; } while ($counter <= $_POST['txtMonth2']) ?>
      <th nowrap="nowrap">Total</th>
      <th>Taux</th>
      <th>Montant</th>
      <th nowrap="nowrap">Retenu(<?php echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
      <th nowrap="nowrap">Net à percevoir</th>      
      </tr>
    <?php $compter=0; $Montant_global2 = 0; $user_rib = null; do  { $compter++; ?>
      <?php           
		$showGoToPersonnes2 = "upd_rib.php?recordID=". $row_rsSessions_sCom['personne_id'];
	  ?>
    <tr>
      <td nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions_sCom['membres_personnes_personne_id']; ?>&amp;map=personnes"> <?php echo $compter; ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap">
      <a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>">
	  <?php echo strtoupper($row_rsSessions_sCom['personne_nom']). " " . $row_rsSessions_sCom['personne_prenom']; ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap">Expert&nbsp; </td>
      <td align="left" nowrap="nowrap"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions_sCom['personne_id']);
	  if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ } ?></td>
      <?php //$count=0; $sommer=0; do  { $count++; ?>
      <?php $count = $_POST['txtMonth1']; $sommer=0;  do  {  ?>
      <td align="right" nowrap="nowrap"><?php /*echo $_POST['comID'] ."-" . $row_rsSessions_sCom['personne_id'] ."-" . $row_rsSessions_sCom['fonction_id'] ."-" . $count ."-" .$_POST['txtYear'];/*
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions_sCom['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}*/
			?>
			<?php 

				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_sCom_by_values($_POST['comID'], $row_rsSessions_sCom['personne_id'], $count, $_POST['txtYear']);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}	
			?>
            
            </td>
      <?php //}while ($count < 12); ?>
      <?php $count++; } while ($count <= $_POST['txtMonth2']) ?>
      <td align="right" nowrap="nowrap"><?php echo $sommer;//$row_rsSessions_sCom['nombre_jour']; ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions_sCom['montant'],0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; ?></td>
      <td align="right" nowrap="nowrap"><?php $somme2 = $row_rsSessions_sCom['montant']*$sommer; echo number_format($row_rsSessions_sCom['total'],0,' ',' '); ?>
        <?php  $Montant_global2 = $Montant_global2 + $row_rsSessions_sCom['total'];?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format(($retenu_3 = $row_rsSessions_sCom['total']*$_SESSION['MM_Retenu']),0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; 
	  $retenu_global_3 = $retenu_global_3 + $retenu_3;
	  ?></td>
      <td align="right" nowrap="nowrap">
	  <strong><?php echo number_format(($net_percu_3 = $row_rsSessions_sCom['total']-$retenu_3),0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; 
	  $net_percu_global_3 = $net_percu_global_3+$net_percu_3; ?></strong>
      </td>
      </tr>
    <?php } while ($row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom)); ?>
<tr>
      <td colspan="4" nowrap="nowrap">
        <a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>">&nbsp; </a></td>
      <?php //$count=0; $sommer=0; do  { $count++; ?>
      <?php $count = $_POST['txtMonth1']; $sommer=0;  do  { $count++; ?>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <?php //}while ($count < 12); ?>
      <?php } while ($count <= $_POST['txtMonth2']) ?>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <th align="right" nowrap="nowrap"><strong>Sous total</strong></th>
      <th align="right" nowrap="nowrap"><strong>
        <?php //echo number_format($somme3,0,' ',' '); ?>
        <?php echo number_format($Montant_global2,0,' ',' '); ?>      </strong></th>
      <th align="right" nowrap="nowrap"><strong><?php echo number_format($retenu_global_3,0,' ',' '); ?></strong>&nbsp; </th>
      <th align="right" nowrap="nowrap"><strong><?php echo number_format($net_percu_global_3,0,' ',' '); ?></strong></th>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3" align="left">&nbsp;
      <?php if ($totalRows_rsSessionRepresentant > 0) { // Show if recordset not empty ?>
  <h1>Représentant Maitre d'Ouvrage</h1>
        <table border="1" align="center" class="print">
          <tr>
            <th>ID</th>
            <th>Nom et Prenom</th>
            <th>Representant structure</th>
            <th>RIB</th>
            <?php //$count=0; do { $count++ ?>
            <?php $count = $_POST['txtMonth1']; do {   ?>
            <th><?php echo GetValueMonth($count); ?>&nbsp;</th>
            <?php //} while($count<12) ?>
            <?php $count++; } while ($count <= $_POST['txtMonth2']) ?>
            <th>Total</th>
            <th>Taux</th>
            <th>Montant</th>
            <th>retenu <?php echo $_SESSION['MM_Retenu']*100 ."%"; ?></th>
            <th nowrap="nowrap">Net à percevoir</th>
          </tr>
          <?php $compter=0; $Montant_global3 = 0; $user_rib = null; do { $sommes=0; $compter++ ?>
		  <?php           
            $showGoToPersonnes3 = "upd_rib.php?recordID=". $row_rsSessionRepresentant['personne_id'];
          ?>
          <tr>
            <td><a href="detail_to_delete.php?recordID=<?php echo $row_rsSessionRepresentant['commission_id']; ?>"> <?php echo $compter ?>&nbsp; </a></td>
            <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes3, "700", "400"); ?>">
			<?php echo strtoupper($row_rsSessionRepresentant['personne_nom'] . " " . $row_rsSessionRepresentant['personne_prenom']); ?>&nbsp; </a></td>
            <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessionRepresentant['code_structure']); ?>&nbsp; </td>
            <td align="left"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessionRepresentant['personne_id']);
			if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  	echo $user_rib; }else{ } ?></td>
            <?php //$count=0; $sommer=0; do { $count++ ?>
            <?php $count = $_POST['txtMonth1']; $sommer=0; do {  ?>
            <td align="right">
              <?php 
				$nombre_jour = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessionRepresentant['commission_id'], $row_rsSessionRepresentant['personne_id'], $count, $_POST['txtYear']);
				if (isset($nombre_jour)){ echo $nombre_jour; $sommes = $sommes + $nombre_jour;} else {echo 0;}		
			?>
            &nbsp;</td>
            <?php //} while($count<12) ?>
            <?php $count++;  } while ($count <= $_POST['txtMonth2']) ?>
            <td align="right"><?php echo $sommes //$row_rsSessionRepresentant['mois']; ?>&nbsp; </td>
            <td align="right"><?php echo number_format(($row_rsSessionRepresentant['montant']),0,' ',' '); ?>&nbsp; </td>
            <td align="right">
				<?php $Montant = $sommes*$row_rsSessionRepresentant['montant']; 
					  $Montant_global3 = $Montant_global3 + $Montant; 
					  echo number_format($Montant,0,' ',' '); 
				?>
                &nbsp; </td>
            <td align="right">
				<?php echo number_format(($retenu_2 = $Montant*$_SESSION['MM_Retenu']),0,' ',' '); ?><?php $retenu_global_2 = $retenu_global_2 + $retenu_2; ?></td>
            <td align="right">
				<strong><?php echo number_format($net_percu_2 = $Montant-$retenu_2,0,' ',' '); ?></strong><?php $net_percu_global_2 = $net_percu_global_2+$net_percu_2; ?></td>
          </tr>
          <?php } while ($row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant)); ?>
<tr>
            <td colspan="4"><a href="#" onclick="<?php popup($showGoToPersonnes3, "700", "400"); ?>">&nbsp; </a> </td>
            <?php //$count=0; $sommer=0; do { $count++ ?>
            <?php $count = $_POST['txtMonth1']; $sommer=0; do {  $count++ ?>
            <td align="right">&nbsp;</td>
            <?php //} while($count<12) ?>
            <?php } while ($count <= $_POST['txtMonth2']) ?>
            <td align="right">&nbsp; </td>
            <th align="right" nowrap="nowrap"><strong>Sous Tota</strong>l&nbsp; </th>
            <th align="right" nowrap="nowrap"><strong>
              <?php  //echo number_format($montants,0,' ',' '); ?>
            <?php echo number_format($Montant_global3,0,' ',' '); ?>            </strong>&nbsp; </th>
            <th align="right" nowrap="nowrap"><strong><?php echo number_format($retenu_global_2,0,' ',' '); ?></strong> </th>
            <th align="right" nowrap="nowrap"><strong><?php echo number_format($net_percu_global_2,0,' ',' '); ?></strong></th>
            </tr>      
        </table>
        <br />
        <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table border="0" align="center" class="print">
      <tr>
        <td>Total des representants des maitres d'ouvrages</td>
        <td align="right"><strong><?php echo number_format($Montant_global3,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
      <tr>
        <td>Total de la Sous Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
	  <tr>
        <td>Total de la Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global1,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
	  <tr>
        <td>Montant retenu global (<?php echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</td>
        <td align="right"><strong><?php echo number_format($retenu_global_1+$retenu_global_2+$retenu_global_3,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>

      <tr>
        <th>Total G&eacute;n&eacute;ral</th>
        <th align="right"><strong>
          <?php  echo number_format(($Montant_global1 + $Montant_global2 + $Montant_global3),0,' ',' '); ?>
          </strong></th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>'; ?>Etat imprimé par : <strong><?php echo strtoupper($_SESSION['MM_Username']); ?></strong> le <?php echo $dateCreation ?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsSessions);

mysql_free_result($rsJourMois);

mysql_free_result($rsCommissions);

mysql_free_result($rsMembres);

mysql_free_result($rsSessionRepresentant);
?>
