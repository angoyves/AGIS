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

$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user_name']));
$compteur = (MinmapDB::getInstance()->get_compteur_by_name($_POST['user_name']));

if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2,3,4,5,6,7,8";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE rib SET agence_cle=%s, banque_code=%s, agence_code=%s, numero_compte=%s, date_creation=%s, dateUpdate=%s, cle=%s WHERE personne_id=%s",
                       GetSQLValueString($_POST['banque_code'].$_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['numero_compte'], "text"),
                       GetSQLValueString($_POST['date_creation'], "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['cle'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  
  $compteur = $compteur + 1;
  $updateSQL = sprintf("UPDATE users SET  compteur=%s WHERE user_id=%s",
						   GetSQLValueString($compteur, "int"),
						   GetSQLValueString($user_id, "int"));
	
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  
  $insertSQL = sprintf("INSERT INTO listing_action (UserId, PageLink, ModificationType, DateUpdate) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($user_id, "int"),
                       GetSQLValueString('upd_rib.php?recordID='.$_GET['recordID'], "text"),
                       GetSQLValueString('Modification du RIB : '.$_POST['old_rib'].' par le RIB : '.$_POST['banque_code'].'-'.$_POST['agence_code']
					   .'-'.$_POST['numero_compte']
					   .'-'.$_POST['cle']
					   .' cree le '.$_POST['date_creation']
					   .' par '.$_POST['personne_id'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result3 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());


  /*$updateGoTo = "show_personnes.php";
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

$colname_rsUpdRib = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdRib = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdRib = sprintf("SELECT * FROM rib WHERE personne_id = %s", GetSQLValueString($colname_rsUpdRib, "int"));
$rsUpdRib = mysql_query($query_rsUpdRib, $MyFileConnect) or die(mysql_error());
$row_rsUpdRib = mysql_fetch_assoc($rsUpdRib);
$totalRows_rsUpdRib = mysql_num_rows($rsUpdRib);

$colname_rsPersonne = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsPersonne = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonne = sprintf("SELECT personne_nom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsPersonne, "int"));
$rsPersonne = mysql_query($query_rsPersonne, $MyFileConnect) or die(mysql_error());
$row_rsPersonne = mysql_fetch_assoc($rsPersonne);
$totalRows_rsPersonne = mysql_num_rows($rsPersonne);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std2">
    <tr valign="baseline">
      <td colspan="2" nowrap="nowrap"><h1><?php echo $row_rsPersonne['personne_nom']; ?></h1></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Code Banque:</th>
      <td><input name="banque_code" type="text" value="<?php echo htmlentities($row_rsUpdRib['banque_code'], ENT_COMPAT, 'utf-8'); ?>" size="15" maxlength="5" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Code Agence:</th>
      <td><input name="agence_code" type="text" value="<?php echo htmlentities($row_rsUpdRib['agence_code'], ENT_COMPAT, 'utf-8'); ?>" size="15" maxlength="5" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">N° de Compte:</th>
      <td><input name="numero_compte" type="text" value="<?php echo htmlentities($row_rsUpdRib['numero_compte'], ENT_COMPAT, 'utf-8'); ?>" size="32" maxlength="11" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Cle:</th>
      <td><input name="cle" type="text" value="<?php echo htmlentities($row_rsUpdRib['cle'], ENT_COMPAT, 'utf-8'); ?>" size="10" maxlength="2" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="old_rib" value="<?php echo $row_rsUpdRib['banque_code'].'-'.$row_rsUpdRib['agence_code'].'-'.$row_rsUpdRib['numero_compte'].'-'.$row_rsUpdRib['cle']; ?>" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdRib['personne_id']; ?>" />
  <input type="hidden" name="personne_matricule" value="<?php echo htmlentities($row_rsUpdRib['personne_matricule'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="agence_cle" value="<?php echo htmlentities($row_rsUpdRib['agence_cle'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="date_creation" value="<?php echo htmlentities($row_rsUpdRib['date_creation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsUpdRib['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdRib['personne_id']; ?>" />
  <input type="hidden" name="user_name" value="<?php echo $_SESSION['MM_Username']; ?>" size="32" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUpdRib);

mysql_free_result($rsPersonne);
?>
