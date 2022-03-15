<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php
  if (!isset($_SESSION)) {
  session_start();
  }
  
  $bank_code = substr(trim($_REQUEST['rID']),0,5);
  $agence_code = substr(trim($_REQUEST['rID']),5,5);
  $num_compte = substr(trim($_REQUEST['rID']),10,11);
  $cle = substr(trim($_REQUEST['rID']),21,2);
  $personne_id = $_REQUEST['persID'];
  $person_id = trim($_REQUEST['pID']);
  $action = $_REQUEST['actID'];
  $duedate = date('Y-m-d H:i:s');
  
  if (isset($action) && $action == 'ins')
  //MinmapDB::getInstance()->insert_rib($personne_id, 'xxxxx', 'xxxxx', 'xxxxxxxxxxx', 'xx', $_SESSION['MM_UserID'], $duedate);
  MinmapDB::getInstance()->insert_rib_by_person_id($personne_id, 'xxxxx', 'xxxxx', 'xxxxxxxxxxx', 'xx', $_SESSION['MM_UserID'], $duedate);
  
  if (isset($action) && $action == 'del') {
  MinmapDB::getInstance()->delete_record('membres', 'membres_personnes_personne_id', $personne_id);
  MinmapDB::getInstance()->delete_record('personnes', 'personne_id', $personne_id);
  MinmapDB::getInstance()->delete_record('rib', 'personne_id', $personne_id); }

  
 /*   echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>"; */

  $updateGoTo = "search_reps.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
  header(sprintf("Location: %s", $updateGoTo));
?>