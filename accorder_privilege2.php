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

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO privileges_accordes (user_groupe_id, privilege_id, ckb_value) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
					   GetSQLValueString($_POST['privilege_2'], "int"),
                       GetSQLValueString(isset($_POST['create_2']) ? "true" : "", "defined","1","0"));

  $insertSQL2 = sprintf("INSERT INTO privileges_accordes (user_groupe_id, privilege_id, ckb_value) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
					   GetSQLValueString($_POST['privilege_3'], "int"),
                       GetSQLValueString(isset($_POST['upd_3']) ? "true" : "", "defined","1","0"));

  $insertSQL3 = sprintf("INSERT INTO privileges_accordes (user_groupe_id, privilege_id, ckb_value) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
					   GetSQLValueString($_POST['privilege_4'], "int"),
                       GetSQLValueString(isset($_POST['print_4']) ? "true" : "", "defined","1","0"));
					   
  $insertSQL4 = sprintf("INSERT INTO privileges_accordes (user_groupe_id, privilege_id, ckb_value) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
					   GetSQLValueString($_POST['privilege_5'], "int"),
                       GetSQLValueString(isset($_POST['del_5']) ? "true" : "", "defined","1","0"));
					  
  $updateSQL = sprintf("UPDATE user_groupes SET user_groupe_active=%s WHERE user_groupe_id=%s",
                       GetSQLValueString($_POST['user_groupe_active'], "text"),
                       GetSQLValueString($_POST['user_groupe_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());
  $Result3 = mysql_query($insertSQL3, $MyFileConnect) or die(mysql_error());
  $Result4 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error());
  $Result5 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
}

$maxRows_rsUserGroupe = 10;
$pageNum_rsUserGroupe = 0;
if (isset($_GET['pageNum_rsUserGroupe'])) {
  $pageNum_rsUserGroupe = $_GET['pageNum_rsUserGroupe'];
}
$startRow_rsUserGroupe = $pageNum_rsUserGroupe * $maxRows_rsUserGroupe;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUserGroupe = "SELECT * FROM user_groupes WHERE display = '1' AND user_groupe_active = '0'";
$query_limit_rsUserGroupe = sprintf("%s LIMIT %d, %d", $query_rsUserGroupe, $startRow_rsUserGroupe, $maxRows_rsUserGroupe);
$rsUserGroupe = mysql_query($query_limit_rsUserGroupe, $MyFileConnect) or die(mysql_error());
$row_rsUserGroupe = mysql_fetch_assoc($rsUserGroupe);

if (isset($_GET['totalRows_rsUserGroupe'])) {
  $totalRows_rsUserGroupe = $_GET['totalRows_rsUserGroupe'];
} else {
  $all_rsUserGroupe = mysql_query($query_rsUserGroupe);
  $totalRows_rsUserGroupe = mysql_num_rows($all_rsUserGroupe);
}
$totalPages_rsUserGroupe = ceil($totalRows_rsUserGroupe/$maxRows_rsUserGroupe)-1;

$maxRows_rsPrivileges = 10;
$pageNum_rsPrivileges = 0;
if (isset($_GET['pageNum_rsPrivileges'])) {
  $pageNum_rsPrivileges = $_GET['pageNum_rsPrivileges'];
}
$startRow_rsPrivileges = $pageNum_rsPrivileges * $maxRows_rsPrivileges;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPrivileges = "SELECT privilege_id, privilege_lib, ckb_name FROM `privileges` WHERE display = '1'";
$query_limit_rsPrivileges = sprintf("%s LIMIT %d, %d", $query_rsPrivileges, $startRow_rsPrivileges, $maxRows_rsPrivileges);
$rsPrivileges = mysql_query($query_limit_rsPrivileges, $MyFileConnect) or die(mysql_error());
$row_rsPrivileges = mysql_fetch_assoc($rsPrivileges);

if (isset($_GET['totalRows_rsPrivileges'])) {
  $totalRows_rsPrivileges = $_GET['totalRows_rsPrivileges'];
} else {
  $all_rsPrivileges = mysql_query($query_rsPrivileges);
  $totalRows_rsPrivileges = mysql_num_rows($all_rsPrivileges);
}
$totalPages_rsPrivileges = ceil($totalRows_rsPrivileges/$maxRows_rsPrivileges)-1;

$queryString_rsPrivileges = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPrivileges") == false && 
        stristr($param, "totalRows_rsPrivileges") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPrivileges = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPrivileges = sprintf("&totalRows_rsPrivileges=%d%s", $totalRows_rsPrivileges, $queryString_rsPrivileges);

$queryString_rsUserGroupe = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsUserGroupe") == false && 
        stristr($param, "totalRows_rsUserGroupe") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsUserGroupe = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsUserGroupe = sprintf("&totalRows_rsUserGroupe=%d%s", $totalRows_rsUserGroupe, $queryString_rsUserGroupe);

$queryString_rsPrivileges = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPrivileges") == false && 
        stristr($param, "totalRows_rsPrivileges") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPrivileges = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPrivileges = sprintf("&totalRows_rsPrivileges=%d%s", $totalRows_rsPrivileges, $queryString_rsPrivileges);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table border="0" align="center" class="std">
    <tr>
      <th align="right">Groupe d'utilisateurs:</t>
      <td><select name="user_groupe_id" size="15">
        <?php
do {  
?>
        <option value="<?php echo $row_rsUserGroupe['user_groupe_id']?>"><?php echo $row_rsUserGroupe['user_groupe_lib']?></option>
        <?php
} while ($row_rsUserGroupe = mysql_fetch_assoc($rsUserGroupe));
  $rows = mysql_num_rows($rsUserGroupe);
  if($rows > 0) {
      mysql_data_seek($rsUserGroupe, 0);
	  $row_rsUserGroupe = mysql_fetch_assoc($rsUserGroupe);
  }
?>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php do { ?>
    <tr>
      <th align="right"><?php echo $row_rsPrivileges['privilege_lib']; ?> : </th>
      <td>&nbsp;
        <input type="checkbox" name="<?php echo $row_rsPrivileges['ckb_name'] . "" . $row_rsPrivileges['privilege_id'] ; ?>" value="" />
        <input type="hidden" name="<?php echo "privilege_" . $row_rsPrivileges['privilege_id']; ?>" value="<?php echo $row_rsPrivileges['privilege_id'] ?>" size="32" /></td>
    </tr>
    <?php } while ($row_rsPrivileges = mysql_fetch_assoc($rsPrivileges)); ?>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" value="Enregistrer" /></td>
    </tr>
  </table>
  <input type="hidden" name="user_groupe_active" value="1" size="32" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
<p>&nbsp;
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsPrivileges > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsPrivileges=%d%s", $currentPage, 0, $queryString_rsPrivileges); ?>">First</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPrivileges > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsPrivileges=%d%s", $currentPage, max(0, $pageNum_rsPrivileges - 1), $queryString_rsPrivileges); ?>">Previous</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPrivileges < $totalPages_rsPrivileges) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsPrivileges=%d%s", $currentPage, min($totalPages_rsPrivileges, $pageNum_rsPrivileges + 1), $queryString_rsPrivileges); ?>">Next</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsPrivileges < $totalPages_rsPrivileges) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsPrivileges=%d%s", $currentPage, $totalPages_rsPrivileges, $queryString_rsPrivileges); ?>">Last</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsPrivileges + 1) ?> to <?php echo min($startRow_rsPrivileges + $maxRows_rsPrivileges, $totalRows_rsPrivileges) ?> of <?php echo $totalRows_rsPrivileges ?>
</p>
</body>
</html>
<?php
mysql_free_result($rsUserGroupe);

mysql_free_result($rsPrivileges);
?>
