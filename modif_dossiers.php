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
 /* $updateSQL = sprintf("UPDATE dossiers SET dossier_ref=%s, dossier_nom=%s, dossiers_jour=%s, dossier_observ=%s, display=%s, commission_id=%s WHERE dossier_id=%s",
                       GetSQLValueString($_POST['dossier_ref'], "text"),
                       GetSQLValueString($_POST['dossier_nom'], "text"),
                       GetSQLValueString($_POST['dossiers_jour'], "undefined"),
                       GetSQLValueString($_POST['dossier_observ'], "text"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"));*/
$updateSQL = sprintf("UPDATE dossiers SET dossier_ref=%s WHERE dossier_id=%s",
                       GetSQLValueString($_POST['dossier_ref'], "text"),
                       GetSQLValueString($_PGET['dosID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

 /* $updateGoTo = "sample38.php";
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

$colname_rsDossier = "-1";
if (isset($_GET['dosID'])) {
  $colname_rsDossier = $_GET['dosID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsDossier = sprintf("SELECT * FROM dossiers WHERE dossier_id = %s", GetSQLValueString($colname_rsDossier, "int"));
$rsDossier = mysql_query($query_rsDossier, $MyFileConnect) or die(mysql_error());
$row_rsDossier = mysql_fetch_assoc($rsDossier);
$totalRows_rsDossier = mysql_num_rows($rsDossier);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modifier Doissiers</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Reference:</td>
      <td><textarea name="dossier_ref" cols="32" rows="6"><?php echo htmlentities($row_rsDossier['dossier_ref'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Libelle:</td>
      <td><input name="dossier_nom" type="text" value="<?php echo htmlentities($row_rsDossier['dossier_nom'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Jour:</td>
      <td><?php echo htmlentities($row_rsDossier['dossiers_jour'], ENT_COMPAT, 'utf-8'); ?></td>
    </tr>
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Obervations:</td>
      <td><textarea name="dossier_observ" cols="32" rows="6"><?php echo htmlentities($row_rsDossier['dossier_observ'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre ?? jour" /></td>
    </tr>
  </table>
  <input type="hidden" name="dossier_id" value="<?php echo $row_rsDossier['dossier_id']; ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsDossier['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="display" value="<?php echo htmlentities($row_rsDossier['display'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="commission_id" value="<?php echo htmlentities($row_rsDossier['commission_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="dossier_id" value="<?php echo $row_rsDossier['dossier_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsDossier);
?>
