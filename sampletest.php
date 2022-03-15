<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require('fonction_db.php');
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

$maxRows_rsCommissions = 10;
$pageNum_rsCommissions = 0;
if (isset($_GET['pageNum_rsCommissions'])) {
  $pageNum_rsCommissions = $_GET['pageNum_rsCommissions'];
}
$startRow_rsCommissions = $pageNum_rsCommissions * $maxRows_rsCommissions;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = "SELECT * FROM commissions WHERE display = '1'";
$query_limit_rsCommissions = sprintf("%s LIMIT %d, %d", $query_rsCommissions, $startRow_rsCommissions, $maxRows_rsCommissions);
$rsCommissions = mysql_query($query_limit_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);

if (isset($_GET['totalRows_rsCommissions'])) {
  $totalRows_rsCommissions = $_GET['totalRows_rsCommissions'];
} else {
  $all_rsCommissions = mysql_query($query_rsCommissions);
  $totalRows_rsCommissions = mysql_num_rows($all_rsCommissions);
}
$totalPages_rsCommissions = ceil($totalRows_rsCommissions/$maxRows_rsCommissions)-1;

$queryString_rsCommissions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsCommissions") == false && 
        stristr($param, "totalRows_rsCommissions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsCommissions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsCommissions = sprintf("&totalRows_rsCommissions=%d%s", $totalRows_rsCommissions, $queryString_rsCommissions);
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
    <td>ID</td>
    <td>Localite</td>
    <td>Libelle</td>
    <td>Membres</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="sample108.php?recordID=<?php echo $row_rsCommissions['commission_id']; ?>"> <?php echo $row_rsCommissions['commission_id']; ?>&nbsp; </a></td>
      <td><?php echo MinmapDB2::getInstance()->get_1_col_lib_by_id(localites, localite_lib, localite_id, $row_rsCommissions['localite_id']); ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['commission_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['membre_insert']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsCommissions = mysql_fetch_assoc($rsCommissions)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, 0, $queryString_rsCommissions); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, max(0, $pageNum_rsCommissions - 1), $queryString_rsCommissions); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsCommissions < $totalPages_rsCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, min($totalPages_rsCommissions, $pageNum_rsCommissions + 1), $queryString_rsCommissions); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsCommissions < $totalPages_rsCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, $totalPages_rsCommissions, $queryString_rsCommissions); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsCommissions + 1) ?> à <?php echo min($startRow_rsCommissions + $maxRows_rsCommissions, $totalRows_rsCommissions) ?> sur <?php echo $totalRows_rsCommissions ?>
</body>
</html>
<?php
mysql_free_result($rsCommissions);
?>
