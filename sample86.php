<?php 
/*	require_once('Connections/MyFileConnect.php'); 
	require_once('includes/db.php');
	MinmapDB::getInstance()->update_session_appurement($_POST['mois'], $_POST['annee'], $_POST['commission'], $_POST['personne']);
	$insertGoTo = $_POST['url'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/
?>

<?php
	  require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $date = date('Y-m-d');
  $updateSQL = sprintf("UPDATE sessions SET etat_appur=%s, dateUpdate=%s, user_id=%s 
					   WHERE mois = %s
					   AND annee = %s
					   AND membres_commissions_commission_id=%s
					   AND membres_personnes_personne_id=%s",
                       GetSQLValueString($_POST['appur'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['user_id'], "int"),
					   GetSQLValueString($_POST['mois'], "text"),
					   GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['commission'], "int"),
					   GetSQLValueString($_POST['personne'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = $_POST['url'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
  
if ((isset($_POST["MM_update2"])) && ($_POST["MM_update2"] == "form2")) {
  $date = date('Y-m-d');
  $updateSQL = sprintf("UPDATE sessions SET etat_appur=%s, dateUpdate=%s, user_id=%s 
					   WHERE mois = %s
					   AND annee = %s
					   AND membres_commissions_commission_id=%s
					   AND membres_personnes_personne_id=%s",
                       GetSQLValueString($_POST['appur2'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['user_id2'], "int"),
					   GetSQLValueString($_POST['mois2'], "text"),
					   GetSQLValueString($_POST['annee2'], "text"),
                       GetSQLValueString($_POST['commission2'], "int"),
					   GetSQLValueString($_POST['personne2'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = $_POST['url2'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}
  /*echo GetSQLValueString($_POST['appur'], "text"). " " .
                       GetSQLValueString($date, "date"). " " .
                       GetSQLValueString($_POST['user_id'], "int"). " " .
					   GetSQLValueString($_POST['mois'], "text"). " " .
					   GetSQLValueString($_POST['annee'], "text"). " " .
                       GetSQLValueString($_POST['commission'], "int"). " " .
					   GetSQLValueString($_POST['personne'], "int");*/
}

?>