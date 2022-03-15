<?php 
	  require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_rsSessions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsSessions = $_GET['comID'];
} else {
  $colname_rsSessions = $_POST['comID'];
}

if (isset($_GET['m1'])) {
  $colname_rsSessions1 = $_GET['m1'];
} elseif (isset($_POST['txtMonth1'])) {
  $colname_rsSessions1 = $_POST['txtMonth1'];
} else { $colname_rsSessions1 = "01"; }

if (isset($_GET['m2'])) {
  $colname_rsSessions3 = $_GET['m2'];
} elseif (isset($_POST['txtMonth2'])) {
  $colname_rsSessions3 = $_POST['txtMonth2'];
} else { $colname_rsSessions3 = "12"; }

if (isset($_GET['an'])) {
  $colname_rsSessions2 = $_GET['an'];
} elseif (isset($_POST['txtYear'])) {
  $colname_rsSessions2 = $_POST['txtYear'];
} else { $colname_rsSessions2 = "2015";	}


$RefDossierIsUnique = true;
$RefDossierIsEmpty = false;
$MotifSaisieIsEmpty = false;
$RefDossier = (MinmapDB::getInstance()->get_ref_dossier_id_by_lib($_POST['ref_dossier']));
    if ($_POST['ref_dossier'] == 'Ref Dossier'){
		$RefDossierIsEmpty = true;
	} else if ($RefDossier) {
        $RefDossierIsUnique = false;
    } 	
	if ($_POST['motif_saisie'] == 'Motif Saisie') {
		$MotifSaisieIsEmpty = true;
	}

if (isset($_POST["MM_insert"]) && ($_POST["MM_insert"] == "form1") && !$RefDossierIsEmpty && !$MotifSaisieIsEmpty){
	
$date = date('Y-m-d H:i:s');


$ref_dossier = MinmapDB::getInstance()->get_ref_dossier($_POST['ref_dossier']);
	
if ($RefDossierIsUnique){
$insertSQL = sprintf("INSERT INTO ordre_virements (ref_dossier, motif_saisie, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ref_dossier'], "text"),
                       GetSQLValueString($_POST['motif_saisie'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
}
  
$compter = 0; do { $compter++;
$add_appur = MinmapDB::getInstance()->get_person_add_appur($_POST['personne_id'.$compter]); // ici on controle que la personne est dans l'etat d'appurement
if (isset($_POST['num_virement'.$compter]) && $_POST['num_virement'.$compter] != 'N° Virement'){
if (isset($add_appur) && $add_appur == '1') {  
  $insertSQL = sprintf("INSERT INTO appurements (num_virement, commission_id, personne_id, fonction_id, ref_dossier, nombre_jour, etat_appur, periode_debut, periode_fin, annee, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['num_virement'.$compter], "int"),
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['personne_id'.$compter], "int"),
                       GetSQLValueString($_POST['fonction_id'.$compter], "int"),
                       GetSQLValueString($_POST['ref_dossier'], "text"),
                       GetSQLValueString($_POST['nombre_jour'.$compter], "int"),
                       GetSQLValueString($_POST['etat_appur'], "text"),
                       GetSQLValueString($colname_rsSessions1, "text"),
                       GetSQLValueString($colname_rsSessions3, "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $month = $_POST['periode_debut']; 
  // ici on met a jour la table session 
  do { 
  $updateSQL = sprintf("UPDATE sessions SET etat_appur=%s, dateUpdate=%s WHERE membres_commissions_commission_id=%s AND membres_personnes_personne_id = %s AND mois=%s AND annee=%s AND etat_appur=0",
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['commission_id'], "int"),
					   GetSQLValueString($_POST['personne_id'.$compter], "int"),
					   GetSQLValueString($month, "int"),
					   GetSQLValueString($_POST['annee'], "text"));

  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
  	$Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
	$month++;
  }  while ($month < $_POST['periode_fin']);
  
  }
}
  MinmapDB::getInstance()->update_person_appur($_POST['personne_id'.$compter], date()); //on reinitialise la personne
  }  while ($compter < $_POST['nombre_personne']);
		
}


?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
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
$query_rsAnnee = "SELECT * FROM annee ORDER BY lib_annee ASC";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);


mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, personne_prenom, fonctions.fonction_id, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte, cle, add_appur
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND personnes.personne_id = rib.personne_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id BETWEEN 1 AND 40
AND membres_commissions_commission_id = %s  
AND annee = %s 
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_id
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);




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
    <td><form action="<?php echo "sample113.php?comID=".$colname_rsSessions ?>" method="post">
      <p><a href="sample110.php">Retour</a></p>
      <table align="left" cellspacing="15">
        <tr>
          <th align="right" scope="row">Exercice:</th>
          <td><select name="txtYear" id="txtYear">
            <option value=""<?php if (!(strcmp("", $_POST['txtType']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
            <?php
do {  
?>
            <?php if (isset($colname_rsSessions2)){ ?>
            <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], $colname_rsSessions2))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
            <?php } else { ?>
            <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], $colname_rsSessions2))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
            <?php } ?>
            <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
  $rows = mysql_num_rows($rsAnnee);
  if($rows > 0) {
      mysql_data_seek($rsAnnee, 0);
	  $row_rsAnnee = mysql_fetch_assoc($rsAnnee);
  }
