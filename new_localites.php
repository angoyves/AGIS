<?php require_once('Connections/MyFileConnect.php'); ?>
<?php

$DATE = date('Y-m-d H:i:s');
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
  $insertSQL = sprintf("INSERT INTO localites (localite_id, localite_lib, type_localite, date_creation, display) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString(strtoupper($_POST['localite_lib']), "text"),
                       GetSQLValueString($_POST['type_localite'], "int"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "new_localites.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeLocalite = "SELECT * FROM type_localites WHERE display = '1' ORDER BY type_localite_lib ASC";
$rsTypeLocalite = mysql_query($query_rsTypeLocalite, $MyFileConnect) or die(mysql_error());
$row_rsTypeLocalite = mysql_fetch_assoc($rsTypeLocalite);
$totalRows_rsTypeLocalite = mysql_num_rows($rsTypeLocalite);
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
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Localite :</td>
      <td><input type="text" name="localite_lib" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type localite :</td>
      <td><select name="type_localite">
        <option value="" >::Select </option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsTypeLocalite['type_localite_id']?>"><?php echo ucfirst($row_rsTypeLocalite['type_localite_lib'])?></option>
        <?php
} while ($row_rsTypeLocalite = mysql_fetch_assoc($rsTypeLocalite));
  $rows = mysql_num_rows($rsTypeLocalite);
  if($rows > 0) {
      mysql_data_seek($rsTypeLocalite, 0);
	  $row_rsTypeLocalite = mysql_fetch_assoc($rsTypeLocalite);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="localite_id" value="" />
  <input type="hidden" name="date_creation" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsTypeLocalite);
?>
