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
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_REQUEST['recID']))
	$value_id = $_REQUEST['recID'];
if (isset($_REQUEST['map']))
	$table = $_REQUEST['map'];
if (isset($_REQUEST['mapid']))
	$champ_id = $_REQUEST['mapid'];	
if (isset($_REQUEST['display']))
	$display = $_REQUEST['display'];

 $PageLink = 'show_all_ov.php';
 $ModificationType = 'Desactivation de l\'identifaint numero :'.$value_id.' dans le fichier des OV';
	
$date = date('Y-m-d H:i:s');
$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_GET['uID']));	
	
MinmapDB::getInstance()->change_etat_index($table, $champ_id, $value_id, $display);
MinmapDB::getInstance()->create_listing_action($_SESSION['MM_UserID'], $PageLink, $ModificationType, date('Y-m-d H:i:s'));

      echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";

?>
