<?php require_once('Connections/MyFileConnect.php'); ?>
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

function calculerSomme($a)
  {
    // Déclaration de la variable locale $somme
    $somme = $somme + $a;
    // Renvoi de la somme au programme principal
    return $somme;
  }

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
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, banque_code, agence_code, numero_compte, cle
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND membres.personnes_personne_id = rib.personne_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id <> 4
AND membres.fonctions_fonction_id <> 5
AND membres.fonctions_fonction_id <> 40
AND membres_commissions_commission_id = %s 
AND mois = %s 
AND annee = %s 
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_sCom = sprintf("SELECT commission_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, banque_code, agence_code, numero_compte, cle
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id 
AND membres.personnes_personne_id = personnes.personne_id 
AND membres.personnes_personne_id = rib.personne_id 
AND membres.fonctions_fonction_id = fonctions.fonction_id 
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id AND personnes.display = '1' 
AND sessions.membres_fonctions_fonction_id <> 4 
AND commission_parent = %s  
AND mois = %s AND annee = %s 
ORDER BY commissions.commission_id, fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
$rsSessions_sCom = mysql_query($query_rsSessions_sCom, $MyFileConnect) or die(mysql_error());
$row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom);
$totalRows_rsSessions_sCom = mysql_num_rows($rsSessions_sCom);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_rep = sprintf("SELECT commission_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, banque_code, agence_code, numero_compte, cle
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.personnes_personne_id = rib.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id = 40
AND membres_commissions_commission_id = %s 
AND mois = %s 
AND annee = %s
GROUP BY personnes.personne_nom 
ORDER BY fonctions.fonction_id",  GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
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
$query_rsCountIndemnity = sprintf("SELECT(nombre_jour * montant) as total 
FROM sessions, personnes, commissions, natures, type_commissions,membres WHERE sessions.membres_personnes_personne_id = personnes.personne_id AND sessions.membres_commissions_commission_id = commissions.commission_id AND sessions.membres_personnes_personne_id = membres.personnes_personne_id AND commissions.nature_id = natures.nature_id AND commissions.type_commission_id = type_commissions.type_commission_id AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s AND sessions.membres_personnes_personne_id = %s ", GetSQLValueString($colname_rsCountIndemnity, "int"),GetSQLValueString($colname_rsCountIndemnity1, "text"),GetSQLValueString($colname_rsCountIndemnity2, "text") , GetSQLValueString(2129, "int"));
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
$query_rsSessionRepresentant = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, personne_prenom, fonction_lib, code_structure, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, banque_code, agence_code, numero_compte, cle FROM commissions, membres, personnes,  fonctions, sessions, rib, structures WHERE membres.commissions_commission_id = commissions.commission_id AND membres.personnes_personne_id = personnes.personne_id AND personnes.structure_id = structures.structure_id AND membres.fonctions_fonction_id = fonctions.fonction_id AND sessions.membres_commissions_commission_id = membres.commissions_commission_id AND sessions.membres_personnes_personne_id = membres.personnes_personne_id AND sessions.membres_personnes_personne_id = rib.personne_id AND membres.fonctions_fonction_id = 40 AND membres_commissions_commission_id = %s AND mois = %s  AND annee = %s ORDER BY annee, mois", GetSQLValueString($colname_rsSessionRepresentant, "int"),GetSQLValueString($colname2_rsSessionRepresentant, "int"),GetSQLValueString($colname1_rsSessionRepresentant, "int"));
$rsSessionRepresentant = mysql_query($query_rsSessionRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant);
$totalRows_rsSessionRepresentant = mysql_num_rows($rsSessionRepresentant);

$colname_rsDossiers_traites = "-1";
if (isset($_GET['comID'])) {
  $colname_rsDossiers_traites = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsDossiers_traites = sprintf("SELECT * FROM dossier_traites WHERE dossiers_commission_id = %s AND mois = %s AND annee = %s", GetSQLValueString($colname_rsDossiers_traites, "int"), GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
$rsDossiers_traites = mysql_query($query_rsDossiers_traites, $MyFileConnect) or die(mysql_error());
$row_rsDossiers_traites = mysql_fetch_assoc($rsDossiers_traites);
$totalRows_rsDossiers_traites = mysql_num_rows($rsDossiers_traites);

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
<a href="#" onclick="window.print()" title="Imprimer cette page">Imprimer<img src="images/img/b_print.png" alt="print" width="16" height="16" hspace="2" vspace="2" border="0" align="absmiddle" /></a>
<table width="60%" border="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="869" height="46" align="center" valign="bottom">&nbsp;</td>
    <td width="376" align="right" valign="top"><a href="#" onclick="window.print()" title="Imprimer cette page"></a>&nbsp;</td>
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
    <td colspan="3" align="center">ETAT DES COMMISIONS DE PASSATION DES MARCHES</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><strong class="welcome"><?php echo htmlentities(strtoupper($row_rsSessions['commission_lib'])); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">BILAN du mois de <strong><?php echo strtoupper($row_rsJourMois['lib_mois'] . "/" . $_GET['aID']); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">	
	<?php 	if (isset($_GET['tab']) && ($_GET['tab']==4 || $_GET['tab']==1)) { // Show if $_GET['tab'] ?>
    <table border="1" align="center" class="print">
	<tr>
        <th nowrap="nowrap">N°</th>
        <th nowrap="nowrap">Nom et Prenom</th>
        <th nowrap="nowrap">Fonction</th>
        <?php $counter=0; do  { $counter++; ?>
        <th nowrap="nowrap"><?php echo $counter ?></th>
        <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
        <th nowrap="nowrap">Total</th>
        <th nowrap="nowrap">Taux</th>
        <th nowrap="nowrap">Montant</th>
      </tr>
      <tr>
        <td colspan="3" align="center" nowrap="nowrap">Nombre de dossiers traités</td>
        <td nowrap="nowrap">&nbsp;</td>
        <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?></td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
      </tr>
      <?php $counter=0; $somme1=0; do  { $counter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $counter ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><?php echo htmlentities(strtoupper($row_rsSessions['personne_nom']. ' ' . $row_rsSessions['personne_prenom'])); ?></td>
        <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
        <?php $count=0; do  { $count++; ?>
        <td nowrap="nowrap"><?php 
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions['jour']) as $val){
						$countDoc++; 
						$val == $count ? (print "1") : $countDoc--; 
						
					}
			?></td>
        <?php }while ($count < $row_rsJourMois['nbre_jour']); ?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_jour']; ?></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['total'],0,' ',' '); ?><strong>
          <?php $somme1 = $somme; $somme = $somme + $row_rsSessions['total']; ?>
          </strong></td>
      </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
      <tr>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <?php $count=0; do  { $count++; ?>
        <td nowrap="nowrap">&nbsp;</td>
        <?php }while ($count < $row_rsJourMois['nbre_jour']); ?>
        <td nowrap="nowrap">&nbsp;</td>
        <td align="right" nowrap="nowrap"><strong>Sous Total</strong></td>
        <td align="right" nowrap="nowrap"><strong>
          <?php $somme = $somme - $row_rsCountIndemnity['total']; echo number_format($somme1,0,' ',' '); ?>
        </strong></td>
      </tr>

    </table>
    <?php } // if isset $_GET['tab']==4 ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">	
	<?php 	if (isset($_GET['tab']) && ($_GET['tab']==3 || $_GET['tab']==4)) { // Show if $_GET['tab'] ?>
	<?php if ($totalRows_rsSessionRepresentant > 0) { // Show if recordset not empty ?>
  <table border="1" align="center" class="print">
    <tr>
      <th>ID</th>
      <th>Nom </th>
      <th>Representant Structure</th>
      <?php $count=0; do { $count++ ?>
      <th><?php echo $count ?>&nbsp;</th>
      <?php } while($count<$row_rsJourMois['nbre_jour']) ?>
      <th>Total</th>
      <th>Taux</th>
      <th>Montant</th>
      </tr>
    <?php $compter=0; $sommes=0; do { $compter++ ?>
    <tr>
      <td nowrap="nowrap"><a href="detail_to_delete.php?recordID=<?php echo $row_rsSessionRepresentant['commission_id']; ?>"> <?php echo $compter ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessionRepresentant['personne_nom'].' '.$row_rsSessionRepresentant['personne_prenom']); ?>&nbsp; </td>
      <td align="left" nowrap="nowrap"><?php echo $row_rsSessionRepresentant['code_structure']; ?>&nbsp; </td>
      <?php $count=0; $sommer=0; do { $count++ ?>
      <td nowrap="nowrap"><?php 
					$countDoc = 0;
					foreach(explode("**", $row_rsSessionRepresentant['jour']) as $val){ $countDoc++; $val == $count ? (print "1") : $countDoc--; }
			?>
        &nbsp;</td>
      <?php } while($count<$row_rsJourMois['nbre_jour']) ?>
      <td nowrap="nowrap"><?php echo $row_rsSessionRepresentant['nombre_jour']; ?>&nbsp; </td>
      <td align="right" nowrap="nowrap"><?php echo number_format(($row_rsSessionRepresentant['montant']),0,' ',' '); ?>&nbsp; </td>
      <td align="right" nowrap="nowrap"><?php $sommes = $sommes + $row_rsSessionRepresentant['total']; echo number_format($row_rsSessionRepresentant['total'],0,' ',' '); ?>
        &nbsp; </td>
      </tr>
    <?php } while ($row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant)); ?>
    <tr>
      <td colspan="<?php echo $row_rsJourMois['nbre_jour']+4 ?>" nowrap="nowrap">&nbsp;</td>
      <?php $count=0; do  { $count++; ?>
      <?php }while ($count < 12); ?>
      <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
      <th align="right" nowrap="nowrap"><strong>
        <?php  echo number_format($sommes,0,' ',' '); ?>
      </strong></th>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?>
  <?php } // Show if $_GET['tab'] == 3 ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">	
	<?php 	if (isset($_GET['tab']) && $_GET['tab']==4) { // Show if $_GET['tab'] ?>
    <table border="0" align="center" class="print">
      <tr>
        <td>Total des representants des maitres d'ouvrages</td>
        <td align="right"><strong><?php echo number_format($sommes,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
      <tr>
        <td>Total de la Commission</td>
        <td align="right"><strong><?php echo number_format($somme1+$somme3,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="right"><strong>
          <?php  //echo number_format((($somme+$somme3)*1/100),0,' ',' '); ?>
          &nbsp;</strong></td>
      </tr>
      <tr>
        <th>Total G&eacute;n&eacute;ral</th>
        <th align="right"><strong>
          <?php  echo number_format($somme + $sommes,0,' ',' '); ?>
          &nbsp;</strong></th>
      </tr>
    </table>
    <?php } //end if $_GET['tab']==4 ?></td>
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
