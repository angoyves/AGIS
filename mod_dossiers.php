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
$date = date('Y-m-d H:i:s');
$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user_name']));
$compteur = (MinmapDB::getInstance()->get_compteur_by_name($_POST['user_name']));
//$editFormAction = $_SERVER['PHP_SELF'];
//$editFormAction = "valid_sessions_db.php";

$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1") ) {	
	//debut de la boucle
$x = 0; $count = 0; $variable = 0; do { $x++;
// debut boucle
if ((isset($_GET['valid']))&& ($_GET['valid']=='mb') && (isset($_POST['jour'.$x])) && ($_POST['jour'.$x] !="")) {
//if ((isset($_POST['jour'.$x])) && $_POST['jour'.$x] !="") {
//controle si l'enregistrement est à mo (maitre d'ouvrage) ou à mb (membre)

// ici on compare les valeurs pour assigner la plus grande valeur à la variable indemnité minmap
if ($count<=count($_POST['jour'.$x])){
	$variable = implode( "**", $_POST['jour'.$x] );
	$count = count($_POST['jour'.$x]); }
//debut insertion des valeurs du formulaire
	MinmapDB::getInstance()->update_session_person_day(count($_POST['jour'.$x]),
													implode( "**", $_POST['jour'.$x] ),
													$date,
												   	$_POST['mois'],
                       							   	$_POST['annee'], 
												   	$_POST['membres_commissions_commission_id'],
                       								$_POST['membres_personnes_personne_id'.$x]);

  $compteur = $compteur + 1;
  MinmapDB::getInstance()->update_users_compteur($compteur, $user_id, $date);  
  
  $link = 'sample26.php?aID='.$_GET['aID'].'&mID='.$_GET['mID'].'&comID='.$_GET['comID'].'&menuID='.$_GET['menuID'].'&lID='.$_GET['lID'].'&valid=mb';
  $modif_text ='A modifier la session du membre '. $_POST['membres_personnes_personne_id'.$x] .' de la '.$row_rsCommissions['commission_lib'].' exercice '.$_GET['aID'].'/'.$_GET['mID'];
  MinmapDB::getInstance()->create_listing_action($user_id, $link, $modif_text, $date);

  
} elseif ((isset($_GET['valid']))&& ($_GET['valid']=='mo') && (isset($_POST['jour'.$x])) && ($_POST['jour'.$x] !="")) {
	
	MinmapDB::getInstance()->update_session_person_day(count($_POST['jour'.$x]),
													implode( "**", $_POST['jour'.$x] ),
													$date,
												   	$_POST['mois2'],
                       							   	$_POST['annee2'], 
												   	$_POST['membres_commissions_commission_id'],
                       								$_POST['membres_personnes_personne_id'.$x]);

 } elseif ((isset($_GET['valid']))&& ($_GET['valid']=='scom') && (isset($_POST['jour'.$x])) && ($_POST['jour'.$x] !="")) {
  
  	MinmapDB::getInstance()->update_session_person_day(count($_POST['jour'.$x]),
													implode( "**", $_POST['jour'.$x] ),
													$date,
												   	$_POST['mois2'],
                       							   	$_POST['annee2'], 
												   	$_POST['membres_commissions_commission_id'],
                       								$_POST['membres_personnes_personne_id'.$x]);
 }
//fin boucle insertion 
} while ($x < $_POST['counter']);

// debut insertion Indemnite MINMAP
if ((isset($_GET['valid']))&& ($_GET['valid']=='mb') && $count != 0) {
//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && $count != 0) {
  	MinmapDB::getInstance()->update_session_person_day($count,
													$variable,
													$date,
												   	$_POST['mois2'],
                       							   	$_POST['annee2'], 
												   	$_POST['membres_commissions_commission_id'],
                       								$_POST['membres_personnes_personne_id']);

  
}

