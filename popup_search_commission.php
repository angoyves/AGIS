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

$maxRows_rsShowCommissions = 50;
$pageNum_rsShowCommissions = 0;
if (isset($_GET['pageNum_rsShowCommissions'])) {
  $pageNum_rsShowCommissions = $_GET['pageNum_rsShowCommissions'];
}
$startRow_rsShowCommissions = $pageNum_rsShowCommissions * $maxRows_rsShowCommissions;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowCommissions = "SELECT commissions.commission_id,  commissions.localite_id, localites.localite_lib, commissions.nature_id, natures.lib_nature,  commissions.type_commission_id, type_commissions.type_commission_lib FROM commissions, localites, natures, type_commissions WHERE commissions.commission_id = localites.localite_id AND commissions.nature_id=natures.nature_id AND commissions.type_commission_id=type_commissions.type_commission_id AND commissions.membre_insert IS NOT NULL AND commissions.display= '1'";
$query_limit_rsShowCommissions = sprintf("%s LIMIT %d, %d", $query_rsShowCommissions, $startRow_rsShowCommissions, $maxRows_rsShowCommissions);
$rsShowCommissions = mysql_query($query_limit_rsShowCommissions, $MyFileConnect) or die(mysql_error());
$row_rsShowCommissions = mysql_fetch_assoc($rsShowCommissions);

if (isset($_GET['totalRows_rsShowCommissions'])) {
  $totalRows_rsShowCommissions = $_GET['totalRows_rsShowCommissions'];
} else {
  $all_rsShowCommissions = mysql_query($query_rsShowCommissions);
  $totalRows_rsShowCommissions = mysql_num_rows($all_rsShowCommissions);
}
$totalPages_rsShowCommissions = ceil($totalRows_rsShowCommissions/$maxRows_rsShowCommissions)-1;

$queryString_rsShowCommissions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowCommissions") == false && 
        stristr($param, "totalRows_rsShowCommissions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowCommissions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowCommissions = sprintf("&totalRows_rsShowCommissions=%d%s", $totalRows_rsShowCommissions, $queryString_rsShowCommissions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <label for="txt_research">:</label>
  <table border="1">
    <tr>
      <th scope="row">Rechercher </th>
      <td><input type="text" name="txt_research" id="txt_research" /></td>
      <td><input type="submit" name="txt_btn" id="txt_btn" value="Submit" /></td>
    </tr>
  </table>
</form>
<table border="1" align="center">
  <tr>
    <td>nature_id</td>
    <td>Nature</td>
    <td>Localite</td>
    <td>Type commission</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="detail_commissions_test.php?recordID=<?php echo $row_rsShowCommissions['commission_id']; ?>"> <?php echo $row_rsShowCommissions['commission_id']; ?>&nbsp; </a></td>
      <td><a href="detail_commissions_test.php?recordID=<?php echo $row_rsShowCommissions['nature_id']; ?>"><?php echo $row_rsShowCommissions['lib_nature']; ?></a></td>
      <td><a href="detail_commissions_test.php?recordID=<?php echo $row_rsShowCommissions['nature_id']; ?>"><?php echo $row_rsShowCommissions['localite_lib']; ?></a></td>
      <td><a href="detail_commissions_test.php?recordID=<?php echo $row_rsShowCommissions['nature_id']; ?>"><?php echo $row_rsShowCommissions['type_commission_lib']; ?></a></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php } while ($row_rsShowCommissions = mysql_fetch_assoc($rsShowCommissions)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsShowCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommissions=%d%s", $currentPage, 0, $queryString_rsShowCommissions); ?>">First</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommissions=%d%s", $currentPage, max(0, $pageNum_rsShowCommissions - 1), $queryString_rsShowCommissions); ?>">Previous</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowCommissions < $totalPages_rsShowCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommissions=%d%s", $currentPage, min($totalPages_rsShowCommissions, $pageNum_rsShowCommissions + 1), $queryString_rsShowCommissions); ?>">Next</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsShowCommissions < $totalPages_rsShowCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommissions=%d%s", $currentPage, $totalPages_rsShowCommissions, $queryString_rsShowCommissions); ?>">Last</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsShowCommissions + 1) ?> to <?php echo min($startRow_rsShowCommissions + $maxRows_rsShowCommissions, $totalRows_rsShowCommissions) ?> of <?php echo $totalRows_rsShowCommissions ?>
</body>
</html>
<?php
mysql_free_result($rsShowCommissions);
?>
