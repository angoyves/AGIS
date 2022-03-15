<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php

$agenceIsEmpty = false;
$date = date('Y-m-d H:i:s');

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO user_groupes (user_groupe_id, user_groupe_lib, type_user_groupe_id, dateCreation, display) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
                       GetSQLValueString($_POST['user_groupe_lib'], "text"),
                       GetSQLValueString($_POST['type_user_groupe_id'], "int"),
					   GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "afficher_user_groupe.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUserTypeGroupe = "SELECT * FROM type_user_groupe WHERE display = '1'";
$rsUserTypeGroupe = mysql_query($query_rsUserTypeGroupe, $MyFileConnect) or die(mysql_error());
$row_rsUserTypeGroupe = mysql_fetch_assoc($rsUserTypeGroupe);
$totalRows_rsUserTypeGroupe = mysql_num_rows($rsUserTypeGroupe);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Libelle groupe :</td>
      <td><input type="text" name="user_groupe_lib" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type groupe :</td>
      <td><select name="type_user_groupe_id">
        <option value="" >:: Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsUserTypeGroupe['type_user_groupe_id']?>"><?php echo $row_rsUserTypeGroupe['type_user_groupe_lib']?></option>
        <?php
} while ($row_rsUserTypeGroupe = mysql_fetch_assoc($rsUserTypeGroupe));
  $rows = mysql_num_rows($rsUserTypeGroupe);
  if($rows > 0) {
      mysql_data_seek($rsUserTypeGroupe, 0);
	  $row_rsUserTypeGroupe = mysql_fetch_assoc($rsUserTypeGroupe);
  }
?>
      </select>
      <a href="#" onclick="<?php popup("create_type_user_groupe.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="user_groupe_id" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUserTypeGroupe);
?>
