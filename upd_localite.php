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
  $updateSQL = sprintf("UPDATE localite_expert SET AD=%s, OUEST=%s, LIT=%s, NORD=%s, NORD_OUEST=%s, SUD_OUEST=%s, SUD=%s, EXT_NORD=%s, CE=%s, EST=%s, AG=%s WHERE personne_id=%s",
                       GetSQLValueString(isset($_POST['AD']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['OUEST']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['LIT']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['NORD']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['NORD_OUEST']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['SUD_OUEST']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['SUD']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['EXT_NORD']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['CE']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['EST']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString(isset($_POST['AG']) ? "true" : "", "defined","'X'","''"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "show_localite.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUpdLocalite = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdLocalite = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdLocalite = sprintf("SELECT * FROM localite_expert WHERE personne_id = %s", GetSQLValueString($colname_rsUpdLocalite, "int"));
$rsUpdLocalite = mysql_query($query_rsUpdLocalite, $MyFileConnect) or die(mysql_error());
$row_rsUpdLocalite = mysql_fetch_assoc($rsUpdLocalite);
$totalRows_rsUpdLocalite = mysql_num_rows($rsUpdLocalite);

$colname_rsPersonne = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsPersonne = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonne = sprintf("SELECT personne_nom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsPersonne, "int"));
$rsPersonne = mysql_query($query_rsPersonne, $MyFileConnect) or die(mysql_error());
$row_rsPersonne = mysql_fetch_assoc($rsPersonne);
$totalRows_rsPersonne = mysql_num_rows($rsPersonne);
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
      <th colspan="2" align="right"><h1><?php echo $row_rsPersonne['personne_nom']; ?></h1> </th>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Adamaoua:</td>
      <td><input type="checkbox" name="AD" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['AD'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Ouest:</td>
      <td><input type="checkbox" name="OUEST" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['OUEST'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Littoral:</td>
      <td><input type="checkbox" name="LIT" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['LIT'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nord:</td>
      <td><input type="checkbox" name="NORD" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['NORD'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nord Ouest:</td>
      <td><input type="checkbox" name="NORD_OUEST" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['NORD_OUEST'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Sud Ouest:</td>
      <td><input type="checkbox" name="SUD_OUEST" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['SUD_OUEST'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Sud:</td>
      <td><input type="checkbox" name="SUD" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['SUD'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Extreme Nord:</td>
      <td><input type="checkbox" name="EXT_NORD" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['EXT_NORD'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Centre:</td>
      <td><input type="checkbox" name="CE" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['CE'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Est:</td>
      <td><input type="checkbox" name="EST" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['EST'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">AG:</td>
      <td><input type="checkbox" name="AG" value=""  <?php if (!(strcmp(htmlentities($row_rsUpdLocalite['AG'], ENT_COMPAT, 'utf-8'),"X"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdLocalite['personne_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdLocalite['personne_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdLocalite);

mysql_free_result($rsPersonne);
?>
