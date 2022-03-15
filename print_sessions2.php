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
if (isset($_GET['comID'])) {
  $colname_rsSessions = $_GET['comID'];
}
$colname_rsSessions1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsSessions1 = $_GET['mID'];
}
$colname_rsSessions2 = "-1";
if (isset($_GET['aID'])) {
  $colname_rsSessions2 = $_GET['aID'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personne_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  fonctions, sessions
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id BETWEEN 1 AND 3
AND membres_commissions_commission_id = %s 
AND mois = %s 
AND annee = %s 
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_sCom = sprintf("SELECT distinct(personne_id), personne_nom, fonctions.fonction_id, fonction_lib, nombre_jour, annee, montant, (nombre_jour * montant) as total
FROM sessions, membres, commissions, personnes, fonctions
WHERE sessions.membres_commissions_commission_id = membres.commissions_commission_id 
AND commissions.commission_id = membres.commissions_commission_id
AND personnes.personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND (sessions.membres_fonctions_fonction_id BETWEEN 1 AND 3 OR sessions.membres_fonctions_fonction_id = 141)
AND personnes.display = '1'
AND commission_parent = %s AND annee = %s
ORDER BY personne_nom, annee, mois", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions2, "text"));
$rsSessions_sCom = mysql_query($query_rsSessions_sCom, $MyFileConnect) or die(mysql_error());
$row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom);
$totalRows_rsSessions_sCom = mysql_num_rows($rsSessions_sCom);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_rep = sprintf("SELECT commission_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  fonctions, sessions
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id = 40
AND membres_commissions_commission_id = %s 
AND annee = %s
GROUP BY personnes.personne_nom 
ORDER BY fonctions.fonction_id",  GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"));
$rsSessions_rep = mysql_query($query_rsSessions_rep, $MyFileConnect) or die(mysql_error());
$row_rsSessions_rep = mysql_fetch_assoc(rsSessions_rep);
$totalRows_rsSessions_rep = mysql_num_rows($rsSessions_rep);



$colname_rsCountIndemnit2 = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCountIndemnity = $_GET['comID'];
}
$colname_rsCountIndemnity1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsCountIndemnity1 = $_GET['mID'];
}
$colname_rsCountIndemnity2 = "-1";
if (isset($_GET['aID'])) {
  $colname_rsCountIndemnity2 = $_GET['aID'];
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
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id  AND fonctions_fonction_id = fonctions.fonction_id  AND personnes_personne_id = personne_id  AND commissions_commission_id = %s", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsSessionRepresentant = "-1";
if (isset($_GET['comID'])) {
  $colname_rsSessionRepresentant = $_GET['comID'];
}
$colname1_rsSessionRepresentant = "-1";
if (isset($_GET['aID'])) {
  $colname1_rsSessionRepresentant = $_GET['aID'];
}
$colname2_rsSessionRepresentant = "-1";
if (isset($_GET['mID'])) {
  $colname2_rsSessionRepresentant = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionRepresentant = sprintf("SELECT commission_id, personne_id, personne_nom, personne_prenom, fonction_lib, code_structure, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total FROM commissions, membres, personnes,  fonctions, sessions, structures WHERE membres.commissions_commission_id = commissions.commission_id AND membres.personnes_personne_id = personnes.personne_id AND  personnes.structure_id = structures.structure_id AND membres.fonctions_fonction_id = fonctions.fonction_id AND sessions.membres_commissions_commission_id = membres.commissions_commission_id AND sessions.membres_personnes_personne_id = membres.personnes_personne_id AND membres.fonctions_fonction_id = 40 AND membres_commissions_commission_id = %s  AND annee = %s ORDER BY annee, mois", GetSQLValueString($colname_rsSessionRepresentant, "int"),GetSQLValueString($colname1_rsSessionRepresentant, "int"));
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
    <td colspan="3" align="center">BILAN du mois de <strong>JANVIER<?php //echo strtoupper($row_rsJourMois['lib_mois'] . "/" . $_GET['aID']); ?></strong> à <strong>DECEMBRE <?php echo  $_GET['aID']; ?></strong></td>
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
        <?php $counter=0; do  { $counter++; ?>
        <th nowrap="nowrap"><?php echo GetValueMonth($counter) ?></th>
        <?php }while ($counter < 12);?>
        <th nowrap="nowrap">Total</th>
        <th nowrap="nowrap">Taux</th>
        <th nowrap="nowrap">Montant</th>
      </tr>
      <tr>
        <td colspan="3" align="center" nowrap="nowrap">Nombre de dossiers traités</td>
        <?php $counter=0; do  { $counter++; ?>
        <td nowrap="nowrap">&nbsp;</td>
        <?php }while ($counter < 12);?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?></td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
      </tr>
      <?php $counter=0; $Montant_global1 = 0; do  { $counter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $counter ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><?php echo htmlentities(strtoupper($row_rsSessions['personne_nom'].' '.$row_rsSessions['personne_prenom'])); ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
        <?php $count=0; $sommer=0; $montant2=0; do  { $count++; ?>
        <td align="right" nowrap="nowrap"><?php 
					//$countDoc = 0;
					//foreach(explode("**", $row_rsSessions['jour']) as $val){
						//$countDoc++; 
						//$val == $count ? (print "1") : $countDoc--; 
						
						
					//}
				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessions['commission_id'], $row_rsSessions['personne_id'], $count, $_GET['aID']);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsSessions['commission_id']. "personne_id : " . $row_rsSessions['personne_id'] . " mois : " . $count . " annee : " . $_GET['aID'] . "</BR>";
					
			?></td>
        <?php }while ($count < 12); ?>
        <td align="right" nowrap="nowrap"><?php echo $sommer //$row_rsSessions['nombre_jour']; ?></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap"><?php $somme = $row_rsSessions['montant']*$sommer; $Montant_global1 = $Montant_global1 + $somme; $montant2=$montant; $montant = $montant + $somme;  //echo number_format(($somme),0,' ',' '); ?><?php echo number_format($somme,0,' ',' '); ?><strong>
          <?php $somme1 = $somme; $somme = $somme + $row_rsSessions['total']; ?>
          </strong></td>
      </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
      <tr>
        <td colspan="16" nowrap="nowrap">&nbsp;</td>
        <?php $count=0; do  { $count++; ?>
        <?php }while ($count < 12); ?>
        <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
        <th align="right" nowrap="nowrap"><strong>
          <?php $total = -($montant-($row_rsCountIndemnity['total'])); echo number_format(($Montant_global1),0,' ',' ')//$somme = $somme - $row_rsCountIndemnity['total']; echo number_format($somme,0,' ',' '); ?>
        </strong></th>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left"><?php if ($totalRows_rsSessions_sCom > 0) { // Show if recordset not empty ?>
    <h1>Sous Commissions Dépendante</h1>
  <table border="1" align="center" class="print">
    <tr>
      <th>ID</th>
      <th>NomPrenom</th>
      <th>Fonction</th>
      <?php $counter=0; do  { $counter++; ?>
      <th><?php echo GetValueMonth($counter) ?></th>
      <?php }while ($counter < 12);?>
      <th>Total</th>
      <th>Taux</th>
      <th>Montant</th>
      </tr>
    <?php $compter=0; $Montant_global2 = 0; do  { $compter++; ?>
    <tr>
      <td nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions_sCom['membres_personnes_personne_id']; ?>&amp;map=personnes"> <?php echo $compter; ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap"><?php echo htmlentities(strtoupper($row_rsSessions_sCom['personne_nom']). " " . $row_rsSessions_sCom['personne_prenom']); ?>&nbsp; </td>
      <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessions_sCom['fonction_lib']); ?>&nbsp; </td>
      <?php $count=0; $sommer=0; do  { $count++; ?>
      <td align="right" nowrap="nowrap"><?php /*echo $_GET['comID'] ."-" . $row_rsSessions_sCom['personne_id'] ."-" . $row_rsSessions_sCom['fonction_id'] ."-" . $count ."-" .$_GET['aID'];/*
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions_sCom['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}*/
			?>
			<?php 

				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_sCom_by_values($_GET['comID'], $row_rsSessions_sCom['personne_id'], $row_rsSessions_sCom['fonction_id'], $count, $_GET['aID']);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}	
			?>
            
            </td>
      <?php }while ($count < 12); ?>
      <td align="right" nowrap="nowrap"><?php echo $sommer;//$row_rsSessions_sCom['nombre_jour']; ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions_sCom['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><strong><?php $somme2 = $row_rsSessions_sCom['montant']*$sommer; echo number_format($somme2,0,' ',' '); ?></strong>
        <?php  $Montant_global2 = $Montant_global2 + $somme2;?></td>
      </tr>
    <?php } while ($row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom)); ?>
    <tr>
      <td colspan="16" nowrap="nowrap">&nbsp;</td>
      <?php $counter=0; do  { $counter++; ?>
      <?php }while ($counter < 16);?>
      <th align="right" nowrap="nowrap"><strong>Sous total</strong></th>
      <th align="right" nowrap="nowrap"><strong><?php //echo number_format($somme3,0,' ',' '); ?><?php echo number_format($Montant_global2,0,' ',' '); ?></strong></th>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
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
            <?php $count=0; do { $count++ ?>
            <th><?php echo $count ?>&nbsp;</th>
            <?php } while($count<12) ?>
            <th>Total</th>
            <th>Taux</th>
            <th>Montant</th>
          </tr>
          <?php $compter=0; $Montant_global3 = 0; do { $sommes=0; $compter++ ?>
          <tr>
            <td><a href="detail_to_delete.php?recordID=<?php echo $row_rsSessionRepresentant['commission_id']; ?>"> <?php echo $compter ?>&nbsp; </a></td>
            <td align="left"><?php echo strtoupper($row_rsSessionRepresentant['personne_nom'] . " " . $row_rsSessionRepresentant['personne_prenom']); ?>&nbsp; </td>
            <td align="left"><?php echo $row_rsSessionRepresentant['code_structure']; ?>&nbsp; </td>
            <?php $count=0; $sommer=0; do { $count++ ?>
            <td align="right">
              <?php 
				$nombre_jour = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessionRepresentant['commission_id'], $row_rsSessionRepresentant['personne_id'], $count, $_GET['aID']);
				if (isset($nombre_jour)){ echo $nombre_jour; $sommes = $sommes + $nombre_jour;} else {echo 0;}		
			?>
            &nbsp;</td>
            <?php } while($count<12) ?>
            <td align="right"><?php echo $sommes //$row_rsSessionRepresentant['mois']; ?>&nbsp; </td>
            <td align="right"><?php echo number_format(($row_rsSessionRepresentant['montant']),0,' ',' '); ?>&nbsp; </td>
            <td align="right">
				<?php $Montant = $sommes*$row_rsSessionRepresentant['montant']; 
					  $Montant_global3 = $Montant_global3 + $Montant; 
					  echo number_format($Montant,0,' ',' '); 
				?>&nbsp; </td>
            </tr>
          <?php } while ($row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant)); ?>
          <tr>
            <td colspan="16" nowrap="nowrap">&nbsp;</td>
            <?php $count=0; do  { $count++; ?>
            <?php }while ($count < 12); ?>
            <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
            <th align="right" nowrap="nowrap"><strong>
              <?php  //echo number_format($montants,0,' ',' '); ?><?php echo number_format($Montant_global3,0,' ',' '); ?>
            </strong></th>
            </tr>      
        </table>
        <br />
        <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table border="0" align="center" class="print">
      <tr>
        <td>Total des representants des maitres d'ouvrages</td>
        <td align="right"><strong><?php echo number_format($Montant_global3,0,' ',' '); ?>&nbsp;F CFA</strong></td>
      </tr>
      <tr>
        <td>Total de la Sous Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global2,0,' ',' '); ?>&nbsp;F CFA</strong></td>
      </tr>
	  <tr>
        <td>Total de la Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global1,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;F CFA</strong></td>
      </tr>
      <tr>
        <th>Total G&eacute;n&eacute;ral</th>
        <th align="right"><strong>
          <?php  echo number_format(($Montant_global1 + $Montant_global2 + $Montant_global3),0,' ',' '); ?>
          F CFA</strong></th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
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
