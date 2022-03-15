<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php 
$dossierIsEmpty = false;
$anneeIsEmpty = false;
$moisIsEmpty = false;
//$logonSuccess = false;
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

if ($_POST['annee']=="" ){
        $anneeIsEmpty = true;
    }
	
if ($_POST['nombre_dossier']==""){
    	$dossierIsEmpty = true;
    }
if ($_GET['moisID']==""){
    	$moisIsEmpty = true;
    }

$DATE = date('Y-m-d H:i:s');
$count1 = 0;
$count2 = 0;
$count3 = 0;
$count4 = 0;
$count5 = 0;
$count6 = 0;
$count7 = 0;
$variable1 =0;
$variable2 =0;
$variable3 =0;
$variable4 =0;
$variable5 =0;
$variable6 =0;
$variable7 =0;

/*
if (isset($_POST['personne_id1']) && $_POST['personne_id1'] != "")	{
$variable1 = implode( "**", $_POST['jour1'] ); 
$count1 = count($_POST['jour1']);  }

if (isset($_POST['personne_id2']) && $_POST['personne_id2'] != "")	{ 
$variable2 = implode( "**", $_POST['jour2'] ); 
$count2 = count($_POST['jour2']); }

if (isset($_POST['personne_id3']) && $_POST['personne_id3'] != "")	{
$variable3 = implode( "**", $_POST['jour3'] ); 
$count3 = count($_POST['jour3']); }

if (isset($_POST['personne_id4']) && $_POST['personne_id4'] != "")	{
$variable4 = implode( "**", $_POST['jour4'] ); 
$count4 = count($_POST['jour4']); }

if (isset($_POST['personne_id5']) && $_POST['personne_id5'] != "")	{
$variable5 = implode( "**", $_POST['jour5'] ); 
$count5 = count($_POST['jour5']); }

if (isset($_POST['personne_id6']) && $_POST['personne_id6'] != "")	{
$variable6 = implode( "**", $_POST['jour6'] ); 
$count6 = count($_POST['jour6']); }

if (isset($_POST['personne_id7']) && $_POST['personne_id7'] != "")	{
$variable7 = implode( "**", $_POST['jour7'] ); 
$count7 = count($_POST['jour7']); }



if (isset($count1) && $count1 >= $count2 && $count1 >= $count3 && $count1 >= $count4 && $count1 >= $count5 && $count1 >= $count6 && $count1 >= $count7 ){
	$count = $count1;
	$variable = $variable1;
	} elseif (isset($count2) && $count2 >= $count1 && $count2 >= $count3 && $count2 >= $count4 && $count2 >= $count5 && $count2 >= $count6 && $count2 >= $count7 ){
	$count = $count2;
	$variable = $variable2;
	} elseif (isset($count3) && $count3 >= $count1 && $count3 >= $count2 && $count3 >= $count4 && $count3 >= $count5 && $count3 >= $count6 && $count3 >= $count7 ){
	$count = $count3;
	$variable = $variable3;
	} elseif (isset($count4) && $count4 >= $count1 && $count4 >= $count2 && $count4 >= $count3 && $count4 >= $count5 && $count4 >= $count6 && $count4 >= $count7 ){
	$count = $count4;
	$variable = $variable4;
	} elseif (isset($count5) && $count5 >= $count1 && $count5 >= $count2 && $count5 >= $count3 && $count5 >= $count4 && $count5 >= $count6 && $count5 >= $count7 ){
	$count = $count5;
	$variable = $variable5;
	} elseif (isset($count6) && $count6 >= $count1 && $count6 >= $count2 && $count6 >= $count3 && $count6 >= $count4 && $count6 >= $count5 && $count6 >= $count7 ){
	$count = $count6;
	$variable = $variable6;
	} elseif (isset($count7) && $count7 >= $count1 && $count7 >= $count2 && $count7 >= $count3 && $count7 >= $count4 && $count7 >= $count5 && $count7 >= $count6 ){
	$count = $count7;
	$variable = $variable7;
	} else {
	$count = $count1;
	$variable = $variable1;
	}

*/
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$commission = $_POST['commission_id'];
$month = $_POST['mois'];
$year = $_POST['annee'];
//$logonSuccess = (MinmapDB::getInstance()->verify_session_credentials($commission, $month, $year));
/*
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && ($logonSuccess == false) && !$anneeIsEmpty && !$dossierIsEmpty && !$moisIsEmpty) {
	
if ((isset($count) && $count != "") && (isset($variable) && $variable != "")){
$insertSQL = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
                       GetSQLValueString($_POST['display'], "text"));}

if (isset($_POST['personne_id1']) && isset($variable1) && $variable1 != "" )	{
$insertSQL1 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count1, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id1'], "int"),
                       GetSQLValueString($_POST['fonction_id1'], "int"),
                       GetSQLValueString($variable1, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text")); }
if (isset($_POST['personne_id2']) && isset($variable2) && $variable2 != "") {
$insertSQL2 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count2, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id2'], "int"),
                       GetSQLValueString($_POST['fonction_id2'], "int"),
                       GetSQLValueString($variable2, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text")); }
if (isset($_POST['personne_id3']) && isset($variable3) && $variable3 != "")	{				   
$insertSQL3 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count3, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id3'], "int"),
                       GetSQLValueString($_POST['fonction_id3'], "int"),
                       GetSQLValueString($variable3, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text")); }
if (isset($_POST['personne_id4']) && isset($variable4) && $variable4 != "")	{
$insertSQL4 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count4, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id4'], "int"),
                       GetSQLValueString($_POST['fonction_id4'], "int"),
                       GetSQLValueString($variable4, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text")); }
if (isset($_POST['personne_id5']) && isset($variable5) && $variable5 != "")	{
$insertSQL5 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count5, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id5'], "int"),
                       GetSQLValueString($_POST['fonction_id5'], "int"),
                       GetSQLValueString($variable5, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text")); }
if (isset($_POST['personne_id6']) && isset($variable6) && $variable6 != "")	{
$insertSQL6 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count6, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id6'], "int"),
                       GetSQLValueString($_POST['fonction_id6'], "int"),
                       GetSQLValueString($variable6, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));}
if (isset($_POST['personne_id7']) && isset($variable7) && $variable7 != "")	{				   
$insertSQL7 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count7, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id7'], "int"),
                       GetSQLValueString($_POST['fonction_id7'], "int"),
                       GetSQLValueString($variable7, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));}					   				   					   					   					   

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
    $Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
if (isset($_POST['personne_id1']) && isset($variable1) && $variable1 != "")	{  
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());}
if (isset($_POST['personne_id2']) && isset($variable2) && $variable2 != "")	{  
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());}
if (isset($_POST['personne_id3']) && isset($variable3) && $variable3 != "")	{  
  $Result3 = mysql_query($insertSQL3, $MyFileConnect) or die(mysql_error());}
if (isset($_POST['personne_id4']) && isset($variable4) && $variable4 != "")	{  
  $Result4 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error());}
if (isset($_POST['personne_id5']) && isset($variable5) && $variable5 != "")	{  
  $Result5 = mysql_query($insertSQL5, $MyFileConnect) or die(mysql_error());}
if (isset($_POST['personne_id6']) && isset($variable6) && $variable6 != "")	{  
  $Result6 = mysql_query($insertSQL6, $MyFileConnect) or die(mysql_error());}
if (isset($_POST['personne_id7']) && isset($variable7) && $variable7 != "")	{  
  $Result7 = mysql_query($insertSQL7, $MyFileConnect) or die(mysql_error());}           

  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
*/

