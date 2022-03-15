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
$query_Recordset1 = "SELECT * FROM mois";
$Recordset1 = mysql_query($query_Recordset1, $MyFileConnect) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

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
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="80%" border="1" align="center">
    <tr>
      <td><table width="75%" border="1" align="left" class="std2">
          <tr valign="baseline">
            <td colspan="5" nowrap="nowrap">Selectionner  une commission
              <?php           
	$showGoTo = "sample62.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
              <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
              <strong>
                <?php if (isset($_GET['comID'])) { ?>
                <?php echo strtoupper($row_rsCommissions['commission_lib']); ?>
                <?php } ?>
              </strong>
              <input name="commission_id" type="hidden" id="commission_id2" value="<?php echo $row_rsCommissions['commission_id']; ?>" />
              <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
              <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
              <?php }
            ?>
              <input type="hidden" name="txt_Com2" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" /></td>
          </tr>
          <tr>
            <th>ID</th>
            <th>&nbsp;</th>
            <th>Type</th>
            <th>Nature</th>
            <th>Localite</th>
          </tr>
          <tr>
            <td nowrap="nowrap"><img src="images/img/b_views.png" alt="" width="16" height="16" /></td>
            <td nowrap="nowrap"><a href="#"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a>&nbsp;</td>
            <td nowrap="nowrap"><?php echo $row_rsCommissions['type_commission_lib']; ?>&nbsp;</td>
            <td nowrap="nowrap"><?php echo $row_rsCommissions['lib_nature']; ?>&nbsp;</td>
            <td nowrap="nowrap"><?php echo $row_rsCommissions['localite_lib']; ?>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="5" align="right" nowrap="nowrap"><input type="hidden" name="MM_insert2" value="form1" /></td>
          </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php // if ($totalRows_rsCommissions > 0) { // Show if recordset not empty ?>
        <table border="0" align="left" class="std2">
          <tr>
            <th align="right" scope="row">Exercice:</th>
            <td><select name="txtYear" id="txtYear">
              <option value=""<?php if (!(strcmp("", $_POST['txtType']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
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
            <td><input type="hidden" name="comID" id="comID" value="<?php echo $_GET['comID'] ?>"/></td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <th align="right" scope="row">Periode allant de :</th>
            <td><select name="txtMonth1" id="txtMonth1">
              <option value="" <?php if (!(strcmp("", $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rsMois1['mois_id']?>" <?php if (!(strcmp($row_rsMois1['mois_id'], $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>><?php echo Ucfirst($row_rsMois1['lib_mois']);?></option>
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
              <option value="" <?php if (!(strcmp("", $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rsMois2['mois_id']?>" <?php if (!(strcmp($row_rsMois2['mois_id'], $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois2['lib_mois']); ?></option>
              <?php
} while ($row_rsMois2 = mysql_fetch_assoc($rsMois2));
  $rows = mysql_num_rows($rsMois2);
  if($rows > 0) {
      mysql_data_seek($rsMois2, 0);
	  $row_rsMois2 = mysql_fetch_assoc($rsMois2);
  }
?>
            </select></td>
            <td><input type="submit" name="button" id="button" value="Rechercher" />
              &nbsp;</td>
            </tr>
        </table>
        <?php //} // Show if recordset not empty ?></td>
    </tr>
    <tr>
      <td><input type="submit" value="Rechercher..." /></td>
    </tr>
  </table>
</form>
<table width="60%" border="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="869" align="center" valign="top">&nbsp;</td>
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
    <table border="1" align="center" class="std">
	<tr>
        <th nowrap="nowrap">N&deg;</th>
        <th nowrap="nowrap">NomPrenom</th>
        <th nowrap="nowrap">Fonction</th>
        <th nowrap="nowrap">RIB</th>
		<?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
        <th nowrap="nowrap"><?php echo GetValueMonth($counter);//ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
        <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
        <th nowrap="nowrap">Total</th>
        <th nowrap="nowrap">Taux</th>
        <th nowrap="nowrap">Montant</th>
        <th nowrap="nowrap">Retenu 16,50%</th>
        <th nowrap="nowrap">Net &agrave; percevoir</th>
      </tr>
      <tr>
        <td colspan="4" align="center" nowrap="nowrap">Nombre de dossiers trait&eacute;s</td>
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
        <td align="right" nowrap="nowrap"><?php echo number_format(($retenu=$somme*0.165),0,' ',' '); ?><?php $retenu_global_1 = $retenu_global_1 + $retenu; ?>&nbsp;</td>
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
    <h1>Sous Commissions D&eacute;pendante</h1>
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
      <th nowrap="nowrap">Retenu 16,50%</th>
      <th nowrap="nowrap">Net &agrave; percevoir</th>      
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
      <td align="right" nowrap="nowrap"><?php echo number_format(($retenu_3 = $row_rsSessions_sCom['total']*0.165),0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; 
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
  <h1>Repr&eacute;sentant Maitre d'Ouvrage</h1>
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
            <th>retenu 16,50%</th>
            <th>Net &agrave; percevoir</th>
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
				<?php echo number_format(($retenu_2 = $Montant*0.1650),0,' ',' '); ?><?php $retenu_global_2 = $retenu_global_2 + $retenu_2; ?></td>
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
        <td align="right"><strong><?php echo number_format($Montant_global3,0,' ',' '); ?></strong></td>
      </tr>
      <tr>
        <td>Total de la Sous Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global2,0,' ',' '); ?></strong></td>
      </tr>
	  <tr>
        <td>Total de la Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global1,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?></strong></td>
      </tr>
	  <tr>
        <td>Montant retenu global (16,50%)</td>
        <td align="right"><strong><?php echo number_format($retenu_global_1+$retenu_global_2+$retenu_global_3,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?></strong></td>
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
    <td colspan="3" align="left">Etat imprim&eacute; par : <strong><?php echo strtoupper($_SESSION['MM_Username']); ?></strong> le <?php echo date("d-m-Y"); ?> à <?php echo date("H:i") ?></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($Recordset1);

mysql_free_result($rsAnnee);
?>
