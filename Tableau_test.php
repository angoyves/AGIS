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
	  
	  	$comID = "527";
		if (isset($_REQUEST['comID'])) {
		  $comID = $_REQUEST['comID'];
		}
		$txtMonth1 = "01";
		if (isset($_REQUEST['txtMonth1'])) {
		  $txtMonth1 = $_REQUEST['txtMonth1'];
		}
		$txtMonth2 = "12";
		if (isset($_REQUEST['txtMonth2'])) {
		  $txtMonth2 = $_REQUEST['txtMonth2'];
		}
		$txtYear = "2014";
		if (isset($_REQUEST['txtYear'])) {
		  $txtYear = $_REQUEST['txtYear'];
		  
		$test_valid = "1";
}

?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$dateCreation = date('d-m-Y H:i:s');
MinmapDB::getInstance()->create_etats($comID, $txtMonth1, $txtMonth2, $txtYear, $dateCreation, 1, $_SESSION['MM_UserID']);
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
	$codeEtatEdition = 'AGIS-RECAP'.$etat_id.'-'.$_SESSION['MM_UserID'].$comID.$txtMonth1.$txtMonth2.$txtYear.date('Y-m-d H:i:s');

    $filename = $PNG_TEMP_DIR.'qrcode'.md5($codeEtatEdition.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
    QRcode::png($codeEtatEdition, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 

?>
<?php
if (trim($txtYear) == '')
            die('Année cannot be empty! <a href="sample52.php?comID='. $comID .'">back</a>');

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
ORDER BY fonctions.fonction_id", GetSQLValueString($comID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
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
ORDER BY personne_nom, annee, mois", GetSQLValueString($comID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
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
ORDER BY personnes.personne_nom",  GetSQLValueString($comID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
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
AND sessions.membres_personnes_personne_id = %s ", GetSQLValueString($comID, "int"),GetSQLValueString($txtYear, "text") , GetSQLValueString(2129, "int"));
$rsCountIndemnity = mysql_query($query_rsCountIndemnity, $MyFileConnect) or die(mysql_error());
$row_rsCountIndemnity = mysql_fetch_assoc($rsCountIndemnity);
$totalRows_rsCountIndemnity = mysql_num_rows($rsCountIndemnity);

$colname_rsJourMois = "-1";
if (isset($_REQUEST['mID'])) {
  $colname_rsJourMois = $_REQUEST['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($comID, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id  AND fonctions_fonction_id = fonctions.fonction_id  AND personnes_personne_id = personne_id  AND commissions_commission_id = %s", GetSQLValueString($comID, "int"));
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
ORDER BY personnes.personne_nom", GetSQLValueString($comID, "int"),GetSQLValueString($txtYear, "text"),GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"));
$rsSessionRepresentant = mysql_query($query_rsSessionRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant);
$totalRows_rsSessionRepresentant = mysql_num_rows($rsSessionRepresentant);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
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
    <th nowrap="nowrap">Retenu (
      <?php $_SESSION['MM_Retenu'] = (MinmapDB::getInstance()->get_retenu($txtYear)); 
		echo $_SESSION['MM_Retenu']*100 ."%"; ?>
      )</th>
    <th nowrap="nowrap">Net à percevoir</th>
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
  </tr>
  <?php $counter=0; $Montant_global1 = 0; $user_rib = null; do  { $counter++; ?>
  <?php           
		$showGoToPersonnes1 = "upd_rib.php?recordID=". $row_rsSessions['personne_id'];
	  ?>
  <tr>
    <td nowrap="nowrap"><?php echo $counter ?>&nbsp; </td>
    <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>"> <?php echo htmlentities(strtoupper($row_rsSessions['personne_nom'].' '.$row_rsSessions['personne_prenom'])); ?>&nbsp; </a></td>
    <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
    <td align="left" nowrap="nowrap"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions['personne_id']);
		if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ } ?></td>
    <?php $count = $txtMonth1; $sommer=0; $montant2=0; do  { GetValueMonth($count); ?>
    <td align="right" nowrap="nowrap"><?php 
					//$countDoc = 0;
					//foreach(explode("**", $row_rsSessions['jour']) as $val){
						//$countDoc++; 
						//$val == $count ? (print "1") : $countDoc--; 
						
						
					//}
				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessions['commission_id'], $row_rsSessions['personne_id'], $count, $txtYear, $test_valid);
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
    <td align="right" nowrap="nowrap"><?php 
		$somme = $row_rsSessions['montant']*$sommer; 
		$Montant_global1 = $Montant_global1 + $somme; 
		$montant2=$montant; 
		$montant = $montant + $somme;  //echo number_format(($somme),0,' ',' '); ?>
      <?php echo number_format($somme,0,' ',' '); ?><strong> </strong></td>
    <td align="right" nowrap="nowrap"><?php echo number_format(($retenu=$somme*$_SESSION['MM_Retenu']),0,' ',' '); ?>
      <?php $retenu_global_1 = $retenu_global_1 + $retenu; ?>
      &nbsp;</td>
    <td align="right" nowrap="nowrap"><strong><?php echo number_format(($net_percu_1 = $somme-$retenu),0,' ',' '); ?></strong>
      <?php $net_global_1 = $net_global_1 + $net_percu_1; ?>
      <?php $somme1 = $somme; $somme = $somme + $row_rsSessions['total']; ?></td>
  </tr>
  <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
  <tr>
    <td colspan="4" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">&nbsp; </a></td>
    <?php $counter = $txtMonth1; $sommer=0; $montant2=0; do  { GetValueMonth($counter); ?>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <?php $counter++; } while ($counter <= $txtMonth2) ?>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
    <th align="right" nowrap="nowrap"><strong>
      <?php $total = -($montant-($row_rsCountIndemnity['total'])); echo number_format(($Montant_global1),0,' ',' ')//$somme = $somme - $row_rsCountIndemnity['total']; echo number_format($somme,0,' ',' '); ?>
    </strong></th>
    <th align="right" nowrap="nowrap"><?php echo number_format($retenu_global_1,0,' ',' '); ?></th>
    <th align="right" nowrap="nowrap"><?php echo number_format($net_global_1,0,' ',' '); ?></th>
  </tr>
</table>
<p>&nbsp;</p>
<table width="50%" border="1" align="center" class="print">
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
    <th nowrap="nowrap">Retenu(<?php echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
    <th nowrap="nowrap">Net à percevoir</th>
  </tr>
  <?php $compter=0; $Montant_global2 = 0; $user_rib = null; do  { $compter++; ?>
  <?php           
		$showGoToPersonnes2 = "upd_rib.php?recordID=". $row_rsSessions_sCom['personne_id'];
	  ?>
  <tr>
    <td nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions_sCom['membres_personnes_personne_id']; ?>&amp;map=personnes"> <?php echo $compter; ?>&nbsp; </a></td>
    <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>"> <?php echo strtoupper($row_rsSessions_sCom['personne_nom']). " " . $row_rsSessions_sCom['personne_prenom']; ?>&nbsp; </a></td>
    <td align="left" nowrap="nowrap">Expert&nbsp; </td>
    <td align="left" nowrap="nowrap"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions_sCom['personne_id']);
	  if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ } ?></td>
    <?php //$count=0; $sommer=0; do  { $count++; ?>
    <?php $count = $txtMonth1; $sommer=0;  do  {  ?>
    <td align="right" nowrap="nowrap"><?php /*echo $comID ."-" . $row_rsSessions_sCom['personne_id'] ."-" . $row_rsSessions_sCom['fonction_id'] ."-" . $count ."-" .$txtYear;/*
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions_sCom['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}*/
			?>
      <?php 

				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_sCom_by_values($comID, $row_rsSessions_sCom['personne_id'], $count, $txtYear, 0);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}	
			?></td>
    <?php //}while ($count < 12); ?>
    <?php $count++; } while ($count <= $txtMonth2) ?>
    <td align="right" nowrap="nowrap"><?php echo $sommer;//$row_rsSessions_sCom['nombre_jour']; ?></td>
    <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions_sCom['montant'],0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; ?></td>
    <td align="right" nowrap="nowrap"><?php $somme2 = $row_rsSessions_sCom['montant']*$sommer; echo number_format($row_rsSessions_sCom['total'],0,' ',' '); ?>
      <?php  $Montant_global2 = $Montant_global2 + $row_rsSessions_sCom['total'];?></td>
    <td align="right" nowrap="nowrap"><?php echo number_format(($retenu_3 = $row_rsSessions_sCom['total']*$_SESSION['MM_Retenu']),0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; 
	  $retenu_global_3 = $retenu_global_3 + $retenu_3;
	  ?></td>
    <td align="right" nowrap="nowrap"><strong><?php echo number_format(($net_percu_3 = $row_rsSessions_sCom['total']-$retenu_3),0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; 
	  $net_percu_global_3 = $net_percu_global_3+$net_percu_3; ?></strong></td>
  </tr>
  <?php } while ($row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom)); ?>
  <tr>
    <td colspan="4" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>">&nbsp; </a></td>
    <?php //$count=0; $sommer=0; do  { $count++; ?>
    <?php $count = $txtMonth1; $sommer=0;  do  { $count++; ?>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <?php //}while ($count < 12); ?>
    <?php } while ($count <= $txtMonth2) ?>
    <td align="right" nowrap="nowrap">&nbsp;</td>
    <th align="right" nowrap="nowrap"><strong>Sous total</strong></th>
    <th align="right" nowrap="nowrap"><strong>
      <?php //echo number_format($somme3,0,' ',' '); ?>
      <?php echo number_format($Montant_global2,0,' ',' '); ?></strong></th>
    <th align="right" nowrap="nowrap"><strong><?php echo number_format($retenu_global_3,0,' ',' '); ?></strong>&nbsp; </th>
    <th align="right" nowrap="nowrap"><strong><?php echo number_format($net_percu_global_3,0,' ',' '); ?></strong></th>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>