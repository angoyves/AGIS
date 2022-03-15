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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsSessions = 10;
$pageNum_rsSessions = 0;
if (isset($_GET['pageNum_rsSessions'])) {
  $pageNum_rsSessions = $_GET['pageNum_rsSessions'];
}
$startRow_rsSessions = $pageNum_rsSessions * $maxRows_rsSessions;

$colname_rsSessions = "08";
if (isset($_GET['comID'])) {
  $colname_rsSessions = $_GET['comID'];
}
$colname_rsSessions1 = "01";
if (isset($_GET['mID'])) {
  $colname_rsSessions1 = $_GET['mID'];
}
$colname_rsSessions2 = "2015";
if (isset($_GET['aID'])) {
  $colname_rsSessions2 = $_GET['aID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom, banque_code, agence_code, numero_compte, cle,  
membres_commissions_commission_id, commission_lib, membres_fonctions_fonction_id, fonction_lib,  
lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee, 
montant,(nombre_jour * montant) as total 
FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, rib
WHERE sessions.mois = mois.mois_id
AND sessions.membres_personnes_personne_id = personnes.personne_id  
AND sessions.membres_commissions_commission_id = commissions.commission_id  
AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id  
AND commissions.nature_id = natures.nature_id   
AND commissions.type_commission_id = type_commissions.type_commission_id 
AND personnes.personne_id = rib.personne_id  
AND commissions.localite_id = localites.localite_id 
AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s", GetSQLValueString($colname_rsSessions, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
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

$colname_rsJourMois = "01";
if (isset($_GET['mID'])) {
  $colname_rsJourMois = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

$maxRows_rsSousCommission = 10;
$pageNum_rsSousCommission = 0;
if (isset($_GET['pageNum_rsSousCommission'])) {
  $pageNum_rsSousCommission = $_GET['pageNum_rsSousCommission'];
}
$startRow_rsSousCommission = $pageNum_rsSousCommission * $maxRows_rsSousCommission;

$colname1_rsSousCommission = "-1";
if (isset($_GET['aID'])) {
  $colname1_rsSousCommission = $_GET['aID'];
}
$colname2_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname2_rsSousCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom, banque_code, agence_code, numero_compte, cle,   membres_commissions_commission_id,  membres_fonctions_fonction_id, fonction_lib,   lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee,  montant,(nombre_jour * montant) as total																																																																																	FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, rib																																																																																			WHERE sessions.mois = mois.mois_id																																																																																			AND sessions.membres_personnes_personne_id = personnes.personne_id																																																																																AND sessions.membres_commissions_commission_id = commissions.commission_id																																																																																			AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id																																																																																			AND sessions.membres_personnes_personne_id = membres.personnes_personne_id																																																																																			AND commissions.nature_id = natures.nature_id																																																																																			AND commissions.type_commission_id = type_commissions.type_commission_id
AND personnes.personne_id = rib.personne_id																																																																																			AND commissions.localite_id = localites.localite_id  AND annee = %s AND commission_parent = %s GROUP BY membres_personnes_personne_id", GetSQLValueString($colname1_rsSousCommission, "text"),GetSQLValueString($colname2_rsSousCommission, "text"));
$query_limit_rsSousCommission = sprintf("%s LIMIT %d, %d", $query_rsSousCommission, $startRow_rsSousCommission, $maxRows_rsSousCommission);
$rsSousCommission = mysql_query($query_limit_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);

if (isset($_GET['totalRows_rsSousCommission'])) {
  $totalRows_rsSousCommission = $_GET['totalRows_rsSousCommission'];
} else {
  $all_rsSousCommission = mysql_query($query_rsSousCommission);
  $totalRows_rsSousCommission = mysql_num_rows($all_rsSousCommission);
}
$totalPages_rsSousCommission = ceil($totalRows_rsSousCommission/$maxRows_rsSousCommission)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois4 = "SELECT * FROM mois";
$rsMois4 = mysql_query($query_rsMois4, $MyFileConnect) or die(mysql_error());
$row_rsMois4 = mysql_fetch_assoc($rsMois4);
$totalRows_rsMois4 = mysql_num_rows($rsMois4);

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

$queryString_rsSousCommission = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSousCommission") == false && 
        stristr($param, "totalRows_rsSousCommission") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSousCommission = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSousCommission = sprintf("&totalRows_rsSousCommission=%d%s", $totalRows_rsSousCommission, $queryString_rsSousCommission);

$queryString_rsMois = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsMois") == false && 
        stristr($param, "totalRows_rsMois") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsMois = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsMois = sprintf("&totalRows_rsMois=%d%s", $totalRows_rsMois, $queryString_rsMois);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table>
  <tr>
    <th scope="row">&nbsp;</th>
    <td><?php echo $row_rsSessions['membres_commissions_commission_id']; ?>&nbsp;</td>
  </tr>
  <tr>
    <th align="right" scope="row">Commission : </th>
    <td><span class="welcome"><?php echo $row_rsSessions['commission_lib']; ?></span>&nbsp;</td>
  </tr>
  <tr>
    <th align="right" scope="row">Localite :</th>
    <td><?php echo $row_rsSessions['localite_lib']; ?>&nbsp;</td>
  </tr>
</table>
</BR>
<a href="#">Version Imprimable</a></BR>
<table border="1" class="std">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Fonction</th>
    <th>Releve d'Identite Bancaire</th>
 	<?php $count = 0; do { $count++;?>
    <th><?php echo ucfirst($row_rsMois['lib_mois']); ?></th>
    <?php } while ($row_rsMois = mysql_fetch_assoc($rsMois)); ?>
    <th>Montant</th>
    <th>Total</th>
  </tr>
  <?php $countj = 0; $somme2 = 0; do { $countj++; ?>
  <?php 
  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
    $query_rsMois2 = "SELECT * FROM mois";
    $rsMois2 = mysql_query($query_rsMois2, $MyFileConnect) or die(mysql_error());
    $row_rsMois2 = mysql_fetch_assoc($rsMois2);
    $totalRows_rsMois2 = mysql_num_rows($rsMois2); ?>
    <tr>
      <td><a href="todelete.php?recordID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>"> <?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsSessions['personne_nom'] . " " . $row_rsSessions['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo $row_rsSessions['fonction_lib']; ?>&nbsp; </td>
      <td><?php if ($row_rsSessions['banque_code'] == 'XXXXX'){ echo 'Absent'; } else { echo strtoupper($row_rsSessions['banque_code'] . "-" . $row_rsSessions['agence_code'] . "-" . $row_rsSessions['numero_compte'] . "-" . $row_rsSessions['cle']);} ?>&nbsp; </td>
      <?php $counti = 0; $somme = 0; do { $counti++; ?>
		<?php 
        mysql_select_db($database_MyFileConnect, $MyFileConnect);
        $query_rsTotal = sprintf("SELECT (Nombre_jour * montant) as Total 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 	
		AND sessions.membres_personnes_personne_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($row_rsSessions['membres_personnes_personne_id'], "text"),
		GetSQLValueString($row_rsMois2['mois_id'], 	"text"),
		GetSQLValueString($row_rsSessions['annee'], "text"));
        $rsTotal = mysql_query($query_rsTotal, $MyFileConnect) or die(mysql_error());
        $row_rsTotal = mysql_fetch_assoc($rsTotal);
        $totalRows_rsTotal = mysql_num_rows($rsTotal);
		?>
      <td align="right"><?php echo number_format($row_rsTotal['Total'],0,' ',' '); ?>&nbsp;<strong>
      <?php $somme = $somme + $row_rsTotal['Total']; ?>
      <?php 
	  	$comissionValue = $row_rsSessions['membres_commissions_commission_id'];
		$anneeValue = $row_rsSessions['annee']
	  
	  ?>
      </strong></td>
      <?php } while ($row_rsMois2 = mysql_fetch_assoc($rsMois2)); ?>

      <td align="right"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?>&nbsp; </td>
      <td align="right"><strong><?php echo number_format($somme,0,' ',' '); ?>&nbsp;</strong> </td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <?php  
	  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
        $query_rsMois3 = "SELECT * FROM mois";
        $rsMois3 = mysql_query($query_rsMois3, $MyFileConnect) or die(mysql_error());
        $row_rsMois3 = mysql_fetch_assoc($rsMois3);
        $totalRows_rsMois3 = mysql_num_rows($rsMois3); ?>
      <?php $counter = 0; do { $counter++; ?>  
      <?php
		$query_rsTotalMois = sprintf("SELECT sum(Nombre_jour * montant) as TotalMois 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 
		AND membres_commissions_commission_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($comissionValue, "int"),
		GetSQLValueString($row_rsMois3['mois_id'], 	"text"),
		GetSQLValueString($anneeValue, "text"));
		$rsTotalMois = mysql_query($query_rsTotalMois, $MyFileConnect) or die(mysql_error());
		$row_rsTotalMois = mysql_fetch_assoc($rsTotalMois);
		$totalRows_rsTotalMois = mysql_num_rows($rsTotalMois);
		?>  
      <td align="right"><strong>
        <?php 
	  		echo number_format($row_rsTotalMois['TotalMois'],0,' ',' '); 
			$Totaux = $Totaux + $row_rsTotalMois['TotalMois'];
	  ?>
      </strong></td>
      <?php } while ($row_rsMois3 = mysql_fetch_assoc($rsMois3)); ?>      <td align="right">&nbsp;</td>
      <td align="right"><?php echo number_format($Totaux,0,' ',' '); ?>&nbsp;</td>
    </tr>
</table>
<p>
</p>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSessions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, 0, $queryString_rsSessions); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, max(0, $pageNum_rsSessions - 1), $queryString_rsSessions); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessions < $totalPages_rsSessions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, min($totalPages_rsSessions, $pageNum_rsSessions + 1), $queryString_rsSessions); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSessions < $totalPages_rsSessions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, $totalPages_rsSessions, $queryString_rsSessions); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<p>Enregistrements <?php echo ($startRow_rsSessions + 1) ?> à <?php echo min($startRow_rsSessions + $maxRows_rsSessions, $totalRows_rsSessions) ?> sur <?php echo $totalRows_rsSessions ?></p>
<table border="1" class="std">
  <tr>
    <th nowrap="nowrap">ID</th>
    <th nowrap="nowrap">Nom</th>
    <th nowrap="nowrap">Fonction</th>    
    <th nowrap="nowrap">Releve d'Identite Bancaire</th>
    <?php $count = 0; do { $count++;?>
    <th><?php echo ucfirst($row_rsMois4['lib_mois']); ?></th>
    <?php } while ($row_rsMois4 = mysql_fetch_assoc($rsMois4)); ?>
    <th nowrap="nowrap">Montant</th>
    <th nowrap="nowrap">Total</th>
  </tr>
  <?php $countj = 0; $somme2 = 0; do { $countj++; ?>
  <?php
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$query_rsMois5 = "SELECT * FROM mois";
	$rsMois5 = mysql_query($query_rsMois5, $MyFileConnect) or die(mysql_error());
	$row_rsMois5 = mysql_fetch_assoc($rsMois5);
	$totalRows_rsMois5 = mysql_num_rows($rsMois5);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois6 = "SELECT * FROM mois";
$rsMois6 = mysql_query($query_rsMois6, $MyFileConnect) or die(mysql_error());
$row_rsMois6 = mysql_fetch_assoc($rsMois6);
$totalRows_rsMois6 = mysql_num_rows($rsMois6);
	?>
  
    <tr>
      <td nowrap="nowrap"><a href="todele.php?recordID=<?php echo $row_rsSousCommission['membres_personnes_personne_id']; ?>"> <?php echo $row_rsSousCommission['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td nowrap="nowrap"><?php echo strtoupper($row_rsSousCommission['personne_nom']) . " " . ucfirst($row_rsSousCommission['personne_prenom']); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSousCommission['fonction_lib']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php if ($row_rsSousCommission['banque_code'] == 'XXXXX'){ echo 'Absent'; } else { echo strtoupper($row_rsSousCommission['banque_code'] . "-" . $row_rsSousCommission['agence_code'] . "-" . $row_rsSousCommission['numero_compte'] . "-" . $row_rsSousCommission['cle']);} ?>&nbsp; </td>
      <?php $counti = 0; $somme2 = 0; do { $counti++; ?>
		<?php 
        mysql_select_db($database_MyFileConnect, $MyFileConnect);
        $query_rsTotal2 = sprintf("SELECT (Nombre_jour * montant) as Total 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 	
		AND sessions.membres_personnes_personne_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($row_rsSousCommission['membres_personnes_personne_id'], "text"),
		GetSQLValueString($row_rsMois5['mois_id'], 	"text"),
		GetSQLValueString($row_rsSousCommission['annee'], "text"));
        $rsTotal2 = mysql_query($query_rsTotal2, $MyFileConnect) or die(mysql_error());
        $row_rsTotal2 = mysql_fetch_assoc($rsTotal2);
        $totalRows_rsTotal2 = mysql_num_rows($rsTotal2);
		?>
    <td><?php echo number_format($row_rsTotal2['Total'],0,' ',' '); ?>&nbsp;<strong>
      <?php $somme2 = $somme2 + $row_rsTotal2['Total']; ?>
      <?php 
	  	$comissionValue2 = $row_rsSousCommission['membres_commissions_commission_id'];
		$anneeValue2 = $row_rsSousCommission['annee']
	  ?>
      </strong></td>
    <?php } while ($row_rsMois5 = mysql_fetch_assoc($rsMois5)); ?>
      <td nowrap="nowrap"><?php echo number_format($row_rsSousCommission['montant'],0,' ',' '); ?>&nbsp; </td>
      <td align="right" nowrap="nowrap"><?php echo number_format($somme2,0,' ',' '); ?>&nbsp;&nbsp; </td>
    </tr>
    <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
    <tr>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $count = 0; do { $count++;?>
      <?php
		$query_rsTotalMois2 = sprintf("SELECT sum(Nombre_jour * montant) as TotalMois 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 
		AND membres_commissions_commission_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($comissionValue, "int"),
		GetSQLValueString($row_rsMois6['mois_id'], 	"text"),
		GetSQLValueString($anneeValue, "text"));
		$rsTotalMois2 = mysql_query($query_rsTotalMois2, $MyFileConnect) or die(mysql_error());
		$row_rsTotalMois2 = mysql_fetch_assoc($rsTotalMois2);
		$totalRows_rsTotalMois2 = mysql_num_rows($rsTotalMois2);
		?> 
      <td><strong>
        <?php 
	  		echo number_format($row_rsTotalMois2['TotalMois'],0,' ',' '); 
			$Totaux = $Totaux + $row_rsTotalMois2['TotalMois'];
	  ?>
      </strong></td>
      <?php } while ($row_rsMois6 = mysql_fetch_assoc($rsMois6)); ?>
      <td nowrap="nowrap">&nbsp;</td>
      <td align="right" nowrap="nowrap"><?php echo number_format($Totaux,0,' ',' '); ?></td>
    </tr>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSousCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, 0, $queryString_rsSousCommission); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSousCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, max(0, $pageNum_rsSousCommission - 1), $queryString_rsSousCommission); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSousCommission < $totalPages_rsSousCommission) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, min($totalPages_rsSousCommission, $pageNum_rsSousCommission + 1), $queryString_rsSousCommission); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSousCommission < $totalPages_rsSousCommission) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, $totalPages_rsSousCommission, $queryString_rsSousCommission); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsSousCommission + 1) ?> à <?php echo min($startRow_rsSousCommission + $maxRows_rsSousCommission, $totalRows_rsSousCommission) ?> sur <?php echo $totalRows_rsSousCommission ?>
</p>
<p>&nbsp; </p>
<p>&nbsp;
</body>
</html>
<?php
mysql_free_result($rsSessions);

mysql_free_result($rsJourMois);

mysql_free_result($rsMois);

mysql_free_result($rsSousCommission);

mysql_free_result($rsMois4);

mysql_free_result($rsMois5);

mysql_free_result($rsMois6);

mysql_free_result($rsTotalMois);

mysql_free_result($rsTotal);

mysql_free_result($rsMois2);
?>
