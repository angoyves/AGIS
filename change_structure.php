<?php virtual('/AGIS/Connections/MyFileConnect.php'); ?>
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
  $updateSQL = sprintf("UPDATE personnes SET structure_id=%s, user_id=%s, dateUpdate=%s, display=%s WHERE personne_id=%s",
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "sample38.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsPersonnes = "-1";
if (isset($_GET['persID'])) {
  $colname_rsPersonnes = $_GET['persID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = sprintf("SELECT personne_id, structure_id FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsPersonnes, "int"));
$rsPersonnes = mysql_query($query_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);
$totalRows_rsPersonnes = mysql_num_rows($rsPersonnes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Structure_id:</td>
      <td><select name="structure_id">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", <?php echo htmlentities($row_rsPersonnes['structure_id'], ENT_COMPAT, 'utf-8'); ?>))) {echo "SELECTED";} ?>&gt;[ Etiquette ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", <?php echo htmlentities($row_rsPersonnes['structure_id'], ENT_COMPAT, 'utf-8'); ?>))) {echo "SELECTED";} ?>&gt;[ Etiquette ]</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="personne_id" value="<?php echo $row_rsPersonnes['personne_id']; ?>" />
  <input type="hidden" name="user_id" value="" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="display" value="" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsPersonnes['personne_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsPersonnes);
?>
