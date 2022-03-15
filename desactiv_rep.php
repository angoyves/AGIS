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

$typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($_GET['comID']);
$personaddcommission = MinmapDB::getInstance()->get_if_person_add_commission_by_pers_id($_GET['perID']);
$verifyRepresentant = MinmapDB::getInstance()->verify_commissions_representant($_GET['comID'], $_GET['perID']);
//$verifyPersonAddCommission = MinmapDB::getInstance()->verify_person_add_commission($_GET['comID'], $_GET['perID']);

$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}

	if ($verifyRepresentant){
		  MinmapDB::getInstance()->activ_or_desactiv_person_display_by_pers_id($_GET['perID'], 1, $date); 
		  MinmapDB::getInstance()->activ_or_desactiv_membre_display_by_person_id($_GET['perID'], 1, $date, $_GET['comID']);
	} else {
		  //$Montant = GetMontantValue($typeCommission_id, 40);
		  //MinmapDB::getInstance()->create_membre($_GET['comID'],40,$_GET['perID'], $Montant,$date);
		  //MinmapDB::getInstance()->activ_or_desactiv_person_add_rep_by_pers_id($_GET['perID'], 1, $date);
		  $insertSQL = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($_GET['comID'], "int"),
							   GetSQLValueString(40, "int"),
							   GetSQLValueString($_GET['perID'], "int"),
							   GetMontantValue( GetSQLValueString($typeCommission_id, "int")),
							   GetSQLValueString("un", "text"),
							   GetSQLValueString(1, "int"),
							   GetSQLValueString($date, "date"),
							   GetSQLValueString(1, "int"));
		
		$updateSQL = sprintf("UPDATE personnes SET add_commission='0', add_rep='1', dateUpdate=%s WHERE personne_id=%s",
							   GetSQLValueString($_GET['perID'], "int"), GetSQLValueString($date, "date"));
		
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
		$Results1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
	}

  echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
?>
