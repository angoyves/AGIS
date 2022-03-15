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

$colname_rsSelect1 = "-1";
if (isset($_POST['txtChamp'])) {
  $colname_rsSelect1 = $_POST['txtChamp'];
}
$colname_rsSelect2 = "aa";
if (isset($_POST['txtSearch'])) {
  $colname_rsSelect2 = $_POST['txtSearch'];
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelect = sprintf("SELECT distinct(membres_personnes_personne_id), personne_nom, personne_prenom, fonction_id FROM personnes, sessions WHERE personnes.personne_id=sessions.membres_personnes_personne_id AND personnes.personne_nom like %s AND personnes.display = '1'", GetSQLValueString($colname_rsSelect2, "text"));
$rsSelect = mysql_query($query_rsSelect, $MyFileConnect) or die(mysql_error());
$row_rsSelect = mysql_fetch_assoc($rsSelect);
$totalRows_rsSelect = mysql_num_rows($rsSelect);

} else {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelect = "SELECT distinct(membres_personnes_personne_id), personne_nom, personne_prenom, fonction_id FROM personnes, sessions WHERE personnes.personne_id=sessions.membres_personnes_personne_id AND personnes.display = '1' ORDER BY personnes.personne_id ASC";
$rsSelect = mysql_query($query_rsSelect, $MyFileConnect) or die(mysql_error());
$row_rsSelect = mysql_fetch_assoc($rsSelect);
$totalRows_rsSelect = mysql_num_rows($rsSelect);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="sample118.php">
  <table border="0" class="std">
    <tr>
      <td><strong>Rechercher Par :</strong></td>
      <td><select name="txtChamp" id="txtChamp">
        <option value="personne_nom" <?php if (!(strcmp("personne_nom", $colname_rsSelect1))) {echo "selected=\"selected\"";} ?>>Nom</option>
        <option value="personne_id" <?php if (!(strcmp("personne_id", $colname_rsSelect1))) {echo "selected=\"selected\"";} ?>>ID</option>
        <option value="personne_prenom" <?php if (!(strcmp("personne_prenom", $colname_rsSelect1))) {echo "selected=\"selected\"";} ?>>Prenom</option>
      </select></td>
      <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo htmlentities($colname_rsSelect2, ENT_COMPAT, 'utf-8'); ?>" />
      <input name="menuID" type="hidden" id="menuID" value="<?php echo $_GET['menuID']; ?>" />
      <input name="action" type="hidden" id="action" value="<?php echo $_GET['action']; ?>" /></td>
      <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
    </tr>
    <tr>
      <td><?php echo $maxRows_rsAffichePerson; ?>&nbsp;<strong>Commission :</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="reset" name="button" id="button" value="Réinitialiser" />
      </label></td>
    </tr>
    <tr>
      <td colspan="4" align="right"><input type="hidden" name="MM_insert" value="form1" />
        Nombre d'enregistrements
      <select name="nbr_reg">
        <option value="10" <?php if (!(strcmp("10", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="30" <?php if (!(strcmp("30", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="60" <?php if (!(strcmp("60", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>60</option>
        <option value="100" <?php if (!(strcmp("100", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>100</option>
      </select>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><?php if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {?>
        Enregistrements trouvés pour votre recherche &quot; <strong> <?php echo $colname_rsSelect2; ?></strong> &quot; dans la colonne <strong> <?php echo $colname_rsSelect1; ?></strong>
        <?php } ?></td>
    </tr>
  </table>
</form>
<table border="1" align="center">
  <tr>
    <td>ID</td>
    <td>Nom &amp; Prenom</td>
    <td>Fonction</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="sample116.php?recordID=<?php echo $row_rsSelect['membres_personnes_personne_id']; ?>"> <?php echo $row_rsSelect['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsSelect['personne_nom']; ?></td>
      <td><?php echo $row_rsSelect['fonction_id']; ?></td>
      <td><a href="sample119.php?recordID=<?php echo $row_rsSelect['membres_personnes_personne_id']; ?>"><?php echo $row_rsSelect['membres_personnes_personne_id']; ?></a></td>
      <td><a href="sample119.php?recordID=<?php echo $row_rsSelect['membres_personnes_personne_id']; ?>"><?php echo $row_rsSelect['membres_personnes_personne_id']; ?></a></td>
    </tr>
    <?php } while ($row_rsSelect = mysql_fetch_assoc($rsSelect)); ?>
</table>
<br />
<?php echo $totalRows_rsSelect ?> Records Total
</body>
</html>
<?php
mysql_free_result($rsSelect);
?>
