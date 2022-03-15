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
  $updateSQL = sprintf("UPDATE membres SET montant=%s WHERE commissions_commission_id=%s AND personnes_personne_id=%s AND fonctions_fonction_id=%s",
                       GetSQLValueString($_POST['montant'], "int"),
					   GetSQLValueString($_GET['comID'], "int"),
					   GetSQLValueString($_GET['persID'], "int"),
					   GetSQLValueString($_GET['foncID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  /*$updateGoTo = "sample38.php";
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

$colname_rsMembresSCA = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembresSCA = $_GET['comID'];
}
$colname1_rsMembresSCA = "-1";
if (isset($_GET['persID'])) {
  $colname1_rsMembresSCA = $_GET['persID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembresSCA = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s AND membres.personnes_personne_id = %s", GetSQLValueString($colname_rsMembresSCA, "int"),GetSQLValueString($colname1_rsMembresSCA, "int"));
$rsMembresSCA = mysql_query($query_rsMembresSCA, $MyFileConnect) or die(mysql_error());
$row_rsMembresSCA = mysql_fetch_assoc($rsMembresSCA);
$totalRows_rsMembresSCA = mysql_num_rows($rsMembresSCA);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <p>&nbsp;</p>
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Montant:</td>
      <td><input type="text" name="montant" value="<?php echo htmlentities($row_rsMembresSCA['montant'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <input type="hidden" name="commissions_commission_id" value="<?php echo $row_rsMembresSCA['commissions_commission_id']; ?>" />
  <input type="hidden" name="fonctions_fonction_id" value="<?php echo htmlentities($row_rsMembresSCA['fonctions_fonction_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="personnes_personne_id" value="<?php echo htmlentities($row_rsMembresSCA['personnes_personne_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="checboxName" value="<?php echo htmlentities($row_rsMembresSCA['checboxName'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="position" value="<?php echo htmlentities($row_rsMembresSCA['position'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="display" value="<?php echo htmlentities($row_rsMembresSCA['display'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsMembresSCA['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsMembresSCA['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_id" value="<?php echo htmlentities($row_rsMembresSCA['user_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="commissions_commission_id" value="<?php echo $row_rsMembresSCA['commissions_commission_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsMembresSCA);
?>
