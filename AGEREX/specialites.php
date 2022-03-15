<?php require_once('../Connections/AGEREX.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO domaine (domaineCompetenceID, domaineName, TypeDomaines_typeDomaineID, display) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['domaineCompetenceID'], "int"),
                       GetSQLValueString($_POST['domaineName'], "text"),
                       GetSQLValueString($_POST['TypeDomaines_typeDomaineID'], "int"),
                       GetSQLValueString(isset($_POST['display']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_AGEREX, $AGEREX);
  $Result1 = mysql_query($insertSQL, $AGEREX) or die(mysql_error());

  $insertGoTo = "showSpecialites.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_AGEREX, $AGEREX);
$query_rsTypeDomaine = "SELECT * FROM typedomaines WHERE display = '1' ORDER BY typeDomaineName ASC";
$rsTypeDomaine = mysql_query($query_rsTypeDomaine, $AGEREX) or die(mysql_error());
$row_rsTypeDomaine = mysql_fetch_assoc($rsTypeDomaine);
$totalRows_rsTypeDomaine = mysql_num_rows($rsTypeDomaine);
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
      <td nowrap="nowrap" align="right">Specialité :</td>
      <td><input type="text" name="domaineName" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Domaines :</td>
      <td><select name="TypeDomaines_typeDomaineID">
        <option value="" <?php if (!(strcmp("", $row_rsTypeDomaine['typeDomaineID']))) {echo "selected=\"selected\"";} ?>>Ajouter...</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsTypeDomaine['typeDomaineID']?>"<?php if (!(strcmp($row_rsTypeDomaine['typeDomaineID'], $row_rsTypeDomaine['typeDomaineID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTypeDomaine['typeDomaineName']?></option>
        <?php
} while ($row_rsTypeDomaine = mysql_fetch_assoc($rsTypeDomaine));
  $rows = mysql_num_rows($rsTypeDomaine);
  if($rows > 0) {
      mysql_data_seek($rsTypeDomaine, 0);
	  $row_rsTypeDomaine = mysql_fetch_assoc($rsTypeDomaine);
  }
?>
      </select>
      <a href="domaines.php">Ajouter</a></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Display:</td>
      <td><input type="checkbox" name="display" value="" checked="checked" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Ins&eacute;rer un enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="domaineCompetenceID" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsTypeDomaine);
?>
