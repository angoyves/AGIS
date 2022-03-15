<?php 
	require_once('Connections/MyFileConnect.php'); 
	require_once('includes/db.php');
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

$maxRows_rsPersonnes = 10;
$pageNum_rsPersonnes = 0;
if (isset($_GET['pageNum_rsPersonnes'])) {
  $pageNum_rsPersonnes = $_GET['pageNum_rsPersonnes'];
}
$startRow_rsPersonnes = $pageNum_rsPersonnes * $maxRows_rsPersonnes;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_POST["MM_search"])) && ($_POST["MM_search"] == "form1")) {
  $query_rsPersonnes = sprintf("SELECT * FROM personnes WHERE add_commission = '2' 
  AND " . $_POST['txtColumn'] . " like %s",GetSQLValueString("%" . $_POST['txtSearch'] . "%", "text"));
} else {
$query_rsPersonnes = "SELECT * FROM personnes WHERE add_commission = '2' ORDER BY personne_nom ASC";
}
$query_limit_rsPersonnes = sprintf("%s LIMIT %d, %d", $query_rsPersonnes, $startRow_rsPersonnes, $maxRows_rsPersonnes);
$rsPersonnes = mysql_query($query_limit_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);

if (isset($_GET['totalRows_rsPersonnes'])) {
  $totalRows_rsPersonnes = $_GET['totalRows_rsPersonnes'];
} else {
  $all_rsPersonnes = mysql_query($query_rsPersonnes);
  $totalRows_rsPersonnes = mysql_num_rows($all_rsPersonnes);
}
$totalPages_rsPersonnes = ceil($totalRows_rsPersonnes/$maxRows_rsPersonnes)-1;

$queryString_rsPersonnes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPersonnes") == false && 
        stristr($param, "totalRows_rsPersonnes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPersonnes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPersonnes = sprintf("&totalRows_rsPersonnes=%d%s", $totalRows_rsPersonnes, $queryString_rsPersonnes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
<table width="200" border="0">
  <tr>
    <th scope="row">Recherche:</th>
    <td>
      <label for="select">Champ</label>
      <select name="txtColumn" id="txtColumn">
        <option>Select...</option>
        <option value="personne_nom">Nom</option>
        <option value="personne_prenom">Prenom</option>
</select>
    </td>
    <td><label for="txtSearch">Mot rechercher</label>
      <input type="text" name="txtSearch" id="txtSearch" /></td>
    <td><input type="submit" name="btnSearch" id="btnSearch" value="Rechercher" /></td>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" scope="row">Resultat de la recherche de <strong>"<?php echo $_POST['txtSearch'] ?>"</strong> dans la colonne <strong>"<?php echo $_POST['txtColumn'] ?>"</strong></td>
    </tr>
</table>
<input type="hidden" name="MM_search" value="form1" />
</form>
<p>&nbsp;</p>
<table border="1" align="center">
  <tr>
    <th>Code</th>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Grade</th>
    <th>Telephone</th>
    <th>Specialisation</th>
    <th>Qualification</th>
    <th>Structure</th>
    <th>Sous groupe</th>
    <th>Fonction</th>
    <th>Domaine</th>
    <th>Type Personne</th>
    <th>Localite</th>
    <th>Membre commission</th>
    <th>Expert</th>
    <th>Actif</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="sample84.php?persID=<?php echo $row_rsPersonnes['personne_id']; ?>"> <?php echo $row_rsPersonnes['personne_id']; ?>&nbsp; </a></td>
      <td nowrap="nowrap"><?php echo $row_rsPersonnes['personne_nom']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsPersonnes['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['personne_grade']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsPersonnes['personne_telephone']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['personne_specialisation']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['personne_qualification']; ?>&nbsp; </td>
      <td><?php echo MinmapDB::getInstance()->get_structure_lib_by_id($row_rsPersonnes['structure_id']); ?>&nbsp; </td>
      <td><?php echo MinmapDB::getInstance()->get_Sous_groupe_lib_by_id($row_rsPersonnes['sous_groupe_id']); ?>&nbsp; </td>
      <td><?php echo MinmapDB::getInstance()->get_fonction_lib_by_id($row_rsPersonnes['fonction_id']); ?>&nbsp;</td>
      <td><?php echo MinmapDB::getInstance()->get_domaine_lib_by_id($row_rsPersonnes['domaine_id']); ?>&nbsp; </td>
      <td>
        <?php echo MinmapDB::getInstance()->get_type_personne_lib_by_id($row_rsPersonnes['type_personne_id']); ?>&nbsp; 
      </td>
      <td>
	  <?php echo MinmapDB::getInstance()->get_localite_lib_by_localite_id($row_rsPersonnes['localite_id']); ?>&nbsp; </td>
      <td>
        <?php if (isset($row_rsPersonnes['add_commission']) && $row_rsPersonnes['add_commission']==2) { ?>
        <img src="images/img/s_okay.png" width="16" height="16" />
        <?php } else { ?>
        <img src="images/img/s_error.png" width="16" height="16" />
        <?php } ?>
      &nbsp; &nbsp; </td>
      <td>
        <?php if (isset($row_rsPersonnes['add_rep']) && $row_rsPersonnes['add_rep']==1) { ?>
        <img src="images/img/s_okay.png" width="16" height="16" />
        <?php } else { ?>
        <img src="images/img/s_error.png" width="16" height="16" />
      <?php } ?>&nbsp; </td>
      <td>
        <?php if (isset($row_rsPersonnes['display']) && $row_rsPersonnes['display']==1) { ?>
        <img src="images/img/s_okay.png" width="16" height="16" />
        <?php } else { ?>
        <img src="images/img/s_error.png" width="16" height="16" />
        <?php } ?>
      </td>
    </tr>
    <?php } while ($row_rsPersonnes = mysql_fetch_assoc($rsPersonnes)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, 0, $queryString_rsPersonnes); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, max(0, $pageNum_rsPersonnes - 1), $queryString_rsPersonnes); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, min($totalPages_rsPersonnes, $pageNum_rsPersonnes + 1), $queryString_rsPersonnes); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, $totalPages_rsPersonnes, $queryString_rsPersonnes); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsPersonnes + 1) ?> à <?php echo min($startRow_rsPersonnes + $maxRows_rsPersonnes, $totalRows_rsPersonnes) ?> sur <?php echo $totalRows_rsPersonnes ?>
</body>
</html>
<?php
mysql_free_result($rsPersonnes);
?>
