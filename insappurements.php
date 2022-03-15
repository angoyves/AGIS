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
  $insertSQL = sprintf("INSERT INTO appurements (appurement_id, commission_id, num_virement, rib_beneficiaire, nom_beneficiaire, ref_dossier, motif, montant, periode_debut, periode_fin, annee, `date`, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['appurement_id'], "double"),
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['num_virement'], "text"),
                       GetSQLValueString($_POST['rib_beneficiaire'], "text"),
                       GetSQLValueString($_POST['nom_beneficiaire'], "text"),
                       GetSQLValueString($_POST['ref_dossier'], "text"),
                       GetSQLValueString($_POST['motif'], "text"),
                       GetSQLValueString($_POST['montant'], "double"),
                       GetSQLValueString($_POST['periode_debut'], "text"),
                       GetSQLValueString($_POST['periode_fin'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['date'], "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "show_all_ov.php?txtSearch=nom4";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
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
      <td nowrap="nowrap" align="right">Commission:</td>
      <td><input name="commission_id" type="text" value="" size="10" maxlength="15" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Numero Virement:</td>
      <td><input name="num_virement" type="text" value="" size="32" maxlength="20" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">RIB Beneficiaire</td>
      <td><input name="rib_beneficiaire" type="text" value="" size="32" maxlength="23" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nom Beneficiaire:</td>
      <td><input type="text" name="nom_beneficiaire" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Reference Dossier:</td>
      <td><input type="text" name="ref_dossier" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Motif:</td>
      <td><input type="text" name="motif" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Montant:</td>
      <td><input type="text" name="montant" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Periode debut:</td>
      <td><input name="periode_debut" type="text" value="01" size="15" maxlength="2" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Periode fin:</td>
      <td><input name="periode_fin" type="text" value="12" size="15" maxlength="2" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Annee:</td>
      <td><input type="text" name="annee" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Ins&eacute;rer un enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="appurement_id" value="" size="32" />
  <input type="hidden" name="date" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>