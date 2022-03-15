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
		$query_rsSessions = sprintf("SELECT commission_id, commission_lib, type_commission_id, fonctions.fonction_id, personnes.personne_id, personne_nom, personne_prenom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte, cle
		FROM commissions, membres, personnes,  fonctions, sessions, rib
		WHERE membres.commissions_commission_id = commissions.commission_id
		AND membres.personnes_personne_id = personnes.personne_id
		AND membres.fonctions_fonction_id = fonctions.fonction_id
		AND personnes.personne_id = rib.personne_id
		AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
		AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
		AND membres.fonctions_fonction_id BETWEEN 1 AND 40 
		AND sessions.mois between %s AND %s 
		AND sessions.annee = %s 
		AND (sessions.membres_commissions_commission_id = %s OR commission_parent = %s)
		GROUP BY personnes.personne_id
		ORDER BY sessions.membres_commissions_commission_id, fonctions.fonction_id",GetSQLValueString($txtMonth1, "text"),GetSQLValueString($txtMonth2, "text"), GetSQLValueString($txtYear, "text"), GetSQLValueString($txtComID, "int"), GetSQLValueString($txtComID, "int"));
		$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
		$row_rsSessions = mysql_fetch_assoc($rsSessions);
		$totalRows_rsSessions = mysql_num_rows($rsSessions);




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

        <th nowrap="nowrap">Sessions</th>

        <th nowrap="nowrap">Montant</th>
        <th nowrap="nowrap">Montant Payé</th>
        <th nowrap="nowrap">Reste &agrave; payer</th>
        <?php (isset($_POST['percent']) && $_POST['percent'] != 0 ? '<th nowrap=\"nowrap\">pourcentage</th>' : ''); ?>
        <th nowrap="nowrap">Retenu (<?php $_SESSION['MM_Retenu'] = (MinmapDB::getInstance()->get_retenu($txtYear)); 
		echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
        <th nowrap="nowrap">Net à payer</th>
        <th nowrap="nowrap">Emargement</th>
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
	  
	  do  { $compter++; ?>
      <?php           
		$showGoToPersonnes1 = "upd_rib.php?recordID=". $row_rsSessions['personne_id'];
		$showGoToPersonnes11 = "sample43.php?recordID=". $row_rsSessions['personne_id'];
	  ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes11, "700", "400"); ?>">
		<?php echo strtoupper(htmlentities($row_rsSessions['personne_nom'].' '.$row_rsSessions['personne_prenom'])); 
		$user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions['personne_id']);
	  	if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		   $user_rib; }else{  'Absent'; }
		?>&nbsp; </a></td>
        <td align="left" nowrap="nowrap">
		<?php echo $row_rsSessions['fonction_lib']; ?>&nbsp;</td>


        <td align="right" nowrap="nowrap">
			  <?php
                if (isset($row_rsSessions['type_commission_id']) && $row_rsSessions['type_commission_id'] == 6){
				$nombre_jours_year = MinmapDB::getInstance()->get_nombre_jour_sCom_by_year($txtComID, $row_rsSessions['personne_id'], $row_rsSessions['fonctions_fonction_id'], $txtMonth1, $txtMonth2, $txtYear);
				if (isset($nombre_jours_year))
				{ 
					echo $nombre_jours_year;				
				}
				else
					{echo 0;}
				} else {
				$nombre_jours_year = MinmapDB::getInstance()->get_nombre_jour_by_year($txtComID, $row_rsSessions['personne_id'], $txtMonth1, $txtMonth2, $txtYear);
				if (isset($nombre_jours_year))
				{ 
					echo $nombre_jours_year;
				}
				else
					{echo 0;}
				}
		
				?></td>
        <?php 
		  
		$txtSearch = substr($row_rsSessions['personne_nom'],0,5);
		if (isset($row_rsSessions['type_commission_id']) && $row_rsSessions['type_commission_id'] == 6){
		//$montantPaye =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtSearch, $row_rsSessions['commission_id'], $txtYear);
		$montanSessions = GetMontantMembreSessionsSousCommission2($txtComID, $row_rsSessions['personne_id'], $row_rsSessions['fonctions_fonction_id'], $txtMonth1, $txtMonth2, $txtYear, $row_rsSessions['commission_id']);
		$montantPaye =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtSearch, $txtComID, $txtYear);
		} else {
		$montanSessions = GetMontantMembreSessionsCommission($txtComID, $row_rsSessions['personne_id'], $row_rsSessions['fonctions_fonction_id'], $txtMonth1, $txtMonth2, $txtYear);
		$montantPaye =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtSearch, $row_rsSessions['commission_id'], $txtYear);
		}
		//$montantPaye =  MinmapDB::getInstance()->get_montant_appurement_by_rib($user_rib, $txtComID, $txtYear); 
		//$montanSessions = $row_rsSessions['montant']*$nombre_jours_year;
		
		$resteAPaye = $montanSessions - $montantPaye;
		($resteAPaye < 0 ? $resteAPaye = 0 : $resteAPaye);
		$somResteAPayer = $somResteAPayer + $resteAPaye;
		//(isset($_POST['percent']) && empty($_POST['percent'])? $resteAPaye = ($resteAPaye * $_POST['percent'])/100 : '');
		//$montantRetenu = $resteAPaye*$_SESSION['MM_Retenu'];
		($montantRetenu < 0 ? $montantRetenu = 0 : $montantRetenu = $resteAPaye*$_SESSION['MM_Retenu']);
		$somMontantRetenu = $somMontantRetenu + $montantRetenu;
		$netAPercevoir = $resteAPaye - $montantRetenu; 
		$somMontantPaye = $somMontantPaye + $montantPaye;
		$somMontantSessions = $somMontantSessions + $montanSessions;
		($netAPercevoir < 0 ? $netAPercevoir = 0 : number_format($netAPercevoir,0,' ',' '));
		$somNetPercevoir = $somNetPercevoir + $netAPercevoir;
		($netAPercevoir < 0 ? $cumulResteAPayer = $cumulResteAPayer : $cumulResteAPayer = $cumulResteAPayer + $netAPercevoir);
		//echo number_format(($somme),0,' ',' '); ?>
        <td align="right" nowrap="nowrap">
		<?php echo number_format($montanSessions,0,' ',' '); ?><strong>
          
          </strong></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($montantPaye,0,' ',' '); ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><?php echo number_format($resteAPaye,0,' ',' '); ?>&nbsp;</td>
        <?php (isset($_POST['percent']) && empty($_POST['percent'])? "<td nowrap=\"nowrap\">pourcentage</td>" : ""); ?>
        <td align="right" nowrap="nowrap"><?php echo  number_format($montantRetenu,0,' ',' '); ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><strong><?php 
		echo number_format($netAPercevoir,0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap">&nbsp;</td>
      </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
<tr>
        <td colspan="3" nowrap="nowrap"> <a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">&nbsp; </a> </td>

        <td align="right" nowrap="nowrap">&nbsp;</td>

        <th align="right" nowrap="nowrap"><strong>Sous Total</strong>&nbsp;</th>
        <th align="right" nowrap="nowrap"><?php echo number_format($somMontantPaye,0,' ',' ') ?>&nbsp;</th>
        <th align="right" nowrap="nowrap"><?php echo number_format($somResteAPayer,0,' ',' '); ?>&nbsp;</th>
        <?php (isset($_POST['percent'])? "<th align=\"right\" nowrap=\"nowrap\">pourcentage</th>" : ""); ?>
        <th align="right" nowrap="nowrap"><?php echo number_format($somMontantRetenu,0,' ',' '); ?></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($somNetPercevoir,0,' ',' '); ?></th>
        <th align="right" nowrap="nowrap">&nbsp;</th>
      </tr>

    </table></td>
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
	    <td>Total Net à Mandater</td>
	    <td align="right"><strong><?php echo number_format($cumulResteAPayer,0,' ',' '); ?><?php //echo number_format($somMontantSessions,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;</strong></td>
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