?>
          </select></td>
          <td>&nbsp;</td>
          <td><input type="hidden" name="comID" id="comID" value="<?php echo $colname_rsSessions ?>"/></td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <th align="right" scope="row">Periode allant de :</th>
          <td><select name="txtMonth1" id="txtMonth1">
            <option value="" <?php if (!(strcmp("", $colname_rsSessions1))) {echo "selected=\"selected\"";} ?>>Select:::</option>
            <?php
do {  
?>
            <?php if (isset($colname_rsSessions1)){ ?>
            <option value="<?php echo $row_rsMois1['mois_id']?>" <?php if (!(strcmp($row_rsMois1['mois_id'], $colname_rsSessions1))) {echo "selected=\"selected\"";} ?>><?php echo Ucfirst($row_rsMois1['lib_mois']);?></option>
            <?php } else { ?>
            <option value="<?php echo $row_rsMois1['mois_id']?>" <?php if (!(strcmp($row_rsMois1['mois_id'], $colname_rsSessions1))) {echo "selected=\"selected\"";} ?>><?php echo Ucfirst($row_rsMois1['lib_mois']);?></option>
            <?php } ?>
            <?php
} while ($row_rsMois1 = mysql_fetch_assoc($rsMois1));
  $rows = mysql_num_rows($rsMois1);
  if($rows > 0) {
      mysql_data_seek($rsMois1, 0);
	  $row_rsMois1 = mysql_fetch_assoc($rsMois1);
  }
?>
          </select></td>
          <th>à</th>
          <td><select name="txtMonth2" id="txtMonth2">
            <option value="" <?php if (!(strcmp("", $colname_rsSessions3))) {echo "selected=\"selected\"";} ?>>Select:::</option>
            <?php
do {  
?>
            <?php if (isset($colname_rsSessions3)){ ?>
            <option value="<?php echo $row_rsMois2['mois_id']?>" <?php if (!(strcmp($row_rsMois2['mois_id'], $colname_rsSessions3))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois2['lib_mois']); ?></option>
            <?php } else { ?>
            <option value="<?php echo $row_rsMois2['mois_id']?>" <?php if (!(strcmp($row_rsMois2['mois_id'], $colname_rsSessions3))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois2['lib_mois']); ?></option>
            <?php } ?>
            <?php
} while ($row_rsMois2 = mysql_fetch_assoc($rsMois2));
  $rows = mysql_num_rows($rsMois2);
  if($rows > 0) {
      mysql_data_seek($rsMois2, 0);
	  $row_rsMois2 = mysql_fetch_assoc($rsMois2);
  }
