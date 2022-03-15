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

	$txtComID = (isset($_REQUEST['comID'])?$_REQUEST['comID']:-1);
	$txtMonth1 = (isset($_REQUEST['m1ID'])?$_REQUEST['m1ID']:01);
	$txtMonth2 = (isset($_REQUEST['m2ID'])?$_REQUEST['m2ID']:12);
	$txtYear = (isset($_REQUEST['aID'])?$_REQUEST['aID']:2014);


$dateCreation = date('d-m-Y H:i:s');
MinmapDB::getInstance()->create_etats($txtComID, $txtMonth1, $txtMonth2, $txtYear, $dateCreation, 1, $_SESSION['MM_UserID']);
$etat_id = MinmapDB::getInstance()->get_etat_id_by_date($dateCreation);
?>
<?php

/*$MM_authorizedUsers = "1,5,6";
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
}*/
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
	$codeEtatEdition = 'AGIS-RECAP'.$etat_id.'-'.$_SESSION['MM_UserID'].$txtComID.$txtMonth1.$txtMonth2.$txtYear.date('Y-m-d H:i:s');

    $filename = $PNG_TEMP_DIR.'qrcode'.md5($codeEtatEdition.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
    QRcode::png($codeEtatEdition, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 

?>
<?php
if (trim($txtYear) == '')
            die('Année cannot be empty! <a href="sample52.php?comID='. $txtComID .'">back</a>');

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, personne_prenom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte, cle
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND personnes.personne_id = rib.personne_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id BETWEEN 1 AND 3 
AND sessions.mois between %s AND %s 
AND sessions.annee = %s 
AND sessions.membres_commissions_commission_id = %s 
GROUP BY personnes.personne_id
ORDER BY fonctions.fonction_id",GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"), GetSQLValueString($txtYear, "text"), GetSQLValueString($txtComID, "int"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_sCom = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, personne_prenom, fonctions_fonction_id, sum(nombre_jour) as nombre_jour, annee, montant, (sum(nombre_jour) * montant) as total
FROM sessions, membres, commissions, personnes
WHERE sessions.membres_commissions_commission_id = membres.commissions_commission_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.commission_id = membres.commissions_commission_id
AND personnes.personne_id = membres.personnes_personne_id
AND personnes.display = '1'
AND commission_parent = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY fonctions_fonction_id, personnes.personne_id
ORDER BY personne_nom, fonctions_fonction_id, annee, mois ASC", GetSQLValueString($txtComID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
$rsSessions_sCom = mysql_query($query_rsSessions_sCom, $MyFileConnect) or die(mysql_error());
$row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom);
$totalRows_rsSessions_sCom = mysql_num_rows($rsSessions_sCom);

/*mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_sCom2 = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, personne_prenom, fonctions_fonction_id, sum(nombre_jour) as nombre_jour, annee, montant, (sum(nombre_jour) * montant) as total
FROM sessions, membres, commissions, personnes
WHERE sessions.membres_commissions_commission_id = membres.commissions_commission_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.commission_id = membres.commissions_commission_id
AND personnes.personne_id = membres.personnes_personne_id
AND personnes.display = '1'
AND commission_parent = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY fonctions_fonction_id, personnes.personne_id
ORDER BY personne_nom, annee, mois ASC", GetSQLValueString($txtComID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
$rsSessions_sCom2 = mysql_query($query_rsSessions_sCom2, $MyFileConnect) or die(mysql_error());
$row_rsSessions_sCom2 = mysql_fetch_assoc($rsSessions_sCom2);
$totalRows_rsSessions_sCom2 = mysql_num_rows($rsSessions_sCom2);*/

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
ORDER BY personnes.personne_nom ASC",  GetSQLValueString($txtComID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
$rsSessions_rep = mysql_query($query_rsSessions_rep, $MyFileConnect) or die(mysql_error());
$row_rsSessions_rep = mysql_fetch_assoc(rsSessions_rep);
$totalRows_rsSessions_rep = mysql_num_rows($rsSessions_rep);




mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountIndemnity = sprintf("SELECT (sum(nombre_jour) * montant) as total
FROM sessions, membres
WHERE `membres`.personnes_personne_id = `sessions`.`membres_personnes_personne_id`
AND membres_commissions_commission_id = %s 
AND mois between 1 AND 12 
AND annee = %s
AND sessions.membres_personnes_personne_id = %s ", GetSQLValueString($txtComID, "int"),GetSQLValueString($txtYear, "text") , GetSQLValueString(2129, "int"));
$rsCountIndemnity = mysql_query($query_rsCountIndemnity, $MyFileConnect) or die(mysql_error());
$row_rsCountIndemnity = mysql_fetch_assoc($rsCountIndemnity);
$totalRows_rsCountIndemnity = mysql_num_rows($rsCountIndemnity);


mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($txtMonth1, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);


mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($txtComID, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id  AND fonctions_fonction_id = fonctions.fonction_id  AND personnes_personne_id = personne_id  AND commissions_commission_id = %s", GetSQLValueString($txtComID, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);


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
ORDER BY personnes.personne_nom", GetSQLValueString($txtComID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
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
    <td colspan="3" align="center">BILAN du mois de <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($txtMonth1)); ?></strong> à <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($txtMonth2)); ?> <?php echo  $txtYear; ?></strong></td>
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
		<?php $counter = $txtMonth1; do {  GetValueMonth($counter); ?>
        <th nowrap="nowrap"><?php echo GetValueMonth($counter);//ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
        <?php $counter++;} while ($counter <= $txtMonth2) ?>
        <th nowrap="nowrap">Total</th>
        <th nowrap="nowrap">Taux</th>
        <th nowrap="nowrap">Montant</th>
        <th nowrap="nowrap">Montant Payé</th>
        <th nowrap="nowrap">Retenu (<?php $_SESSION['MM_Retenu'] = (MinmapDB::getInstance()->get_retenu($txtYear)); 
		echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
        <th nowrap="nowrap">Reste à payer</th>
      </tr>
      <tr>
        <td colspan="4" align="center" nowrap="nowrap">Nombre de dossiers traités</td>
        <?php $counter = $txtMonth1; do {  GetValueMonth($counter); ?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?>&nbsp;</td>
        <?php $counter++;} while ($counter <= $txtMonth2) ?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?></td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
      </tr>
      <?php 
	  	$montantPaye =  0;
		$montanSessions = 0; 
		$ResteAPaye = 0;
		$montantRetenu = 0;
		$netAPercevoir = 0; 
		$somMontantPaye = 0;
		$somMontantSessions = 0;
		$somMontantRetenu = 0;
		$somNetPercevoir = 0;
		
		$user_rib = null; 
	  
	  do  { $counter++; ?>
      <?php           
		$showGoToPersonnes1 = "upd_rib.php?recordID=". $row_rsSessions['personne_id'];
		$showGoToPersonnes11 = "sample43.php?recordID=". $row_rsSessions['personne_id'];
	  ?>
      <tr>
        <td nowrap="nowrap"><?php echo $counter ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes11, "700", "400"); ?>">
		<?php echo strtoupper(htmlentities($row_rsSessions['personne_nom'].' '.$row_rsSessions['personne_prenom'])); ?>&nbsp; </a></td>
        <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp;</td>
        <td align="left" nowrap="nowrap">
		<a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">
		<?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions['personne_id']);
		if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ echo 'Absent'; } ?>
          </a>
          </td>
        <?php $count = $txtMonth1; $sommer=0; $montant2=0; do  { GetValueMonth($count); ?>
        <td align="right" nowrap="nowrap"><?php 
					//$countDoc = 0;
					//foreach(explode("**", $row_rsSessions['jour']) as $val){
						//$countDoc++; 
						//$val == $count ? (print "1") : $countDoc--; 
						
						
					//}
				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($txtComID, $row_rsSessions['personne_id'], $count, $txtYear);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
					{echo 0;}
				//echo "commission_id : " . $row_rsSessions['commission_id']. "personne_id : " . $row_rsSessions['personne_id'] . " mois : " . $count . " annee : " . $txtYear . "</BR>";
					
			?></td>
        <?php $count++; } while ($count <= $txtMonth2) ?>
        <td align="right" nowrap="nowrap"><?php echo $sommer //$row_rsSessions['nombre_jour']; ?></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap">
		<?php 
		$txtSearch = substr($row_rsSessions['personne_nom'],0,5);
		$montantPaye =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtSearch, $txtComID, $txtYear);
		//$montantPaye =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtComID, $txtYear);
		$montanSessions = $row_rsSessions['montant']*$sommer; 
		$resteAPaye = $montanSessions - $montantPaye;
		$montantRetenu = $resteAPaye*$_SESSION['MM_Retenu'];
		$netAPercevoir = $resteAPaye - $montantRetenu; 
		$somMontantPaye = $somMontantPaye + $montantPaye;
		$somMontantSessions = $somMontantSessions + $montanSessions;
		$somMontantRetenu = $somMontantRetenu + $montantRetenu;
		$somNetPercevoir = $somNetPercevoir + $netAPercevoir;
		//echo number_format(($somme),0,' ',' '); ?><?php echo number_format($montanSessions,0,' ',' '); ?><strong>
          
          </strong></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($montantPaye,0,' ',' '); ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><?php echo  number_format($montantRetenu,0,' ',' '); ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><strong><?php echo number_format($netAPercevoir,0,' ',' '); ?></td>
      </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
