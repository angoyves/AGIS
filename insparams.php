<?php require_once('Connections/MyFileConnect.php'); ?><?php
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
  $updateSQL = sprintf("UPDATE params SET retenu=%s, Exercice=%s, Taux=%s WHERE param_id=%s",
                       GetSQLValueString($_POST['retenu'], "double"),
                       GetSQLValueString($_POST['Exercice'], "text"),
                       GetSQLValueString($_POST['Taux'], "double"),
                       GetSQLValueString($_POST['param_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  /*$updateGoTo = "params.php";
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

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_DetailRS1 = sprintf("SELECT * FROM params WHERE param_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
$DetailRS1 = mysql_query($query_DetailRS1, $MyFileConnect) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);
$totalRows_DetailRS1 = mysql_num_rows($DetailRS1);

$colname_RsInsParams = "-1";
if (isset($_GET['param_id'])) {
  $colname_RsInsParams = $_GET['param_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_RsInsParams = sprintf("SELECT * FROM params WHERE param_id = %s", GetSQLValueString($colname_RsInsParams, "int"));
$RsInsParams = mysql_query($query_RsInsParams, $MyFileConnect) or die(mysql_error());
$row_RsInsParams = mysql_fetch_assoc($RsInsParams);
$totalRows_RsInsParams = mysql_num_rows($RsInsParams);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<p>&nbsp;</p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">:</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Retenu:</td>
      <td><input type="text" name="retenu" value="<?php echo htmlentities($row_DetailRS1['retenu'], ENT_COMPAT, 'utf-8'); ?>" size="20" />
        ex : 0.11 pour 11%</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Exercice:</td>
      <td><input type="text" name="Exercice" value="<?php echo htmlentities($row_DetailRS1['Exercice'], ENT_COMPAT, 'utf-8'); ?>" size="20" />
        ex : 2014</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Taux:</td>
      <td><input type="text" name="Taux" value="<?php echo htmlentities($row_DetailRS1['Taux'], ENT_COMPAT, 'utf-8'); ?>" size="15" /> 
        ex : 60.26 pour 60.26%</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="param_id" value="<?php echo $row_DetailRS1['param_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html><?php
mysql_free_result($DetailRS1);

mysql_free_result($RsInsParams);
?>