?>
          </select></td>
          <td align="center"><input type="submit" name="button" id="button" value="Rechercher" />
            </td>
        </tr>
      </table>
      <p>&nbsp;</p>
    </form></td>
  </tr>
  <tr>
    <td align="center"><strong class="welcome"><?php echo MinmapDB::getInstance()->get_commission_lib_by_commission_id($_GET['comID']); ?></strong></td>
  </tr>
  <tr>
    <td align="left">
    <h1>Commissions</h1>
    <?php
    //$link = "sample115.php?comID=". $_GET['comID'] . "&amp;menuID=" . $_GET['menuID'] . "&amp;an=" . $colname_rsSessions2 . "&amp;m1=" . $colname_rsSessions1 . "&amp;m2=" .  $colname_rsSessions3; 
	$link = "sample115.php?comID=". $_GET['comID'] . "&amp;menuID=" . $_GET['menuID'] . "&amp;an=" . $colname_rsSessions2 . "&amp;m1=" . $colname_rsSessions1 . "&amp;m2=" .  $colname_rsSessions3;?>
    <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form2">
      <p>
        <input type="text" name="ref_dossier" onblur="if (this.value=='') { this.value='Ref Dossier'; }" onfocus="if (this.value=='Ref Dossier') { this.value=''; }" value="<?php if (isset($_POST['ref_dossier'])) { echo $_POST['ref_dossier']; } else { echo "Ref Dossier"; } ?>" />
        <input name="motif_saisie" type="text" onfocus="if (this.value=='Motif Saisie') { this.value=''; }" onblur="if (this.value=='') { this.value='Motif Saisie'; }" value="<?php if (isset($_POST['motif_saisie'])) { echo $_POST['motif_saisie']; } else { echo "Motif Saisie"; } ?>" size="100" />
        <input type="hidden" name="etat_validation" value="1" />
      </p>
      <p>
      <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
        if ($RefDossierIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the Reference Dossier, please!</div>
        <?php  }
        if (!$RefDossierIsUnique) { ?>
        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This Reference already exists. Please check the spelling and try again</div>
        <?php  } 
		if ($MotifSaisieIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the Motif of File, please!</div> 
		<?php } ?>
      </p>
      <table border="1" align="center" class="print">
        <tr>
        <th nowrap="nowrap">N° Virement</th>
        <th nowrap="nowrap">Nom et Prenom</th>
        <th nowrap="nowrap">Fonction</th>
        <th nowrap="nowrap">RIB</th>
		<?php $counter = $colname_rsSessions1; do {  GetValueMonth($counter); ?>
        <th nowrap="nowrap"><?php echo GetValueMonth($counter);//ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
        <?php $counter++;} while ($counter <= $colname_rsSessions3) ?>
        <th nowrap="nowrap">Total</th>
        <th nowrap="nowrap">Taux</th>
        <th nowrap="nowrap">Montant</th>
        <th nowrap="nowrap">Retenu (<?php $_SESSION['MM_Retenu'] = (MinmapDB::getInstance()->get_retenu($colname_rsSessions2)); echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
        <th nowrap="nowrap">Net à percevoir</th>
        <th nowrap="nowrap">&nbsp;</th>
        </tr>
      <?php $counter=0; $Montant_global1 = 0; $user_rib = null; do  { $counter++; ?>
        <?php           
		$showGoToPersonnes1 = "upd_rib.php?recordID=". $row_rsSessions['personne_id'];
		$Sessions = MinmapDB::getInstance()->get_session_by_periode($colname_rsSessions2, $colname_rsSessions1, $colname_rsSessions3, $colname_rsSessions, $row_rsSessions['personne_id']); ?>
        <?php if (isset($Sessions) && $Sessions != 0) {?>
      <tr>
        <td nowrap="nowrap"><input name="<?php echo "num_virement".$counter; ?>" type="text" size="10" maxlength="10" onblur="if (this.value=='') { this.value='N° Virement'; }" onfocus="if (this.value=='N° Virement') { this.value=''; }" value="<?php if (isset($_POST['num_virement'.$counter])) { echo $_POST['num_virement'.$counter]; } else { echo 'N° Virement'; } ?>" />
          <?php //echo $counter ?> &nbsp; </td>
        <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">
          <?php echo htmlentities(strtoupper($row_rsSessions['personne_nom'].' '.$row_rsSessions['personne_prenom'])); ?>&nbsp;</a>
          <input type="hidden" name="<?php echo "personne_id".$counter; ?>" value="<?php echo $row_rsSessions['personne_id']; ?>" />
          <input type="hidden" name="<?php echo "fonction_id".$counter; ?>" value="<?php echo $row_rsSessions['fonction_id']; ?>" />
        </td>
        <td align="left" nowrap="nowrap"><?php echo $_POST['num_virement'.$counter];  //$Sessions."-".$colname_rsSessions1."-".$colname_rsSessions3; //echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions['personne_id']);
		if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ } ?></td>
        <?php $count = $colname_rsSessions1; $sommer=0; $montant2=0; do  { GetValueMonth($count); ?>
        <td align="right" nowrap="nowrap"><?php 
					//$countDoc = 0;
					//foreach(explode("**", $row_rsSessions['jour']) as $val){
						//$countDoc++; 
						//$val == $count ? (print "1") : $countDoc--; 
						
						
					//}
				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessions['commission_id'], $row_rsSessions['personne_id'], $count, $colname_rsSessions2);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsSessions['commission_id']. "personne_id : " . $row_rsSessions['personne_id'] . " mois : " . $count . " annee : " . $colname_rsSessions2 . "</BR>";
					
			?></td>
        <?php $count++; } while ($count <= $colname_rsSessions3) ?>
        <td align="right" nowrap="nowrap"><?php echo $sommer //$row_rsSessions['nombre_jour']; ?></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap">
		<?php 
		$somme = $row_rsSessions['montant']*$sommer; 
		$Montant_global1 = $Montant_global1 + $somme; 
		$montant2=$montant; 
		$montant = $montant + $somme;  //echo number_format(($somme),0,' ',' '); ?>
		<a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">
		<input type="hidden" name="<?php echo "nombre_jour".$counter; ?>" value="<?php echo $sommer; ?>" />
		</a><?php echo number_format($somme,0,' ',' '); ?><strong>
          
          </strong></td>
        <td align="right" nowrap="nowrap"><?php echo number_format(($retenu=$somme*$_SESSION['MM_Retenu']),0,' ',' '); ?><?php $retenu_global_1 = $retenu_global_1 + $retenu; ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><strong><?php echo number_format(($net_percu_1 = $somme-$retenu),0,' ',' '); ?></strong>
          <?php $net_global_1 = $net_global_1 + $net_percu_1; ?><?php $somme1 = $somme; $somme = $somme + $row_rsSessions['total']; ?></td>
        <td align="right" nowrap="nowrap"><input type="hidden" name="<?php echo "personne_id".$counter; ?>2" value="<?php echo $row_rsSessions['personne_id']; ?>" />
          <?php if (isset($row_rsSessions['add_appur']) && $row_rsSessions['add_appur'] == '1') { ?>
          <a href="sample97.php?recordID=<?php echo $row_rsSessions['personne_id']; ?>&amp;comID=<?php echo $_GET['comID']; ?>&amp;map=personnes&amp;mapid=personne_id&amp;action=desactive&amp;menuID=<?php echo $_GET['menuID']; ?>&amp;page=sample113.php&amp;col=add_appur&amp;an=<?php echo $colname_rsSessions2 ?>&amp;m1=<?php echo $colname_rsSessions1 ?>&amp;m2=<?php echo $colname_rsSessions3 ?>"> <img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/> </a>
          <?php } else { ?>
          <a href="sample97.php?recordID=<?php echo $row_rsSessions['personne_id']; ?>&amp;comID=<?php echo $_GET['comID']; ?>&amp;map=personnes&amp;mapid=personne_id&amp;action=active&amp;menuID=<?php echo $_GET['menuID']; ?>&amp;page=sample113.php&amp;col=add_appur&amp;an=<?php echo $colname_rsSessions2 ?>&amp;m1=<?php echo $colname_rsSessions1 ?>&amp;m2=<?php echo $colname_rsSessions3 ?>">
          <img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/>
          </a>
          <?php } ?></td>
        </tr>
        <?php } ?>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
