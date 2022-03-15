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


if (isset($_REQUEST['comID']))
$commission_id = $_REQUEST['comID'];

$fonction_id = 3;
/*if (isset($_REQUEST['foncID']))
$fonction_id = $_REQUEST['foncID'];*/

if (isset($_REQUEST['persID']))
$personne_id = $_REQUEST['persID'];


 $typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($commission_id);
 //$user_id = MinmapDB::getInstance()->get_user_id_by_name($_GET['uID']);
 //$personne = MinmapDB::getInstance()->verify_personne_id($personne_id);

 $verifyIsOk = MinmapDB::getInstance()->verify_membre($commission_id, $personne_id, $fonction_id);
 
 if (verifyIsOk) {
 MinmapDB::getInstance()->activ_or_desactiv_membre($personne_id, 2, $date, $commission_id, $fonction_id);
 } else {
 MinmapDB::getInstance()->create_membre($commission_id, $fonction_id, $personne_id, GetMontantValue($typeCommission_id, GetSQLValueString($fonction_id, "int")), $date, $user_id);
 }
  

 $updateSQL1 = sprintf("UPDATE personnes SET user_id=%s, add_commission=%s, dateUpdate=%s WHERE personne_id=%s",
                       GetSQLValueString($user_id, "text"),
					   GetSQLValueString(2, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($personne_id, "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL1, $MyFileConnect) or die(mysql_error());

  $updatesSQL = sprintf("UPDATE commissions SET membre_insert=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($commission_id, "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Resultes = mysql_query($updatesSQL, $MyFileConnect) or die(mysql_error());


  /*$insertGoTo = "sample26.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/
 
 echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";

?>