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
  $updateSQL = sprintf("UPDATE domaine_expert SET AG=%s, SPI=%s, BAT=%s, RTES=%s, AI=%s, SCENT=%s WHERE personne_id=%s",
                       GetSQLValueString(isset($_POST['AG']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['SPI']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['BAT']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['RTES']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['AI']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['SCENT']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "show_domaine.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUpdDomaine = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdDomaine = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdDomaine = sprintf("SELECT * FROM domaine_expert WHERE personne_id = %s", GetSQLValueString($colname_rsUpdDomaine, "int"));
$rsUpdDomaine = mysql_query($query_rsUpdDomaine, $MyFileConnect) or die(mysql_error());
$row_rsUpdDomaine = mysql_fetch_assoc($rsUpdDomaine);
$totalRows_rsUpdDomaine = mysql_num_rows($rsUpdDomaine);
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
      <td nowrap="nowrap" align="right">AG:</td>
      <td><input type="checkbox" name="AG" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdDomaine['AG'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">SPI:</td>
      <td><input type="checkbox" name="SPI" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdDomaine['SPI'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">BAT:</td>
      <td><input type="checkbox" name="BAT" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdDomaine['BAT'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">RTES:</td>
      <td><input type="checkbox" name="RTES" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdDomaine['RTES'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">AI:</td>
      <td><input type="checkbox" name="AI" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdDomaine['AI'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">SCENT:</td>
      <td><input type="checkbox" name="SCENT" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdDomaine['SCENT'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdDomaine['personne_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdDomaine['personne_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdDomaine);
?>
