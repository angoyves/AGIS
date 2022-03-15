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
  $updateSQL = sprintf("UPDATE rib SET personne_matricule=%s, agence_cle=%s, banque_code=%s, agence_code=%s, numero_compte=%s, date_creation=%s, cle=%s WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString($_POST['agence_cle'], "text"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['numero_compte'], "text"),
                       GetSQLValueString($_POST['date_creation'], "date"),
                       GetSQLValueString($_POST['cle'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "show_personnes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUpdRIB = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdRIB = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdRIB = sprintf("SELECT * FROM rib WHERE personne_id = %s", GetSQLValueString($colname_rsUpdRIB, "int"));
$rsUpdRIB = mysql_query($query_rsUpdRIB, $MyFileConnect) or die(mysql_error());
$row_rsUpdRIB = mysql_fetch_assoc($rsUpdRIB);
$totalRows_rsUpdRIB = mysql_num_rows($rsUpdRIB);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Banque_code:</td>
      <td><select name="banque_code">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", <?php echo htmlentities($row_rsUpdRIB['banque_code'], ENT_COMPAT, 'utf-8'); ?>))) {echo "SELECTED";} ?>&gt;[ Label ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", <?php echo htmlentities($row_rsUpdRIB['banque_code'], ENT_COMPAT, 'utf-8'); ?>))) {echo "SELECTED";} ?>&gt;[ Label ]</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Agence_code:</td>
      <td><select name="agence_code">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", <?php echo htmlentities($row_rsUpdRIB['agence_code'], ENT_COMPAT, 'utf-8'); ?>))) {echo "SELECTED";} ?>&gt;[ Label ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", <?php echo htmlentities($row_rsUpdRIB['agence_code'], ENT_COMPAT, 'utf-8'); ?>))) {echo "SELECTED";} ?>&gt;[ Label ]</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Numero_compte:</td>
      <td><input type="text" name="numero_compte" value="<?php echo htmlentities($row_rsUpdRIB['numero_compte'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Cle:</td>
      <td><input type="text" name="cle" value="<?php echo htmlentities($row_rsUpdRIB['cle'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdRIB['personne_id']; ?>" />
  <input type="hidden" name="personne_matricule" value="<?php echo htmlentities($row_rsUpdRIB['personne_matricule'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="agence_cle" value="<?php echo htmlentities($row_rsUpdRIB['agence_cle'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="date_creation" value="<?php echo htmlentities($row_rsUpdRIB['date_creation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdRIB['personne_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdRIB);
?>
