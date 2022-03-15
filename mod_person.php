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
  $updateSQL = sprintf("UPDATE personnes SET personne_matricule=%s, personne_nom=%s, personne_prenom=%s, personne_grade=%s, personne_telephone=%s, personne_specialisation=%s, personne_qualification=%s, structure_id=%s, sous_groupe_id=%s, fonction_id=%s, domaine_id=%s, user_id=%s, personne_date_nais=%s, type_personne_id=%s, localite_id=%s, add_commission=%s, add_rep=%s, date_creation=%s, dateUpdate=%s, display=%s WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString($_POST['personne_nom'], "text"),
                       GetSQLValueString($_POST['personne_prenom'], "text"),
                       GetSQLValueString($_POST['personne_grade'], "text"),
                       GetSQLValueString($_POST['personne_telephone'], "text"),
                       GetSQLValueString($_POST['personne_specialisation'], "text"),
                       GetSQLValueString($_POST['personne_qualification'], "text"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['sous_groupe_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($_POST['domaine_id'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['personne_date_nais'], "date"),
                       GetSQLValueString($_POST['type_personne_id'], "int"),
                       GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString($_POST['add_commission'], "text"),
                       GetSQLValueString($_POST['add_rep'], "text"),
                       GetSQLValueString($_POST['date_creation'], "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

 /* $updateGoTo = "sample23.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo)); */
     echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
}

$colname_rsPersonnes = "-1";
if (isset($_GET['perID'])) {
  $colname_rsPersonnes = $_GET['perID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = sprintf("SELECT * FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsPersonnes, "int"));
$rsPersonnes = mysql_query($query_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);
$totalRows_rsPersonnes = mysql_num_rows($rsPersonnes);

$colname_rsStructures = "-1";
if (isset($_GET['locID'])) {
  $colname_rsStructures = $_GET['locID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructures = sprintf("SELECT structure_id, code_structure, localite_id FROM structures WHERE localite_id = %s", GetSQLValueString($colname_rsStructures, "int"));
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
      <td nowrap="nowrap" align="right">Nom:</td>
      <td><input type="text" name="personne_nom" value="<?php echo ucwords($row_rsPersonnes['personne_nom']); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Prenom:</td>
      <td><input type="text" name="personne_prenom" value="<?php echo htmlentities($row_rsPersonnes['personne_prenom'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Numero Telephone:</td>
      <td><input type="text" name="personne_telephone" value="<?php echo htmlentities($row_rsPersonnes['personne_telephone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Structure:</td>
      <td><select name="structure_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsStructures['structure_id']?>"<?php if (!(strcmp($row_rsStructures['structure_id'], strtolower($row_rsPersonnes['structure_id'])))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsStructures['code_structure']?></option>
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
      <td nowrap="nowrap" align="right">Localite:</td>
      <td><input name="textfield" type="text" disabled="disabled" id="textfield" value="<?php echo strtoupper(MinmapDB::getInstance()->get_localite_lib_by_localite_id($row_rsStructures['localite_id'])); ?>" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Modifier" /></td>
    </tr>
  </table>
  <input type="hidden" name="personne_id" value="<?php echo $row_rsPersonnes['personne_id']; ?>" />
  <input type="hidden" name="personne_matricule" value="<?php echo htmlentities($row_rsPersonnes['personne_matricule'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="personne_grade" value="<?php echo htmlentities($row_rsPersonnes['personne_grade'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="personne_specialisation" value="<?php echo htmlentities($row_rsPersonnes['personne_specialisation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="personne_qualification" value="<?php echo htmlentities($row_rsPersonnes['personne_qualification'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="sous_groupe_id" value="<?php echo htmlentities($row_rsPersonnes['sous_groupe_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="fonction_id" value="<?php echo htmlentities($row_rsPersonnes['fonction_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="domaine_id" value="<?php echo htmlentities($row_rsPersonnes['domaine_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_id" value="<?php echo htmlentities($row_rsPersonnes['user_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="personne_date_nais" value="<?php echo htmlentities($row_rsPersonnes['personne_date_nais'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="type_personne_id" value="<?php echo htmlentities($row_rsPersonnes['type_personne_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="add_commission" value="<?php echo htmlentities($row_rsPersonnes['add_commission'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="add_rep" value="<?php echo htmlentities($row_rsPersonnes['add_rep'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="add_appur" value="<?php echo htmlentities($row_rsPersonnes['add_appur'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="date_creation" value="<?php echo htmlentities($row_rsPersonnes['date_creation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsPersonnes['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="display" value="<?php echo htmlentities($row_rsPersonnes['display'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input name="localite_id" type="hidden" value="<?php echo htmlentities($row_rsStructures['localite_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsPersonnes['personne_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsPersonnes);

mysql_free_result($rsStructures);
?>
