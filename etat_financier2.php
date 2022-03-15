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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO recherches (search_id, txtSearch, monthTo, monthAt, yearTo, yearAt, dateCreate) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['search_id'], "int"),
                       GetSQLValueString($_POST['txtSearch'], "text"),
                       GetSQLValueString($_POST['monthTo'], "text"),
                       GetSQLValueString($_POST['monthAt'], "text"),
                       GetSQLValueString($_POST['yearTo'], "text"),
                       GetSQLValueString($_POST['yearAt'], "text"),
                       GetSQLValueString($_POST['dateCreate'], "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "etat_financier2.php?txtSearch=".$_POST['txtSearch']."&monthTo=".$_POST['monthTo']."&monthAt=".$_POST['monthAt']."&yearTo=".$_POST['yearTo'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelectMonth = "SELECT mois_id, lib_mois FROM mois";
$rsSelectMonth = mysql_query($query_rsSelectMonth, $MyFileConnect) or die(mysql_error());
$row_rsSelectMonth = mysql_fetch_assoc($rsSelectMonth);
$totalRows_rsSelectMonth = mysql_num_rows($rsSelectMonth);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsYear = "SELECT * FROM annee";
$rsYear = mysql_query($query_rsYear, $MyFileConnect) or die(mysql_error());
$row_rsYear = mysql_fetch_assoc($rsYear);
$totalRows_rsYear = mysql_num_rows($rsYear);

$maxRows_rsSessions = 25;
$pageNum_rsSessions = 0;
if (isset($_GET['pageNum_rsSessions'])) {
  $pageNum_rsSessions = $_GET['pageNum_rsSessions'];
}
$startRow_rsSessions = $pageNum_rsSessions * $maxRows_rsSessions;

$colname1_rsSessions = "-1";
if (isset($_GET['monthTo'])) {
  $colname1_rsSessions = $_GET['monthTo'];
}
$colname3_rsSessions = "-1";
if (isset($_GET['yearTo'])) {
  $colname3_rsSessions = $_GET['yearTo'];
}
$colname2_rsSessions = "-1";
if (isset($_GET['monthAt'])) {
  $colname2_rsSessions = $_GET['monthAt'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT membres_commissions_commission_id, commission_lib, membres_personnes_personne_id, personne_nom, membres_fonctions_fonction_id, fonction_lib, nombre_jour, jour, montant, (nombre_jour * montant) as total FROM sessions, commissions, personnes, fonctions, membres WHERE sessions.membres_commissions_commission_id = commissions.commission_id AND sessions.membres_personnes_personne_id = personnes.personne_id AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id AND membres.commissions_commission_id = commissions.commission_id AND membres.personnes_personne_id = personnes.personne_id AND membres.fonctions_fonction_id = fonctions.fonction_id AND mois BETWEEN %s AND %s AND annee = %s ORDER BY sessions.dateCreation, annee, mois, fonctions.fonction_id, commissions.commission_id", GetSQLValueString($colname1_rsSessions, "text"),GetSQLValueString($colname2_rsSessions, "text"),GetSQLValueString($colname3_rsSessions, "text"));
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Rechercher:</td>
      <td><input type="text" name="txtSearch" value="<?php echo $_POST['txtSearch']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mois debut:</td>
      <td><select name="monthTo">
        <option value=""  <?php if (!(strcmp("", $_POST['monthTo']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsSelectMonth['mois_id']?>"<?php if (!(strcmp($row_rsSelectMonth['mois_id'], $_POST['monthTo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsSelectMonth['lib_mois']?></option>
        <?php
} while ($row_rsSelectMonth = mysql_fetch_assoc($rsSelectMonth));
  $rows = mysql_num_rows($rsSelectMonth);
  if($rows > 0) {
      mysql_data_seek($rsSelectMonth, 0);
	  $row_rsSelectMonth = mysql_fetch_assoc($rsSelectMonth);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mois fin:</td>
      <td><select name="monthAt">
        <option value=""  <?php if (!(strcmp("", $_POST['monthAt']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsSelectMonth['mois_id']?>"<?php if (!(strcmp($row_rsSelectMonth['mois_id'], $_POST['monthAt']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsSelectMonth['lib_mois']?></option>
        <?php
} while ($row_rsSelectMonth = mysql_fetch_assoc($rsSelectMonth));
  $rows = mysql_num_rows($rsSelectMonth);
  if($rows > 0) {
      mysql_data_seek($rsSelectMonth, 0);
	  $row_rsSelectMonth = mysql_fetch_assoc($rsSelectMonth);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Exercice:</td>
      <td><select name="yearTo">
        <option value=""  <?php if (!(strcmp("", $_POST['yearTo']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsYear['lib_annee']?>"<?php if (!(strcmp($row_rsYear['lib_annee'], $_POST['yearTo']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsYear['lib_annee']?></option>
        <?php
} while ($row_rsYear = mysql_fetch_assoc($rsYear));
  $rows = mysql_num_rows($rsYear);
  if($rows > 0) {
      mysql_data_seek($rsYear, 0);
	  $row_rsYear = mysql_fetch_assoc($rsYear);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Exercice fin:</td>
      <td><input type="text" name="yearAt" value="<?php echo $_POST['yearAt']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="hidden" name="dateCreate" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Ins&eacute;rer un enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="search_id" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;
<table border="1" align="center">
  <tr>
    <th nowrap="nowrap">ID</th>
    <th nowrap="nowrap">Commission</th>
    <th nowrap="nowrap">Nom</th>
    <th nowrap="nowrap">Fonction</th>
    <th nowrap="nowrap">Sessions</th>
    <th nowrap="nowrap">Montant</th>
    <th nowrap="nowrap">Total</th>
  </tr>
  <?php do { ?>
    <tr>
      <td nowrap="nowrap"><a href="todelete.php?recordID=<?php echo $row_rsSessions['membres_commissions_commission_id']; ?>"> <?php echo $row_rsSessions['membres_commissions_commission_id']; ?>&nbsp; </a></td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['commission_lib']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['personne_nom']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['fonction_lib']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_jour']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['montant']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['total']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
</table>
<br />
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
Enregistrements <?php echo ($startRow_rsSessions + 1) ?> à <?php echo min($startRow_rsSessions + $maxRows_rsSessions, $totalRows_rsSessions) ?> sur <?php echo $totalRows_rsSessions ?>
</p>
</body>
</html>
<?php
mysql_free_result($rsSelectMonth);

mysql_free_result($rsYear);

mysql_free_result($rsSessions);
?>
