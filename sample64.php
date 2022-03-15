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

$date = date('Y-m-d H:i:s');
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
  
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE commissions SET montant_cumul=%s, nombre_offre=%s, dateUpdate=%s, user_id=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['montant_cumul'], "text"),
                       GetSQLValueString($_POST['nombre_offre'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['commission_id'], "int"));

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

$colname_rsCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = sprintf("SELECT commission_id, commission_lib, montant_cumul, nombre_offre, dateUpdate, user_id FROM commissions WHERE commission_id = %s AND commission_parent is not null", GetSQLValueString($colname_rsCommission, "int"));
$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
$row_rsCommission = mysql_fetch_assoc($rsCommission);
$totalRows_rsCommission = mysql_num_rows($rsCommission);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">N° :</th>
      <td><?php echo $row_rsCommission['commission_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Nom commission:</th>
      <td><?php echo htmlentities($row_rsCommission['commission_lib']); ?></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Montant cumulé:</th>
      <td><input type="text" name="montant_cumul" value="<?php echo htmlentities($row_rsCommission['montant_cumul'], ENT_COMPAT, 'utf-8'); ?>" size="20" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Nombre d'offres:</th>
      <td><input type="text" name="nombre_offre" value="<?php echo htmlentities($row_rsCommission['nombre_offre'], ENT_COMPAT, 'utf-8'); ?>" size="10" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">&nbsp;</th>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsCommission['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_id" value="<?php echo htmlentities($row_rsCommission['user_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="commission_id" value="<?php echo $row_rsCommission['commission_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsCommission);
?>
