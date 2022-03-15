<?php 
	  require_once('Connections/MyFileConnect.php'); 
	  require_once("Includes/db.php");
?>
<?php
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE commissions SET localite_id=%s, type_commission_id=%s, nature_id=%s, structure_id=%s, commission_lib=%s, commission_parent=%s, montant_cumul=%s, nombre_offre=%s, membre_insert=%s, dateCreation=%s, dateUpdate=%s, display=%s, user_id=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString($_POST['type_commission_id'], "int"),
                       GetSQLValueString($_POST['nature_id'], "int"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['commission_lib'], "text"),
                       GetSQLValueString($_POST['commission_parent'], "int"),
                       GetSQLValueString($_POST['montant_cumul'], "text"),
                       GetSQLValueString($_POST['nombre_offre'], "text"),
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($_POST['dateCreation'], "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['commission_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "show_commission.php?comID=".$_GET['comID'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdateCommission = "SELECT * FROM commissions";
$rsUpdateCommission = mysql_query($query_rsUpdateCommission, $MyFileConnect) or die(mysql_error());
$row_rsUpdateCommission = mysql_fetch_assoc($rsUpdateCommission);
$totalRows_rsUpdateCommission = mysql_num_rows($rsUpdateCommission);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table border="1" align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Localite :</td>
      <td><select name="localite_id">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", htmlentities($row_rsUpdateCommission['localite_id'], ENT_COMPAT, 'utf-8') ))) {echo "SELECTED";} ?>>[ Etiquette ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", htmlentities($row_rsUpdateCommission['localite_id'], ENT_COMPAT, 'utf-8') ))) {echo "SELECTED";} ?>>[ Etiquette ]</option>
      </select></td>
      <td rowspan="7">fhjffjggffhg</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Type commission :</td>
      <td><select name="type_commission_id">
        <option value="" >[ Etiquette ]</option>
        <?php combo_sel_value("type_commissions", "type_commission_id", "type_commission_lib", "type_commission_lib", "type_commission_id") ?>
        </option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nature :</td>
      <td><select name="nature_id">
        <option value="menuitem2" >[ Etiquette ]</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Structure :</td>
      <td><select name="structure_id">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", htmlentities($row_rsUpdateCommission['structure_id'], ENT_COMPAT, 'utf-8') ))) {echo "SELECTED";} ?>>[ Etiquette ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", htmlentities($row_rsUpdateCommission['structure_id'], ENT_COMPAT, 'utf-8') ))) {echo "SELECTED";} ?>>[ Etiquette ]</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Libelle :</td>
      <td><input type="text" name="commission_lib" value="<?php htmlentities($row_rsUpdateCommission['commission_lib'], ENT_COMPAT, 'utf-8') ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Commission Dependante (pour les sous commssions):</td>
      <td><select name="commission_parent">
        <option value="menuitem1" <?php if (!(strcmp("menuitem1", htmlentities($row_rsUpdateCommission['commission_parent'], ENT_COMPAT, 'utf-8') ))) {echo "SELECTED";} ?>>[ Etiquette ]</option>
        <option value="menuitem2" <?php if (!(strcmp("menuitem2", htmlentities($row_rsUpdateCommission['commission_parent'], ENT_COMPAT, 'utf-8') ))) {echo "SELECTED";} ?>>[ Etiquette ]</option>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="commission_id" value="<?php echo $row_rsUpdateCommission['commission_id']; ?>" />
  <input type="hidden" name="montant_cumul" value="<?php echo htmlentities($row_rsUpdateCommission['montant_cumul'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="nombre_offre" value="<?php echo htmlentities($row_rsUpdateCommission['nombre_offre'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="membre_insert" value="<?php echo htmlentities($row_rsUpdateCommission['membre_insert'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsUpdateCommission['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsUpdateCommission['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdateCommission['display'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_id" value="<?php echo htmlentities($row_rsUpdateCommission['user_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="commission_id" value="<?php echo $row_rsUpdateCommission['commission_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdateCommission);
?>