//debut insertion representant maitres d'ouvrage
$count = 0; do { $count++;
	if (isset($_POST['representant_id'.$count]) && $_POST['representant_id'.$count] != "" && isset($_POST['day'.$count]) && $_POST['day'.$count] != null){
	$insertSQL = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['commission_id'], "int"),
						   GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
						   GetSQLValueString(array_sum($_POST['nombre_dossier']), "int"),
						   GetSQLValueString(count($_POST['day'.$count]), "int"),
						   GetSQLValueString($_GET['mID'], "text"),
						   GetSQLValueString($_GET['aID'], "text"),
						   GetSQLValueString($_POST['representant_id'.$count], "int"),
						   GetSQLValueString($_POST['representant_fonction_id'.$count], "int"),
						   GetSQLValueString(implode( "**", $_POST['day'.$count] ), "text"),
						   GetSQLValueString($date, "date"),
						   GetSQLValueString($_POST['display'], "text"),
						   GetSQLValueString($user_id, "int"));
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
	
	$updateSQL = sprintf("UPDATE membres SET  display='0' WHERE commissions_commission_id=%s AND fonctions_fonction_id=%s AND personnes_personne_id=%s",
						   GetSQLValueString($_POST['commission_id'], "int"),
						   GetSQLValueString($_POST['representant_fonction_id'.$count], "int"),
						   GetSQLValueString($_POST['representant_id'.$count], "int"));
	
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
	}
} while ($count < $_POST['nombre_representant']);
// fin insertion et debut redirection
	/*$compteur = $compteur + 1;
	$updateSQL = sprintf("UPDATE users SET  compteur=%s WHERE user_id=%s",
						   GetSQLValueString($compteur, "int"),
						   GetSQLValueString($user_id, "int"));
	
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
	
  $insertSQL = sprintf("INSERT INTO listing_action (UserId, PageLink, ModificationType, DateUpdate) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($user_id, "int"),
                       GetSQLValueString('sample26.php?aID='.$_GET['aID'].'&mID='.$_GET['mID'].'&comID='.$_GET['comID'].'&menuID='.$_GET['menuID'].'&lID='.$_GET['lID'].'&valid=mb', "text"),
                       GetSQLValueString('A modifie la session du mois de '.$_GET['mID'].'/'.$_GET['aID'].' pour '.$row_rsCommissions['commission_lib'].htmlentities(' à ').$date, "text"),
                       GetSQLValueString($date, "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result3 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());*/

  $insertGoTo = "sample38.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}

$colname_rsSessions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsSessions = $_GET['comID'];
}
$colname_rsSessions1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsSessions1 = $_GET['mID'];
}
$colname_rsSessions2 = "-1";
if (isset($_GET['aID'])) {
  $colname_rsSessions2	 = $_GET['aID'];
}
$colname_rsSessions3 = "-1";
if (isset($_GET['sCom'])) {
  $colname_rsSessions3	 = $_GET['sCom'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_GET['valid'])) && ($_GET['valid'] == 'mb')) {
$query_rsSessions = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, fonctions.fonction_id, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  fonctions, sessions
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND (sessions.membres_fonctions_fonction_id BETWEEN 1 AND 5)
AND commissions.commission_id = %s 
AND mois = %s 
AND annee = %s 
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
} else {
$query_rsSessions = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, fonctions.fonction_id, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  fonctions, sessions
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND sessions.membres_fonctions_fonction_id = 40
AND commissions.commission_id = %s 
AND mois = %s 
AND annee = %s 
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
}
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

