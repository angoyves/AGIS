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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$region_id = MinmapDB::getInstance()->get_region_id_by_localite_id($_GET['locID']);
$localite_id = MinmapDB::getInstance()->get_localite_id_by_region_id($region_id);
$commission_id = MinmapDB::getInstance()->get_commission_id_by_localite_id($localite_id);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  //$region_id = MinmapDB::getInstance()->get_region_id_by_localite_id($_GET['locID']);
  $updateSQL = sprintf("UPDATE commissions SET commission_parent = %s, localite_id=%s, nature_id=%s, type_commission_id=%s, structure_id=%s, dateUpdate=%s, display=%s WHERE commission_id=%s",
                       GetSQLValueString($commission_id, "int"),
					   GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString($_POST['nature_id'], "int"),
                       GetSQLValueString($_POST['type_commission_id'], "int"),
					   GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_GET['comID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

 /* $updateGoTo = "localites.php";
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

$colname_rsLocalites = "-1";
if (isset($_GET['locID'])) {
  $colname_rsLocalites = $_GET['locID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocalites = sprintf("SELECT * FROM localites WHERE localite_id = %s", GetSQLValueString($colname_rsLocalites, "int"));
$rsLocalites = mysql_query($query_rsLocalites, $MyFileConnect) or die(mysql_error());
$row_rsLocalites = mysql_fetch_assoc($rsLocalites);
$totalRows_rsLocalites = mysql_num_rows($rsLocalites);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRegions = "SELECT * FROM regions WHERE display = '1' ORDER BY region_lib ASC";
$rsRegions = mysql_query($query_rsRegions, $MyFileConnect) or die(mysql_error());
$row_rsRegions = mysql_fetch_assoc($rsRegions);
$totalRows_rsRegions = mysql_num_rows($rsRegions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeLocalite = "SELECT * FROM type_localites WHERE display = '1'";
$rsTypeLocalite = mysql_query($query_rsTypeLocalite, $MyFileConnect) or die(mysql_error());
$row_rsTypeLocalite = mysql_fetch_assoc($rsTypeLocalite);
$totalRows_rsTypeLocalite = mysql_num_rows($rsTypeLocalite);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocalite2 = "SELECT * FROM localites WHERE display = '1'";
$rsLocalite2 = mysql_query($query_rsLocalite2, $MyFileConnect) or die(mysql_error());
$row_rsLocalite2 = mysql_fetch_assoc($rsLocalite2);
$totalRows_rsLocalite2 = mysql_num_rows($rsLocalite2);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsNature = "SELECT * FROM natures WHERE display = '1'";
$rsNature = mysql_query($query_rsNature, $MyFileConnect) or die(mysql_error());
$row_rsNature = mysql_fetch_assoc($rsNature);
$totalRows_rsNature = mysql_num_rows($rsNature);

$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, localite_id, type_commission_id, nature_id, structure_id, commission_parent FROM commissions WHERE commission_id = %s", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommissions = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommissions = mysql_query($query_rsTypeCommissions, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommissions = mysql_fetch_assoc($rsTypeCommissions);
$totalRows_rsTypeCommissions = mysql_num_rows($rsTypeCommissions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructures = "SELECT structure_id, structure_lib FROM structures WHERE display = '1' ORDER BY structure_lib ASC";
$rsStructures = mysql_query($query_rsStructures, $MyFileConnect) or die(mysql_error());
$row_rsStructures = mysql_fetch_assoc($rsStructures);
$totalRows_rsStructures = mysql_num_rows($rsStructures);
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
      <td nowrap="nowrap" align="right">Localite:</td>
      <td><label>
          <select name="localite_id" id="localite_id">
            <?php
do {  
?>
            <option value="<?php echo $row_rsLocalite2['localite_id']?>"<?php if (!(strcmp($row_rsLocalite2['localite_id'], $row_rsCommissions['localite_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsLocalite2['localite_lib']?></option>
            <?php
} while ($row_rsLocalite2 = mysql_fetch_assoc($rsLocalite2));
  $rows = mysql_num_rows($rsLocalite2);
  if($rows > 0) {
      mysql_data_seek($rsLocalite2, 0);
	  $row_rsLocalite2 = mysql_fetch_assoc($rsLocalite2);
  }
?>
          </select>
      </label></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nature:</td>
      <td><select name="nature_id" id="nature_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsNature['nature_id']?>"<?php if (!(strcmp($row_rsNature['nature_id'], $row_rsCommissions['nature_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsNature['lib_nature']?></option>
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
      <td nowrap="nowrap" align="right">Type commission:</td>
      <td><select name="type_commission_id" id="type_commission_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsTypeCommissions['type_commission_id']?>"<?php if (!(strcmp($row_rsTypeCommissions['type_commission_id'], $row_rsCommissions['type_commission_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTypeCommissions['type_commission_lib']?></option>
        <?php
} while ($row_rsTypeCommissions = mysql_fetch_assoc($rsTypeCommissions));
  $rows = mysql_num_rows($rsTypeCommissions);
  if($rows > 0) {
      mysql_data_seek($rsTypeCommissions, 0);
	  $row_rsTypeCommissions = mysql_fetch_assoc($rsTypeCommissions);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Structure</td>
      <td><select name="structure_id" id="structure_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsStructures['structure_id']?>"<?php if (!(strcmp($row_rsStructures['structure_id'], $row_rsCommissions['structure_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsStructures['structure_lib']?></option>
        <?php
} while ($row_rsStructures = mysql_fetch_assoc($rsStructures));
  $rows = mysql_num_rows($rsStructures);
  if($rows > 0) {
      mysql_data_seek($rsStructures, 0);
	  $row_rsStructures = mysql_fetch_assoc($rsStructures);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="commission_parent" value="<?php echo $commission_id; ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsCommissions['date_creation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_update" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsLocalites);

mysql_free_result($rsRegions);

mysql_free_result($rsTypeLocalite);

mysql_free_result($rsLocalite2);

mysql_free_result($rsNature);

mysql_free_result($rsCommissions);

mysql_free_result($rsTypeCommissions);

mysql_free_result($rsStructures);
?>
