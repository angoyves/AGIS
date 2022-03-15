<?php require_once('Connections/MyFileConnect.php'); ?>
<?php
	  require_once('includes/db.php');
	  require_once('fonction_db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
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

$maxRows_SelSession = 20;
if(isset($_GET['nbr_reg'])){
  $maxRows_SelSession = $_GET['nbr_reg'];
}
$pageNum_SelSession = 0;
if (isset($_GET['pageNum_SelSession'])) {
  $pageNum_SelSession = $_GET['pageNum_SelSession'];
}
$startRow_SelSession = $pageNum_SelSession * $maxRows_SelSession;

$colname_rsPersonne = $_GET['txtChamp'];
$text_rsPersonne = $_GET['txtSearch'];

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_GET['txtChamp']) || isset($_GET['txtSearch'])) {
$query_SelSession = sprintf("SELECT distinct(membres_personnes_personne_id), personne_nom, personne_prenom, type_personne_id, localite_id, date_creation, personnes.dateUpdate  from sessions, personnes where sessions.membres_personnes_personne_id = personnes.personne_id
AND $colname_rsPersonne LIKE %s", GetSQLValueString("%" . $text_rsPersonne . "%", "text"));
$query_limit_SelSession = sprintf("%s LIMIT %d, %d", $query_SelSession, $startRow_SelSession, $maxRows_SelSession);
$SelSession = mysql_query($query_limit_SelSession, $MyFileConnect) or die(mysql_error());
$row_SelSession = mysql_fetch_assoc($SelSession);
} else {
$query_SelSession = "SELECT distinct(membres_personnes_personne_id), personne_nom, personne_prenom, type_personne_id, localite_id, date_creation, personnes.dateUpdate  from sessions, personnes where sessions.membres_personnes_personne_id = personnes.personne_id";
$query_limit_SelSession = sprintf("%s LIMIT %d, %d", $query_SelSession, $startRow_SelSession, $maxRows_SelSession);
$SelSession = mysql_query($query_limit_SelSession, $MyFileConnect) or die(mysql_error());
$row_SelSession = mysql_fetch_assoc($SelSession);
}

if (isset($_GET['totalRows_SelSession'])) {
  $totalRows_SelSession = $_GET['totalRows_SelSession'];
} else {
  $all_SelSession = mysql_query($query_SelSession);
  $totalRows_SelSession = mysql_num_rows($all_SelSession);
}
$totalPages_SelSession = ceil($totalRows_SelSession/$maxRows_SelSession)-1;

$queryString_SelSession = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_SelSession") == false && 
        stristr($param, "totalRows_SelSession") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_SelSession = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_SelSession = sprintf("&totalRows_SelSession=%d%s", $totalRows_SelSession, $queryString_SelSession);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<form id="form1" name="form1" method="get" action="sample99.php">
  <table border="0" class="std">
    <tr>
      <td><strong>Rechercher Par :</strong></td>
      <td><select name="txtChamp" id="txtChamp">
        <option value="personne_nom" <?php if (!(strcmp("personne_nom", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Nom</option>
        <option value="personne_id" <?php if (!(strcmp("personne_id", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>ID</option>
        <option value="personne_prenom" <?php if (!(strcmp("personne_prenom", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Prenom</option>
      </select></td>
      <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo htmlentities($_GET['txtSearch'], ENT_COMPAT, 'utf-8'); ?>" />
      <input name="menuID" type="hidden" id="menuID" value="<?php echo $_GET['menuID']; ?>" />
      <input name="action" type="hidden" id="action" value="<?php echo $_GET['action']; ?>" /></td>
      <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
    </tr>
    <tr>
      <td><?php echo $maxRows_rsAffichePerson; ?>&nbsp;<strong>Commission :</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="reset" name="button" id="button" value="Réinitialiser" />
      </label></td>
    </tr>
    <tr>
      <td colspan="4" align="right">Nombre d'enregistrements
      <select name="nbr_reg">
        <option value="10" <?php if (!(strcmp("10", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="30" <?php if (!(strcmp("30", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="60" <?php if (!(strcmp("60", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>60</option>
        <option value="100" <?php if (!(strcmp("100", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>100</option>
      </select>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><?php if (isset($_GET['txtSearch'])) { ?>
        Enregistrements trouvés pour votre recherche &quot; <strong> <?php echo $_GET['txtSearch']; ?></strong> &quot; dans la colonne <strong> <?php echo $_GET['txtChamp']; ?></strong>
        <?php } ?></td>
    </tr>
  </table>
</form>
<table border="1" align="center">
  <tr>
    <th>N°</th>
    <th>Nom et Prenom</th>
    <th>Type Personne</th>
    <th>Localite</th>
    <th>Date Creation</th>
    <th>Date mis a jour</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="sample98.php?recordID=<?php echo $row_SelSession['membres_personnes_personne_id']; ?>"> <?php echo $row_SelSession['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td><?php echo strtoupper(MinmapDB2::getInstance()->get_2_col_lib_by_id(personnes, personne_nom, personne_prenom, personne_id, $row_SelSession['membres_personnes_personne_id'])); ?> &nbsp; </td>
      <td><?php echo strtoupper(MinmapDB2::getInstance()->get_1_col_lib_by_id(type_personnes, type_personne_lib, type_personne_id, $row_SelSession['type_personne_id'])); ?>&nbsp;</td>
      <td><?php echo strtoupper(MinmapDB2::getInstance()->get_1_col_lib_by_id(localites, localite_lib, localite_id, $row_SelSession['localite_id'])); ?>&nbsp;</td>
      <td><?php echo $row_SelSession['date_creation']; ?>&nbsp;</td>
      <td><?php echo $row_SelSession['dateUpdate']; ?>&nbsp;</td>
    </tr>
    <?php } while ($row_SelSession = mysql_fetch_assoc($SelSession)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_SelSession > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_SelSession=%d%s", $currentPage, 0, $queryString_SelSession); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_SelSession > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_SelSession=%d%s", $currentPage, max(0, $pageNum_SelSession - 1), $queryString_SelSession); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_SelSession < $totalPages_SelSession) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_SelSession=%d%s", $currentPage, min($totalPages_SelSession, $pageNum_SelSession + 1), $queryString_SelSession); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_SelSession < $totalPages_SelSession) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_SelSession=%d%s", $currentPage, $totalPages_SelSession, $queryString_SelSession); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_SelSession + 1) ?> à <?php echo min($startRow_SelSession + $maxRows_SelSession, $totalRows_SelSession) ?> sur <?php echo $totalRows_SelSession ?>
</body>
</html>
<?php
mysql_free_result($SelSession);
?>
