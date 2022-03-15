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

$maxRows_rsTableAgences = 10;
$pageNum_rsTableAgences = 0;
if (isset($_GET['pageNum_rsTableAgences'])) {
  $pageNum_rsTableAgences = $_GET['pageNum_rsTableAgences'];
}
$startRow_rsTableAgences = $pageNum_rsTableAgences * $maxRows_rsTableAgences;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTableAgences = "SELECT * FROM agences WHERE display = '1'";
$query_limit_rsTableAgences = sprintf("%s LIMIT %d, %d", $query_rsTableAgences, $startRow_rsTableAgences, $maxRows_rsTableAgences);
$rsTableAgences = mysql_query($query_limit_rsTableAgences, $MyFileConnect) or die(mysql_error());
$row_rsTableAgences = mysql_fetch_assoc($rsTableAgences);

if (isset($_GET['totalRows_rsTableAgences'])) {
  $totalRows_rsTableAgences = $_GET['totalRows_rsTableAgences'];
} else {
  $all_rsTableAgences = mysql_query($query_rsTableAgences);
  $totalRows_rsTableAgences = mysql_num_rows($all_rsTableAgences);
}
$totalPages_rsTableAgences = ceil($totalRows_rsTableAgences/$maxRows_rsTableAgences)-1;

$queryString_rsTableAgences = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsTableAgences") == false && 
        stristr($param, "totalRows_rsTableAgences") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsTableAgences = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsTableAgences = sprintf("&totalRows_rsTableAgences=%d%s", $totalRows_rsTableAgences, $queryString_rsTableAgences);
 
function alter_color($cpt, $col1, $col2){
	$col = (($cpt%2)==0)?$col1:$col2;
	return $col;
	echo $col;
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0" align="center" class="std">
  <tr>
    <th>agence_id</th>
    <th>agence_code</th>
    <th>agence_lib</th>
    <th>banque_code</th>
    <th>agence_cle</th>
    <th>date_creation</th>
    <th>display</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="detail_table_delete.php?recordID=<?php echo $row_rsTableAgences['agence_id']; ?>"> <?php echo $row_rsTableAgences['agence_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsTableAgences['agence_code']; ?>&nbsp; </td>
      <td><?php echo $row_rsTableAgences['agence_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsTableAgences['banque_code']; ?>&nbsp; </td>
      <td><?php echo $row_rsTableAgences['agence_cle']; ?>&nbsp; </td>
      <td><?php echo $row_rsTableAgences['date_creation']; ?>&nbsp; </td>
      <td><?php echo $row_rsTableAgences['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsTableAgences = mysql_fetch_assoc($rsTableAgences)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsTableAgences > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsTableAgences=%d%s", $currentPage, 0, $queryString_rsTableAgences); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsTableAgences > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsTableAgences=%d%s", $currentPage, max(0, $pageNum_rsTableAgences - 1), $queryString_rsTableAgences); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsTableAgences < $totalPages_rsTableAgences) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsTableAgences=%d%s", $currentPage, min($totalPages_rsTableAgences, $pageNum_rsTableAgences + 1), $queryString_rsTableAgences); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsTableAgences < $totalPages_rsTableAgences) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsTableAgences=%d%s", $currentPage, $totalPages_rsTableAgences, $queryString_rsTableAgences); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsTableAgences + 1) ?> to <?php echo min($startRow_rsTableAgences + $maxRows_rsTableAgences, $totalRows_rsTableAgences) ?> of <?php echo $totalRows_rsTableAgences ?>
</body>
</html>
<?php
mysql_free_result($rsTableAgences);
?>
