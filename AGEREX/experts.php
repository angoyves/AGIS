<?php require_once('../Connections/AGEREX.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO experts (expertID, nomExpert, prenomExpert, dateNaissance, lieuNaissance, numeroCNI, telephone, fax, email, adresse, dateDesignation, domaineCompetenceID, localisationID, Qualifications_qualificationID, sanctionne, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['expertID'], "int"),
                       GetSQLValueString($_POST['nomExpert'], "text"),
                       GetSQLValueString($_POST['prenomExpert'], "text"),
                       GetSQLValueString($_POST['dateNaissance'], "date"),
                       GetSQLValueString($_POST['lieuNaissance'], "text"),
                       GetSQLValueString($_POST['numeroCNI'], "double"),
                       GetSQLValueString($_POST['telephone'], "double"),
                       GetSQLValueString($_POST['fax'], "double"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['adresse'], "text"),
                       GetSQLValueString($_POST['dateDesignation'], "text"),
                       GetSQLValueString($_POST['domaineCompetenceID'], "int"),
                       GetSQLValueString($_POST['localisationID'], "int"),
                       GetSQLValueString($_POST['Qualifications_qualificationID'], "int"),
                       GetSQLValueString(isset($_POST['sanctionne']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['display']) ? "true" : "", "defined","1","0"));

  mysql_select_db($database_AGEREX, $AGEREX);
  $Result1 = mysql_query($insertSQL, $AGEREX) or die(mysql_error());

  $insertGoTo = "showExperts.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_AGEREX, $AGEREX);
$query_rsQualification = "SELECT * FROM domaine WHERE display = '1'";
$rsQualification = mysql_query($query_rsQualification, $AGEREX) or die(mysql_error());
$row_rsQualification = mysql_fetch_assoc($rsQualification);
$totalRows_rsQualification = mysql_num_rows($rsQualification);

mysql_select_db($database_AGEREX, $AGEREX);
$query_rsDomaineCompetence = "SELECT * FROM domaine WHERE display = '1'";
$rsDomaineCompetence = mysql_query($query_rsDomaineCompetence, $AGEREX) or die(mysql_error());
$row_rsDomaineCompetence = mysql_fetch_assoc($rsDomaineCompetence);
$totalRows_rsDomaineCompetence = mysql_num_rows($rsDomaineCompetence);
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
      <td nowrap="nowrap" align="right">Nom Expert:</td>
      <td><input type="text" name="nomExpert" value="" size="32" /></td>
      <td rowspan="2">&nbsp;</td>
    </tr>
<tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Prenom Expert:</td>
      <td><input type="text" name="prenomExpert" value="" size="32" /></td>
      <td>&nbsp;</td>
</tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Date de Naissance:</td>
      <td><input type="text" name="dateNaissance" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Lieu de Naissance:</td>
      <td><input type="text" name="lieuNaissance" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Numero CNI:</td>
      <td><input type="text" name="numeroCNI" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Telephone:</td>
      <td><input type="text" name="telephone" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fax:</td>
      <td><input type="text" name="fax" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email:</td>
      <td><input type="text" name="email" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Adresse:</td>
      <td><input type="text" name="adresse" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Date Designation:</td>
      <td><input type="text" name="dateDesignation" value="" size="32" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Domaine de Competence:</td>
      <td><label>
        <input name="textfield" type="text" id="textfield" value="<?php echo $row_rsDomaineCompetence['TypeDomaines_typeDomaineID']; ?>" />
      </label></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Localisation:</td>
      <td><select name="localisationID">
        <option value="menuitem1" >[ Etiquette ]</option>
        <option value="menuitem2" >[ Etiquette ]</option>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Qualifications:</td>
      <td><select name="Qualifications_qualificationID">
        <option value="" <?php if (!(strcmp("", $row_rsQualification['domaineCompetenceID']))) {echo "selected=\"selected\"";} ?>>Ajouter...</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsQualification['domaineCompetenceID']?>"<?php if (!(strcmp($row_rsQualification['domaineCompetenceID'], $row_rsQualification['domaineCompetenceID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsQualification['domaineName']?></option>
        <?php
} while ($row_rsQualification = mysql_fetch_assoc($rsQualification));
  $rows = mysql_num_rows($rsQualification);
  if($rows > 0) {
      mysql_data_seek($rsQualification, 0);
	  $row_rsQualification = mysql_fetch_assoc($rsQualification);
  }
?>
      </select></td>
      <td>&nbsp;</td>
    </tr>
    <tr> </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Sanctionne:</td>
      <td><input type="checkbox" name="sanctionne" value="" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Display:</td>
      <td><input name="display" type="checkbox" value="" checked="checked" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Ins&eacute;rer un enregistrement" /></td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="expertID" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<form method="POST" action="upload.php" enctype="application/x-www-form-urlencoded">
     <!-- On limite le fichier à 100Ko -->
     
     Fichier : 
     
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsQualification);

mysql_free_result($rsDomaineCompetence);
?>