if (isset($_POST['counter'])) {
$counter = $_POST['counter'];
}

//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && ($logonSuccess == false) && !$anneeIsEmpty && !$dossierIsEmpty && !$moisIsEmpty) {
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) { 
$x = 0; do { $x++;
/*$variable = implode( "**", $_POST['jour'.$x] );
$count = count($_POST['jour'.$x]);
$month = $_POST['mois'];
$year = $_POST['annee'];
echo 'commission :' . $_POST['commission_id'] . " Dossier : " . $_POST['dossier_id'] . " Nombre de dossier : " . $_POST['nombre_dossier'] . " Compte : " . $count . " Mois : " . $month . "Annee :" . $year . " Personne ID : " . $_POST['personne_id'.$x] . "Fonction ID :" . $_POST['fonction_id'.$x] . "Variable :" . $variable . "Date :" . $DATE . "Display :" . $_POST['display'] . "</BR>";*/

$insertSQL1 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id'.$x], "int"),
                       GetSQLValueString($_POST['fonction_id'.$x], "int"),
                       GetSQLValueString(count($_POST['jour'.$x]), "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
//MinmapDB::getInstance()->create_representant($_POST['commission_id'], $_POST['dossier_id'], $_POST['nombre_dossier'], count($_POST['jour'.$x]), $_POST['mois'], $_POST['annee'], $_POST['personne_id'.$x], $_POST['fonction_id'.$x], implode( "**", $_POST['jour'.$x] ), $DATE, $_POST['display']);
} while ($x < $_POST['counter']);

  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}

/*$x = 0; do { $x++;
$variable = implode( "**", $_POST['jour'.$x] ); 
$count = count($_POST['jour'.$x]);
$commission = $_POST['commission_id'];
$month = $_POST['mois'];
$year = $_POST['annee'];
/*MinmapDB::getInstance()->create_representant($_POST['commission_id'], $_POST['dossier_id'], $_POST['nombre_dossier'], $count, $month, $year, $_POST['personne_id'.$x], $_POST['fonction_id'.$x], $variable, $DATE, $_POST['display']);
	} while ($x <= 7);
	
  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
 */
//echo "commission :" . $_POST['commission_id']; /*. " Dossier : " . $_POST['dossier_id'] . " Nombre de dossier : " . $_POST['nombre_dossier'] . " Compte : " . $count . " Mois : " . $month . "Annee :" . $year . " Personne ID : " . $_POST['personne_id'.$x] . "Fonction ID :" . $_POST['fonction_id'.$x] . "Variable :" . $variable . "Date :" . $DATE "Display :" . $_POST['display']*/
//}


$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_GET['valid']) && $_GET['valid']== "mo") {
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id 
FROM commissions, membres, fonctions, personnes 
WHERE membres.commissions_commission_id = commissions.commission_id  
AND fonctions_fonction_id = fonctions.fonction_id  
AND personnes_personne_id = personne_id 
AND membres.fonctions_fonction_id <> 1
AND membres.fonctions_fonction_id <> 2
AND membres.fonctions_fonction_id <> 3
AND personnes.display = 1 
AND commissions_commission_id = %s 
ORDER BY fonctions_fonction_id", GetSQLValueString($colname_rsMembres, "int"));
} else {
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id 
FROM commissions, membres, fonctions, personnes 
WHERE membres.commissions_commission_id = commissions.commission_id  
AND fonctions_fonction_id = fonctions.fonction_id  
AND personnes_personne_id = personne_id 
AND membres.fonctions_fonction_id <> 4
AND membres.fonctions_fonction_id <> 5
AND membres.fonctions_fonction_id <> 40
AND personnes.display = 1 
AND commissions_commission_id = %s 
ORDER BY fonctions_fonction_id", GetSQLValueString($colname_rsMembres, "int"));
	}
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsJourMois = "-1";
if (isset($_GET['moisID'])) {
  $colname_rsJourMois = $_GET['moisID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois ORDER BY mois_id ASC";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);
?>
<?php 
switch ($_GET['moisID']){
case 01 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 02 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');break;
case 03 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 04 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 05 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 06 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 07 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 8 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 9 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 10 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 11 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 12 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr"><!-- InstanceBegin template="/Templates/FilesTemplates.php" codeOutsideHTMLIsLocked="false" -->
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<link href="css/file.css" rel="stylesheet" type="text/css">
<head>
<meta name="google-site-verification" content="UTnSYJLBKJIOsm6MUJcJBkF7fv-Cm7koeofeyBHwBRA" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<!-- InstanceEndEditable -->
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<meta name="description" content="Le ministère des Finances et du Budget et met en œuvre les politiques du Gouvernement en matière  financière.">
<meta name="keywords" content="essimi menye, ministre, Finances, Industrie, Ministre Industrie, Ministère des Finances, consommation, entreprises, PME, TPE, finances, Finances Cameroun">
<link type="text/css" rel="stylesheet" href="css/v2.css">
<!--[if IE 6]>
		<link type="text/css" rel="stylesheet" href="/css/patch-ie6.css">
	<![endif]-->
<!--[if IE]>
		<link type="text/css" rel="stylesheet" href="/css/patch-ie.css">
	<![endif]-->
<link href="css/mktree.css" rel="stylesheet" type="text/css">
<link href="css/mktree2.css" rel="stylesheet" type="text/css">
<script src="js/tools.js" language="JavaScript" type="text/javascript"></script>
</head>
<body class="home">
<a name="haut"></a>
<!--b001n-->
<div class="hiden">
  <h3>Menu accessibilit&eacute;</h3>
  <p><a href="index.htm" target="_top" accesskey="A">Aller &agrave; l'accueil</a><br>
    <a href="#menu" accesskey="M">Aller au menu</a><br>
    <a href="#work_h" accesskey="C">Aller au contenu</a><br>
    <a href="#">Aller &agrave; la page d'aide</a><br>
    <a href="#" target="_top" accesskey="P">Plan du site</a></p>
</div>
<!--/b001n-->
<div id="outter">
  <div id="inner">
    <!--b002n1-->
    <div id="header">
      <div id="logo"><img src="images/img/v2/amoirie.gif" alt="armoirie" width="78" height="52"><a href="#" target="_top"><img src="images/img/v2/flag.gif" alt="Ministère des Finances et du Budget" width="89" border="0" height="52" hspace="2"></a>
               <h2>Application de Gestion des Indemanites de Sessions au MINMAP</h2>
      </div>
      <!-- /#logo -->
      <div class="menu">
        <ul>
        <?php do { ?>
        
          <li id="accueil"><a href="<?php echo htmlentities($row_rsMenu['lien']); ?>"><?php echo htmlentities($row_rsMenu['menu_lib']); ?></a></li>
        <?php } while ($row_rsMenu = mysql_fetch_assoc($rsMenu)); ?>
        </ul>
      </div>
      <!--/.menu-->
    </div>
    <!--/#header-->
    <!--/b002n1-->
    <div class="col_droite">
      <div class="bloc express">   <h4>Session active</h4>
<p>Connect&eacute; :</p>

<p>&nbsp;</p><div class="clear-both"></div>   </div>
      <div class="bloc lois_recentes">
        <h4>Sous Menu</h4>
        <ul class="tous_les">
    <?php do { ?>
	<a href="<?php echo $row_rsSousMenu['sous_menu_lien']; ?>?menuID=<?php echo $_GET['menuID']; ?>&&action=<?php echo $row_rsSousMenu['action']; ?>"><img src="images/img/		<?php echo $row_rsSousMenu['image']; ?>" width="16" height="16" align="middle" /><?php echo htmlentities($row_rsSousMenu['sous_menu_lib']); ?></a>&nbsp;</BR>
      <?php } while ($row_rsSousMenu = mysql_fetch_assoc($rsSousMenu)); ?>
        </ul>
      </div>
      <!--div class="bloc express">
				<h4>Informations express</h4>
				<p><span>9 mars 2009</span><br>
				Observatoire des prix et des marges : mise en place d'outils op&eacute;rationnels de suivi des marges</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>5 mars 2009</span><br>
				Hausse du chômage au 4e trimestre 2008 &agrave; 7,8  %</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>26 f&eacute;vrier 2009</span><br>
				L'État accompagne la naissance du 2e groupe bancaire français</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<a href="#" class="rss">Flux RSS</a>
				<div class="clear-both"></div>
			</div-->
      <!--div class="bloc">
				<a href="http://www.entreprises.gouv.fr/jeunesactifs/index.html" target="_blank"><img src="http://www.entreprises.gouv.fr/jeunesactifs/repository/images/embauche_250x250.gif" alt="Mesures jeunes actifs" width="180" height="180" border="0" title="Mesures jeunes actifs"></a>
			</div>
			<div class="bloc">
				<a href="http://www.nosentreprisesnosemplois.gouv.fr/" title="www.nosentreprisesnosemplois.gouv.fr nouvelle fenêtre" target="_blank"><img src="/images/actu/agir-entreprises180X150.gif" alt="Agir pour nos entreprises, c'est agir pour l'emploi" width="180" height="150" border="0" title="Agir pour nos entreprises, c'est agir pour l'emploi"></a>
			</div>
			<div class="bloc">
				<a href="http://www.prix-carburants.gouv.fr" title="Le site des prix des carburant nouvelle fenêtre" target="_blank"><img src="/images/actu/prixcarburants.jpg" alt="Le site des prix des carburant" width="180" height="130" border="0" title="Le site des prix des carburant"></a>
			</div-->
      <div class="logos">
        <div class="bloc express">
          <h4><strong>Mon profil</strong></h4>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div>
        <div class="bloc express">
          <h4><strong>Statistiques</strong></h4>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
      </div>
      <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pandémie grippale" width="180" height="150" border="0"></a> </div> -->

	 
	  <!--/#tag_cloud-->

    </div>
    <!-- InstanceBeginEditable name="EditBoby" -->
    <h1>Enregistrer une session</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="75%" border="0" align="center">
    <tr>
      <td><table align="left" class="std2">
        <tr valign="baseline">
          <th nowrap="nowrap" align="right">Mois:</th>
          <td><select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
            <option value="" <?php if (!(strcmp("", $_GET['moisID']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
            <?php
do {  
?>
            <option value="valid_representant.php?comID=<?php echo $_GET['comID'] ?>&menuID=<?php echo $_GET['menuID'] ?>&moisID=<?php echo $row_rsMois['mois_id']?>&valid=<?php echo $_GET['valid'] ?>"<?php if (!(strcmp($row_rsMois['mois_id'], $_GET['moisID']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois['lib_mois'])?></option>
            <?php
} while ($row_rsMois = mysql_fetch_assoc($rsMois));
  $rows = mysql_num_rows($rsMois);
  if($rows > 0) {
      mysql_data_seek($rsMois, 0);
	  $row_rsMois = mysql_fetch_assoc($rsMois);
  }
?>
          </select>
            <?php
             /** Display error messages if the "password" field is empty */
       if ($moisIsEmpty) { ?>
            <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" /><blink>Select month, please</blink></div>
            <?php  } ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>
<table width="75%" border="1" align="left" class="std2">
        <tr valign="baseline">
          <td colspan="4" nowrap="nowrap">Selectionner  une commission
<?php           
	$showGoTo = "search_commissions2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
            <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
            <strong>
              <?php if (isset($_GET['comID'])) { ?>
              <?php echo $row_rsCommissions['type_commission_lib']; ?> de <?php echo $row_rsCommissions['lib_nature']; ?> du (de)<?php echo $row_rsCommissions['localite_lib']; ?>
              <?php } ?>
              </strong>
            <input name="commission_id2" type="hidden" id="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" />
            <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
            <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
            <?php }
            ?>
            <input type="hidden" name="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" /></td>
        </tr>
        <tr>
          <th>ID</th>
          <th>Type</th>
          <th>Nature</th>
          <th>Localite</th>
        </tr>
        <tr>
          <td nowrap="nowrap"><img src="images/img/b_views.png" alt="" width="16" height="16" /></td>
          <td nowrap="nowrap"><?php echo $row_rsCommissions['type_commission_lib']; ?>&nbsp;</td>
          <td nowrap="nowrap"><?php echo $row_rsCommissions['lib_nature']; ?>&nbsp;</td>
          <td nowrap="nowrap"><?php echo $row_rsCommissions['localite_lib']; ?>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="4" align="right" nowrap="nowrap">&nbsp;</td>
        </tr>
</table>

      
      </td>
    </tr>
    <tr>
      <td><table width="75%" border="1" class="std2">
        <?php if (isset($_GET['moisID']) && isset($_GET['comID'])) { // Show if recordset not empty ?>
        <tr valign="baseline">
          <th nowrap="nowrap" align="right">Annee:</th>
          <td nowrap="nowrap"><input name="annee2" type="hidden" size="15" value="<?php if ( isset($_POST['annee'])) { echo $_POST['annee'];} ?>" />
            <select name="annee">
            <option value="" <?php if (!(strcmp("", $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>>Select:::</option>
              <?php 
do {  
?>
              <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($_POST['annee'], $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
              <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
?>
            </select>
            <?php
             /** Display error messages if the "password" field is empty */
       if ($anneeIsEmpty) { ?>
            <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Enter the YEAR, please</div>
            <?php  } ?></td>
          <th align="right" nowrap="nowrap">Nombre de dossiers :</th>
          <td nowrap="nowrap"><input type="text" name="nombre_dossier" value="1" size="15" />
            <?php
             /** Display error messages if the "password" field is empty */
            if ($dossierIsEmpty) { ?>
            <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Enter the Number of File, please</div>
            <?php  } ?></td>
        </tr>
        <tr valign="baseline">
          <th height="58" align="right" valign="top" nowrap="nowrap">Ref Dossier :</th>
          <td colspan="3" valign="top"><textarea name="dossier_id" id="dossier_id" cols="45" rows="5">3</textarea>
<?php           
	$showGoTo2 = "search_dossier.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoT2 .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo2 .= $_SERVER['QUERY_STRING'];
  }
?>
            <a href="#" onclick="<?php popup($showGoTo2, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" />
            <input type="hidden" name="mois" value="<?php if ( isset($_GET['moisID'])) { echo $_GET['moisID'];} ?>" size="32" />
            <input type="hidden" name="jour" value="" size="32" />
            </a></td>
        </tr>
        <?php  } ?>
      </table></td>
    </tr>
    <tr>
      <td><input type="hidden" name="dateCreation" value="" />
        <input type="hidden" name="display" value="1" />
        <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
    <tr>
      <td><?php if (isset($_GET['comID'])) { // Show if recordset not empty ?>
        <h1>Membres</h1>
        <?php if (isset($_GET['moisID'])) { // Show if recordset not empty ?>
        <?php //if ($logonSuccess == true){ ?>
        <table>
          <tr>
            <td><?php $link = "etat_financier_recap.php?menuID=" .  $_GET['menuID']. "&action=" . $_GET['action'] . "&menuID=" . $_GET['menuID'] ?>
            	<?php $link2 = "upd_sessions.php?aID=" . $_POST['annee']. "&mID=" . $_POST['mois'] . "&comID=" . $_POST['commission_id'] . "&menuID=" . $_GET['menuID']?>
              	<?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=6" ?>              <img src="images/img/s_attention.png" width="16" height="16" /><span class="error">Enregistrement existant dans la base...</span></td>
          </tr>
        </table>
        <?php // } ?>
        <table border="1" align="center" class="std">
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Fonction</th>
            <?php 
			$counter=0; do  { $counter++; ?>
            <th><?php echo $counter ?></th>
            <?php }while ($counter< $row_rsJourMois['nbre_jour']);
	?>
          </tr>
          <?php $counter=0; do  { $counter++; ?>
          <tr>
            <td nowrap="nowrap"><a href="detail_personnes.php?recordID=<?php echo $row_rsMembres['commission_id']; ?>"> <?php echo $counter; ?></a></td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsMembres['personne_nom'] . " " . ucfirst($row_rsMembres['personne_prenom'])); ?>
              <input type="hidden" name="<?php echo "personne_id".$counter; ?>" value="<?php echo $row_rsMembres['personne_id']; ?>" size="32" />
              <a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
              <input type="hidden" name="personne_id" id="personne_id" value="2129"/>
              </a>&nbsp; </td>
            <td nowrap="nowrap"><?php echo strtoupper(htmlentities($row_rsMembres['fonction_lib'])); ?>&nbsp;
              <input type="hidden" name="<?php echo "fonction_id".$counter; ?>" value="<?php echo $row_rsMembres['fonctions_fonction_id']; ?>" size="32" />
              &nbsp;<a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
              <input type="hidden" name="fonction_id" id="fonction_id" value="41"/>
              </a></td>
            <?php 
// debut
 foreach($list as $value){ ?>
    <td nowrap="nowrap">
	<?php
	switch($counter){
	case 1 :
	$jour1 = $_POST['jour1'];
	$liste1 = $_POST['jour1'];
	$count1 = count($_POST['jour1']);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste1))? "checked='checked'":"") ?> >
    <?php
	break;
	case 2 :
	$jour2 = $_POST['jour2'];
	$liste2 = $_POST['jour2'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste2))? "checked='checked'":"") ?> >
	<?php
	break;
	case 3 :
	$jour3 = $row_rsSessions['jour'];
	$liste3 = $_POST['jour3'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste3))? "checked='checked'":"") ?> >
    <?php
	break;
	case 4 :
	$jour4 = $row_rsSessions['jour'];
	$liste4 = $_POST['jour4'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste4))? "checked='checked'":"") ?> >
    <?php
	break;
	case 5 :
	$jour5 = $row_rsSessions['jour'];
	$liste5 = $_POST['jour5'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste5))? "checked='checked'":"") ?> >
    <?php
	break;
	case 6 :
	$jour6 = $row_rsSessions['jour'];
	$liste6 = $_POST['jour6'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste6))? "checked='checked'":"") ?> >
    <?php
	break;
	case 7 :
	$jour7 = $row_rsSessions['jour'];
	$liste7 = $_POST['jour7'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste7))? "checked='checked'":"") ?> >
    <?php
	break;
	case 8 :
	$jour8 = $row_rsSessions['jour'];
	$liste8 = $_POST['jour8'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste8))? "checked='checked'":"") ?> >
    <?php
	break;
	case 9 :
	$jour9 = $row_rsSessions['jour'];
	$liste9 = $_POST['jour9'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste9))? "checked='checked'":"") ?> >
    <?php
	break;
	case 10 :
	$jour10 = $row_rsSessions['jour'];
	$liste10 = $_POST['jour10'];
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste10))? "checked='checked'":"") ?> >
    <?php
	break;
	}
	?>
	</td>
	<?php }
	
	//fin
	
	?>
        </tr>
          <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
        </table>
        <?php } // Show if recordset not empty ?>
        <?php } // Show if recordset not empty ?>
        <a href="detail_personnes.php?recordID=<?php echo $row_rsMembres['commission_id']; ?>">
        </a><br />
        <?php echo $totalRows_rsMembres ?> Records Total
        <input type="hidden" name="counter" value="<?php echo $totalRows_rsMembres; ?>" />
        </p>
        <input type="submit" value="Enregistrer la session" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
