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

$maxRows_rsLocalites = 50;
$pageNum_rsLocalites = 0;
if (isset($_GET['pageNum_rsLocalites'])) {
  $pageNum_rsLocalites = $_GET['pageNum_rsLocalites'];
}
$startRow_rsLocalites = $pageNum_rsLocalites * $maxRows_rsLocalites;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocalites = "SELECT localite_id, localite_lib, region_id, type_localite_id FROM localites WHERE display = '1' ORDER BY region_id ASC";
$query_limit_rsLocalites = sprintf("%s LIMIT %d, %d", $query_rsLocalites, $startRow_rsLocalites, $maxRows_rsLocalites);
$rsLocalites = mysql_query($query_limit_rsLocalites, $MyFileConnect) or die(mysql_error());
$row_rsLocalites = mysql_fetch_assoc($rsLocalites);

if (isset($_GET['totalRows_rsLocalites'])) {
  $totalRows_rsLocalites = $_GET['totalRows_rsLocalites'];
} else {
  $all_rsLocalites = mysql_query($query_rsLocalites);
  $totalRows_rsLocalites = mysql_num_rows($all_rsLocalites);
}
$totalPages_rsLocalites = ceil($totalRows_rsLocalites/$maxRows_rsLocalites)-1;

$queryString_rsLocalites = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsLocalites") == false && 
        stristr($param, "totalRows_rsLocalites") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsLocalites = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsLocalites = sprintf("&totalRows_rsLocalites=%d%s", $totalRows_rsLocalites, $queryString_rsLocalites);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <th>Numero</th>
    <th>Localite</th>
    <th>Region</th>
    <th>Type localité</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="localites_details.php?recordID=<?php echo $row_rsLocalites['localite_id']; ?>"> <?php echo $row_rsLocalites['localite_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsLocalites['localite_lib']; ?>&nbsp; </td>
      <td><?php           
			$showGoTo = "localites_upd.php?locID=".$row_rsLocalites['localite_id'];
	 	  ?>        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><?php echo MinmapDB::getInstance()->get_lib_by_id(regions, region_lib, region_id, $row_rsLocalites['region_id']).MinmapDB::getInstance()->get_region_lib_by_region_id($row_rsLocalites['region_id']); ?><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a>&nbsp;</td>
      <td><?php echo MinmapDB::getInstance()->get_type_localte_lib_by_type_localite_id($row_rsLocalites['type_localite_id']); ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsLocalites = mysql_fetch_assoc($rsLocalites)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsLocalites > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsLocalites=%d%s", $currentPage, 0, $queryString_rsLocalites); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsLocalites > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsLocalites=%d%s", $currentPage, max(0, $pageNum_rsLocalites - 1), $queryString_rsLocalites); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsLocalites < $totalPages_rsLocalites) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsLocalites=%d%s", $currentPage, min($totalPages_rsLocalites, $pageNum_rsLocalites + 1), $queryString_rsLocalites); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsLocalites < $totalPages_rsLocalites) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsLocalites=%d%s", $currentPage, $totalPages_rsLocalites, $queryString_rsLocalites); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsLocalites + 1) ?> à <?php echo min($startRow_rsLocalites + $maxRows_rsLocalites, $totalRows_rsLocalites) ?> sur <?php echo $totalRows_rsLocalites ?>
</body>
</html>
<?php
mysql_free_result($rsLocalites);
?>
