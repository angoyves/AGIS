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
  $updateSQL = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateCreation=%s, dateUpdate=%s, display=%s WHERE membres_personnes_personne_id=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($_POST['Nombre_jour'], "int"),
                       GetSQLValueString($_POST['jour'], "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['dateCreation'], "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['membres_personnes_personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "inde.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpd = "SELECT membres_personnes_personne_id, display FROM sessions";
$rsUpd = mysql_query($query_rsUpd, $MyFileConnect) or die(mysql_error());
$row_rsUpd = mysql_fetch_assoc($rsUpd);
$totalRows_rsUpd = mysql_num_rows($rsUpd);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);
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
      <td nowrap="nowrap" align="right">Membres_personnes_personne_id:</td>
      <td><?php echo $row_rsUpd['membres_personnes_personne_id']; ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Membres_commissions_commission_id:</td>
      <td><input type="text" name="membres_commissions_commission_id" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Membres_fonctions_fonction_id:</td>
      <td><input type="text" name="membres_fonctions_fonction_id" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Dossiers_dossier_id:</td>
      <td><input type="text" name="dossiers_dossier_id" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nombre_dossier:</td>
      <td><input type="text" name="nombre_dossier" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nombre_jour:</td>
      <td><input type="text" name="Nombre_jour" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Jour:</td>
      <td><input type="text" name="jour" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mois:</td>
      <td><select name="mois">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsMois['mois_id']?>" <?php if (!(strcmp($row_rsUpdSessions['mois_id'], $row_rsMois['mois_id']))) {echo "SELECTED";} ?>><?php echo $row_rsMois['lib_mois']?></option>
        <?php
} while ($row_rsMois = mysql_fetch_assoc($rsMois));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Annee:</td>
      <td><select name="annee">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsUpdSessions['lib_annee'], $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
        <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
?>
      </select></td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">DateCreation:</td>
      <td><input type="text" name="dateCreation" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">DateUpdate:</td>
      <td><input type="text" name="dateUpdate" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Display:</td>
      <td><input type="text" name="display" value="<?php echo htmlentities($row_rsUpd['display'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="membr