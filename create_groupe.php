<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php

$sousGroupeLibIsEmpty = false;
$GroupeIsEmpty = false;
$date = date('Y-m-d H:i:s');

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
  $insertSQL = sprintf("INSERT INTO sous_groupes (sous_groupe_id, sous_groupe_lib, sous_groupe_taux, sous_groupe_effectif, sous_groupe_mtt_glo, sous_groupe_mtt_ind, groupe_id, date_creation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['sous_groupe_id'], "int"),
                       GetSQLValueString($_POST['sous_groupe_lib'], "text"),
                       GetSQLValueString($_POST['sous_groupe_taux'], "double"),
                       GetSQLValueString($_POST['sous_groupe_effectif'], "int"),
                       GetSQLValueString($_POST['sous_groupe_mtt_glo'], "double"),
                       GetSQLValueString($_POST['sous_groupe_mtt_ind'], "double"),
                       GetSQLValueString($_POST['groupe_id'], "int"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "detail_groupe.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupe = "SELECT groupe_id, groupe_lib FROM groupes WHERE display = '1'";
$rsGroupe = mysql_query($query_rsGroupe, $MyFileConnect) or die(mysql_error());
$row_rsGroupe = mysql_fetch_assoc($rsGroupe);
$totalRows_rsGroupe = mysql_num_rows($rsGroupe);
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
  <table align="center" class="std">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Libelle sous groupe :</th>
      <td><input type="text" name="sous_groupe_lib" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Groupe: ::</th>
      <td><select name="groupe_id">
        <option value="">:: Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsGroupe['groupe_id']?>"><?php echo $row_rsGroupe['groupe_lib']?></option>
        <?php
} while ($row_rsGroupe = mysql_fetch_assoc($rsGroupe));
  $rows = mysql_num_rows($rsGroupe);
  if($rows > 0) {
      mysql_data_seek($rsGroupe, 0);
	  $row_rsGroupe = mysql_fetch_assoc($rsGroupe);
  }
?>
      </select>
      <a href="#" onclick="<?php popup("create_groupe.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
    </tr>
  </table>
  <input type="hidden" name="sous_groupe_id" value="" />
  <input type="hidden" name="date_creation" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsGroupe);
?>