$colname_rsJourMois = "-1";
if (isset($_GET['mID'])) {
  $colname_rsJourMois = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

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

$colname_rsUpdSessions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsUpdSessions = $_GET['comID'];
}
$colname_rsUpdSessions1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsUpdSessions1 = $_GET['mID'];
}
$colname_rsUpdSessions2 = "-1";
if (isset($_GET['aID'])) {
  $colname_rsUpdSessions2 = $_GET['aID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdSessions = sprintf("SELECT * FROM sessions WHERE membres_commissions_commission_id = %s
							   AND mois = %s
							   AND annee = %s", 
							   GetSQLValueString($colname_rsUpdSessions, "int"),
							   GetSQLValueString($colname_rsUpdSessions1, "text"),
							   GetSQLValueString($colname_rsUpdSessions2, "text"));
$rsUpdSessions = mysql_query($query_rsUpdSessions, $MyFileConnect) or die(mysql_error());
$row_rsUpdSessions = mysql_fetch_assoc($rsUpdSessions);
$totalRows_rsUpdSessions = mysql_num_rows($rsUpdSessions);

$colname_rsRepresentant = "-1";
if (isset($_GET['comID'])) {
  $colname_rsRepresentant = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRepresentant = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id, code_structure FROM commissions, membres, fonctions, personnes, structures  WHERE membres.commissions_commission_id = commissions.commission_id   AND fonctions_fonction_id = fonctions.fonction_id   AND personnes_personne_id = personne_id  AND personnes.structure_id = structures.structure_id AND membres.fonctions_fonction_id <> 1 AND membres.fonctions_fonction_id <> 2 AND membres.fonctions_fonction_id <> 3 AND personnes.display = 1  AND membres.display = 1 AND commissions_commission_id = %s ORDER BY fonctions_fonction_id ", GetSQLValueString($colname_rsRepresentant, "int"));
$rsRepresentant = mysql_query($query_rsRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsRepresentant = mysql_fetch_assoc($rsRepresentant);
$totalRows_rsRepresentant = mysql_num_rows($rsRepresentant);
?>

<?php 
$jour1 = $_POST['jour1'];
$jour2 = $_POST['jour2'];
$jour3 = $_POST['jour3'];
$jour1 = implode('**',$jour1);
$liste1 = explode('**',$jour1);
$jour2 = implode('**',$jour2);
$liste2 = explode('**',$jour2);
$jour3 = implode('**',$jour3);
$liste3 = explode('**',$jour3);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr"><!-- InstanceBegin template="/Templates/FilesTemplates.dwt.php" codeOutsideHTMLIsLocked="false" -->
<?php

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

$MM_authorizedUsers = "0,1,2,3,4,5,6,7,8";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "connexions.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}
// ** Logout the current user after inactive. **
$logoutInactiv = $_SERVER['PHP_SELF']."?doLogout=false";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutInactiv .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  MinmapDB::getInstance()->update_users_connected('0', $_SESSION['MM_UserID']);
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['MM_UserID'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['MM_taux'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['MM_UserID']);
  unset($_SESSION['PrevUrl']);
  unset($_SESSION['MM_taux']);

	
  $logoutGoTo = "connexions.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="false")){
  //to fully log out a visitor we need to clear the session varialbles
  MinmapDB::getInstance()->update_users_connected('0', $_SESSION['MM_UserID']);
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['MM_UserID'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  $_SESSION['MM_taux'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['MM_UserID']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "inactiv.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}

$colname_rsMenus = "-1";
if (isset($_SESSION['MM_UserGroup'])) {
  $colname_rsMenus = $_SESSION['MM_UserGroup'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenus = sprintf("SELECT * FROM menus WHERE user_groupe_id = %s OR  user_groupe_id = 0 AND display = 1 ORDER BY menu_id ASC", GetSQLValueString($colname_rsMenus, "int"));
$rsMenus = mysql_query($query_rsMenus, $MyFileConnect) or die(mysql_error());
$row_rsMenus = mysql_fetch_assoc($rsMenus);
$totalRows_rsMenus = mysql_num_rows($rsMenus);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

$UserGroupe = (MinmapDB::getInstance()->get_user_groupe_by_user_login($_SESSION['MM_Username']));

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
?>

<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<link href="css/file.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
<meta name="google-site-verification" content="UTnSYJLBKJIOsm6MUJcJBkF7fv-Cm7koeofeyBHwBRA" />


<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<script src="js/tools.js" language="JavaScript" type="text/javascript"></script>
<!-- InstanceEndEditable -->
<head>
<meta name="google-site-verification" content="UTnSYJLBKJIOsm6MUJcJBkF7fv-Cm7koeofeyBHwBRA" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>AGIS :  Accueil</title>
<!-- InstanceEndEditable -->
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<meta name="description" content="Le ministère des Marches Publics du Cameroun">
<meta name="keywords" content="Abba SADOU, ministre, Marches, Ministère des Marches Publics, ">
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
<script language="javascript" type='text/javascript'>
    function session(){
        window.location="<?php echo $logoutInactiv ?>"; //page de déconnexion
    }
	function window_onbeforeunload()
    {
       window.navigate('<?php echo $logoutInactiv ?>'); 
       //ne pas oublier de préciser le chemin si vous mettez la page dans un autre répertoire
    }
    setTimeout("session()",1800000); //Pour 30min
	//setTimeout("session()",10000);
	window.onbeforeunload="<?php echo $logoutInactiv ?>";
</script>
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
        
          <li id="accueil"><a href="<?php echo htmlentities($row_rsMenus['lien']); ?>"><?php echo htmlentities($row_rsMenus['menu_lib']); ?></a></li>
        <?php } while ($row_rsMenus = mysql_fetch_assoc($rsMenus)); ?>
        </ul>
      </div>
      <!--/.menu-->
    </div>
    <!--/#header-->
    <!--/b002n1-->
    <div class="col_droite">
	<div class="bloc lois_recentes">
        <h4>Session ouverte</h4>
        <ul class="tous_les">

    	<table width="200" border="0">
    	  <tr>
    	    <td width="54" align="center"><ul class="tous_les">
    	      <img src="images/Icones/user.jpg" width="30" height="30">
    	      </ul></td>
    	    <td width="136" valign="middle"><ul class="tous_les">
    	      <strong><?php //echo $_SESSION['MM_Username'] ?><?php echo MinmapDB::getInstance()->get_user_name($_SESSION['MM_UserID']); ?></strong>
    	    </ul></td>
    	    </tr>
    	  <tr>
    	    <td width="54" align="center"><ul class="tous_les">
    	      <img src="images/Icones/user_group_100832.jpg" width="40" height="40">
  	      </ul></td>
    	    <td valign="middle"><?php echo $_SESSION['MM_UserGroupName']; ?></td>
  	    </tr>
    	  <tr>
    	    <td><ul class="tous_les">
    	      <a href="<?php echo $logoutAction ?>"><img src="images/Icones/naviguer-8.png" width="50" height="32"></a>
    	      </ul></td>
    	    <td><a href="<?php echo $logoutAction ?>"><strong>Se déconnecter</strong></a></td>
  	    </tr>
  	  </table></BR>
        </ul>
      </div>
    <?php if ($totalRows_rsSousMenu > 0) { // Show if recordset not empty ?>
  <div class="bloc lois_recentes">
    <h4>Sous Menu <?php echo $row_rsMenus['menu_id']?></h4>
    <ul class="tous_les">
      <?php do { ?>
        <a href="<?php echo $row_rsSousMenu['sous_menu_lien']; ?>?menuID=<?php echo $_GET['menuID']; ?>&&action=<?php echo $row_rsSousMenu['action']; ?>"><img src="images/img/		<?php echo $row_rsSousMenu['image']; ?>" width="16" height="16" align="middle" /><?php echo htmlentities($row_rsSousMenu['sous_menu_lib']); ?></a>&nbsp;</BR>
        <?php } while ($row_rsSousMenu = mysql_fetch_assoc($rsSousMenu)); ?>
    </ul>
  </div>
  <?php } // Show if recordset not empty ?>
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
        <div class="bloc lois_recentes">
          <h4>Profil</h4>
          <ul class="tous_les">
            <a href="sample65.php?uID=<?php echo $_SESSION['MM_UserID']; ?>">Modifier mon mot de passe? </a></BR></BR>
          </ul>
        </div>
        <div class="bloc express">
          <h4><strong>Statistiques</strong></h4>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div>
        <p>&nbsp;</p>
        <div class="bloc lois_recentes">
          <h4>Notifications</h4>
          <ul class="tous_les">
            <p>Vous avez  :<strong> 
			<?php echo MinmapDB::getInstance()->get_nbre_commentaire_non_lu($_SESSION['MM_UserID']); ?></strong>
          <?php           
			$showGoTo = "sample50.php?userID=".$_SESSION['MM_UserID'];
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
          <a href="#" onclick="<?php popup($showGoTo, "710", "350"); ?>"><img src="images/img/message.png" width="28" height="18" align="middle"> Messages non lus</a></p>
            <p><a href="#" onclick="<?php popup($showGoTo, "710", "350"); ?>"><img src="images/Icones/envoyer-icone.png" width="25" height="25" border="0" align="middle"></a>  
              <?php           
			$showGoTo = "sample49.php";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
              <a href="#" onclick="<?php popup($showGoTo, "710", "350"); ?>">Envoyer un commentaire</a></p>
            <p>
              <?php           
			$showGoTo = "sample67.php";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
              <a href="#" onclick="<?php popup($showGoTo, "710", "350"); ?>"><img src="images/Icones/ico_forum.png" width="30" height="31" border="0" align="left">Afficher historique des conversations</a></p>
            <p><a href="messenger/edit_infos.php">editer mes informations</a></p>
          </ul>
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
      </div>
      <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pandémie grippale" width="180" height="150" border="0"></a> </div> -->

	 
	  <!--/#tag_cloud-->

    </div>
    <!-- InstanceBeginEditable name="EditBoby" -->
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
<img width="1" height="1" alt="" src="" >
</noscript>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="75%" border="0" align="center">
    <tr>
      <td><table border="1" class="std2">
        <?php //if (isset($_GET['moisID']) && isset($_GET['comID'])) { // Show if recordset not empty ?>
        <tr valign="baseline">
          <th nowrap="nowrap" align="right">Annee :</th>
          <td nowrap="nowrap"><select name="annee">
            <?php 
			do {  
			?>
            <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsUpdSessions['annee'], $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
            <?php
			} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
		?>
          </select>
            <input type="hidden" name="annee2" value="<?php echo htmlentities($row_rsUpdSessions['annee'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
            &nbsp;</td>
          <th align="right" nowrap="nowrap">Mois :</th>
          <td nowrap="nowrap"><select name="mois">
            <?php 
			do {  
			?>
            <option value="<?php echo $row_rsMois['mois_id']?>" <?php if (!(strcmp($row_rsUpdSessions['mois'], $row_rsMois['mois_id']))) {echo "SELECTED";} ?>><?php echo ucfirst($row_rsMois['lib_mois'])?></option>
            <?php
			} while ($row_rsMois = mysql_fetch_assoc($rsMois));
			?>
          </select>
            <input type="hidden" name="mois2" value="<?php echo htmlentities($row_rsUpdSessions['mois'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
            <input type="hidden" name="dossiers_dossier_id" value="<?php echo htmlentities($row_rsUpdSessions['dossiers_dossier_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
            &nbsp;
            <input type="hidden" name="nombre_dossier" value="<?php echo htmlentities($row_rsUpdSessions['nombre_dossier'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
        </tr>
        <?php  //} ?>
      </table></td>
    </tr>
    <tr>
      <td>
      <h1>Identification Commission</h1>
      <table width="75%" border="1" align="left" class="std">
        <tr valign="baseline">
          <td colspan="4" nowrap="nowrap"><input name="membres_commissions_commission_id" type="hidden" value="<?php echo htmlentities($row_rsUpdSessions['membres_commissions_commission_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" readonly="readonly" />
            <br/>
            <strong>
              <?php //if (isset($_GET['comID'])) { ?>
              <?php echo strtoupper($row_rsCommissions['commission_lib']); ?>
              <?php //} ?>
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
          <td nowrap="nowrap"><?php echo ucfirst($row_rsCommissions['lib_nature']); ?>&nbsp;</td>
          <td nowrap="nowrap"><?php echo strtoupper($row_rsCommissions['localite_lib']); ?>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="4" align="right" nowrap="nowrap">&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td>
      <table border="1" class="std">
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Fonction</th>
            <?php $counter=0; do  { $counter++; ?>
            <th><?php echo $counter ?></th>
            <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
          </tr>
          <?php $compter=0; do  { $compter++; ?>
          <tr>
            <td nowrap="nowrap"><a href="detail_personnes.php?recordID=<?php echo $row_rsSessions['commission_id']; ?>"> <?php echo $compter; ?></a></td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsSessions['personne_nom'] . " " . ucfirst($row_rsSessions['personne_prenom'])); ?>
              <input type="hidden" name="<?php echo "membres_personnes_personne_id".$compter; ?>" value="<?php echo htmlentities($row_rsSessions['personne_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              <a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>"> </a>&nbsp; </td>
            <td nowrap="nowrap"><?php echo strtoupper(htmlentities($row_rsSessions['fonction_lib'])); ?>&nbsp;
              <input type="hidden" name="<?php echo "membres_fonctions_fonction_id".$compter; ?>" value="<?php echo htmlentities($row_rsSessions['fonction_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              &nbsp;<a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>"> </a></td>
            <?php 
// debut
 foreach(GetValueList($row_rsJourMois['mois_id']) as $value){ ?>
            <td nowrap="nowrap"><?php
	switch($compter){
	case $compter :
	$jour = $row_rsSessions['jour'];
	$liste = explode('**',$jour);
	?>
              <input type="checkbox" name=<?php echo "jour" . $compter . "[]" ?> id=<?php echo "jour". $compter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste))? "checked='checked'":"") ?> >
              <?php
	break;
	}
	?></td>
            <?php }
	
	//fin
	
	?>
          </tr>
          <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
        </table>&nbsp;</td>
    </tr>
    <tr>
      <td><h1>Mise a jour Commission</h1></td>
    </tr>
    <tr>
      <td><table border="0">
        <tr>
          <td>
			<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" />&nbsp;
              <?php           
			$showGoTo = "sample27.php";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
            <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>">Ajouter un repr&eacute;sentant de maitre d'ouvrage...</a>&nbsp;<strong>&nbsp;</strong><strong>&nbsp;</strong>
            &nbsp;</td>
          <td><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" />&nbsp;
              <?php           
			$showGoTo = "sample41.php?uID=".$_SESSION['MM_Username'];
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
        <a href="<?php echo $showGoTo?>" >Ajouter  un nouveau membre &agrave; la commission...</a>&nbsp;&nbsp;</td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td align="left"><input type="hidden" name="MM_update" value="form1" />
        <input type="hidden" name="membres_personnes_personne_id" id="membres_personnes_personne_id" value="2129"/>
        <input type="hidden" name="membres_fonctions_fonction_id" id="membres_fonctions_fonction_id" value="41"/>
        <input type="hidden" name="counter" value="<?php echo $totalRows_rsSessions; ?>" />
        <input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdSessions['display'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsUpdSessions['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <input type="hidden" name="Nombre_jour" value="<?php echo htmlentities($row_rsUpdSessions['Nombre_jour'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
        <input type="hidden" name="user_name" value="<?php echo $_SESSION['MM_Username']; ?>" size="32" />
        <table border="1" class="std">
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Fonction</th>
            <?php $counter=0; do  { $counter++; ?>
            <th><?php echo $counter ?></th>
            <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
          </tr>
          <?php $compter=0; do  { $compter++; ?>
          <tr>
            <td nowrap="nowrap"><a href="detail_personnes.php?recordID=<?php echo $row_rsSessions['commission_id']; ?>"> <?php echo $compter; ?></a></td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsSessions['personne_nom'] . " " . ucfirst($row_rsSessions['personne_prenom'])); ?>
              <input type="hidden" name="<?php echo "membres_personnes_personne_id".$compter; ?>" value="<?php echo htmlentities($row_rsSessions['personne_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              <a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>"> </a>&nbsp; </td>
            <td nowrap="nowrap"><?php echo strtoupper(htmlentities($row_rsSessions['fonction_lib'])); ?>&nbsp;
              <input type="hidden" name="<?php echo "membres_fonctions_fonction_id".$compter; ?>" value="<?php echo htmlentities($row_rsSessions['fonction_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
              &nbsp;<a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>"> </a></td>
            <?php 
// debut
 foreach(GetValueList($row_rsJourMois['mois_id']) as $value){ ?>
            <td nowrap="nowrap"><?php
	switch($compter){
	case $compter :
	$jour = $row_rsSessions['jour'];
	$liste = explode('**',$jour);
	?>
              <input type="checkbox" name=<?php echo "jour" . $compter . "[]" ?> id=<?php echo "jour". $compter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste))? "checked='checked'":"") ?> >
              <?php
	break;
	}
	?></td>
            <?php }
	
	//fin
	
	?>
          </tr>
          <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
        </table>
        <?php if ($totalRows_rsRepresentant > 0) { // Show if recordset not empty ?>
	  <h1>Representant maitre d'ouvrage</h1>
      <table border="1" class="std">
          <tr>
            <th><strong>ID</strong></th>
            <th><strong>Noms et Prenoms</strong></th>
            <th><strong>Structures</strong></th>
            <?php $counter = 0; do { $counter++; ?>
            <th><strong><?php echo $counter ?>&nbsp;</strong></th>
            <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
            <th>&nbsp;</th>
          </tr>
          <?php $count = 0; do { $count++; ?>
            <tr>
              <td><a href="show_personnes.php?txtChamp=personne_id&amp;txtSearch=<?php echo $row_rsRepresentant['personne_id']; ?>&amp;menuID=2&amp;action=upd"> <?php echo $count; ?></a>&nbsp; </td>
              <td><?php echo strtoupper($row_rsRepresentant['personne_nom'] . " " . $row_rsRepresentant['personne_prenom']); ?>
                <input type="hidden" name="<?php echo "representant_id".$count; ?>" value="<?php echo $row_rsRepresentant['personne_id']; ?>" />
                &nbsp; </td>
              <td><?php echo $row_rsRepresentant['code_structure']; ?>
                <input type="hidden" name="<?php echo "representant_fonction_id".$count; ?>" value="<?php echo $row_rsRepresentant['fonctions_fonction_id']; ?>" />
                &nbsp; </td>
              <?php //$counter = 0; do { $counter++;
			foreach(GetValueList($_GET['mID']) as $value){
			switch($count){
			case $count :
			$liste = $_POST['day'.$count];
		  ?>
              <td><input type="checkbox" name="<?php echo "day". $count . "[]" ?>" id="<?php echo "day". $count . $value ?>" value="<?php echo $value ?>"  
          <?php echo ((in_array($value, $liste))? "checked='checked'":"") ?> /></td>
              <?php break; } 
		  } ?>
            <?php //} while($counter < $row_rsJourMois['nbre_jour']); ?>
<td><a href="activ_rep2.php?aID=<?php echo $_GET['aID']; ?>&amp;mID=<?php echo $_GET['mID']; ?>&amp;comID=<?php echo $_GET['comID']; ?>&amp;perID=<?php echo $row_rsRepresentant['personne_id'];?>&amp;action=desactive&amp;menuID=<?php echo $_GET['menuID']; ?>&page=sample26"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
</tr>
<input type="hidden" name="nombre_representant" id="nombre_representant" value="<?php echo $count ?>"/>
          <?php } while ($row_rsRepresentant = mysql_fetch_assoc($rsRepresentant)); ?>
      </table>
      <?php } // Show if recordset not empty ?>
<input type="submit" value="Mettre &agrave; jour l'enregistrement" />
        &nbsp;</td>
    </tr>
  </table>
</form>
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
mysql_free_result($rsSessions);

mysql_free_result($rsJourMois);

mysql_free_result($rsMois);

mysql_free_result($rsAnnee);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsCommissions);

mysql_free_result($rsRepresentant);

mysql_free_result($rsUpdSessions);
?>
