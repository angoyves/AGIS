<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php
  $bank_code = substr(trim($_REQUEST['rID']),0,5);
  $agence_code = substr(trim($_REQUEST['rID']),5,5);
  $num_compte = substr(trim($_REQUEST['rID']),10,11);
  $cle = substr(trim($_REQUEST['rID']),21,2);
  $personne_id = $_REQUEST['perID'];
  $person_id = trim($_REQUEST['pID']);
  $duedate = date('Y-m-d H:i:s');
  
  if (isset($personne_id))
  MinmapDB::getInstance()->update_rib($bank_code, $agence_code, $num_compte, $cle, $personne_id, $duedate);
  
  if (isset($person_id))
  MinmapDB::getInstance()->update_rib('xxxxx', 'xxxxx', 'xxxxxxxxxxx', 'xx', $person_id, $duedate);
  
  $updateGoTo = "view_appurements.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
  header(sprintf("Location: %s", $updateGoTo));
?>