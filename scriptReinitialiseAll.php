<?php require_once('Connections/MyFileConnect.php'); ?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<!--<script type="text/javascript">

return confirm('Etes vous sur de vouloir créer une nouvelle Sous-commission ?')

</script>//-->
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,4,6,7";
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

$MM_restrictGoTo = "accesdenied.php";
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

  //if (isset($_GET['champ']) || $_GET['champ'] == 1){
  $champ1 = 'sessions.membres_commissions_commission_id';
 // } else {
  $champ2 = 'commissions.commission_parent';
 // }
  if (isset($_GET['mID']))
  //$mois = GetSQLValueString($_GET['mID'], "text");
  $mois = $_GET['mID'];
  
  if (isset($_GET['aID']))
  //$annee = GetSQLValueString($_GET['aID'], "text");
  $annee = $_GET['aID'];
  
  if (isset($_GET['comID']))
  $commission_id = GetSQLValueString($_GET['comID'], "int");
  //$commission_id = $_GET['comID'];
  
  if (isset($_SESSION['MM_UserID']))
  $UserId = GetSQLValueString($_SESSION['MM_UserID'], "int");
  
  if (isset($_GET['value']))
  //$value = GetSQLValueString($_GET['value'], "text");
  $value = $_GET['value'];
  
  if (isset($_GET['champ']))
  //$value = GetSQLValueString($_GET['value'], "text");
  $champ = $_GET['champ'];
  
  $PageLink = GetSQLValueString('sample57.php?comID='.$_GET['comID'].'&menuID='.$_GET['menuID'].'&lID='.$_GET['lID'], "text");
  $ModificationType = GetSQLValueString('Validation par les SERVICES DU CONTROLE de l\'etat du '.$_GET['mID'].'/'.$_GET['aID'].' pour '.$_GET['comID'], "text");
  $date = date('Y-m-d H:i:s');
  //$counter=0; do { $counter++; 
  //echo '$champ'.$counter;
  //MinmapDB::getInstance()->validate_sessions(commission_id, 1, $date, 03, 2014, 527, 12);
  //echo $champ1.'-'.$value.'-'.$date.'-'.$mois.'-'.$annee.'-'.$commission_id.'-'.$UserId;
  //MinmapDB::getInstance()->validate_sessions($value, $date, $mois, $annee, $commission_id, $UserId, $champ);
  MinmapDB::getInstance()->validate_sessions_all($value, $date, $annee, $commission_id, $UserId, $champ);
  //MinmapDB::getInstance()->create_listing_action($UserId, $PageLink, $ModificationType, $DateUpdate);

	$colname_rsCommission = "-1";
	if (isset($_GET['comID'])) {
	  $colname_rsCommission = $_GET['comID'];
	}
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$query_rsCommission = sprintf("SELECT commission_id FROM commissions WHERE commission_parent = %s", GetSQLValueString($colname_rsCommission, "int"));
	$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
	$row_rsCommission = mysql_fetch_assoc($rsCommission);
	$totalRows_rsCommission = mysql_num_rows($rsCommission);

   do { 
     MinmapDB::getInstance()->validate_sessions_all($value, $date, $annee, $row_rsCommission['commission_id'], $UserId, $champ);
   } while ($row_rsCommission = mysql_fetch_assoc($rsCommission));

   echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
?>