<!-- InstanceEndEditable -->
    <!--div class="bloc express">
				<h4>Informations express</h4>
				<p><span>9 mars 2009</span><br>
				Observatoire des prix et des marges : mise en place d'outils op&eacute;rationnels de suivi des marges</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>5 mars 2009</span><br>
				Hausse du chômage au 4e trimestre 2008 &agrave; 7,8  %</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>26 f&eacute;vrier 2009</span><br>
				L'État accompagne la naissance du 2e groupe bancaire français</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<a href="#" class="rss">Flux RSS</a>
				<div class="clear-both"></div>
			</div-->
    <!--div class="bloc">
				<a href="http://www.entreprises.gouv.fr/jeunesactifs/index.html" target="_blank"><img src="http://www.entreprises.gouv.fr/jeunesactifs/repository/images/embauche_250x250.gif" alt="Mesures jeunes actifs" width="180" height="180" border="0" title="Mesures jeunes actifs"></a>
			</div>
			<div class="bloc">
				<a href="http://www.nosentreprisesnosemplois.gouv.fr/" title="www.nosentreprisesnosemplois.gouv.fr nouvelle fenêtre" target="_blank"><img src="/images/actu/agir-entreprises180X150.gif" alt="Agir pour nos entreprises, c'est agir pour l'emploi" width="180" height="150" border="0" title="Agir pour nos entreprises, c'est agir pour l'emploi"></a>
			</div>
			<div class="bloc">
				<a href="http://www.prix-carburants.gouv.fr" title="Le site des prix des carburant nouvelle fenêtre" target="_blank"><img src="/images/actu/prixcarburants.jpg" alt="Le site des prix des carburant" width="180" height="130" border="0" title="Le site des prix des carburant"></a>
			</div-->
    <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pandémie grippale" width="180" height="150" border="0"></a> </div> -->

	 
    <!--/#tag_cloud-->

    <!--/.col_droite-->
    <!-- /contenu -->
