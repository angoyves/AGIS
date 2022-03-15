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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsCommissions = 10;
$pageNum_rsCommissions = 0;
if (isset($_GET['pageNum_rsCommissions'])) {
  $pageNum_rsCommissions = $_GET['pageNum_rsCommissions'];
}
$startRow_rsCommissions = $pageNum_rsCommissions * $maxRows_rsCommissions;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = "SELECT * FROM commissions ORDER BY type_commission_id ASC";
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
    <td><strong>ID</strong></td>
    <td><strong>localite_id</strong></td>
    <td><strong>type_commission_id</strong></td>
    <td><strong>nature_id</strong></td>
    <td><strong>structure_id</strong></td>
    <td><strong>commission_lib</strong></td>
    <td><strong>montant_cumul</strong></td>
    <td><strong>membre_insert</strong></td>
    <td><strong>dateCreation</strong></td>
    <td><strong>dateUpdate</strong></td>
    <td><strong>display</strong></td>
    <td><strong>user_id</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php           
			$showGoTo = "commission_loc_upd.php?locID=".$row_rsCommissions['localite_id']."&comID=".$row_rsCommissions['commission_id']."&regID=".$row_rsCommissions['region_id'];
	 	  ?> <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"> <?php echo $row_rsCommissions['commission_id']; ?></a>&nbsp;</td>
      <td><?php           
			$showGoTo = "localites_upd.php?locID=".$row_rsCommissions['localite_id'];
	 	  ?>        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><?php echo MinmapDB::getInstance()->get_localite_lib_by_localite_id($row_rsCommissions['localite_id']); ?><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a>&nbsp;</td>
      <td><?php           
			$showGoTo = "localites_upd.php?locID=".$row_rsLocalites['localite_id'];
	 	  ?>        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><?php echo MinmapDB::getInstance()->get_type_commission_lib_by_commission_id($row_rsCommissions['type_commission_id']); ?><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td><?php           
			$showGoTo = "localites_upd.php?locID=".$row_rsLocalites['localite_id'];
	 	  ?>        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><?php echo MinmapDB::getInstance()->get_nature_lib_by_nature_id($row_rsCommissions['nature_id']); ?><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a>&nbsp;</td>
      <td><?php           
			$showGoTo = "localites_upd.php?locID=".$row_rsLocalites['localite_id'];
	 	  ?>        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><?php echo MinmapDB::getInstance()->get_structure_lib_by_structure_id($row_rsCommissions['structure_id']); ?><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/> </a>&nbsp;</td>
      <td><?php echo $row_rsCommissions['commission_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['montant_cumul']; ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['membre_insert']; ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['dateUpdate']; ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['display']; ?>&nbsp; </td>
      <td><?php echo $row_rsCommissions['user_id']; ?>&nbsp; </td>
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
