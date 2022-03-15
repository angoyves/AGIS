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
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_GET['mID1'])) && ($_GET['mID1'] != "") && (isset($_GET['mID2'])) && ($_GET['mID2'] != "") && (isset($_GET['aID'])) && ($_GET['aID'] != "")) {
$colname_rsmID1 = "-1";
if (isset($_GET['mID1'])) {
  $colname_rsmID1 = $_GET['mID1'];
}
$colname_rsmID2 = "-1";
if (isset($_GET['mID2'])) {
  $colname_rsmID2 = $_GET['mID2'];
}
$colname_rsaID = "-1";
if (isset($_GET['aID'])) {
  $colname_rsaID = $_GET['aID'];
}
$query_rsMontantIndemnite = sprintf("SELECT sum(nombre_jour * montant) as total FROM sessions, membres WHERE commissions_commission_id = membres_commissions_commission_id AND membres_personnes_personne_id = personnes_personne_id AND mois between %s AND %s AND annee = %s AND membres_fonctions_fonction_id = 41",GetSQLValueString($colname_rsmID1, "text"),GetSQLValueString($colname_rsmID2, "text"), GetSQLValueString($colname_rsaID, "text")); 
} else {
$query_rsMontantIndemnite = "SELECT sum(nombre_jour * montant) as total FROM sessions, membres WHERE commissions_commission_id = membres_commissions_commission_id AND membres_personnes_personne_id = personnes_personne_id AND membres_fonctions_fonction_id = 41";
}

