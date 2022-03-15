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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE membres SET commissions_commission_id=%s, fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateCreation=%s, display=%s WHERE personnes_personne_id=%s",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonctions_fonction_id'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($_POST['Nombre_jour'], "int"),
                       GetSQLValueString($_POST['jour'], "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['dateCreation'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['personnes_personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "show_membres.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUpdmembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsUpdmembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdmembres = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s", GetSQLValueString($colname_rsUpdmembres, "int"));
$rsUpdmembres = mysql_query($query_rsUpdmembres, $MyFileConnect) or die(mysql_error());
$row_rsUpdmembres = mysql_fetch_assoc($rsUpdmembres);
$totalRows_rsUpdmembres = "-1";
if (isset($_GET['comID'])) {
  $totalRows_rsUpdmembres = $_GET['comID'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdmembres = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s", GetSQLValueString($colname_rsUpdmembres, "int"));
$rsUpdmembres = mysql_query($query_rsUpdmembres, $MyFileConnect) or die(mysql_error());
$row_rsUpdmembres = mysql_fetch_assoc($rsUpdmembres);
$totalRows_rsUpdmembres = mysql_num_rows($rsUpdmembres);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = "SELECT fonction_id, fonction_lib FROM fonctions WHERE display = '1' AND groupe_fonction_id = '3'";
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Commission :</td>
      <td><input type="text" name="commissions_commission_id" value="<?php echo htmlentities($row_rsUpdmembres['commissions_commission_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Dossier :</td>
      <td><input type="text" name="dossiers_dossier_id" value="<?php echo htmlentities($row_rsUpdmembres['dossiers_dossier_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nombre dossier:</td>
      <td><input type="text" name="nombre_dossier" value="<?php echo htmlentities($row_rsUpdmembres['nombre_dossier'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nombre jour:</td>
      <td><input type="text" name="Nombre_jour" value="<?php echo htmlentities($row_rsUpdmembres['Nombre_jour'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Jour:</td>
      <td><input type="text" name="jour" value="<?php echo htmlentities($row_rsUpdmembres['jour'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mois:</td>
      <td><input type="text" name="mois" value="<?php echo htmlentities($row_rsUpdmembres['mois'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Annee:</td>
      <td><input type="text" name="annee" value="<?php echo htmlentities($row_rsUpdmembres['annee'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Update record" /></td>
    </tr>
  </table>
  <p>&nbsp;
  <table border="1" align="center">
    <tr>
      <td>Personne</td>
      <td>Commission</td>
      <td>Fonction</td>
      <td>Montant</td>
    </tr>
    <?php do { ?>
      <tr>
        <td><a href="upd_membre.php?recordID=<?php echo $row_rsUpdmembres['personnes_personne_id']; ?>"> <?php echo $row_rsUpdmembres['personnes_personne_id']; ?>&nbsp; </a></td>
        <td><?php echo $row_rsUpdmembres['commissions_commission_id']; ?>&nbsp; </td>
        <td><?php echo $row_rsUpdmembres['fonctions_fonction_id']; ?>
          <select name="fonctions_fonction_id">
            <option value="menuitem1" <?php if (!(strcmp("menuitem1", htmlentities($row_rsUpdmembres['fonctions_fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsUpdmembres['fonctions_fonction_id']; ?></option>
            <option value="menuitem2" <?php if (!(strcmp("menuitem2", htmlentities($row_rsUpdmembres['fonctions_fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsUpdmembres['fonctions_fonction_id']; ?>	</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsFonction['fonction_id']?>"<?php if (!(strcmp($row_rsFonction['fonction_id'], htmlentities($row_rsUpdmembres['fonctions_fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsFonction['fonction_lib']?></option>
            <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
          </select>          &nbsp; </td>
        <td><input type="text" name="montant" id="montant" value="<?php echo $row_rsUpdmembres['montant']; ?>" />&nbsp; </td>
      </tr>
      <?php } while ($row_rsUpdmembres = mysql_fetch_assoc($rsUpdmembres)); ?>
  </table>
  <br />
  <?php echo $totalRows_rsUpdmembres ?> Records Total
  </p>
  <p>
    <input type="hidden" name="personnes_personne_id" value="<?php echo $row_rsUpdmembres['personnes_personne_id']; ?>" />
    <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsUpdmembres['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdmembres['display'], ENT_COMPAT, 'utf-8'); ?>" />
    <input type="hidden" name="MM_update" value="form1" />
    <input type="hidden" name="personnes_personne_id" value="<?php echo $row_rsUpdmembres['personnes_personne_id']; ?>" />
  </p>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdmembres);

mysql_free_result($rsFonction);
?>
