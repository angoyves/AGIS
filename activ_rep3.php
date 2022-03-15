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

$date = date('Y-m-d H:i:s');

$typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($_GET['comID']);
$personaddcommission = MinmapDB::getInstance()->get_if_person_add_commission_by_pers_id($_GET['perID']);
$verifyRepresentant = (MinmapDB::getInstance()->verify_commissions_representant($_GET['comID'], $_GET['perID']));
$verifyPersonAddCommission = (MinmapDB::getInstance()->verify_person_add_commission($_GET['comID'], $_GET['perID']));

$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if ((isset($_GET['action'])) && ($_GET['action'] == "desactive")) {
		if (isset($personaddcommission) && $personaddcommission==2){
			MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 0, $date);
			MinmapDB::getInstance()->activ_or_desactiv_membre_by_person_id($_GET['perID'], 0, $date, $_GET['comID']);
		} else {
			MinmapDB::getInstance()->activ_or_desactiv_membre_by_person_id($_GET['perID'], 0, $date, $_GET['comID']);
		//$fonction_id = (MinmapDB::getInstance()->get_membre_fonction_by_personne_id($_GET['perID']));
		/*$updateSQL = sprintf("UPDATE membres SET display='0', dateUpdate=%s WHERE commissions_commission_id=%s AND fonctions_fonction_id=%s AND personnes_personne_id=%s",
							   GetSQLValueString($date, "date"),
							   GetSQLValueString($_GET['comID'], "int"),
							   GetSQLValueString(40, "int"),
							   GetSQLValueString($_GET['perID'], "int"));
	
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$Result = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());*/
		}
	
	  $updateGoTo = "sample23.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  header(sprintf("Location: %s", $updateGoTo));
	}
	
	//ici on active le representant
  if ((isset($_GET["action"])) && ($_GET["action"] == "active")) {

	if ($verifyRepresentant){ // si il es deja dans le table membre
		
	    if (isset($_GET['modID']) && ($_GET['modID']==1)){
			MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 2, $date);
			MinmapDB::getInstance()->activ_or_desactiv_membre_by_person_id($_GET['perID'], 1, $date, $_GET['comID']);
			
		} 
		elseif (isset($_POST['structure_id']) && $_POST['structure_id'] != ""){
		$updateSQL6 = sprintf("UPDATE personnes SET structure_id=%s, dateUpdate=%s WHERE personne_id = %s",
							   GetSQLValueString($_POST['structure_id'], "int"), GetSQLValueString($date, "date"), GetSQLValueString($_GET['perID'], "int"));
		
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$Results6 = mysql_query($updateSQL6, $MyFileConnect) or die(mysql_error());
		}
		
		$updateSQL = sprintf("UPDATE membres SET display='1', dateUpdate=%s WHERE commissions_commission_id = %s AND personnes_personne_id=%s",
							   GetSQLValueString($date, "date"), GetSQLValueString($_GET['comID'], "int"), GetSQLValueString($_GET['perID'], "int"));
		
		$updateSQL1 = sprintf("UPDATE personnes SET add_commission='0', add_rep='1', dateUpdate=%s WHERE personne_id=%s",
							   GetSQLValueString($_POST['perID'], "int"), GetSQLValueString($date, "date"));
		
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
		$Results1 = mysql_query($updateSQL1, $MyFileConnect) or die(mysql_error());
		} else {
		//$fonction_id = (MinmapDB::getInstance()->get_membre_fonction_by_personne_id($_GET['perID']));
	    if (isset($_POST['structure_id']) && $_POST['structure_id'] != ""){
		$updateSQL7 = sprintf("UPDATE personnes SET structure_id=%s, dateUpdate=%s WHERE personne_id = %s",
							   GetSQLValueString($_POST['structure_id'], "int"), GetSQLValueString($date, "date"), GetSQLValueString($_GET['perID'], "int"));
		
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$Results7 = mysql_query($updateSQL7, $MyFileConnect) or die(mysql_error());
		}
		
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
	
		//$updateGoTo = "search_rep.php?action=close";
		}
	}
  //$updateGoTo = "index.php";
  /*if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));*/
  echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";

mysql_free_result($rsUdpUsers);
?>
