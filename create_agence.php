<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
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

$colname_rsShowBankLib = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsShowBankLib = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowBankLib = sprintf("SELECT banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsShowBankLib, "text"));
$rsShowBankLib = mysql_query($query_rsShowBankLib, $MyFileConnect) or die(mysql_error());
$row_rsShowBankLib = mysql_fetch_assoc($rsShowBankLib);
$totalRows_rsShowBankLib = mysql_num_rows($rsShowBankLib);

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $agence_cle = $_POST['banque_code'] . "" . $_POST['agence_code'];
  $insertSQL = sprintf("INSERT INTO agences (agence_id, banque_code, agence_code, agence_lib, agence_cle, date_creation, display) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['agence_id'], "int"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['agence_lib'], "text"),
                       GetSQLValueString($agence_cle, "text"),
                       GetSQLValueString($_POST['date_creation'], "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIIER MINMAP...</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <h1>CREATION D'UNE AGENCE A(AU) <?php echo $row_rsShowBankLib['banque_lib']; ?></h1>
  <table align="center" class="std">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Code banque :</th>
      <td><input name="banque_code" type="text" value="<?php echo $_GET['banqueID'] ?>" size="10" maxlength="5" readonly="readonly" />
      <!--<a href="#" onclick="<?php /* popup("afficher_banques.php", "610", "500");<?php */ ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle" />--></a></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Code agence :</th>
      <td><input name="agence_code" type="text" value="" size="10" maxlength="5" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Libelle agence :</th>
      <td><input type="text" name="agence_lib" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" onclick="window.opener.location.href='<?php 
	  $insertGoTo = "create_personnes.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  echo $insertGoTo; ?>';self.close();"/></td>
    </tr>
  </table>
  <input type="hidden" name="agence_id" value="" />
  <input type="hidden" name="agence_cle" value="" size="32" />
  <input type="hidden" name="date_creation" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsShowBankLib);
?>