<tr>
        <td colspan="4" nowrap="nowrap"> <a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">&nbsp; </a> </td>
        <?php $counter = $txtMonth1; $sommer=0; $montant2=0; do  { GetValueMonth($counter); ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <?php $counter++; } while ($counter <= $txtMonth2) ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
        <th align="right" nowrap="nowrap"><strong>
          <?php $total = -($montant-($row_rsCountIndemnity['total'])); echo number_format($somMontantSessions,0,' ',' ')//$somme = $somme - $row_rsCountIndemnity['total']; echo number_format($somme,0,' ',' '); ?>
        </strong></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($somMontantPaye,0,' ',' ') ?>&nbsp;</th>
        <th align="right" nowrap="nowrap"><?php echo number_format($somMontantRetenu,0,' ',' '); ?></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($somNetPercevoir,0,' ',' '); ?></th>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="left">
    <h1>Sous Commissions Dépendante</h1>
	<?php if ($totalRows_rsSessions_sCom > 0) { // Show if recordset not empty ?> 
  
 <table border="1" align="center" class="print">
    <tr>
      <th>ID</th>
      <th>NomPrenom</th>
      <th>Fonction</th>
      <th>RIB</th>
      <?php $counter = $txtMonth1; do {   ?>
      <th><?php echo GetValueMonth($counter) ?></th>
      <?php //}while ($counter < 12);?>
      <?php $counter++; } while ($counter <= $txtMonth2) ?>
      <th nowrap="nowrap">Total</th>
      <th>Taux</th>
      <th>Montant</th>
      <th nowrap="nowrap">Montant Payé</th>
      <th nowrap="nowrap">Retenu(<?php echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
      <th nowrap="nowrap">Reste à payer</th>      
      </tr>
    <?php 
		$montantPayeSco =  0;
		$montanSessionsSco = 0;
		$resteAPayeSco = 0;
		$montantRetenuSco = 0;
		$netAPercevoirSco = 0;
		$somMontantPayeSco = 0;
		$somMontantSessionsSco = 0;
		$somMontantRetenuSco = 0;
		$somNetPercevoirSco = 0;
		$user_rib = null; 
		
		$compter==0; do   { $compter++; ?>
      <?php           
		$showGoToPersonnes2 = "upd_rib.php?recordID=". $row_rsSessions_sCom['personne_id'];
		$showGoToPersonnes22 = "sample43.php?recordID=". $row_rsSessions_sCom['personne_id'];
	  ?>
    <tr>
      <td nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions_sCom['personne_id']; ?>&amp;map=personnes"> <?php echo $compter; ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap">
      <a href="#" onclick="<?php popup($showGoToPersonnes22, "700", "400"); ?>">
	  <?php echo strtoupper(htmlentities($row_rsSessions_sCom['personne_nom'] . " " . $row_rsSessions_sCom['personne_prenom'])); ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap"><?php echo strtoupper(MinmapDB::getInstance()->get_fonction_lib_by_id($row_rsSessions_sCom['fonctions_fonction_id'])); ?>&nbsp; </td>
      <td align="left" nowrap="nowrap">
	  <a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>">	<?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions_sCom['personne_id']);
	  if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ echo 'Absent'; } ?>
      </a>
      </td>
      <?php //$count=0; $sommer=0; do  { $count++; ?>
      <?php $count = $txtMonth1; $sommer=0;  do  {  ?>
      <td align="right" nowrap="nowrap"><?php /*echo $txtComID ."-" . $row_rsSessions_sCom['personne_id'] ."-" . $row_rsSessions_sCom['fonction_id'] ."-" . $count ."-" .$txtYear;/*
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions_sCom['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}*/
			?>
			<?php 

				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_sCom_by_values2($txtComID, $row_rsSessions_sCom['personne_id'], $row_rsSessions_sCom['fonctions_fonction_id'], $count, $txtYear);
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
      <?php $count++; } while ($count <= $txtMonth2) ?>
      <?php
	    $txtSearch = substr($row_rsSessions_sCom['personne_nom'],0,5);
		$montantPayeSco =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtSearch, $txtComID, $txtYear);
		
	  	//$montantPayeSco =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtComID, $txtYear);
		$montanSessionsSco = $row_rsSessions_sCom['montant']*$sommer; 
		$resteAPayeSco = $montanSessionsSco - $montantPayeSco;
		$montantRetenuSco = $resteAPayeSco*$_SESSION['MM_Retenu'];
		$netAPercevoirSco = $resteAPayeSco - $montantRetenuSco; 
		$somMontantPayeSco = $somMontantPayeSco + $montantPayeSco;
		$somMontantSessionsSco = $somMontantSessionsSco + $montanSessionsSco;
		$somMontantRetenuSco = $somMontantRetenuSco + $montantRetenuSco;
		$somNetPercevoirSco = $somNetPercevoirSco + $netAPercevoirSco;
	  ?>
      <td align="right" nowrap="nowrap"><?php echo $sommer; ?><?php //echo '-'.$row_rsSessions_sCom['nombre_jour']; ?>&nbsp;</td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions_sCom['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($montanSessionsSco,0,' ',' '); ?>&nbsp;</td>
      <td align="right" nowrap="nowrap"><?php echo number_format($montantPayeSco,0,' ',' '); ?>&nbsp;</td>
      <td align="right" nowrap="nowrap"><?php echo number_format($montantRetenuSco,0,' ',' ');?>&nbsp;</td>
      <td align="right" nowrap="nowrap">
	  <strong><?php echo number_format($netAPercevoirSco,0,' ',' '); ?></strong>
      </td>
      </tr>
    <?php } while ($row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom)); ?>
