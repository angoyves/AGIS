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
  $insertSQL = sprintf("INSERT INTO users (user_id, user_name, user_lastname, user_login, user_password, user_groupe_id, display, dateCreation, dateUpdate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['user_lastname'], "text"),
                       GetSQLValueString($_POST['user_login'], "text"),
                       GetSQLValueString($_POST['user_password'], "text"),
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "afficher_users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_rsUserGroupe = "-1";
if (isset($_GET['1'])) {
  $colname_rsUserGroupe = $_GET['1'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUserGroupe = sprintf("SELECT user_groupe_id, user_groupe_lib FROM user_groupes WHERE display = %s", GetSQLValueString($colname_rsUserGroupe, "text"));
$rsUserGroupe = mysql_query($query_rsUserGroupe, $MyFileConnect) or die(mysql_error());
$row_rsUserGroupe = mysql_fetch_assoc($rsUserGroupe);
$totalRows_rsUserGroupe = mysql_num_rows($rsUserGroupe);mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUserGroupe = "SELECT user_groupe_id, user_groupe_lib FROM user_groupes WHERE display = '1'";
$rsUserGroupe = mysql_query($query_rsUserGroupe, $MyFileConnect) or die(mysql_error());
$row_rsUserGroupe = mysql_fetch_assoc($rsUserGroupe);
$totalRows_rsUserGroupe = mysql_num_rows($rsUserGroupe);
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
  <table align="center" class="std">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Nom :</td
      h>
      <td><input type="text" name="user_name" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Prenom :</th>
      <td><input type="text" name="user_lastname" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Login :</th>
      <td><input type="text" name="user_login" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Mot de passe :</th>
      <td><input type="password" name="user_password" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Groupe :</th>
      <td><select name="user_groupe_id">
        <option value="" >:: Selectionner</option>
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
      </select>
      <a href="#" onclick="<?php popup("create_user_groupe.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="user_id" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="dateCreaion" value="" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUserGroupe);
?>
