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

$maxRows_rsBanques = 10;
$pageNum_rsBanques = 0;
if (isset($_GET['pageNum_rsBanques'])) {
  $pageNum_rsBanques = $_GET['pageNum_rsBanques'];
}
$startRow_rsBanques = $pageNum_rsBanques * $maxRows_rsBanques;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanques = "SELECT banque_code, banque_lib FROM banques WHERE display = '1'";
$query_limit_rsBanques = sprintf("%s LIMIT %d, %d", $query_rsBanques, $startRow_rsBanques, $maxRows_rsBanques);
$rsBanques = mysql_query($query_limit_rsBanques, $MyFileConnect) or die(mysql_error());
$row_rsBanques = mysql_fetch_assoc($rsBanques);

if (isset($_GET['totalRows_rsBanques'])) {
  $totalRows_rsBanques = $_GET['totalRows_rsBanques'];
} else {
  $all_rsBanques = mysql_query($query_rsBanques);
  $totalRows_rsBanques = mysql_num_rows($all_rsBanques);
}
$totalPages_rsBanques = ceil($totalRows_rsBanques/$maxRows_rsBanques)-1;

$queryString_rsBanques = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsBanques") == false && 
        stristr($param, "totalRows_rsBanques") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsBanques = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsBanques = sprintf("&totalRows_rsBanques=%d%s", $totalRows_rsBanques, $queryString_rsBanques);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIIER MINMAP...</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1" align="center">
  <tr>
    <td><strong>Code Banque</strong></td>
    <td><strong>Libelle banque</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="new_personnes.php?banqueID=<?php echo $row_rsBanques['banque_code']; ?>" target="_parent">  </a>
      	  <a href="#" onclick="window.opener.location.href='create_agence.php?banqueID=<?php echo $row_rsBanques['banque_code']; ?>';self.close();"><?php echo $row_rsBanques['banque_code']; ?>&nbsp;</a></td>
      <td><?php echo $row_rsBanques['banque_lib']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsBanques = mysql_fetch_assoc($rsBanques)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsBanques > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, 0, $queryString_rsBanques); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsBanques > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, max(0, $pageNum_rsBanques - 1), $queryString_rsBanques); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsBanques < $totalPages_rsBanques) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, min($totalPages_rsBanques, $pageNum_rsBanques + 1), $queryString_rsBanques); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsBanques < $totalPages_rsBanques) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, $totalPages_rsBanques, $queryString_rsBanques); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsBanques + 1) ?> à <?php echo min($startRow_rsBanques + $maxRows_rsBanques, $totalRows_rsBanques) ?> sur <?php echo $totalRows_rsBanques ?>
</body>
</html>
<?php
mysql_free_result($rsBanques);
?>
