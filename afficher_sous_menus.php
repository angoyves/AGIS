<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
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

$maxRows_rsShowSousMenu = 50;
$pageNum_rsShowSousMenu = 0;
if (isset($_GET['pageNum_rsShowSousMenu'])) {
  $pageNum_rsShowSousMenu = $_GET['pageNum_rsShowSousMenu'];
}
$startRow_rsShowSousMenu = $pageNum_rsShowSousMenu * $maxRows_rsShowSousMenu;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowSousMenu = "SELECT * FROM sous_menus WHERE display = '1' ORDER BY menu_id ASC";
$query_limit_rsShowSousMenu = sprintf("%s LIMIT %d, %d", $query_rsShowSousMenu, $startRow_rsShowSousMenu, $maxRows_rsShowSousMenu);
$rsShowSousMenu = mysql_query($query_limit_rsShowSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsShowSousMenu = mysql_fetch_assoc($rsShowSousMenu);

if (isset($_GET['totalRows_rsShowSousMenu'])) {
  $totalRows_rsShowSousMenu = $_GET['totalRows_rsShowSousMenu'];
} else {
  $all_rsShowSousMenu = mysql_query($query_rsShowSousMenu);
  $totalRows_rsShowSousMenu = mysql_num_rows($all_rsShowSousMenu);
}
$totalPages_rsShowSousMenu = ceil($totalRows_rsShowSousMenu/$maxRows_rsShowSousMenu)-1;

$queryString_rsShowSousMenu = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowSousMenu") == false && 
        stristr($param, "totalRows_rsShowSousMenu") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowSousMenu = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowSousMenu = sprintf("&totalRows_rsShowSousMenu=%d%s", $totalRows_rsShowSousMenu, $queryString_rsShowSousMenu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0">
  <tr>
    <th><a href="accueil.php"><img src="images/img/b_home.png" alt="" width="16" height="16" align="absmiddle"/></a>Retour à l'accueil</th>
  </tr>
</table>
<table border="0">
  <tr>
    <th scope="col"><a href="create_sous_menu.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter privilège</th>
  </tr>
</table>
<p>&nbsp;</p>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>Libelle Sous-Menu</th>
    <th>Description</th>
    <th>Lien </th>
    <th>Menu Parent</th>
    <th>Date creation</th>
    <th>Date modification</th>
    <th>Etat</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="<?php echo $row_rsShowSousMenu['sous_menu_lien']; ?>"> <?php echo $row_rsShowSousMenu['sous_menu_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsShowSousMenu['sous_menu_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSousMenu['sous_menu_description']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSousMenu['sous_menu_lien']; ?>&nbsp; </td>
      <td>
	  
			<?php 
			$colname_rsMenu = "-1";
            if (isset($row_rsShowSousMenu['menu_id'])) {
              $colname_rsMenu = $row_rsShowSousMenu['menu_id'];
            }
            mysql_select_db($database_MyFileConnect, $MyFileConnect);
            $query_rsMenu = sprintf("SELECT menu_lib FROM menus WHERE menu_id = %s", GetSQLValueString($colname_rsMenu, "int"));
            $rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
            $row_rsMenu = mysql_fetch_assoc($rsMenu);
            $totalRows_rsMenu = mysql_num_rows($rsMenu);?> 
	  <?php echo $row_rsMenu['menu_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSousMenu['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSousMenu['dateUpdate']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSousMenu['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsShowSousMenu = mysql_fetch_assoc($rsShowSousMenu)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsShowSousMenu > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowSousMenu=%d%s", $currentPage, 0, $queryString_rsShowSousMenu); ?>">First</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowSousMenu > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowSousMenu=%d%s", $currentPage, max(0, $pageNum_rsShowSousMenu - 1), $queryString_rsShowSousMenu); ?>">Previous</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowSousMenu < $totalPages_rsShowSousMenu) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowSousMenu=%d%s", $currentPage, min($totalPages_rsShowSousMenu, $pageNum_rsShowSousMenu + 1), $queryString_rsShowSousMenu); ?>">Next</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsShowSousMenu < $totalPages_rsShowSousMenu) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowSousMenu=%d%s", $currentPage, $totalPages_rsShowSousMenu, $queryString_rsShowSousMenu); ?>">Last</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsShowSousMenu + 1) ?> to <?php echo min($startRow_rsShowSousMenu + $maxRows_rsShowSousMenu, $totalRows_rsShowSousMenu) ?> of <?php echo $totalRows_rsShowSousMenu ?>
</body>
</html>
<?php
mysql_free_result($rsShowSousMenu);

mysql_free_result($rsMenu);
?>
