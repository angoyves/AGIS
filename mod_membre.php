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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($_GET['comID']);
  $updateSQL = sprintf("UPDATE membres SET fonctions_fonction_id=%s, montant=%s, position=%s, dateUpdate=%s, user_id=%s WHERE personnes_personne_id=%s AND commissions_commission_id=%s AND fonctions_fonction_id=%s",
                       GetSQLValueString($_POST['fonctions_fonction_id'], "int"),
                       GetMontantValue($typeCommission_id, GetSQLValueString($_POST['fonctions_fonction_id'], "int")),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_GET['perID'], "int"),
					   GetSQLValueString($_GET['comID'], "int"),
					   GetSQLValueString($_GET['fonID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

 /* $updateGoTo = "sample23.php";
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

$colname_rsMembre = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembre = $_GET['comID'];
}
$colname2_rsMembre = "-1";
if (isset($_GET['fonID'])) {
  $colname2_rsMembre = $_GET['fonID'];
}
$colname3_rsMembre = "-1";
if (isset($_GET['perID'])) {
  $colname3_rsMembre = $_GET['perID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembre = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s  AND membres.fonctions_fonction_id = %s AND membres.personnes_personne_id = %s", GetSQLValueString($colname_rsMembre, "int"),GetSQLValueString($colname2_rsMembre, "int"),GetSQLValueString($colname3_rsMembre, "int"));
$rsMembre = mysql_query($query_rsMembre, $MyFileConnect) or die(mysql_error());
$row_rsMembre = mysql_fetch_assoc($rsMembre);
$totalRows_rsMembre = mysql_num_rows($rsMembre);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = "SELECT fonction_id, fonction_lib, groupe_fonction_id FROM fonctions WHERE groupe_fonction_id = 3 ORDER BY fonction_id ASC";
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);
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
      <td nowrap="nowrap" align="right">Fonctions:</td>
      <td><select name="fonctions_fonction_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonction['fonction_id']?>"<?php if (!(strcmp($row_rsFonction['fonction_id'], htmlentities($_GET['fonID'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsFonction['fonction_lib']?></option>
        <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Personnes:</td>
      <td><input type="text" name="personnes_personne_id" value="<?php echo MinmapDB::getInstance()->get_personne_name_by_person_id($row_rsMembre['personnes_personne_id']); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Position:</td>
      <td><!--<input type="text" name="position2" value="<?php //echo htmlentities($row_rsMembre['position'], ENT_COMPAT, 'utf-8'); ?>" size="32" />-->
        <select name="position" id="position">
          <option value="1" <?php if (!(strcmp(1, $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>>1</option>
          <option value="2" <?php if (!(strcmp(2, $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>>2</option>
          <option value="3" <?php if (!(strcmp(3, $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>>3</option>
          <option value="4" <?php if (!(strcmp(4, $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>>4</option>
          <option value="5" <?php if (!(strcmp(5, $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>>5</option>
          <option value="6" <?php if (!(strcmp(6, $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>>6</option>
          <option value="7" <?php if (!(strcmp(7, $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>>7</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsMembre['position']?>"<?php if (!(strcmp($row_rsMembre['position'], $row_rsMembre['position']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsMembre['position']?></option>
          <?php
} while ($row_rsMembre = mysql_fetch_assoc($rsMembre));
  $rows = mysql_num_rows($rsMembre);
  if($rows > 0) {
      mysql_data_seek($rsMembre, 0);
	  $row_rsMembre = mysql_fetch_assoc($rsMembre);
  }
?>
        </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="commissions_commission_id" value="<?php echo htmlentities($row_rsMembre['commissions_commission_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="montant" value="<?php echo htmlentities($row_rsMembre['montant'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="checboxName" value="<?php echo htmlentities($row_rsMembre['checboxName'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsMembre['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsMembre['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_id" value="<?php echo htmlentities($row_rsMembre['user_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personnes_personne_id" value="<?php echo $row_rsMembre['personnes_personne_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsMembre);

mysql_free_result($rsFonction);
?>
