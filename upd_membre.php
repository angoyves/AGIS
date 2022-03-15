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
  $updateSQL = sprintf("UPDATE membres SET montant=%s, display=%s, dateUpdate=%s, user_id=%s WHERE commissions_commission_id=%s AND fonctions_fonction_id=%s AND personnes_personne_id=%s",
                       GetSQLValueString($_POST['montant'], "text"),
                       GetSQLValueString(isset($_POST['display']) ? "true" : "", "defined","'1'","'0'"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_SESSION['MM_UserID'], "int"),
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
					   GetSQLValueString($_POST['fonctions_fonction_id'], "int"),
					   GetSQLValueString($_POST['personnes_personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

 /* $updateGoTo = "sample38.php";
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

$colnameComID_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colnameComID_rsMembres = $_GET['comID'];
}
$colnamePersID_rsMembres = "-1";
if (isset($_GET['persID'])) {
  $colnamePersID_rsMembres = $_GET['persID'];
}
$colnameFoncID_rsMembres = "-1";
if (isset($_GET['foncID'])) {
  $colnameFoncID_rsMembres = $_GET['foncID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s AND personnes_personne_id = %s AND fonctions_fonction_id = %s", GetSQLValueString($colnameComID_rsMembres, "int"),GetSQLValueString($colnamePersID_rsMembres, "int"),GetSQLValueString($colnameFoncID_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Montant Revisé:</td>
      <td><input type="text" name="montant" value="<?php echo htmlentities($row_rsMembres['montant'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Display:</td>
      <td><input type="checkbox" name="display" value=""  <?php if (!(strcmp(htmlentities($row_rsMembres['display'], ENT_COMPAT, 'utf-8'),"1"))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour le Montant" /></td>
    </tr>
  </table>
  <input type="hidden" name="commissions_commission_id" value="<?php echo $row_rsMembres['commissions_commission_id']; ?>" />
  <input type="hidden" name="fonctions_fonction_id" value="<?php echo htmlentities($row_rsMembres['fonctions_fonction_id'], ENT_COMPAT, 'utf-8'); ?>" />
 <input type="hidden" name="personnes_personne_id" value="<?php echo htmlentities($row_rsMembres['personnes_personne_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="checboxName" value="<?php echo htmlentities($row_rsMembres['checboxName'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="position" value="<?php echo htmlentities($row_rsMembres['position'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsMembres['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsMembres['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="user_id" value="<?php echo htmlentities($row_rsMembres['user_id'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="commissions_commission_id" value="<?php echo $row_rsMembres['commissions_commission_id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsMembres);
?>
