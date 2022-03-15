<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if ($_POST['annee']=="" ){
        $anneeIsEmpty = true;
    }
	
if ($_POST['nombre_dossier']==""){
    	$dossierIsEmpty = true;
    }
if ($_POST['mois']==""){
    	$moisIsEmpty = true;
    }

$date = date('Y-m-d H:i:s');
// debut new version
$commission = $_POST['commission_id'];
$month = $_POST['mois'];
$year = $_POST['annee'];
// debut controle si un enregistrement de ce mois existe dans la base
//$logonSuccess = (MinmapDB::getInstance()->verify_session_credentials($commission, $month, $year));
// debut controle de validité des controles
//if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") && !$logonSuccess && !$anneeIsEmpty && !$dossierIsEmpty && !$moisIsEmpty) {
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") ) {	
	//debut de la boucle
$x = 0; $count = 0; $variable = 0; do { $x++;
// debut boucle
//if ((isset($_GET['valid']))&& ($_GET['valid']=='mo') && isset($_POST['jour'.$x]) && $_POST['jour'.$x] !="") {
if ((isset($_POST['jour'.$x])) && $_POST['jour'.$x] !="") {
	//controle si l'enregistrement est à mo (maitre d'ouvrage) ou à mb (membre)

// ici on compare les valeurs pour assigner la plus grande valeur à la variable indemnité minmap
if ($count<=count($_POST['jour'.$x])){
	$variable = implode( "**", $_POST['jour'.$x] );
	$count = count($_POST['jour'.$x]); }
//debut insertion des valeurs du formulaire

$updateSQL = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s, display=%s WHERE membres_personnes_personne_id=%s AND mois = %s AND annee = %s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id'.$x], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString(count($_POST['jour'.$x]), "int"),
                       GetSQLValueString(implode( "**", $_POST['jour'.$x] ), "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['membres_personnes_personne_id'.$x], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  
} elseif ((isset($_GET['valid']))&& ($_GET['valid']=='mo') && isset($_POST['jour'.$x]) && $_POST['jour'.$x] !="") {
	
$updateSQL = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s, display=%s WHERE membres_personnes_personne_id =%s AND mois = %s AND annee = %s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id'.$x], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString(count($_POST['jour'.$x]), "int"),
                       GetSQLValueString(implode( "**", $_POST['jour'.$x] ), "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['membres_personnes_personne_id'.$x], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
 }
//fin boucle insertion
} while ($x < $_POST['counter']);

// debut insertion Indemnite MINMAP
if ((isset($_GET['valid']))&& ($_GET['valid']=='mb') && $count != 0) {
//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && $count != 0) {
$updateSQL = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s, display=%s WHERE membres_personnes_personne_id=%s AND mois = %s AND annee = %s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($count, "int"),
                       GetSQLValueString($variable, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['membres_personnes_personne_id'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
}
// fin insertion et debut redirection

  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}
//End Update

// Begin Insert

$logonSuccess = (MinmapDB::getInstance()->verify_session_credentials($commission, $month, $year));
// debut controle de validité des controles
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && !$logonSuccess && !$anneeIsEmpty && !$dossierIsEmpty && !$moisIsEmpty) {
	//debut de la boucle
$x = 0; $count = 0; $variable = 0; do { $x++;
// debut boucle
//if ((isset($_GET['valid']))&& ($_GET['valid']=='mo') && isset($_POST['jour'.$x]) && $_POST['jour'.$x] !="") {
if ((isset($_GET['valid']))&& ($_GET['valid']=='mb') && isset($_POST['jour'.$x]) && $_POST['jour'.$x] !="") {
	//controle si l'enregistrement est à mo (maitre d'ouvrage) ou à mb (membre)

// ici on compare les valeurs pour assigner la plus grande valeur à la variable indemnité minmap
if ($count<=count($_POST['jour'.$x])){
	$variable = implode( "**", $_POST['jour'.$x] );
	$count = count($_POST['jour'.$x]); }
//debut insertion des valeurs du formulaire
$insertSQL = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString(count($_POST['jour'.$x]), "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id'.$x], "int"),
                       GetSQLValueString($_POST['fonction_id'.$x], "int"),
                       GetSQLValueString(implode( "**", $_POST['jour'.$x] ), "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
}
//fin boucle insertion
} while ($x < $_POST['counter']);

// debut insertion Indemnite MINMAP
if ((isset($_GET['valid']))&& ($_GET['valid']=='mb') && $count != 0) {
//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && $count != 0) {
$insertSQL1 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($variable, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error()); }
// fin insertion et debut redirection

  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}
// End Insert

?>