<tr>
        <td colspan="4" nowrap="nowrap"> <a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">&nbsp; </a> <a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">
          <input type="hidden" name="display" value="1" />
          <input type="hidden" name="commission_id" value="<?php echo $_GET['comID']; ?>" />
          <input type="hidden" name="user_id" value="<?php echo $_SESSION['MM_UserID']; ?>" />
          <input type="hidden" name="nombre_personne" id="nombre_personne" value="<?php echo $counter ?>"/>
          <input type="hidden" name="periode_debut" value="<?php echo $colname_rsSessions1 ?>" />
          <input type="hidden" name="periode_fin" value="<?php echo GetValueMonth($colname_rsSessions3 + 1); ?>" />
          <input type="hidden" name="annee" value="<?php echo $colname_rsSessions2 ?>" />
          <input type="hidden" name="<?php echo "etat_appur".$counter; ?>" value="1" />
          <input type="hidden" name="MM_insert" value="form1" />
        </a></td>
        <?php $counter = $colname_rsSessions1; $sommer=0; $montant2=0; do  { GetValueMonth($counter); ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <?php $counter++; } while ($counter <= $colname_rsSessions3) ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
        <th align="right" nowrap="nowrap"><strong>
          <?php $total = -($montant-($row_rsCountIndemnity['total'])); echo number_format(($Montant_global1),0,' ',' ')//$somme = $somme - $row_rsCountIndemnity['total']; echo number_format($somme,0,' ',' '); ?>
        </strong></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($retenu_global_1,0,' ',' '); ?></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($net_global_1,0,' ',' '); ?></th>
        <th align="right" nowrap="nowrap">&nbsp;</th>
        </tr>

    </table>
    <table align="center">
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><input type="submit" value="Appurer la commission" /></td>
      </tr>
    </table>
    </form>
    </td>
  </tr>
  <tr>
    <td><table border="0" align="center" class="print">
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
    <td align="left">Etat imprimé par : <strong><?php echo strtoupper($_SESSION['MM_Username']); ?></strong> le <?php echo date("d-m-Y"); ?> à <?php echo date("H:i") ?></td>
  </tr>
</table>
</form>
</body>
</html>
<?php
mysql_free_result($rsSessions);

mysql_free_result($rsJourMois);

mysql_free_result($rsCommissions);

mysql_free_result($rsMembres);

mysql_free_result($rsSessionRepresentant);
?>
