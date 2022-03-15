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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$date = date('Y-m-d H:i:s');


$ref_dossier = MinmapDB::getInstance()->get_ref_dossier($_POST['ref_dossier']);
	
$insertSQL = sprintf("INSERT INTO ordre_virements (ref_dossier, motif_saisie, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['ref_dossier'], "text"),
                       GetSQLValueString($_POST['motif_saisie'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
  
$compter = 0; do { $compter++;
$add_appur = MinmapDB::getInstance()->get_person_add_appur($_POST['personne_id'.$compter]); // ici on controle que la personne est dans l'etat d'appurement
if (isset($add_appur) && $add_appur == '1') {  
  $insertSQL = sprintf("INSERT INTO appurements (num_virement, commission_id, personne_id, fonction_id, ref_dossier, nombre_jour, etat_appur, periode_debut, periode_fin, annee, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['num_virement'.$compter], "int"),
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['personne_id'.$compter], "int"),
                       GetSQLValueString($_POST['fonction_id'.$compter], "int"),
                       GetSQLValueString($_POST['ref_dossier'], "text"),
                       GetSQLValueString($_POST['nombre_jour'.$compter], "int"),
                       GetSQLValueString($_POST['etat_appur'], "text"),
                       GetSQLValueString($_POST['periode_debut'], "text"),
                       GetSQLValueString($_POST['periode_fin'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($_POST['user_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $month = $_POST['periode_debut']; 
  // ici on met a jour la table session 
  do { 
  $updateSQL = sprintf("UPDATE sessions SET etat_appur=%s, dateUpdate=%s WHERE membres_commissions_commission_id=%s AND membres_personnes_personne_id = %s AND mois=%s AND annee=%s AND etat_appur=0",
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['commission_id'], "int"),
					   GetSQLValueString($_POST['personne_id'.$compter], "int"),
					   GetSQLValueString($month, "int"),
					   GetSQLValueString($_POST['annee'], "text"));

  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
  	$Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
	$month++;
  }  while ($month < $_POST['periode_fin']);
  
  }
  MinmapDB::getInstance()->update_person_appur($_POST['personne_id'.$compter], date()); //on reinitialise la personne
  }  while ($compter < $_POST['nombre_personne']);

  $updateGoTo = "sample113.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));


?>
