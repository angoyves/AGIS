<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php
  $structure_id = MinmapDB::getInstance()->get_structure_id_by_personne_id($_GET['perID']);
  if (isset($structure_id) && $structure_id != 0){
  $updateGoTo = "activ_rep.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
  } else {
  $updateGoTo = "sample20.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
  }
  
  header(sprintf("Location: %s", $updateGoTo));
?>