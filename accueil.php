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

$maxRows_rsMenu = 10;
$pageNum_rsMenu = 0;
if (isset($_GET['pageNum_rsMenu'])) {
  $pageNum_rsMenu = $_GET['pageNum_rsMenu'];
}
$startRow_rsMenu = $pageNum_rsMenu * $maxRows_rsMenu;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT menu_id, menu_lib FROM menus WHERE display = '1'";
$query_limit_rsMenu = sprintf("%s LIMIT %d, %d", $query_rsMenu, $startRow_rsMenu, $maxRows_rsMenu);
$rsMenu = mysql_query($query_limit_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);

if (isset($_GET['totalRows_rsMenu'])) {
  $totalRows_rsMenu = $_GET['totalRows_rsMenu'];
} else {
  $all_rsMenu = mysql_query($query_rsMenu);
  $totalRows_rsMenu = mysql_num_rows($all_rsMenu);
}
$totalPages_rsMenu = ceil($totalRows_rsMenu/$maxRows_rsMenu)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = "SELECT sous_menu_id, sous_menu_lib, sous_menu_lien, menu_id FROM sous_menus WHERE display = '1'";
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

$queryString_rsMenu = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsMenu") == false && 
        stristr($param, "totalRows_rsMenu") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsMenu = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsMenu = sprintf("&totalRows_rsMenu=%d%s", $totalRows_rsMenu, $queryString_rsMenu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td>menu_lib</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><ul><h1><?php echo $row_rsMenu['menu_lib']; ?></h1></ul>
</td>
    </tr>
    <?php } while ($row_rsMenu = mysql_fetch_assoc($rsMenu)); ?>
</table>
<table border="1">
  <tr>
    <th width="82" scope="row">&nbsp;</th>
  </tr>
  <tr>
    <th scope="row"><?php echo $row_rsSousMenu['sous_menu_lib']; ?></th>
  </tr>
</table>
        <blockquote>&nbsp;</blockquote>
        <table border="0" align="center">
          <?php do { ?>
            <tr>
              <td><li><a href="#"><?php echo $row_rsSousMenu['menu_id']; ?></a></li>
                <li><a href="#"><?php echo $row_rsSousMenu['sous_menu_lib']; ?></a>&nbsp; </li></td>
            </tr>
            <?php } while ($row_rsSousMenu = mysql_fetch_assoc($rsSousMenu)); ?>
        </table>
</body>
</html>
<?php
mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>
