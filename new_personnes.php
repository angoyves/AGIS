   
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

$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user_name']));
$compteur = (MinmapDB::getInstance()->get_compteur_by_name($_POST['user_name']));

$personneNomIsUnique = true;
$personneNomIsEmpty = false;
$numeroCompteIsUnique = true;
$numeroCompteIsEmpty = false;
$personneMatriculeIsUnique = true;
$personneMatriculeIsEmpty = false;
$cleIsEmpty = false;
$groupeIsEmpty = false;
$structureIsEmpty = false;
$fonctionIsEmpty = false;
$agenceIsEmpty = false;
$date = date('Y-m-d H:i:s');

?>
<?php
$colname_rsUserID = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUserID = $_SESSION['MM_Username'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUserID = sprintf("SELECT user_id FROM users WHERE user_login = %s", GetSQLValueString($colname_rsUserID, "text"));
$rsUserID = mysql_query($query_rsUserID, $MyFileConnect) or die(mysql_error());
$row_rsUserID = mysql_fetch_assoc($rsUserID);
$totalRows_rsUserID = mysql_num_rows($rsUserID);

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	
	//$matricule = $_POST['personne_mat1'] . " " . $_POST['personne_mat2'] . "-" . $_POST['personne_mat3'];
    
	if (array_key_exists("back", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: index.php');
        exit;
    }
	if (array_key_exists("back2", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: verify.php?nameID=variableURL');
        exit;
    }
	if (array_key_exists("back3", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: create_personnes.php');
        exit;
    }
	if (array_key_exists("back4", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: agence.php');
        exit;
    }
	if (array_key_exists("back5", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
		  $goTo = "index.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$goTo .= (strpos($goTo, '?')) ? "&" : "?";
			$goTo .= $_SERVER['QUERY_STRING'];
		  }
		  header(sprintf("Location: %s", $updateGoTo));
    }
    /** Check whether the user has filled in the wisher's name in the text field "user" */
    if ($_POST['personne_nom']== ""){
        $personneNomIsEmpty = true;
    }
   // if ($_POST['personne_matricule']==""){
	if ($_POST['personne_matricule']=="" ){
        $personneMatriculeIsEmpty = true;
    }
	
    if ($_POST['numero_compte']==""){
    	$numeroCompteIsEmpty = true;
    }
	
    if ($_POST['cle']==""){
        $cleIsEmpty = true;
    }
	if ($_POST['sous_groupe_id']==""){	
		$groupeIsEmpty = true;
	}
	if ($_POST['agence_code']==""){	
		$agenceIsEmpty = true;
	}
	if ($_POST['structure_id']==""){	
		$structureIsEmpty = true;
	}
	if ($_POST['fonction_id']==""){	
		$fonctionIsEmpty = true;
	}

    /** Create database connection */

   // $personneID = MinmapDB::getInstance()->get_value_id_by_value(personne_id, personnes, personne_nom, $_POST['personne_nom']);
    $personneID = MinmapDB::getInstance()->get_person_id_by_name_and_cpte($_POST['personne_nom'], $_POST['numero_compte']);
    if ($personneID) {
        $personneNomIsUnique = false;
    }

   // $matriculeID = MinmapDB::getInstance()->get_value_id_by_value(personne_id, personnes, personne_matricule, $_POST['personne_matricule']);
    $matriculeID = MinmapDB::getInstance()->get_personne_id_by_matricule($_POST['personne_matricule']);
    if ($matriculeID && ($_GET[personneID]==1 || $_GET[personneID]==2)) {
        $personneMatriculeIsUnique = false;
    }

    $compteID = MinmapDB::getInstance()->get_value_id_by_value('personne_id', 'personnes', 'numero_compte', $_POST['numero_compte']);
  // $compteID = MinmapDB::getInstance()->get_wisher_id_by_name($_POST['numero_compte']);
    if ($compteID) {
        $numeroCompteIsUnique = false;
    }
	
if (!$personneNomIsEmpty && $personneNomIsUnique && !$personneMatriculeIsEmpty && $personneMatriculeIsUnique && !$numeroCompteIsEmpty && $numeroCompteIsUnique && !$cleIsEmpty && !$groupeIsEmpty && !$agenceIsEmpty && !$structureIsEmpty && !$fonctionIsEmpty){
	
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL1 = sprintf("INSERT INTO personnes (personne_matricule, personne_nom, personne_prenom, personne_grade, domaine_id, personne_telephone, structure_id, sous_groupe_id, fonction_id, type_personne_id, user_id, date_creation, display) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                      /* GetSQLValueString($_POST['personne_id'], "int"),*/
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString(strtoupper($_POST['personne_nom']), "text"),
                       GetSQLValueString(ucfirst($_POST['personne_prenom']), "text"),
                       GetSQLValueString($_POST['personne_grade'], "text"),
					   GetSQLValueString($_POST['domaine_id'], "int"),
                       GetSQLValueString($_POST['personne_telephone'], "text"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['sous_groupe_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($_POST['type_personne_id'], "int"),
					   GetSQLValueString($row_rsUserID['user_id'], "int"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
  $Personne_id = MinmapDB::getInstance()->get_value_id_by_value('personne_id', 'personnes', 'personne_nom', $_POST['personne_nom']); 
  $agence_cle = $_POST['banque_code'] . "" . $_POST['agence_code'];
  $insertSQL2 = sprintf("INSERT INTO rib (personne_id, personne_matricule, agence_cle, banque_code, agence_code, numero_compte, user_id, cle, date_creation) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($Personne_id, "int"),
					   GetSQLValueString($_POST['personne_matricule'], "text"),
					   GetSQLValueString($agence_cle, "text"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['numero_compte'], "text"),
					   GetSQLValueString($row_rsUserID['user_id'], "int"),
                       GetSQLValueString($_POST['cle'], "text"),
                       GetSQLValueString($date, "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());
  
  $compteur = $compteur + 1;
  $updateSQL = sprintf("UPDATE users SET  compteur=%s WHERE user_id=%s",
						   GetSQLValueString($compteur, "int"),
						   GetSQLValueString($user_id, "int"));
	
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  
  $PageLink = 'sample23.php?comID='.$_GET['comID'].'&menuID='.$_GET[menuID].'&valid=mb&lID='.$_GET['lID'].'&mID='.$_GET['mID'];
  $ModificationType = 'A saisie une personne avec l\'ID :'.$Personne_id.', dans le fichier.';
  $DateUpdate = date('Y-m-d H:i:s');
  
  /*$insertSQL = sprintf("INSERT INTO listing_action (UserId, PageLink, ModificationType, DateCreation) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($user_id, "int"),
                       GetSQLValueString('sample23.php?comID='.$_GET['comID'].'&menuID='.$_GET[menuID].'&valid=mb&lID='.$_GET['lID'].'&mID='.$_GET['mID'], "text"),
                       GetSQLValueString('A saisie une personne avec l\'ID :'.$Personne_id.', dans le fichier.', "text"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result3 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());*/
  
  MinmapDB::getInstance()->create_listing_action($user_id, $PageLink, $ModificationType, $DateUpdate);
  
  if (isset($_GET['page']) && $_GET['page'] == "new_membre2"){
  $insertGoTo = "new_membres2.php";  
  } elseif (isset($_GET['page']) && $_GET['page'] == "new_session"){
  $insertGoTo = "new_membres.php";
  } elseif (isset($_GET['page']) && $_GET['page'] == "new_representant"){
  $insertGoTo = "new_representant.php";
  } else {
  $insertGoTo = "show_personnes.php";
  }
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
}

$colname_rsStructures = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsStructures = $_GET['personneID'];
  $colname_rsFonctionID = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructures = sprintf("SELECT structure_id, structure_lib, code_structure FROM structures WHERE type_structure_id = %s AND display = '1' ORDER BY structure_lib ASC", GetSQLValueString($colname_rsStructures, "int"));
$rsStructures = mysql_query($query_rsStructures, $MyFileConnect) or die(mysql_error());
$row_rsStructures = mysql_fetch_assoc($rsStructures);
$totalRows_rsStructures = mysql_num_rows($rsStructures);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGroupe = "SELECT groupes.groupe_id,groupes.groupe_lib,sous_groupes.sous_groupe_id,sous_groupes.sous_groupe_lib FROM groupes, sous_groupes WHERE sous_groupes.groupe_id = groupes.groupe_id AND  sous_groupes.display = '1'";
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = sprintf("SELECT fonction_id, fonction_lib FROM fonctions WHERE groupe_fonction_id = %s AND display = '1'",GetSQLValueString($colname_rsFonctionID, "int"));
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypePersonne = "SELECT type_personne_id, type_personne_lib FROM type_personnes WHERE display = '1'";
$rsTypePersonne = mysql_query($query_rsTypePersonne, $MyFileConnect) or die(mysql_error());
$row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
$totalRows_rsTypePersonne = mysql_num_rows($rsTypePersonne);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanques = "SELECT banque_code, banque_lib FROM banques WHERE display = '1'";
$rsBanques = mysql_query($query_rsBanques, $MyFileConnect) or die(mysql_error());
$row_rsBanques = mysql_fetch_assoc($rsBanques);
$totalRows_rsBanques = mysql_num_rows($rsBanques);



$colname_rsBanqueq = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsBanqueq = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanqueq = sprintf("SELECT banque_code, banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsBanqueq, "text"));
$rsBanqueq = mysql_query($query_rsBanqueq, $MyFileConnect) or die(mysql_error());
$row_rsBanqueq = mysql_fetch_assoc($rsBanqueq);
$totalRows_rsBanqueq = mysql_num_rows($rsBanqueq);


$colname_rsSelectTypePersonne = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsSelectTypePersonne = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelectTypePersonne = sprintf("SELECT type_personne_id, type_personne_lib FROM type_personnes WHERE type_personne_id = %s", GetSQLValueString($colname_rsSelectTypePersonne, "int"));
$rsSelectTypePersonne = mysql_query($query_rsSelectTypePersonne, $MyFileConnect) or die(mysql_error());
$row_rsSelectTypePersonne = mysql_fetch_assoc($rsSelectTypePersonne);
$totalRows_rsSelectTypePersonne = mysql_num_rows($rsSelectTypePersonne);

$colname_rsSousGrouepByUrl = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsSousGrouepByUrl = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGrouepByUrl = sprintf("SELECT groupes.groupe_id,groupes.groupe_lib,sous_groupes.sous_groupe_id,sous_groupes.sous_groupe_lib FROM groupes, sous_groupes WHERE sous_groupes.groupe_id = groupes.groupe_id AND  sous_groupes.display = '1' AND  sous_groupes.groupe_id = %s", GetSQLValueString($colname_rsSousGrouepByUrl, "int"));
$rsSousGrouepByUrl = mysql_query($query_rsSousGrouepByUrl, $MyFileConnect) or die(mysql_error());
$row_rsSousGrouepByUrl = mysql_fetch_assoc($rsSousGrouepByUrl);
$totalRows_rsSousGrouepByUrl = mysql_num_rows($rsSousGrouepByUrl);

$colname_rsSelBanqueUrl = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsSelBanqueUrl = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelBanqueUrl = sprintf("SELECT banque_code, banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsSelBanqueUrl, "text"));
$rsSelBanqueUrl = mysql_query($query_rsSelBanqueUrl, $MyFileConnect) or die(mysql_error());
$row_rsSelBanqueUrl = mysql_fetch_assoc($rsSelBanqueUrl);
$totalRows_rsSelBanqueUrl = mysql_num_rows($rsSelBanqueUrl);

$colname_rsGroupe = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsGroupe = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupe = sprintf("SELECT sous_groupe_id, sous_groupe_lib, groupe_id FROM sous_groupes WHERE groupe_id = %s", GetSQLValueString($colname_rsGroupe, "int"));
$rsGroupe = mysql_query($query_rsGroupe, $MyFileConnect) or die(mysql_error());
$row_rsGroupe = mysql_fetch_assoc($rsGroupe);
$totalRows_rsGroupe = mysql_num_rows($rsGroupe);

$colname_rsAgences = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsAgences = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAgences = sprintf("SELECT agence_code, agence_lib FROM agences WHERE banque_code = %s AND display = '1' ORDER BY agence_code", GetSQLValueString($colname_rsAgences, "text"));
$rsAgences = mysql_query($query_rsAgences, $MyFileConnect) or die(mysql_error());
$row_rsAgences = mysql_fetch_assoc($rsAgences);
$totalRows_rsAgences = mysql_num_rows($rsAgences);

$colname_rsDomaines = "-1";
if (isset($_GET['domID'])) {
  $colname_rsDomaines = $_GET['domID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsDomaines = sprintf("SELECT domaine_id, domaine_lib FROM domaines_activites WHERE domaine_id = %s", GetSQLValueString($colname_rsDomaines, "int"));
$rsDomaines = mysql_query($query_rsDomaines, $MyFileConnect) or die(mysql_error());
$row_rsDomaines = mysql_fetch_assoc($rsDomaines);
$totalRows_rsDomaines = mysql_num_rows($rsDomaines);

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
<!-- InstanceEndEditable -->
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
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <h1>Creation d'une personne</h1>
  <table align="center" class="std2">
    <tr valign="baseline">
      <th align="right" valign="top" nowrap="nowrap">Selectionne la banque <span class="error">*</span> :</th>
      <td colspan="3"><select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
        <option value="" <?php if (!(strcmp("", $_GET['banqueID']))) {echo "selected=\"selected\"";} ?>>[Select banque...]</option>
        <?php
do {  
?>
        <option value="new_personnes.php?<?php if(isset($_GET['menuID'])) { echo "menuID=" . $_GET['menuID'] . "&"; } ?>banqueID=<?php echo $row_rsBanques['banque_code']?>&page=<?php echo $_GET['page']; ?>&action=<?php echo $_GET['action']; ?>&comID=<?php echo $_GET['comID']; ?>"<?php if (!(strcmp($row_rsBanques['banque_code'], $_GET['banqueID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsBanques['banque_code'] . " : " . $row_rsBanques['banque_lib'] ?></option>
        <?php
} while ($row_rsBanques = mysql_fetch_assoc($rsBanques));
  $rows = mysql_num_rows($rsBanques);
  if($rows > 0) {
      mysql_data_seek($rsBanques, 0);
	  $row_rsBanques = mysql_fetch_assoc($rsBanques);
  }
?>
      </select>
        <input type="hidden" name="banque_code" value="<?php echo $_GET[banqueID]; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <?php if ( isset($_GET['banqueID'])) { // Show if recordset empty ?>
    <tr valign="baseline">
      <th align="right" valign="middle" nowrap="nowrap">TYPE PERSONNE <span class="error">*</span> :</th>
      <td colspan="3" valign="middle"><?php //if (empty($_GET['personneID'])) { // Show if recordset empty ?>
        <select name="jumpMenu2" id="jumpMenu2" onchange="MM_jumpMenu('parent',this,0)">
          <option value=""  <?php if (!(strcmp("", $_GET['personneID']))) {echo "selected=\"selected\"";} ?>>[Select type personne...]</option>
          <?php
do {  
?>
          <option value="new_personnes.php?menuID=<?php echo $_GET['menuID'] ?>&&banqueID=<?php echo $_GET['banqueID'] ?>&amp;personneID=<?php echo $row_rsTypePersonne['type_personne_id']?>&page=<?php echo $_GET['page']; ?>&action=<?php echo $_GET['action']; ?>&comID=<?php echo $_GET['comID']; ?>"
		  <?php if (!(strcmp($row_rsTypePersonne['type_personne_id'], $_GET['personneID']))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper(htmlentities($row_rsTypePersonne['type_personne_lib']))?></option>
          <?php
} while ($row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne));
  $rows = mysql_num_rows($rsTypePersonne);
  if($rows > 0) {
      mysql_data_seek($rsTypePersonne, 0);
	  $row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
  }
?>
        </select>
        <?php //} // Show if recordset empty ?>
        <?php if (isset($_GET[personneID])) { // Show if recordset not empty ?>
        <input type="hidden" name="type_personne_id" value="<?php echo $_GET[personneID]; ?>" size="32" />
        <?php } // Show if recordset not empty ?>
        <?php echo $_POST['type_personne_id'] ?></td>
    </tr>
    <?php } // Show if recordset empty ?>
    <?php if (isset($_GET['personneID'])) { // Show if recordset empty ?>
    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">MATRICULE <span class="error"><strong>*</strong></span> :</th>
      <td colspan="3"><input name="personne_matricule" type="text" value="<?php if ( isset($_POST['personne_matricule'])) { echo $_POST['personne_matricule'];} ?>" size="12" maxlength="8" />
        (ex :XXXXXX-X)
        <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($personneMatriculeIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the MATRICULE, please!</div>
        <?php }
            if (!$personneMatriculeIsUnique) { ?>
        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This MATRICULE already exists. Please check the spelling and try again</div>
        <?php   } ?></td>
    </tr>
    <?php } else { ?>
    <input name="personne_matricule" type="hidden" value="XXXXXX-X" size="12" maxlength="9" />
    <?php } ?>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">NOM <span class="error">*</span> :</th>
      <td><input type="text" name="personne_nom" value="<?php if ( isset($_POST['personne_nom'])) { echo $_POST['personne_nom'];} ?>" size="32"/>
        <br/>
        <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($personneNomIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the NAME, please!</div>
        <?php  }
            if (!$personneNomIsUnique) { ?>
        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This person already exists. Please check the spelling and try again<?php $link = "detail_personnes.php?recordID=" . $personneID;?>
        <a href="#" onclick="<?php popup($link, "610", "500"); ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle" /></a></div>
        <!--<input type="submit" name="back2" value="Verifier"/>-->
        <?php    }
            ?></td>
      <th align="right">PRENOM :</th>
      <td><input type="text" name="personne_prenom" value="<?php if ( isset($_POST['personne_prenom'])) { echo $_POST['personne_prenom'];} ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
      <input name="domaine_id" type="hidden" value="1" />
      <th nowrap="nowrap" align="right">GRADE :</th>
      <td><input type="text" name="personne_grade" value="<?php if ( isset($_POST['personne_grade'])) { echo $_POST['personne_grade'];} ?>" size="32" /></td>
      <?php } else { ?>
      <input name="personne_grade" type="hidden" value="Certifié" />
      <th align="right" nowrap="nowrap">DOMAINE D'ACTIVITE :</th>
      <td><input type="hidden" name="domaine_id" value="<?php if ( isset($_GET['domID'])) { echo $_GET['domID'];} else {echo "1";} ?>" size="32" />
        <input name="domaine_id2" type="text" value="<?php echo $row_rsDomaines['domaine_lib']; ?>" size="32" readonly="readonly" />
        <a href="#" onclick="<?php popup("popup_domaines.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php           
	$showGoTo = "afficher_domaines.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
        <a href="#" onclick="<?php popup($showGoTo, "610", "500"); ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
      <?php }// Show if recordset empty ?>
      <th>TELEPHONE :</th>
      <td><input type="text" name="personne_telephone" value="<?php if ( isset($_POST['personne_telephone'])) { echo $_POST['personne_telephone'];} ?>" size="32" /></td>
    </tr>
    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2 || $_GET[personneID]==4) { // Show if recordset empty ?>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">STRUCTURE <span class="error">*</span> :</th>
      <td colspan="3"><select name="structure_id">
        <option value="">:: Selectionner </option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsStructures['structure_id']?>" <?php if (!(strcmp($row_rsStructures['structure_id'], htmlentities($_POST['structure_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo strtoupper($row_rsStructures['structure_lib'] . "-" . $row_rsStructures['code_structure'])?></option>
        <?php
} while ($row_rsStructures = mysql_fetch_assoc($rsStructures));
  $rows = mysql_num_rows($rsStructures);
  if($rows > 0) {
      mysql_data_seek($rsStructures, 0);
	  $row_rsStructures = mysql_fetch_assoc($rsStructures);
  }
?>
      </select>
        <a href="#" onclick="<?php popup("new_structures.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($structureIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the STRUCTURE, please</div>
        <?php  } ?></td>
    </tr>
    <?php } else { ?>
    <input name="structure_id" type="hidden" value="342" />
    <?php }// Show if recordset empty ?>
    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
    <tr valign="baseline">
      <th align="right" nowrap="nowrap">GROUPE <span class="error">*</span> :</th>
      <td colspan="3"><select name="sous_groupe_id" id="groupe_id">
        <option selected="selected" value="">:: Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsGroupe['sous_groupe_id']?>" <?php if (!(strcmp($row_rsGroupe['sous_groupe_id'], htmlentities($_POST['sous_groupe_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsGroupe['sous_groupe_lib'])?></option>
        <?php
} while ($row_rsGroupe = mysql_fetch_assoc($rsGroupe));
  $rows = mysql_num_rows($rsGroupe);
  if($rows > 0) {
      mysql_data_seek($rsGroupe, 0);
	  $row_rsGroupe = mysql_fetch_assoc($rsGroupe);
  }
?>
      </select>
        <a href="#" onclick="<?php popup("popup_groupes.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($groupeIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the GROUPE, please</div>
        <?php  } ?></td>
    </tr>
    <?php } else { ?>
    <input name="sous_groupe_id" type="hidden" value="33"/>
    <?php } ?>
    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">FONCTION <span class="error">*</span> :</th>
      <td colspan="3"><select name="fonction_id">
        <option value="" >::Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonction['fonction_id']?>" <?php if (!(strcmp($row_rsFonction['fonction_id'], htmlentities($_POST['fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsFonction['fonction_lib'])?></option>
        <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
      </select>
        <a href="#" onclick="<?php popup("create_fonction.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($fonctionIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the FONCTION, please</div>
        <?php }
            ?></td>
    </tr>
    <?php } else { ?>
    <input name="fonction_id" type="hidden" value="35"/>
    <?php } ?>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <th align="right" valign="top" nowrap="nowrap">AGENCE <span class="error">*</span> :</th>
      <td colspan="3" align="left" valign="top"><select name="agence_code" id="select">
        <option selected="selected" value="">:: Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsAgences['agence_code']?>" <?php if (!(strcmp($row_rsAgences['agence_code'], htmlentities($_POST['agence_code'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsAgences['agence_code'] . " : " . $row_rsAgences['agence_lib']?></option>
        <?php
} while ($row_rsAgences = mysql_fetch_assoc($rsAgences));
  $rows = mysql_num_rows($rsAgences);
  if($rows > 0) {
      mysql_data_seek($rsAgences, 0);
	  $row_rsAgences = mysql_fetch_assoc($rsAgences);
  }
?>
      </select>
        <?php           
	$showGoToAgence = "create_agence.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoToAgence .= (strpos($showGoToAgence, '?')) ? "&" : "?";
    $showGoToAgence .= $_SERVER['QUERY_STRING'];
  }
?>
        <a href="#" onclick="<?php popup($showGoToAgence, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($agenceIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selected AGENCE, please</div>
        <?php  }
            ?></td>
    </tr>
    <tr valign="baseline">
      <th align="right" nowrap="nowrap">N° COMPTE <span class="error">*</span> :</th>
      <td>
      <?php if (isset($_GET['banqueID']) && ($_GET['banqueID']=='XXXXX')){ ?>
	  <input name="numero_compte" type="text" value="<?php echo ( isset($_POST['numero_compte'])) ?  $_POST['numero_compte'] : 'xxxxxxxxxxx'; ?>" size="32" maxlength="11" />  
	  <?php  } else { ?>
      <input name="numero_compte" type="text" value="<?php if ( isset($_POST['numero_compte'])) { echo $_POST['numero_compte'];} ?>" size="32" maxlength="11" />
      <?php } ?>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($numeroCompteIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the NUMERO COMPTE, please</div>
        <?php     }
            if (!$numeroCompteIsUnique) { ?>
        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This NUMERO COMPTE already exists. Please check the spelling and try again</div>
        <?php   }
            ?>
        (11 chiffres)</td>
      <th align="right">CLE <span class="error">*</span> :</th>
      <td>
      <?php if (isset($_GET['banqueID']) && ($_GET['banqueID']=='XXXXX')){ ?>
      <input name="cle" type="text" value="<?php echo ( isset($_POST['cle']))? $_POST['cle'] : 'xx' ?>" size="10" maxlength="2" />
      <?php  } else { ?>
      <input name="cle" type="text" value="<?php if ( isset($_POST['cle'])) { echo $_POST['cle'];} ?>" size="10" maxlength="2" />
      <?php } ?>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($cleIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the KEY, please</div>
        <?php  }
            ?>
        (2 chiffres)</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td colspan="3"><input type="submit" value="Ins&eacute;rer un enregistrement"/>
        <input type="reset" name="back5" value="Reinitialiser"/></td>
    </tr>
    <?php } // Show if recordset empty ?>
  </table>
  <input type="hidden" name="date_creation" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
  <input type="hidden" name="display" value="1" size="32" />
  <input type="hidden" name="personne_id" value="" size="32" />
  <input type="hidden" name="user_name" value="<?php echo $_SESSION['MM_Username']; ?>" size="32" />
</form>
<p>&nbsp;</p>
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
mysql_free_result($rsStructures);

mysql_free_result($rsSousGroupe);

mysql_free_result($rsFonction);

mysql_free_result($rsTypePersonne);

mysql_free_result($rsBanques);

mysql_free_result($rsAgences);

mysql_free_result($rsDomaines);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsUserID);

mysql_free_result($rsBanqueq);

mysql_free_result($rsSelectTypePersonne);

mysql_free_result($rsSousGrouepByUrl);

mysql_free_result($rsSelBanqueUrl);

mysql_free_result($rsGroupe);
?>