<tr>
      <td colspan="4" nowrap="nowrap">
      <a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>">&nbsp; </a></td>
      <?php //$count=0; $sommer=0; do  { $count++; ?>
      <?php $count = $txtMonth1; $sommer=0;  do  { $count++; ?>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <?php //}while ($count < 12); ?>
      <?php } while ($count <= $txtMonth2) ?>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <th align="right" nowrap="nowrap"><strong>Sous total</strong></th>
      <th align="right" nowrap="nowrap"><strong>
        <?php echo number_format($somMontantSessionsSco,0,' ',' '); ?>      </strong></th>
      <th align="right" nowrap="nowrap"><strong><?php echo number_format($somMontantPayeSco,0,' ',' '); ?></strong>&nbsp; </th>
      <th align="right" nowrap="nowrap"><?php echo number_format($somMontantRetenuSco,0,' ',' '); ?>&nbsp;</th>
      <th align="right" nowrap="nowrap"><strong><?php echo number_format($somNetPercevoirSco,0,' ',' '); ?></strong></th>
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
            <?php $count = $txtMonth1; do {   ?>
            <th><?php echo GetValueMonth($count); ?>&nbsp;</th>
            <?php //} while($count<12) ?>
            <?php $count++; } while ($count <= $txtMonth2) ?>
            <th>Total</th>
            <th>Taux</th>
            <th>Montant</th>
            <th nowrap="nowrap">Montant Payé</th>
            <th nowrap="nowrap">retenu <?php echo $_SESSION['MM_Retenu']*100 ."%"; ?></th>
            <th nowrap="nowrap">Reste à payer</th>
          </tr>
          <?php 
		  	$montantPayeRep =  0;
			$montanSessionsRep = 0;
			$resteAPayeRep = 0;
			$montantRetenuRep = 0;
			$netAPercevoirRep = 0;
			$somMontantPayeRep = 0;
			$somMontantSessionsRep = 0;
			$somMontantRetenuRep = 0;
			$somNetPercevoirRep = 0;
			$user_rib = null; 
			
			do { $sommes=0; $compter++ ?>
		  <?php           
            $showGoToPersonnes3 = "upd_rib.php?recordID=". $row_rsSessionRepresentant['personne_id'];
			$showGoToPersonnes33 = "sample43.php?recordID=". $row_rsSessionRepresentant['personne_id'];
          ?>
          <tr>
            <td><a href="detail_to_delete.php?recordID=<?php echo $row_rsSessionRepresentant['commission_id']; ?>"> <?php echo $compter ?>&nbsp; </a></td>
            <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes33, "700", "400"); ?>">
			<?php echo strtoupper(htmlentities($row_rsSessionRepresentant['personne_nom'] . " " . $row_rsSessionRepresentant['personne_prenom'])); ?>&nbsp; </a></td>
            <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessionRepresentant['code_structure']); ?>&nbsp; </td>
            <td align="left" >
			<a href="#" onclick="<?php popup($showGoToPersonnes3, "700", "400"); ?>">
			<?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessionRepresentant['personne_id']);
			if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  	echo $user_rib; }else{ echo 'Absent'; } ?></a></td>
            <?php //$count=0; $sommer=0; do { $count++ ?>
            <?php $count = $txtMonth1; $sommer=0; do {  ?>
            <td align="right">
              <?php 
				$nombre_jour = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessionRepresentant['commission_id'], $row_rsSessionRepresentant['personne_id'], $count, $txtYear);
				if (isset($nombre_jour)){ echo $nombre_jour; $sommes = $sommes + $nombre_jour;} else {echo 0;}		
			?>
            &nbsp;</td>
            <?php //} while($count<12) ?>
            <?php $count++;  } while ($count <= $txtMonth2) ?>
            <?php 
			$montantPayeRep =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtComID, $txtYear);
			$montanSessionsRep = $row_rsSessionRepresentant['montant']*$sommes; 
			$resteAPayeRep = $montanSessionsRep - $montantPayeRep;
			$montantRetenuRep = $resteAPayeRep*$_SESSION['MM_Retenu'];
			$netAPercevoirRep = $resteAPayeRep - $montantRetenuRep; 
			$somMontantPayeRep = $somMontantPayeRep + $montantPayeRep;
			$somMontantSessionsRep = $somMontantSessionsRep + $montanSessionsRep;
			$somMontantRetenuRep = $somMontantRetenuRep + $montantRetenuRep;
			$somNetPercevoirRep = $somNetPercevoirRep + $netAPercevoirRep;
			?>
            <td align="right"><?php echo $sommes; ?>&nbsp; </td>
            <td align="right"><?php echo number_format(($row_rsSessionRepresentant['montant']),0,' ',' '); ?>&nbsp; </td>
            <td align="right"><?php echo number_format($montanSessionsRep,0,' ',' '); ?>&nbsp;</td>
            <td align="right"><?php echo number_format($montantPayeRep,0,' ',' '); ?>&nbsp; </td>
            <td align="right"><?php echo number_format($montantRetenuRep,0,' ',' '); ?>&nbsp;</td>
            <td align="right"><?php echo number_format($netAPercevoirRep,0,' ',' '); ?>&nbsp;</td>
          </tr>
          <?php } while ($row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant)); ?>
