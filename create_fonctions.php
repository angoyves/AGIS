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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO membres (fonctions_fonction_id, commissions_commission_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['fonctions_fonction_id'], "int"),
                       GetSQLValueString($_POST['commissions_commission_id'], "int"));

  $insertSQL2 = sprintf("INSERT INTO membres (fonctions_fonction_id, commissions_commission_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['fonctions_fonction_id2'], "int"),
                       GetSQLValueString($_POST['commissions_commission_id'], "int"));
					   
  $insertSQL3 = sprintf("INSERT INTO membres (fonctions_fonction_id, commissions_commission_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['fonctions_fonction_id3'], "int"),
                       GetSQLValueString($_POST['commissions_commission_id'], "int"));
					   
  $insertSQL4 = sprintf("INSERT INTO membres (fonctions_fonction_id, commissions_commission_id) VALUES (%s, %s)",
                       GetSQLValueString($_POST['fonctions_fonction_id4'], "int"),
                       GetSQLValueString($_POST['commissions_commission_id'], "int")); 
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());
  $Result3 = mysql_query($insertSQL3, $MyFileConnect) or die(mysql_error());
  $Result4 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error());
  
  $insertGoTo = "detail_commission_fonction.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = "SELECT * FROM commissions";
$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
$row_rsCommission = mysql_fetch_assoc($rsCommission);
$totalRows_rsCommission = mysql_num_rows($rsCommission);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = "SELECT fonction_id, fonction_lib FROM fonctions WHERE display = '1'";
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td align="right" valign="top" nowrap="nowrap">Commission :</td>
      <td colspan="2"><select name="commissions_commission_id" size="15">
        <?php
do {  
?>
        
  <?php 
$colname_rsLocaliteUrl = $row_rsCommission['localite_id'];
if (isset($_SERVER['localite_id'])) {
  $colname_rsLocaliteUrl = $_SERVER['localite_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocaliteUrl = sprintf("SELECT localite_lib FROM localites WHERE localite_id = %s", GetSQLValueString($colname_rsLocaliteUrl, "int"));
$rsLocaliteUrl = mysql_query($query_rsLocaliteUrl, $MyFileConnect) or die(mysql_error());
$row_rsLocaliteUrl = mysql_fetch_assoc($rsLocaliteUrl);
$totalRows_rsLocaliteUrl = mysql_num_rows($rsLocaliteUrl);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsNature = "SELECT lib_nature FROM natures WHERE display = '1'";
$rsNature = mysql_query($query_rsNature, $MyFileConnect) or die(mysql_error());
$row_rsNature = mysql_fetch_assoc($rsNature);
$totalRows_rsNature = mysql_num_rows($rsNature);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);
?>
        <option value="<?php echo $row_rsCommission['commission_id']?>"><?php echo $row_rsTypeCommission['type_commission_lib'] . " de " . $row_rsNature['lib_nature'] . " de(du) " . $row_rsLocaliteUrl['localite_lib']?></option>
        <?php
} while ($row_rsCommission = mysql_fetch_assoc($rsCommission));
  $rows = mysql_num_rows($rsCommission);
  if($rows > 0) {
      mysql_data_seek($rsCommission, 0);
	  $row_rsCommission = mysql_fetch_assoc($rsCommission);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">Ajouter des membres </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">Fonction 1 :</td>
      <td><select name="fonctions_fonction_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonction['fonction_id']?>"><?php echo $row_rsFonction['fonction_lib']?></option>
        <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">Fonction 2 :</td>
      <td><select name="fonctions_fonction_id2">
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonction['fonction_id']?>"><?php echo $row_rsFonction['fonction_lib']?></option>
        <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">Fonction 3 :</td>
      <td><select name="fonctions_fonction_id3">
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonction['fonction_id']?>"><?php echo $row_rsFonction['fonction_lib']?></option>
        <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">Fonction 4 :</td>
      <td><select name="fonctions_fonction_id4">
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonction['fonction_id']?>"><?php echo $row_rsFonction['fonction_lib']?></option>
        <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">Autres membres (Personnels independant...)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record" /></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsCommission);

mysql_free_result($rsFonction);

mysql_free_result($rsLocaliteUrl);

mysql_free_result($rsNature);

mysql_free_result($rsTypeCommission);
?>
