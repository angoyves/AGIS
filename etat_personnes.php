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

$maxRows_rsSessionState = 40;
$pageNum_rsSessionState = 0;
if (isset($_GET['pageNum_rsSessionState'])) {
  $pageNum_rsSessionState = $_GET['pageNum_rsSessionState'];
}
$startRow_rsSessionState = $pageNum_rsSessionState * $maxRows_rsSessionState;

$colname_rsSessionState = "2014";
if (isset($_POST['aID'])) {
  $colname_rsSessionState = $_POST['aID'];
}
$colname1_rsSessionState = "1";
if (isset($_POST['m1ID'])) {
  $colname1_rsSessionState = $_POST['m1ID'];
}
$colname2_rsSessionState = "12";
if (isset($_POST['m2ID'])) {
  $colname2_rsSessionState = $_POST['m2ID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionState = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jours, jour, mois, annee, montant, (sum(nombre_jour) * montant) as total, banque_code, agence_code, numero_compte, cle FROM commissions, membres, personnes,  fonctions, sessions, rib WHERE membres.commissions_commission_id = commissions.commission_id  AND membres.personnes_personne_id = personnes.personne_id  AND membres.personnes_personne_id = rib.personne_id  AND membres.fonctions_fonction_id = fonctions.fonction_id  AND sessions.membres_commissions_commission_id = membres.commissions_commission_id  AND sessions.membres_personnes_personne_id = membres.personnes_personne_id   AND mois BETWEEN %s AND %s AND annee = %s GROUP BY personne_id ORDER BY annee, mois, fonctions.fonction_id", GetSQLValueString($colname1_rsSessionState, "int"),GetSQLValueString($colname2_rsSessionState, "int"),GetSQLValueString($colname_rsSessionState, "text"));
$query_limit_rsSessionState = sprintf("%s LIMIT %d, %d", $query_rsSessionState, $startRow_rsSessionState, $maxRows_rsSessionState);
$rsSessionState = mysql_query($query_limit_rsSessionState, $MyFileConnect) or die(mysql_error());
$row_rsSessionState = mysql_fetch_assoc($rsSessionState);

if (isset($_GET['totalRows_rsSessionState'])) {
  $totalRows_rsSessionState = $_GET['totalRows_rsSessionState'];
} else {
  $all_rsSessionState = mysql_query($query_rsSessionState);
  $totalRows_rsSessionState = mysql_num_rows($all_rsSessionState);
}
$totalPages_rsSessionState = ceil($totalRows_rsSessionState/$maxRows_rsSessionState)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

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

$queryString_rsSessionState = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSessionState") == false && 
        stristr($param, "totalRows_rsSessionState") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSessionState = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSessionState = sprintf("&totalRows_rsSessionState=%d%s", $totalRows_rsSessionState, $queryString_rsSessionState);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form name="SearchForm" action="" method="post">
<table border="0">
  <tr>
    <td>Rechercher</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Année</td>
    <td><select name="aID" id="aID">
      <?php
do {  
?>
      <option value="<?php echo $row_rsAnnee['lib_annee']?>"<?php if (!(strcmp($_POST['aID'], $row_rsAnnee['lib_annee']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Période allant de:</td>
    <td><select name="m1ID" id="m1ID">
      <?php
do {  
?>
      <option value="<?php echo $row_rsMois1['mois_id']?>"<?php if (!(strcmp($row_rsMois1['mois_id'], $row_rsAnnee['lib_annee']))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper($row_rsMois1['lib_mois']) ?></option>
      <?php
} while ($row_rsMois1 = mysql_fetch_assoc($rsMois1));
  $rows = mysql_num_rows($rsMois1);
  if($rows > 0) {
      mysql_data_seek($rsMois1, 0);
	  $row_rsMois1 = mysql_fetch_assoc($rsMois1);
  }
?>
    </select></td>
    <td>à</td>
    <td><select name="m2ID" id="m2ID">
      <?php
do {  
?>
      <option value="<?php echo $row_rsMois2['mois_id']?>"<?php if (!(strcmp($row_rsMois2['mois_id'], $row_rsMois2['mois_id']))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper($row_rsMois2['lib_mois']) ?></option>
      <?php
} while ($row_rsMois2 = mysql_fetch_assoc($rsMois2));
  $rows = mysql_num_rows($rsMois2);
  if($rows > 0) {
      mysql_data_seek($rsMois2, 0);
	  $row_rsMois2 = mysql_fetch_assoc($rsMois2);
  }
?>
    </select></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="3"><input type="submit" name="button2" id="button2" value="Envoyer" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
<form name="OtherSearch" action="" method="post" >
<table border="1" align="center">
  <tr align="center">
    <td><strong>ID</strong></td>
    <td><strong>Commission
      <input type="text" name="textfield" id="textfield" />
    </strong></td>
    <td><strong>Noms et Prenoms
      <input name="textfield3" type="text" id="textfield3" />
    </strong></td>
    <td><strong>Fonction
      <input type="text" name="textfield4" id="textfield4" />
    </strong></td>
    <td><strong>Nombre de sessions</strong></td>
    <td><strong>Montant</strong></td>
    <td><strong>Total</strong></td>
    <td><strong>Banque code</strong></td>
    <td><strong>Agence code</strong></td>
    <td><strong>Numero de compte</strong></td>
    <td><strong>cle</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td nowrap="nowrap"><a href="etat_sessions.php?perID=<?php echo $row_rsSessionState['personne_id'] ?>&aID=<?php echo $_POST['aID'] ?>&m1ID=<?php echo $_POST['m1ID'] ?>&m2ID=<?php echo $_POST['m2ID'] ?>"> <?php echo $row_rsSessionState['personne_id'] ?></a></td>
      <td><?php echo $row_rsSessionState['commission_lib']; ?>&nbsp; </td>
      <td><?php echo htmlentities($row_rsSessionState['personne_nom']); ?>&nbsp; </td>
      <td><?php echo $row_rsSessionState['fonction_lib']; ?>&nbsp; </td>
      <td align="right"><?php echo $row_rsSessionState['nombre_jours']; ?>&nbsp; </td>
      <td align="right"><?php echo $row_rsSessionState['montant']; ?>&nbsp; </td>
      <td><?php echo $row_rsSessionState['total']; ?>&nbsp; </td>
      <td><?php echo ($row_rsSessionState['banque_code']=="XXXXX")?(""):($row_rsSessionState['banque_code']); ?>&nbsp; </td>
      <td><?php echo ($row_rsSessionState['agence_code']=="xxxxx")?(""):($row_rsSessionState['agence_code']); ?>&nbsp; </td>
      <td><?php echo ($row_rsSessionState['numero_compte']=="xxxxxxxxxxx")?(""):($row_rsSessionState['numero_compte']); ?>&nbsp; </td>
      <td><?php echo ($row_rsSessionState['cle']=="xx")?(""):($row_rsSessionState['cle']); ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsSessionState = mysql_fetch_assoc($rsSessionState)); ?>
    <tr>
      <td colspan="11" nowrap="nowrap"><input type="submit" name="button" id="button" value="Rechercher..." /></td>
    </tr>

</table>
</form>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSessionState > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessionState=%d%s", $currentPage, 0, $queryString_rsSessionState); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessionState > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessionState=%d%s", $currentPage, max(0, $pageNum_rsSessionState - 1), $queryString_rsSessionState); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessionState < $totalPages_rsSessionState) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessionState=%d%s", $currentPage, min($totalPages_rsSessionState, $pageNum_rsSessionState + 1), $queryString_rsSessionState); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSessionState < $totalPages_rsSessionState) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessionState=%d%s", $currentPage, $totalPages_rsSessionState, $queryString_rsSessionState); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsSessionState + 1) ?> à <?php echo min($startRow_rsSessionState + $maxRows_rsSessionState, $totalRows_rsSessionState) ?> sur <?php echo $totalRows_rsSessionState ?>
</body>
</html>
<?php
mysql_free_result($rsSessionState);

mysql_free_result($rsAnnee);

mysql_free_result($rsMois1);

mysql_free_result($rsMois2);
?>
