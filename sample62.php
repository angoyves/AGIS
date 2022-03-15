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

  $insertGoTo = "sel_value.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }

$maxRows_rsCommissions = 10;
$pageNum_rsCommissions = 0;
if (isset($_GET['pageNum_rsCommissions'])) {
  $pageNum_rsCommissions = $_GET['pageNum_rsCommissions'];
}
$startRow_rsCommissions = $pageNum_rsCommissions * $maxRows_rsCommissions;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$colname_rsCommissions = "-1";
if (isset($_POST['txt_search'])) {
$colname_rsCommissions = $_POST['txt_search'];
$query_rsCommissions = sprintf("SELECT commission_id, natures.lib_nature, type_commission_lib, localite_lib, commission_lib
FROM natures, type_commissions, localites, commissions
WHERE commissions.nature_id = natures.nature_id
AND commissions.type_commission_id = type_commissions.type_commission_id
AND commissions.localite_id = localites.localite_id
AND commissions.membre_insert = '1'
AND commissions.commission_parent is null
AND localite_lib LIKE %s ORDER BY localite_lib ASC", GetSQLValueString("%" . $colname_rsCommissions . "%", "text"));
} else {
$query_rsCommissions = sprintf("SELECT commission_id, natures.lib_nature, type_commission_lib, localite_lib, commission_lib
FROM natures, type_commissions, localites, commissions
WHERE commissions.nature_id = natures.nature_id
AND commissions.type_commission_id = type_commissions.type_commission_id
AND commissions.localite_id = localites.localite_id
AND commissions.membre_insert = '1'
AND commissions.commission_parent is null
ORDER BY localite_lib ASC");
}
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
<title>:: FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1" align="center" class="std">
  <tr>
    <td colspan="5"><form id="form1" name="form1" method="post" action="">
      Localite :
      <input type="text" name="txt_search" id="txt_search" />
      <input type="submit" name="button" id="button" value="Submit" />
    </form></td>
  </tr>
  <tr>
    <th>ID</th>
    <th>Localite</th>
    <th colspan="3">Libelle</th>
  </tr>
  <?php do { ?>
  <?php if (isset($_GET['page']) && $_GET['page'] == 'fin') {
	  $linkPage = 'etat_financier.php'; }
	  else {
	  $linkPage = 'sample61.php';
	  } ?>
    <tr>
      <td nowrap="nowrap">
      <a href="" onclick="window.opener.location.href='<?php echo $linkPage; ?>?comID=<?php echo $row_rsCommissions['commission_id']; ?><?php if(isset($_GET['menuID'])){ echo '&menuID='.$_GET['menuID']; }?>';self.close();"> <img src="images/img/b_import.png" width="16" height="16" alt="<?php echo $row_rsCommissions['commission_id']; ?>" /></a>     
      
      </td>
      <td nowrap="nowrap"><?php echo $row_rsCommissions['localite_lib']; ?>&nbsp; </td>
      <td colspan="3" nowrap="nowrap">&nbsp; &nbsp;<?php echo $row_rsCommissions['commission_lib']; ?>&nbsp</td>
    </tr>
    <?php } while ($row_rsCommissions = mysql_fetch_assoc($rsCommissions)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, 0, $queryString_rsCommissions); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, max(0, $pageNum_rsCommissions - 1), $queryString_rsCommissions); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsCommissions < $totalPages_rsCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, min($totalPages_rsCommissions, $pageNum_rsCommissions + 1), $queryString_rsCommissions); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsCommissions < $totalPages_rsCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, $totalPages_rsCommissions, $queryString_rsCommissions); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsCommissions + 1) ?> to <?php echo min($startRow_rsCommissions + $maxRows_rsCommissions, $totalRows_rsCommissions) ?> of <?php echo $totalRows_rsCommissions ?>
</body>
</html>
<?php
mysql_free_result($rsCommissions);
?>
