<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');
?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$DATE = date('Y-m-d H:i:s');
if (isset($_POST['jour1'])){
	$nbre_jour1 = count($_POST['jour1']); 
	$champJour1 = implode('**',$_POST['jour1']);
	}
	
if (isset($_POST['jour2'])){
	$nbre_jour2 = count($_POST['jour2']); 
	$champJour2 = implode('**',$_POST['jour2']);}

if (isset($_POST['jour3'])){
	$nbre_jour3 = count($_POST['jour3']); 
	$champJour3 = implode('**',$_POST['jour3']);}

if (isset($_POST['jour4'])){
	$nbre_jour4 = count($_POST['jour4']); 
	$champJour4 = implode('**',$_POST['jour4']);}
	
if (isset($_POST['jour5'])){
	$nbre_jour5 = count($_POST['jour5']); 
	$champJour5 = implode('**',$_POST['jour5']);}

if (isset($_POST['jour6'])){
	$nbre_jour6 = count($_POST['jour6']); 
	$champJour6 = implode('**',$_POST['jour6']);}

if (isset($_POST['jour7'])){
	$nbre_jour7 = count($_POST['jour7']); 
	$champJour7 = implode('**',$_POST['jour7']);}
	

if (isset($_POST['membres_personnes_personne_id1'])) {
MinmapDB::getInstance()->update_session($_POST['membres_personnes_personne_id1'], $_POST['mois2'], $_POST['annee2'], $nbre_jour1, $champJour1, $date, $_POST['mois'], $_POST['annee']);
}
if (isset($_POST['membres_personnes_personne_id2'])) {
MinmapDB::getInstance()->update_session($_POST['membres_personnes_personne_id2'], $_POST['mois2'], $_POST['annee2'], $nbre_jour2, $champJour2, $date, $_POST['mois'], $_POST['annee']);
}
if (isset($_POST['membres_personnes_personne_id3'])) {
MinmapDB::getInstance()->update_session($_POST['membres_personnes_personne_id3'], $_POST['mois2'], $_POST['annee2'], $nbre_jour3, $champJour3, $date, $_POST['mois'], $_POST['annee']);
}
if (isset($_POST['membres_personnes_personne_id4'])) {
MinmapDB::getInstance()->update_session($_POST['membres_personnes_personne_id4'], $_POST['mois2'], $_POST['annee2'], $nbre_jour4, $champJour4, $date, $_POST['mois'], $_POST['annee']);
}
if (isset($_POST['membres_personnes_personne_id5'])) {
MinmapDB::getInstance()->update_session($_POST['membres_personnes_personne_id5'], $_POST['mois2'], $_POST['annee2'], $nbre_jour5, $champJour5, $date, $_POST['mois'], $_POST['annee']);
}
if (isset($_POST['membres_personnes_personne_id6'])) {
MinmapDB::getInstance()->update_session($_POST['membres_personnes_personne_id6'], $_POST['mois2'], $_POST['annee2'], $nbre_jour6, $champJour6, $date, $_POST['mois'], $_POST['annee']);
}
if (isset($_POST['membres_personnes_personne_id7'])) {
MinmapDB::getInstance()->update_session($_POST['membres_personnes_personne_id7'], $_POST['mois2'], $_POST['annee2'], $nbre_jour7, $champJour7, $date, $_POST['mois'], $_POST['annee']);
}
/*$updateSQL1 = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s WHERE membres_personnes_personne_id=%s AND mois=%s AND annee=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id1'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($nbre_jour1, "int"),
                       GetSQLValueString($champJour1, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['membres_personnes_personne_id'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text")); } 

if (isset($_POST['membres_personnes_personne_id2'])) {
$updateSQL2 = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s WHERE membres_personnes_personne_id=%s AND mois=%s AND annee=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id2'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($nbre_jour2, "int"),
                       GetSQLValueString($champJour2, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['membres_personnes_personne_id2'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text")); }

if (isset($_POST['membres_personnes_personne_id3'])) {
$updateSQL3 = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s WHERE membres_personnes_personne_id=%s AND mois=%s AND annee=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id3'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($nbre_jour3, "int"),
                       GetSQLValueString($champJour3, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['membres_personnes_personne_id3'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text")); }

if (isset($_POST['membres_personnes_personne_id4'])) {
$updateSQL4 = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s WHERE membres_personnes_personne_id=%s AND mois=%s AND annee=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id4'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($nbre_jour4, "int"),
                       GetSQLValueString($champJour4, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['membres_personnes_personne_id4'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text")); }

if (isset($_POST['membres_personnes_personne_id5'])) {
$updateSQL5 = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s WHERE membres_personnes_personne_id=%s AND mois=%s AND annee=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id5'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($nbre_jour5, "int"),
                       GetSQLValueString($champJour5, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['membres_personnes_personne_id5'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text")); }

if (isset($_POST['membres_personnes_personne_id6'])) {
$updateSQL6 = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s WHERE membres_personnes_personne_id=%s AND mois=%s AND annee=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id6'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($nbre_jour6, "int"),
                       GetSQLValueString($champJour6, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['membres_personnes_personne_id6'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text")); }

if (isset($_POST['membres_personnes_personne_id7'])) {
$updateSQL7 = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateUpdate=%s WHERE membres_personnes_personne_id=%s AND mois=%s AND annee=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id7'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($nbre_jour7, "int"),
                       GetSQLValueString($champJour7, "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['membres_personnes_personne_id7'], "int"),
                       GetSQLValueString($_POST['mois2'], "text"),
                       GetSQLValueString($_POST['annee2'], "text")); }



  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  if (isset($updateSQL1)) {
  $Result1 = mysql_query($updateSQL1, $MyFileConnect) or die(mysql_error());}
  if (isset($updateSQL2)) {
  $Result2 = mysql_query($updateSQL2, $MyFileConnect) or die(mysql_error());}
  if (isset($updateSQL3)) {
  $Result3 = mysql_query($updateSQL3, $MyFileConnect) or die(mysql_error());}
  if (isset($updateSQL4)) {
  $Result4 = mysql_query($updateSQL4, $MyFileConnect) or die(mysql_error());}
  if (isset($updateSQL5)) {
  $Result5 = mysql_query($updateSQL5, $MyFileConnect) or die(mysql_error());}
  if (isset($updateSQL6)) {
  $Result6 = mysql_query($updateSQL6, $MyFileConnect) or die(mysql_error());}
  if (isset($updateSQL7)) {
  $Result7 = mysql_query($updateSQL7, $MyFileConnect) or die(mysql_error());} */


  $updateGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_free_result($rsUpdSessions);
?>