<?php require_once('Connections/MyFileConnect.php'); ?><?php require_once('Connections/MyFileConnect.php'); ?><?php
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

$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_DetailRS1 = sprintf("SELECT * FROM users  WHERE user_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $MyFileConnect) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;

$maxRows_DetailRS2 = 10;
$pageNum_DetailRS2 = 0;
if (isset($_GET['pageNum_DetailRS2'])) {
  $pageNum_DetailRS2 = $_GET['pageNum_DetailRS2'];
}
$startRow_DetailRS2 = $pageNum_DetailRS2 * $maxRows_DetailRS2;

$colname_DetailRS2 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS2 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_DetailRS2 = sprintf("SELECT * FROM users WHERE user_id = %s ORDER BY user_id ASC", GetSQLValueString($colname_DetailRS2, "int"));
$query_limit_DetailRS2 = sprintf("%s LIMIT %d, %d", $query_DetailRS2, $startRow_DetailRS2, $maxRows_DetailRS2);
$DetailRS2 = mysql_query($query_limit_DetailRS2, $MyFileConnect) or die(mysql_error());
$row_DetailRS2 = mysql_fetch_assoc($DetailRS2);

if (isset($_GET['totalRows_DetailRS2'])) {
  $totalRows_DetailRS2 = $_GET['totalRows_DetailRS2'];
} else {
  $all_DetailRS2 = mysql_query($query_DetailRS2);
  $totalRows_DetailRS2 = mysql_num_rows($all_DetailRS2);
}
$totalPages_DetailRS2 = ceil($totalRows_DetailRS2/$maxRows_DetailRS2)-1;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
</head>

<body>

<table border="1" align="center">
  <tr>
    <td>user_id</td>
    <td><?php echo $row_DetailRS1['user_id']; ?></td>
  </tr>
  <tr>
    <td>user_name</td>
    <td><?php echo $row_DetailRS1['user_name']; ?></td>
  </tr>
  <tr>
    <td>user_lastname</td>
    <td><?php echo $row_DetailRS1['user_lastname']; ?></td>
  </tr>
  <tr>
    <td>user_login</td>
    <td><?php echo $row_DetailRS1['user_login']; ?></td>
  </tr>
  <tr>
    <td>user_password</td>
    <td><?php echo $row_DetailRS1['user_password']; ?></td>
  </tr>
  <tr>
    <td>user_groupe_id</td>
    <td><?php echo $row_DetailRS1['user_groupe_id']; ?></td>
  </tr>
  <tr>
    <td>dateCreaion</td>
    <td><?php echo $row_DetailRS1['dateCreaion']; ?></td>
  </tr>
  <tr>
    <td>dateUpdate</td>
    <td><?php echo $row_DetailRS1['dateUpdate']; ?></td>
  </tr>
  <tr>
    <td>display</td>
    <td><?php echo $row_DetailRS1['display']; ?></td>
  </tr>
</table>
<table border="1" align="center">
  <tr>
    <td>user_id</td>
    <td><?php echo $row_DetailRS2['user_id']; ?></td>
  </tr>
  <tr>
    <td>user_name</td>
    <td><?php echo $row_DetailRS2['user_name']; ?></td>
  </tr>
  <tr>
    <td>user_lastname</td>
    <td><?php echo $row_DetailRS2['user_lastname']; ?></td>
  </tr>
  <tr>
    <td>user_login</td>
    <td><?php echo $row_DetailRS2['user_login']; ?></td>
  </tr>
  <tr>
    <td>user_password</td>
    <td><?php echo $row_DetailRS2['user_password']; ?></td>
  </tr>
  <tr>
    <td>user_groupe_id</td>
    <td><?php echo $row_DetailRS2['user_groupe_id']; ?></td>
  </tr>
  <tr>
    <td>display</td>
    <td><?php echo $row_DetailRS2['display']; ?></td>
  </tr>
  <tr>
    <td>dateCreation</td>
    <td><?php echo $row_DetailRS2['dateCreation']; ?></td>
  </tr>
  <tr>
    <td>dateUpdate</td>
    <td><?php echo $row_DetailRS2['dateUpdate']; ?></td>
  </tr>
</table>

</body>
</html><?php
mysql_free_result($DetailRS1);

mysql_free_result($DetailRS2);
?>