<tr>
            <td colspan="4"><a href="#" onclick="<?php popup($showGoToPersonnes3, "700", "400"); ?>">&nbsp; </a> </td>
            <?php //$count=0; $sommer=0; do { $count++ ?>
            <?php $count = $txtMonth1; $sommer=0; do {  $count++ ?>
            <td align="right">&nbsp;</td>
            <?php //} while($count<12) ?>
            <?php } while ($count <= $txtMonth2) ?>
            <td align="right">&nbsp; </td>
            <th align="right" nowrap="nowrap"><strong>Sous Tota</strong>l&nbsp; </th>
            <th align="right" nowrap="nowrap"><?php echo number_format($somMontantSessionsRep,0,' ',' '); ?>&nbsp; </th>
            <th align="right" nowrap="nowrap"><?php echo number_format($somMontantPayeRep,0,' ',' '); ?>&nbsp; </th>
            <th align="right" nowrap="nowrap"><strong><?php echo number_format($somMontantRetenuRep,0,' ',' '); ?></strong></th>
            <th align="right" nowrap="nowrap"><strong><?php echo number_format($somNetPercevoirRep,0,' ',' '); ?></strong></th>
            </tr>      
        </table>
        <br />
        <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
  <?php
  		$montantPayeGlobal = $somMontantPaye + $somMontantPayeSco + $somMontantPayeRep;
		$montantSessionsGlobal = $somMontantSessions + $somMontantSessionsSco + $somMontantSessionsRep;
		$montantRetenuGlobal = $somMontantRetenu + $somMontantRetenuSco + $somMontantRetenuRep;
		$netPercevoirGlobal = $somNetPercevoir + $somNetPercevoirSco + $somNetPercevoirRep + $montantRetenuGlobal;
  
  ?>
    <td colspan="3"><table border="0" align="center" class="print">
      <tr>
        <td>Total des representants des maitres d'ouvrages</td>
        <td align="right"><strong><?php echo number_format($somMontantSessionsRep,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
      <tr>
        <td>Total de la Sous Commission</td>
        <td align="right"><strong><?php echo number_format($somMontantSessionsSco,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
	  <tr>
        <td>Total de la Commission</td>
        <td align="right"><strong><?php echo number_format($somMontantSessions,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
	  <tr>
        <td>Montant retenu global (<?php echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</td>
        <td align="right"><strong><?php echo number_format($montantRetenuGlobal,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>

      <tr>
        <th>Total G&eacute;n&eacute;ral</th>
        <th align="right"><strong>
          <?php  echo number_format(($netPercevoirGlobal),0,' ',' '); ?>
          </strong></th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><?php echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>'; ?><strong><?php echo $codeEtatEdition; ?></strong></td>
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
