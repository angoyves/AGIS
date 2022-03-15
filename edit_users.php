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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET user_name=%s, user_lastname=%s, user_login=%s, user_password=%s, user_groupe_id=%s, display=%s, dateCreation=%s, dateUpdate=%s WHERE user_id=%s",
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['user_lastname'], "text"),
                       GetSQLValueString($_POST['user_login'], "text"),
                       GetSQLValueString($_POST['user_password'], "text"),
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['dateCreation'], "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "afficher_users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUpdUsers = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdUsers = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdUsers = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_rsUpdUsers, "int"));
$rsUpdUsers = mysql_query($query_rsUpdUsers, $MyFileConnect) or die(mysql_error());
$row_rsUpdUsers = mysql_fetch_assoc($rsUpdUsers);
$totalRows_rsUpdUsers = mysql_num_rows($rsUpdUsers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Nom :</th>
      <td><input type="text" name="user_name" value="<?php echo htmlentities($row_rsUpdUsers['user_name'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Prenom :</th>
      <td><input type="text" name="user_lastname" value="<?php echo htmlentities($row_rsUpdUsers['user_lastname'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Login :</th>
      <td><input name="user_login" type="text" value="<?php echo htmlentities($row_rsUpdUsers['user_login'], ENT_COMPAT, 'utf-8'); ?>" size="32" readonly="readonly" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Mot de passe :</th>
      <td><input type="text" name="user_password" value="<?php echo htmlentities($row_rsUpdUsers['user_password'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Groupe :</th>
      <td><select name="user_groupe_id">
        <option value="menuitem1" >[ Label ]</option>
        <option value="menuitem2" >[ Label ]</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">&nbsp;</th>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="user_id" value="<?php echo $row_rsUpdUsers['user_id']; ?>" />
  <input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdUsers['display'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsUpdUsers['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsUpdUsers['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="user_id" value="<?php echo $row_rsUpdUsers['user_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdUsers);
?>
