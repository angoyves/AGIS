<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('includes/MyFonction.php'); ?>
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

$maxRows_rsShowUsers = 10;
$pageNum_rsShowUsers = 0;
if (isset($_GET['pageNum_rsShowUsers'])) {
  $pageNum_rsShowUsers = $_GET['pageNum_rsShowUsers'];
}
$startRow_rsShowUsers = $pageNum_rsShowUsers * $maxRows_rsShowUsers;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowUsers = "SELECT * FROM users WHERE display = '1'";
$query_limit_rsShowUsers = sprintf("%s LIMIT %d, %d", $query_rsShowUsers, $startRow_rsShowUsers, $maxRows_rsShowUsers);
$rsShowUsers = mysql_query($query_limit_rsShowUsers, $MyFileConnect) or die(mysql_error());
$row_rsShowUsers = mysql_fetch_assoc($rsShowUsers);

if (isset($_GET['totalRows_rsShowUsers'])) {
  $totalRows_rsShowUsers = $_GET['totalRows_rsShowUsers'];
} else {
  $all_rsShowUsers = mysql_query($query_rsShowUsers);
  $totalRows_rsShowUsers = mysql_num_rows($all_rsShowUsers);
}
$totalPages_rsShowUsers = ceil($totalRows_rsShowUsers/$maxRows_rsShowUsers)-1;$maxRows_rsShowUsers = 10;
$pageNum_rsShowUsers = 0;
if (isset($_GET['pageNum_rsShowUsers'])) {
  $pageNum_rsShowUsers = $_GET['pageNum_rsShowUsers'];
}
$startRow_rsShowUsers = $pageNum_rsShowUsers * $maxRows_rsShowUsers;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowUsers = "SELECT * FROM users";
$query_limit_rsShowUsers = sprintf("%s LIMIT %d, %d", $query_rsShowUsers, $startRow_rsShowUsers, $maxRows_rsShowUsers);
$rsShowUsers = mysql_query($query_limit_rsShowUsers, $MyFileConnect) or die(mysql_error());
$row_rsShowUsers = mysql_fetch_assoc($rsShowUsers);

if (isset($_GET['totalRows_rsShowUsers'])) {
  $totalRows_rsShowUsers = $_GET['totalRows_rsShowUsers'];
} else {
  $all_rsShowUsers = mysql_query($query_rsShowUsers);
  $totalRows_rsShowUsers = mysql_num_rows($all_rsShowUsers);
}
$totalPages_rsShowUsers = ceil($totalRows_rsShowUsers/$maxRows_rsShowUsers)-1;

$queryString_rsShowUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowUsers") == false && 
        stristr($param, "totalRows_rsShowUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowUsers = sprintf("&totalRows_rsShowUsers=%d%s", $totalRows_rsShowUsers, $queryString_rsShowUsers);
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
  <tr>
    <th width="177" scope="col"><a href="create_users.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter utilisateur</th>
  </tr>
</table>
<p>&nbsp;</p>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Login</th>
    <th>Motde passe</th>
    <th>Etat</th>
    <th>Upd</th>
    <th>Del</th>
    <th>detail</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="detail_user.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>"> <?php echo $row_rsShowUsers['user_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsShowUsers['user_name']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowUsers['user_lastname']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowUsers['user_login']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowUsers['user_password']; ?>&nbsp; </td>
      <td><?php if (isset($row_rsShowUsers['display']) && $row_rsShowUsers['display'] == '1') { ?>
        <a href="change_etat.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&&map=users&&mapid=user_id&&action=desactive"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a> 
      <?php } else { ?>
      <a href="change_etat.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&&map=users&&mapid=user_id&&action=active"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>      <?php } ?></td>
      <td><a href="edit_users.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&amp;&amp;map=users"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td><a href="delete.php?recordID=<?php echo $row_rsShowUsers['user_id']; ?>&amp;&amp;map=users"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td><a href="detail_users.php"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <?php } while ($row_rsShowUsers = mysql_fetch_assoc($rsShowUsers)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsShowUsers > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowUsers=%d%s", $currentPage, 0, $queryString_rsShowUsers); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowUsers > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowUsers=%d%s", $currentPage, max(0, $pageNum_rsShowUsers - 1), $queryString_rsShowUsers); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowUsers < $totalPages_rsShowUsers) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowUsers=%d%s", $currentPage, min($totalPages_rsShowUsers, $pageNum_rsShowUsers + 1), $queryString_rsShowUsers); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsShowUsers < $totalPages_rsShowUsers) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowUsers=%d%s", $currentPage, $totalPages_rsShowUsers, $queryString_rsShowUsers); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsShowUsers + 1) ?> to <?php echo min($startRow_rsShowUsers + $maxRows_rsShowUsers, $totalRows_rsShowUsers) ?> of <?php echo $totalRows_rsShowUsers ?>
</body>
</html>
<?php
mysql_free_result($rsShowUsers);
?>
