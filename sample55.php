<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET user_name=%s, user_lastname=%s, user_login=%s, user_password=%s, user_groupe_id=%s, structure_id=%s, display=%s, date_last_login=%s, dateCreation=%s, dateUpdate=%s, compteur=%s WHERE user_id=%s",
                       GetSQLValueString($_POST['user_name'], "text"),
                       GetSQLValueString($_POST['user_lastname'], "text"),
                       GetSQLValueString($_POST['user_login'], "text"),
                       GetSQLValueString($_POST['user_password'], "text"),
                       GetSQLValueString($_POST['user_groupe_id'], "int"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString(isset($_POST['display']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($_POST['date_last_login'], "date"),
                       GetSQLValueString($_POST['dateCreation'], "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['compteur'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

/*  $updateGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));*/
    echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
}

$colname_rsUsers = "-1";
if (isset($_GET['userID'])) {
  $colname_rsUsers = $_GET['userID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUsers = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_rsUsers, "int"));
$rsUsers = mysql_query($query_rsUsers, $MyFileConnect) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);
$totalRows_rsUsers = mysql_num_rows($rsUsers);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupe = "SELECT * FROM user_groupes WHERE display = '1'";
$rsGroupe = mysql_query($query_rsGroupe, $MyFileConnect) or die(mysql_error());
$row_rsGroupe = mysql_fetch_assoc($rsGroupe);
$totalRows_rsGroupe = mysql_num_rows($rsGroupe);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std2">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">User groupe :</th>
      <td><select name="user_groupe_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsGroupe['user_groupe_id']?>"<?php if (!(strcmp($row_rsGroupe['user_groupe_id'], $row_rsUsers['user_groupe_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsGroupe['user_groupe_lib']?></option>
        <?php
} while ($row_rsGroupe = mysql_fetch_assoc($rsGroupe));
  $rows = mysql_num_rows($rsGroupe);
  if($rows > 0) {
      mysql_data_seek($rsGroupe, 0);
	  $row_rsGroupe = mysql_fetch_assoc($rsGroupe);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Actif :</th>
      <td><input type="checkbox" name="display" value=""  <?php if (!(strcmp(htmlentities($row_rsUsers['display'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="user_id" value="<?php echo $row_rsUsers['user_id']; ?>" />
  <input type="hidden" name="user_name" value="<?php echo htmlentities($row_rsUsers['user_name'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_lastname" value="<?php echo htmlentities($row_rsUsers['user_lastname'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_login" value="<?php echo htmlentities($row_rsUsers['user_login'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_password" value="<?php echo htmlentities($row_rsUsers['user_password'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="structure_id" value="<?php echo htmlentities($row_rsUsers['structure_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="date_last_login" value="<?php echo htmlentities($row_rsUsers['date_last_login'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsUsers['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsUsers['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="compteur" value="<?php echo htmlentities($row_rsUsers['compteur'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="user_id" value="<?php echo $row_rsUsers['user_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUsers);

mysql_free_result($rsGroupe);
?>
