<?php require_once('Connections/MyFileConnect.php'); ?>
<?php

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

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
  $updateSQL = sprintf("UPDATE personnes SET personne_nom=%s, personne_prenom=%s WHERE personne_id=%s",
                       GetSQLValueString(strtoupper($_POST['personne_nom']), "text"),
                       GetSQLValueString(ucfirst(strtolower($_POST['personne_prenom'])), "text"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  /*$updateGoTo = "show_personnes.php";
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

$colname_rsUpdPersName = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdPersName = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdPersName = sprintf("SELECT personne_id, personne_nom, personne_prenom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsUpdPersName, "int"));
$rsUpdPersName = mysql_query($query_rsUpdPersName, $MyFileConnect) or die(mysql_error());
$row_rsUpdPersName = mysql_fetch_assoc($rsUpdPersName);
$totalRows_rsUpdPersName = mysql_num_rows($rsUpdPersName);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/v2.css" rel="stylesheet" type="text/css" />
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std2">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Nom:</th>
      <td><input type="text" name="personne_nom" value="<?php echo $row_rsUpdPersName['personne_nom']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Prenom:</th>
      <td><input type="text" name="personne_prenom" value="<?php echo $row_rsUpdPersName['personne_prenom']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
    <?php if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup']==1) { ?>
    <tr valign="baseline">
      <td rowspan="2" align="right" nowrap="nowrap">&nbsp;</td>
      <td align="left"><a href="delete_doublon.php?txtSearch=<?php echo substr($row_rsUpdPersName['personne_nom'],0,6) ?>">Supprimer les doublons</a></td>
    </tr>
    <tr valign="baseline">
      <td align="left"><a href="show_all_ov.php?txtSearch=<?php echo substr($row_rsUpdPersName['personne_nom'],0,6) ?>">Verfier les OV</a></td>
    </tr>
    <?php } ?>
  </table>
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdPersName['personne_id']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdPersName['personne_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdPersName);
?>
