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

$maxRows_rsSelPresident = 10;
$pageNum_rsSelPresident = 0;
if (isset($_GET['pageNum_rsSelPresident'])) {
  $pageNum_rsSelPresident = $_GET['pageNum_rsSelPresident'];
}
$startRow_rsSelPresident = $pageNum_rsSelPresident * $maxRows_rsSelPresident;

$colname_rsSelPresident = "-1";
if (isset($_GET['personne_id'])) {
  $colname_rsSelPresident = $_GET['personne_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelPresident = sprintf("SELECT personne_id, personne_nom, personne_prenom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsSelPresident, "int"));
$query_limit_rsSelPresident = sprintf("%s LIMIT %d, %d", $query_rsSelPresident, $startRow_rsSelPresident, $maxRows_rsSelPresident);
$rsSelPresident = mysql_query($query_limit_rsSelPresident, $MyFileConnect) or die(mysql_error());
$row_rsSelPresident = mysql_fetch_assoc($rsSelPresident);

if (isset($_GET['totalRows_rsSelPresident'])) {
  $totalRows_rsSelPresident = $_GET['totalRows_rsSelPresident'];
} else {
  $all_rsSelPresident = mysql_query($query_rsSelPresident);
  $totalRows_rsSelPresident = mysql_num_rows($all_rsSelPresident);
}
$totalPages_rsSelPresident = ceil($totalRows_rsSelPresident/$maxRows_rsSelPresident)-1;

$queryString_rsSelPresident = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSelPresident") == false && 
        stristr($param, "totalRows_rsSelPresident") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSelPresident = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSelPresident = sprintf("&totalRows_rsSelPresident=%d%s", $totalRows_rsSelPresident, $queryString_rsSelPresident);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td>personne_id</td>
    <td>personne_nom</td>
    <td>personne_prenom</td>
  </tr>
        <form id="form1" name="form1" method="post" action="">
  <?php do { ?>
      <?php if ($totalRows_rsSelPresident > 0) { // Show if recordset not empty ?>
  <tr>
    <td><a href="detail_personne.php?recordID=<?php echo $row_rsSelPresident['personne_id']; ?>"> <?php echo $row_rsSelPresident['personne_id']; ?>&nbsp; 
      <input type="text" name="<?php echo "personne_id" .  $row_rsSelPresident['personne_id']; ?>" value="<?php echo "personne_id" .  $row_rsSelPresident['personne_id']; ?>" id="textfield" />
    </a></td>
    <td><?php echo $row_rsSelPresident['personne_nom']; ?>
      <label for="textfield"></label>&nbsp; </td>
    <td><?php echo $row_rsSelPresident['personne_prenom']; ?>&nbsp; </td>
  </tr>
  <?php } // Show if recordset not empty ?>
<?php } while ($row_rsSelPresident = mysql_fetch_assoc($rsSelPresident)); ?>
    </form> 
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSelPresident > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSelPresident=%d%s", $currentPage, 0, $queryString_rsSelPresident); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSelPresident > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSelPresident=%d%s", $currentPage, max(0, $pageNum_rsSelPresident - 1), $queryString_rsSelPresident); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSelPresident < $totalPages_rsSelPresident) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSelPresident=%d%s", $currentPage, min($totalPages_rsSelPresident, $pageNum_rsSelPresident + 1), $queryString_rsSelPresident); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSelPresident < $totalPages_rsSelPresident) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSelPresident=%d%s", $currentPage, $totalPages_rsSelPresident, $queryString_rsSelPresident); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsSelPresident + 1) ?> to <?php echo min($startRow_rsSelPresident + $maxRows_rsSelPresident, $totalRows_rsSelPresident) ?> of <?php echo $totalRows_rsSelPresident ?>
</body>
</html>
<?php
mysql_free_result($rsSelPresident);
?>
