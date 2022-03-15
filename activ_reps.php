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

$editFormAction = $_SERVER['PHP_SELF'];
	if (isset($_SERVER['QUERY_STRING'])) {
	  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
	}
	
	if ((isset($_GET['action'])) && ($_GET['action'] == "desactive")) {
		if (isset($personaddcommission) && $personaddcommission==2){
			MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 0, $date);
			MinmapDB::getInstance()->activ_or_desactiv_membre_display_by_person_id($_GET['perID'], 0, $date, $_GET['comID']);
		} else {
			MinmapDB::getInstance()->activ_or_desactiv_membre_display_by_person_id($_GET['perID'], 0, $date, $_GET['comID']);
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
		if (isset($_POST['structure_id']) && $_POST['structure_id'] != ""){
			MinmapDB::getInstance()->update_person_structure_by_pers_id($_GET['perID'], $_POST['structure_id'], $date); 
		}
			MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 0, $date);
			MinmapDB::getInstance()->activ_or_desactiv_membre_display_by_person_id($_GET['perID'], 1, $date, $_GET['comID']);
			MinmapDB::getInstance()->activ_or_desactiv_person_add_rep_by_pers_id($_GET['perID'], 1, $date);
			
	} else {
	
		if (isset($_POST['structure_id']) && $_POST['structure_id'] != ""){
		MinmapDB::getInstance()->update_person_structure_by_pers_id($_GET['perID'], $_POST['structure_id'], $date); 
		}
		MinmapDB::getInstance()->create_membre($_GET['comID'],40,$_GET['perID'], GetMontantValue($typeCommission_id, 40), $date, 12);
		MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 0, $date);
		MinmapDB::getInstance()->activ_or_desactiv_membre_display_by_person_id($_GET['perID'], 1, $date, $_GET['comID']);
		MinmapDB::getInstance()->activ_or_desactiv_person_add_rep_by_pers_id($_GET['perID'], 1, $date);
	}
  $insertGoTo = "search_reps.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }

  /*echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";*/

  }
 header(sprintf("Location: %s", $insertGoTo));
//mysql_free_result($rsUdpUsers);
?>
