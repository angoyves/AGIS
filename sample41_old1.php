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
	  
		$commissionIsEmpty = false;
		$personneIsUnique = true;
		$personneIsEmpty1 = false;
		$fonctionIsEmpty2 = false;
		$fonctionIsEmpty3 = false;
		$fonctionIsEmpty4 = false;
		$fonctionIsEmpty5 = false;
		$fonctionIsEmpty6 = false;
		$fonctionIsEmpty7 = false;	
		$fonctionIsEmpty = false;	
		$sessionIsUnique = true;
		$date = date('Y-m-d H:i:s');
	  ?>
<?php
$typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($_POST['commission_id']);
$counter=0; do { $counter++;
	if (isset($_POST['fonction_id'.$counter]) && $_POST['fonction_id'.$counter]==""){	
		$fonctionIsEmpty = true;		
	}
} while ($counter < $_POST['nombre_personne']);
	
	if ($_POST['commission_id']==""){	
		$commissionIsEmpty = true;
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if (!$commissionIsEmpty && !$fonctionIsEmpty ){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$counter=0; do { $counter++;
if (isset($_POST['personne_id'.$counter]) && isset($_POST['fonction_id'.$counter])) {
/* $verifymembre = (MinmapDB::getInstance()->verify_commissions_representant($_POST['commission_id'], $_POST['personne_id'.$counter]));
 
 if($verifymembre){
	 
 $updateSQL = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id'.$counter], "int"));
 
 mysql_select_db($database_MyFileConnect, $MyFileConnect);
 $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
 
}
else
{*/
 $user_id = (MinmapDB::getInstance()->get_user_id_by_name($_GET['uID']));
 $personne = (MinmapDB::getInstance()->verify_personne_id($_POST['personne_id'.$counter]));
 //if (!$personne){
 $insertSQL = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position, dateCreation, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'.$counter], "int"),
                       GetSQLValueString($_POST['personne_id'.$counter], "int"),
					   //GetSQLValueString($_POST['montant_id'.$counter], "text"),
					   GetMontantValue($typeCommission_id, GetSQLValueString($_POST['fonction_id'.$counter], "int")),
					   GetSQLValueString("un", "text"),
                       GetSQLValueString(1, "int"),
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"));  }
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
//}
  
/* $updateSQL = sprintf("UPDATE personnes SET add_commission='2', dateUpdate=%s, user_id=%s WHERE personne_id=%s",
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"),
					   GetSQLValueString($_POST['personne_id'.$counter], "int"));
 
 mysql_select_db($database_MyFileConnect, $MyFileConnect);
 $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());*/
 
// }
 $updateSQL1 = sprintf("UPDATE personnes SET user_id=%s, add_commission=%s, dateUpdate=%s WHERE personne_id=%s",
                       GetSQLValueString($user_id, "text"),
					   GetSQLValueString(2, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['personne_id'.$counter], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL1, $MyFileConnect) or die(mysql_error());
  
 // MinmapDB::getInstance()->insert_sessions($_POST['commission_id'], 3, 0, 0, $_GET['mID'], $_GET['aID'], $_POST['personne_id'.$counter], $_POST['fonction_id'.$counter], 1, $date, $user_id, 1);
 
$insertSQL = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, user_id, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString(3, "int"),
                       GetSQLValueString(array_sum($_POST['nombre_dossier']), "int"),
	   				   GetSQLValueString(count(1), "int"),
                       GetSQLValueString($_GET['mID'], "text"),
                       GetSQLValueString($_GET['aID'], "text"),
                       GetSQLValueString($_POST['personne_id'.$counter], "int"),
                       GetSQLValueString($_POST['fonction_id'.$counter], "int"),
                       GetSQLValueString(0, "text"),
                       GetSQLValueString($date, "date"),
	    			   GetSQLValueString($user_id, "int"),
                       GetSQLValueString(1, "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

} while ($counter < $_POST['nombre_personne']);


 $updatesSQL = sprintf("UPDATE commissions SET membre_insert=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($_POST['commission_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Resultes = mysql_query($updatesSQL, $MyFileConnect) or die(mysql_error());
  
  ?>