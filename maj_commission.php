<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

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
  $updateSQL = sprintf("UPDATE commissions SET localite_id=%s, type_commission_id=%s, nature_id=%s, commission_lib=%s, region_id=%s, membre_insert=%s, dateUpdate=%s, display=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString($_POST['type_commission_id'], "int"),
                       GetSQLValueString($_POST['nature_id'], "int"),
                       GetSQLValueString($_POST['commission_lib'], "text"),
                       GetSQLValueString($_POST['region_id'], "int"),
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['commission_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  
  $updateSQL2 = sprintf("UPDATE commissions SET region_id=%s, dateUpdate=%s WHERE commission_parent=%s",
                       GetSQLValueString($_POST['region_id'], "int"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['commission_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result2 = mysql_query($updateSQL2, $MyFileConnect) or die(mysql_error());

  /*$updateGoTo = "show_commissions.php";
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

$colname_rsUpdCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsUpdCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdCommissions = sprintf("SELECT * FROM commissions WHERE commission_id = %s", GetSQLValueString($colname_rsUpdCommissions, "int"));
$rsUpdCommissions = mysql_query($query_rsUpdCommissions, $MyFileConnect) or die(mysql_error());
$row_rsUpdCommissions = mysql_fetch_assoc($rsUpdCommissions);
$totalRows_rsUpdCommissions = mysql_num_rows($rsUpdCommissions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocalite = "SELECT localite_id, localite_lib, display FROM localites WHERE display = '1'";
$rsLocalite = mysql_query($query_rsLocalite, $MyFileConnect) or die(mysql_error());
$row_rsLocalite = mysql_fetch_assoc($rsLocalite);
$totalRows_rsLocalite = mysql_num_rows($rsLocalite);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1' ORDER BY type_commission_lib ASC";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsNature = "SELECT nature_id, lib_nature FROM natures WHERE display = '1' ORDER BY lib_nature ASC";
$rsNature = mysql_query($query_rsNature, $MyFileConnect) or die(mysql_error());
$row_rsNature = mysql_fetch_assoc($rsNature);
$totalRows_rsNature = mysql_num_rows($rsNature);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = "SELECT commission_id, commission_lib, region_id FROM commissions WHERE type_commission_id = 3 AND display = '1' ORDER BY commission_lib ASC";
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRegions = "SELECT region_id, region_lib, display FROM regions WHERE display = '1'";
$rsRegions = mysql_query($query_rsRegions, $MyFileConnect) or die(mysql_error());
$row_rsRegions = mysql_fetch_assoc($rsRegions);
$totalRows_rsRegions = mysql_num_rows($rsRegions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <p>&nbsp;</p>
  <table align="center" class="std2">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Localite :</th>
      <td><select name="localite_id">
        <option value="">[ :: Select  ]</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsLocalite['localite_id']?>"<?php if (!(strcmp($row_rsLocalite['localite_id'], htmlentities($row_rsUpdCommissions['localite_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsLocalite['localite_lib']?></option>
        <?php
} while ($row_rsLocalite = mysql_fetch_assoc($rsLocalite));
  $rows = mysql_num_rows($rsLocalite);
  if($rows > 0) {
      mysql_data_seek($rsLocalite, 0);
	  $row_rsLocalite = mysql_fetch_assoc($rsLocalite);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Type commission :</th>
      <td><select name="type_commission_id">
        <option value="menuitem1">[ :: Select  ]</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsTypeCommission['type_commission_id']?>" <?php if (!(strcmp($row_rsTypeCommission['type_commission_id'], htmlentities($row_rsUpdCommissions['type_commission_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities(strtolower($row_rsTypeCommission['type_commission_lib']))?></option>
        <?php
} while ($row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission));
  $rows = mysql_num_rows($rsTypeCommission);
  if($rows > 0) {
      mysql_data_seek($rsTypeCommission, 0);
	  $row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Nature :</th>
      <td><select name="nature_id">
        <option value="menuitem1">[ :: Select  ]</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsNature['nature_id']?>"  <?php if (!(strcmp($row_rsNature['nature_id'], htmlentities($row_rsUpdCommissions['nature_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo strtolower($row_rsNature['lib_nature'])?></option>
        <?php
} while ($row_rsNature = mysql_fetch_assoc($rsNature));
  $rows = mysql_num_rows($rsNature);
  if($rows > 0) {
      mysql_data_seek($rsNature, 0);
	  $row_rsNature = mysql_fetch_assoc($rsNature);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th align="right" valign="top" nowrap="nowrap">Libelle Commission :</th>
      <td><textarea name="commission_lib" id="textarea" cols="45" rows="3"><?php echo htmlentities($row_rsUpdCommissions['commission_lib'], ENT_COMPAT, 'utf-8'); ?></textarea></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Region :</th>
      <td><select name="region_id">
        <option value="" <?php if (!(strcmp("", ucfirst($row_rsUpdCommissions['region_id'])))) {echo "selected=\"selected\"";} ?>>[ :: Select ]</option>
        <?php
do {  
?>
<option value="<?php echo $row_rsRegions['region_id']?>"<?php if (!(strcmp($row_rsRegions['region_id'], ucfirst($row_rsUpdCommissions['region_id'])))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsRegions['region_lib']); ?></option>
        <?php
} while ($row_rsRegions = mysql_fetch_assoc($rsRegions));
  $rows = mysql_num_rows($rsRegions);
  if($rows > 0) {
      mysql_data_seek($rsRegions, 0);
	  $row_rsRegions = mysql_fetch_assoc($rsRegions);
  }
?>
      </select>        <a href="#" onclick="<?php popup('new_region.php', "700", "400"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter region</a></td>
    </tr>
    <tr valign="baseline">
      <th rowspan="2" align="right" nowrap="nowrap">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td><input type="submit" value="Mettre à jour" /></td>
    </tr>
  </table>
  <input type="hidden" name="commission_id" value="<?php echo $row_rsUpdCommissions['commission_id']; ?>" />
  <input type="hidden" name="commission_parent" value="<?php echo $row_rsUpdCommissions['commission_parent']; ?>" />
  <input type="hidden" name="membre_insert" value="<?php echo htmlentities($row_rsUpdCommissions['membre_insert'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsUpdCommissions['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsUpdCommissions['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdCommissions['display'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="commission_id" value="<?php echo $row_rsUpdCommissions['commission_id']; ?>" />
</form>
</body>
</html>
<?php
mysql_free_result($rsUpdCommissions);

mysql_free_result($rsLocalite);

mysql_free_result($rsTypeCommission);

mysql_free_result($rsNature);

mysql_free_result($rsCommissions);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsRegions);

mysql_free_result($rsUpdCommissions);
?>