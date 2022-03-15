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

$maxRows_rsListPrivileges = 10;
$pageNum_rsListPrivileges = 0;
if (isset($_GET['pageNum_rsListPrivileges'])) {
  $pageNum_rsListPrivileges = $_GET['pageNum_rsListPrivileges'];
}
$startRow_rsListPrivileges = $pageNum_rsListPrivileges * $maxRows_rsListPrivileges;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsListPrivileges = "SELECT * FROM `privileges` WHERE display = '1'";
$query_limit_rsListPrivileges = sprintf("%s LIMIT %d, %d", $query_rsListPrivileges, $startRow_rsListPrivileges, $maxRows_rsListPrivileges);
$rsListPrivileges = mysql_query($query_limit_rsListPrivileges, $MyFileConnect) or die(mysql_error());
$row_rsListPrivileges = mysql_fetch_assoc($rsListPrivileges);

if (isset($_GET['totalRows_rsListPrivileges'])) {
  $totalRows_rsListPrivileges = $_GET['totalRows_rsListPrivileges'];
} else {
  $all_rsListPrivileges = mysql_query($query_rsListPrivileges);
  $totalRows_rsListPrivileges = mysql_num_rows($all_rsListPrivileges);
}
$totalPages_rsListPrivileges = ceil($totalRows_rsListPrivileges/$maxRows_rsListPrivileges)-1;

$queryString_rsListPrivileges = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsListPrivileges") == false && 
        stristr($param, "totalRows_rsListPrivileges") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsListPrivileges = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsListPrivileges = sprintf("&totalRows_rsListPrivileges=%d%s", $totalRows_rsListPrivileges, $queryString_rsListPrivileges);
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
    <th scope="col"><a href="create_privileges.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter privilège</th>
  </tr>
  <tr>
    <th><a href="accueil.php"><img src="images/img/b_home.png" alt="" width="16" height="16" align="absmiddle"/></a>Retour à l'accueil</th>
  </tr>
</table>
<p>&nbsp;</p>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>Libellé</th>
    <th>Description</th>
    <th>Etat</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="detail_privilege.php?recordID=<?php echo $row_rsListPrivileges['privilege_id']; ?>"> <?php echo $row_rsListPrivileges['privilege_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsListPrivileges['privilege_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsListPrivileges['privilege_description']; ?>&nbsp; </td>
      <td><?php echo $row_rsListPrivileges['display']; ?>&nbsp; </td>
      <td><?php if (isset($row_rsShowUsers['display']) && $row_rsShowUsers['display'] == '1') { ?>
        <a href="change_etat.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&amp;&amp;map=users&amp;&amp;action=desactive"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } else { ?>
        <a href="change_etat.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&amp;&amp;map=users&amp;&amp;action=active"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } ?></td>
      <td><a href="edit_users.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&amp;&amp;map=users"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td><a href="delete.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&amp;&amp;map=users"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td><a href="detail_users.php"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <?php } while ($row_rsListPrivileges = mysql_fetch_assoc($rsListPrivileges)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsListPrivileges > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsListPrivileges=%d%s", $currentPage, 0, $queryString_rsListPrivileges); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsListPrivileges > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsListPrivileges=%d%s", $currentPage, max(0, $pageNum_rsListPrivileges - 1), $queryString_rsListPrivileges); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsListPrivileges < $totalPages_rsListPrivileges) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsListPrivileges=%d%s", $currentPage, min($totalPages_rsListPrivileges, $pageNum_rsListPrivileges + 1), $queryString_rsListPrivileges); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsListPrivileges < $totalPages_rsListPrivileges) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsListPrivileges=%d%s", $currentPage, $totalPages_rsListPrivileges, $queryString_rsListPrivileges); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsListPrivileges + 1) ?> to <?php echo min($startRow_rsListPrivileges + $maxRows_rsListPrivileges, $totalRows_rsListPrivileges) ?> of <?php echo $totalRows_rsListPrivileges ?>
</body>
</html>
<?php
mysql_free_result($rsListPrivileges);
?>