<!--b006n1-->
<div id="pied">
			<div class="o2paj"><a href="#haut" title="Retour Haut de page">Haut de page</a></div>
		  <p><a href="#">Accueil</a> - <a href="#">Fichier</a> - <a href="#">Commission</a> - <a href="#">Sessions</a> - <a href="#">Paiement</a> - <a href="#">Op&eacute;rations</a> - <a href="#">Parametres</a> - <a href="#">Etats</a> -<a href="#"> Aide</a><br>
	  - Copyright Minist&egrave;re des Marches Publics- <a href="#">Mentions l&eacute;gales</a> site web : <a href="www.minmap.cm">www.minmap.cm</a></p>
	</div>
<!--/b006n1-->
  </div>
</div>
<script type="text/javascript">
<!--
xtnv = document;        //parent.document or top.document or document
xtsd = "http://logp4/";
xtsite = "128343";
xtn2 = "1";        // level 2 site
xtpage = "Accueil_Economie";        //page name (with the use of :: to create chapters)
xtdi = "";        //implication degree
//-->
</script>
<script type="text/javascript" src="js/xtcore.js"></script>
<noscript>
<img width="1" height="1" alt="" src="http://logp4.xiti.com/hit.xiti?s=128343&amp;s2=1&amp;p=Accueil_Economie&amp;di=&amp;" >
</noscript>
</body>

<!-- Mirrored from www.economie.gouv.fr/ by HTTrack Website Copier/3.x [XR&CO'2010], Mon, 22 Nov 2010 11:36:30 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=ISO-8859-1"><!-- /Added by HTTrack -->
<!-- InstanceEnd --></html>
<?php
mysql_free_result($rsCommissions);

mysql_free_result($rsMembres);

mysql_free_result($rsJourMois);

mysql_free_result($rsMois);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsAnnee);
?>