$rsMontantIndemnite = mysql_query($query_rsMontantIndemnite, $MyFileConnect) or die(mysql_error());
$row_rsMontantIndemnite = mysql_fetch_assoc($rsMontantIndemnite);
$totalRows_rsMontantIndemnite = mysql_num_rows($rsMontantIndemnite);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_GET['gID'])) && ($_GET['gID'] != "")) {
$colname_rstxtGroup = "-1";
if (isset($_GET['gID'])) {
  $colname_rstxtGroup = $_GET['gID'];
}
$query_rsSousGroupe = sprintf("SELECT * FROM sous_groupes WHERE groupe_id = %s",GetSQLValueString($colname_rstxtGroup, "int"));
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);
} else {
$query_rsSousGroupe = "SELECT * FROM sous_groupes WHERE groupe_id = 1";
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsYear = "SELECT * FROM annee";
$rsYear = mysql_query($query_rsYear, $MyFileConnect) or die(mysql_error());
$row_rsYear = mysql_fetch_assoc($rsYear);
$totalRows_rsYear = mysql_num_rows($rsYear);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMonth2 = "SELECT mois_id, lib_mois FROM mois";
$rsMonth2 = mysql_query($query_rsMonth2, $MyFileConnect) or die(mysql_error());
$row_rsMonth2 = mysql_fetch_assoc($rsMonth2);
$totalRows_rsMonth2 = mysql_num_rows($rsMonth2);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMonth1 = "SELECT mois_id, lib_mois FROM mois";
$rsMonth1 = mysql_query($query_rsMonth1, $MyFileConnect) or die(mysql_error());
$row_rsMonth1 = mysql_fetch_assoc($rsMonth1);
$totalRows_rsMonth1 = mysql_num_rows($rsMonth1);
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
    <td align="center" valign="top">&nbsp;</td>
    <td width="376">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><strong class="welcome">ETAT DE PAIEMENT PAR VIREMENT SUIVANT LA CLE DE REPARTITION DE L'INDEMNITE FORFAITAIRE SPECIALE ALLOUEE AUX</strong></td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" class="welcome">&nbsp;</td>
    <td align="center" class="welcome"><strong>RESPONSABLES ET PERSONNELS DU MARCHES PUBLICS </strong></td>
    <td align="center" class="welcome">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><table border="1" align="left" class="print">
      <?php do { ?>
      <?php 

$colname_rsPersonnesRib = "-1";
if (isset($row_rsSousGroupe['sous_groupe_id'])) {
  $colname_rsPersonnesRib = $row_rsSousGroupe['sous_groupe_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGroupes = sprintf("SELECT count(personne_id) Nombres
FROM sous_groupes, personnes
WHERE personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND sous_groupes.sous_groupe_id = %s
AND personnes.type_personne_id BETWEEN 1 AND 2
AND personnes.display = 1
group by sous_groupes.sous_groupe_id",GetSQLValueString($colname_rsPersonnesRib, "int"));
$rsSousGroupes = mysql_query($query_rsSousGroupes, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupes = mysql_fetch_assoc($rsSousGroupes);
$totalRows_rsSousGroupes = mysql_num_rows($rsSousGroupes);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_GET['gID'])) && ($_POST['gID'] != "")) {
	
$colname_rstxtGroup = "-1";
if (isset($_GET['gID'])) {
  $colname_rstxtGroup = $_GET['gID'];
}

$query_rsPersonnesRib = sprintf("SELECT groupes.groupe_id, personnes.sous_groupe_id, personnes.personne_id, personne_nom, personne_grade, 
personnes.personne_matricule, personne_telephone, fonction_lib, structure_lib, sous_groupe_lib, sous_groupes.pourcentage as taux, banque_code,
agence_code, numero_compte, cle, sous_groupes.pourcentage as taux
FROM personnes, sous_groupes, groupes, fonctions, rib, structures
WHERE personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND personnes.personne_id = rib.personne_id
AND sous_groupes.groupe_id = groupes.groupe_id
AND personnes.fonction_id = fonctions.fonction_id
AND personnes.structure_id = structures.structure_id
AND personnes.type_personne_id BETWEEN 1 AND 2
AND sous_groupes.groupe_id = %s
AND sous_groupes.sous_groupe_id = %s
AND personnes.display = 1
GROUP BY sous_groupes.sous_groupe_id, structure_lib, personne_id", GetSQLValueString($colname_rstxtGroup, "int"), GetSQLValueString($colname_rsPersonnesRib, "int"));
$rsPersonnesRib = mysql_query($query_rsPersonnesRib, $MyFileConnect) or die(mysql_error());
$row_rsPersonnesRib = mysql_fetch_assoc($rsPersonnesRib);
$totalRows_rsPersonnesRib = mysql_num_rows($rsPersonnesRib); 

} else {
$query_rsPersonnesRib = sprintf("SELECT groupes.groupe_id, personnes.sous_groupe_id, personnes.personne_id, personne_nom, personne_grade, 
personnes.personne_matricule, personne_telephone, fonction_lib, structure_lib, sous_groupe_lib, sous_groupes.pourcentage as taux, banque_code,
agence_code, numero_compte, cle, sous_groupes.pourcentage as taux
FROM personnes, sous_groupes, groupes, fonctions, rib, structures
WHERE personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND personnes.personne_id = rib.personne_id
AND sous_groupes.groupe_id = groupes.groupe_id
AND personnes.fonction_id = fonctions.fonction_id
AND personnes.structure_id = structures.structure_id
AND personnes.type_personne_id BETWEEN 1 AND 2
AND sous_groupes.groupe_id BETWEEN 1 AND 4
AND sous_groupes.sous_groupe_id = %s
AND personnes.display = 1
GROUP BY sous_groupes.sous_groupe_id, structure_lib, personne_id", GetSQLValueString($colname_rsPersonnesRib, "int"));
$rsPersonnesRib = mysql_query($query_rsPersonnesRib, $MyFileConnect) or die(mysql_error());
$row_rsPersonnesRib = mysql_fetch_assoc($rsPersonnesRib);
$totalRows_rsPersonnesRib = mysql_num_rows($rsPersonnesRib); 
}




?>
      <tr>
        <td colspan="12"><strong><?php echo htmlentities($row_rsSousGroupe['sous_groupe_lib']) ?></strong>&nbsp;</td>
      </tr>
      <tr>
        <th nowrap="nowrap"><strong>N°</strong></th>
        <th nowrap="nowrap"><strong>Nom</strong></th>
        <th nowrap="nowrap"><strong>Matricule</strong></th>
        <th nowrap="nowrap"><strong>Grade</strong></th>
        <th nowrap="nowrap"><strong>Telephone</strong></th>
        <th nowrap="nowrap"><strong>Structure</strong></th>
        <th nowrap="nowrap"><strong>Fonction</strong></th>
        <th nowrap="nowrap"><strong>Banque</strong></th>
        <th nowrap="nowrap"><strong>Agence</strong></th>
        <th nowrap="nowrap"><strong>Numero de compte</strong></th>
        <th nowrap="nowrap"><strong>cle</strong></th>
        <th nowrap="nowrap"><strong>Montant</strong></th>
      </tr>
      <?php $Total=0; $SousTotal=0; $count =0; do { $count++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $count ?></td>
        <td nowrap="nowrap"><?php echo htmlentities($row_rsPersonnesRib['personne_nom']." " . $row_rsPersonnesRib['personne_prenom']); ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsPersonnesRib['personne_matricule']; ?>&nbsp; </td>
        <td><?php //echo htmlentities($row_rsPersonnesRib['personne_grade']); ?>&nbsp; </td>
        <td><?php echo $row_rsPersonnesRib['personne_telephone']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsPersonnesRib['structure_lib']; ?>&nbsp; </td>
        <td><?php echo htmlentities($row_rsPersonnesRib['fonction_lib']); ?>&nbsp; </td>
        <td align="center" nowrap="nowrap"><?php echo $row_rsPersonnesRib['banque_code']; ?>&nbsp; </td>
        <td align="center" nowrap="nowrap"><?php echo $row_rsPersonnesRib['agence_code']; ?>&nbsp; </td>
        <td align="center" nowrap="nowrap"><?php echo $row_rsPersonnesRib['numero_compte']; ?>&nbsp; </td>
        <td align="center" nowrap="nowrap"><?php echo $row_rsPersonnesRib['cle']; ?>&nbsp; </td>
        <td nowrap="nowrap" align="right"><?php $SousTotal = $SousTotal+($row_rsPersonnesRib['taux']*$row_rsMontantIndemnite['total'])/$row_rsSousGroupes['Nombres']; $Total = $Total + $SousTotal; echo number_format((($row_rsPersonnesRib['taux']*$row_rsMontantIndemnite['total'])/$row_rsSousGroupes['Nombres']),0,' ',' '); ?>&nbsp; </td>
      </tr>
      <?php } while ($row_rsPersonnesRib = mysql_fetch_assoc($rsPersonnesRib)); ?>
      <tr>
        <td colspan="10" nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">Sous total</td>
        <td align="right" nowrap="nowrap"><strong><?php echo number_format($SousTotal,0,' ',' '); ?></strong>&nbsp; </td>
      </tr>
      <br />
      <?php //echo $totalRows_rsPersonnesRib ?>
      <!--Enregistrements Total-->
      <?php } while ($row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe)); ?>
      <br />
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="left">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3">Montant Indemnite : <strong><?php echo number_format($Total,0,' ',' '); ?></strong>&nbsp;</td>
  </tr>
</table>
<?php //echo $totalRows_rsSousGroupe ?>
</body>
</html>
<?php
mysql_free_result($rsSousGroupe);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsYear);

mysql_free_result($rsMonth2);

mysql_free_result($rsMonth1);

mysql_free_result($rsPersonnesRib);
?>
