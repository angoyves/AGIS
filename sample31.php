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


$link2 = "sample25.php?comID=".$_GET['comID']."&menuID=".$_GET['menuID']."&valid=".$_GET['valid'];
$logonSuccess = (MinmapDB::getInstance()->verify_session_credentials($_POST['commission_id'], $_GET['mID'], $_POST['annee']));

$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, commissions.localite_id, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

if (isset($_GET['mID'])) {
$link = "sample31.php?comID=".$_GET['comID']."&mID=".$_GET['mID']."&menuID=".$_GET['menuID']."&valid=".$_GET['valid']."&lID=".$row_rsCommissions['localite_id'];
} 
elseif (isset($_POST['annee'])) {
$link = "sample31.php?comID=".$_GET['comID']."&aID=".$_POST['annee']."&menuID=".$_GET['menuID']."&valid=".$_GET['valid']."&lID=".$row_rsCommissions['localite_id'];
} 
elseif (isset($_POST['annee']) && isset($_GET['mID'])) {
$link = "sample31.php?comID=".$_GET['comID']."&aID=".$_POST['annee']."&mID=".$_GET['mID']."&menuID=".$_GET['menuID']."&valid=".$_GET['valid']."&lID=".$row_rsCommissions['localite_id'];
} 
else
{
$link = "sample23.php?comID=".$_GET['comID']."&menuID=".$_GET['menuID']."&valid=".$_GET['valid']."&lID=".$row_rsCommissions['localite_id'];
}

$colname_rsJourMois = "-1";
if (isset($_GET['mID'])) {
  $colname_rsJourMois = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

$colname_RsDossiers = "-1";
if (isset($_GET['comID'])) {
  $colname_RsDossiers = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_RsDossiers = sprintf("SELECT * FROM dossiers WHERE commission_id = %s AND display =1", GetSQLValueString($colname_RsDossiers, "int"));
$RsDossiers = mysql_query($query_RsDossiers, $MyFileConnect) or die(mysql_error());
$row_RsDossiers = mysql_fetch_assoc($RsDossiers);
$totalRows_RsDossiers = mysql_num_rows($RsDossiers);

$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id   AND fonctions_fonction_id = fonctions.fonction_id   AND personnes_personne_id = personne_id  AND membres.fonctions_fonction_id BETWEEN 1 AND 143 AND personnes.display = 1  AND commissions_commission_id = %s AND membres.display = 1 ORDER BY fonctions_fonction_id ", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsRepresentant = "-1";
if (isset($_GET['comID'])) {
  $colname_rsRepresentant = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRepresentant = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id, code_structure FROM commissions, membres, fonctions, personnes, structures  WHERE membres.commissions_commission_id = commissions.commission_id   AND fonctions_fonction_id = fonctions.fonction_id   AND personnes_personne_id = personne_id  AND personnes.structure_id = structures.structure_id AND membres.fonctions_fonction_id <> 1 AND membres.fonctions_fonction_id <> 2 AND membres.fonctions_fonction_id <> 3 AND personnes.display = 1  AND membres.display = 1 AND commissions_commission_id = %s ORDER BY fonctions_fonction_id ", GetSQLValueString($colname_rsRepresentant, "int"));
$rsRepresentant = mysql_query($query_rsRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsRepresentant = mysql_fetch_assoc($rsRepresentant);
$totalRows_rsRepresentant = mysql_num_rows($rsRepresentant);

$date = date('Y-m-d H:i:s');
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
	/*$counter=0; $dossier_traite=""; do { $counter++
	$dossier_traite = '**'.$_POST['dossier_id'.$counter];
	} while ($counter<$_POST['nbres_dossiers'])*/
		
	if ($logonSuccess)
	{
		msgbox("Avertissement!! Enregrisment existant");
		$err_msg = "Avertissement!! Cet enregrisment existe déjà dans la base!!!";	
	}else{
	if(empty($_POST['commission_id'])){
		msgbox("Erreur!! Champ Commission");
		$err_msg2 = "Champ Commission obligatoire!!!";
	}
	elseif(empty($_GET['mID'])){
		msgbox("Erreur!! Champ mois obligatoire");
		$err_msg = "Champ mois obligatoire!!!";
	}
	elseif(empty($_POST['annee'])){
		msgbox("Erreur!! Champ annee vide");
		$err_msg1 = "Champ Annee obligatoire!!!";
	}
	elseif(empty($_POST['personne_id'])){
		msgbox("Erreur!!");
		$err_msg = "Champ membre obligatoire!!!";
	}
	elseif(empty($_POST['fonction_id'])){
		msgbox("Erreur!!");
		$err_msg = "Champ fonction obligatoire!!!";
	}
	elseif(array_sum($_POST['nombre_dossier']) == 0){
		msgbox("Erreur!! Champ Dossier obligatoire");
		$err_msg = "Champ Dossier obligatoire!!!...Entrer le nombre de dossiers traités par session";
	}
	else
	{

$compter = 0; $decompte = 0; $variable; do { $compter++;
// ici on compare les valeurs pour assigner la plus grande valeur à la variable indemnité minmap
if ($decompte<=count($_POST['jour'.$compter])){
	$variable = implode( "**", $_POST['jour'.$compter] );
	$decompte = count($_POST['jour'.$compter]); 
	$docCount++;}
//debut insertion des valeurs du formulaire
if (isset($_POST['personne_id'.$compter]) && $_POST['personne_id'.$compter] != "" && isset($_POST['jour'.$compter]) && array_sum($_POST['jour'.$compter]) > 0 ){
$insertSQL = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, user_id, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString(array_sum($_POST['nombre_dossier']), "int"),
					   GetSQLValueString(count($_POST['jour'.$compter]), "int"),
                       GetSQLValueString($_GET['mID'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id'.$compter], "int"),
                       GetSQLValueString($_POST['fonction_id'.$compter], "int"),
                       GetSQLValueString(implode( "**", $_POST['jour'.$compter]), "text"),
                       GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"),
                       GetSQLValueString($_POST['display'], "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
} 
} while ($compter < $_POST['nombre_personne']);

// debut recuperation du nombre de dossier
//$select_id = implode('**',$_POST['nombre_dossier']);
$value = implode('**',$_POST['nombre_dossier']);


//debut insertion dossier
if (isset($_POST['nbres_dossiers']) && $_POST['nbres_dossiers'] != "" && isset($variable) && array_sum($_POST['nombre_dossier']) > 0 ){
$counter = 0; $dossier_jours = ""; do { $counter++; 
  $dossier_jour = (MinmapDB::getInstance()->get_jour_dossier_by_dossier_id($_POST['dossier_id'.$counter]));
  $dossier_jours = $dossier_jours.$dossier_jour."**"; 
  $dossiers_id = $dossiers_id.$_POST['dossier_id'.$counter]."**";
  
  $updateSQL = sprintf("UPDATE dossiers SET display=0 WHERE dossier_id=%s",
                       GetSQLValueString($_POST['dossier_id'.$counter], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  //echo "Dossier_id =".$_POST['dossier_id'.$counter]." & Dossier Jour =". $dossier_jour . " /";

} while ($counter < $_POST['nbres_dossiers']);

   $insertSQL = sprintf("INSERT INTO dossier_traites (dossiers_commission_id, dossiers_dossier_id, jour, mois, annee, nombre_dossier, dateCreation, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($dossiers_id, "text"),
                       GetSQLValueString($dossier_jours, "text"),
                       GetSQLValueString($_GET['mID'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
					   GetSQLValueString(implode('**',$_POST['nombre_dossier']), "text"),
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"));
   
   mysql_select_db($database_MyFileConnect, $MyFileConnect);
   $Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

}
else 
{
   $insertSQL = sprintf("INSERT INTO dossier_traites (dossiers_commission_id, dossiers_dossier_id, jour, mois, annee, nombre_dossier, dateCreation, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString(0, "text"),
                       GetSQLValueString(0, "text"),
                       GetSQLValueString($_GET['mID'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
					   GetSQLValueString(implode('**',$_POST['nombre_dossier']), "text"),
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"));
   
   mysql_select_db($database_MyFileConnect, $MyFileConnect);
   $Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());}
//Fin


if (isset($_POST['personne_id']) && $_POST['personne_id'] != "" ){
$insertSQL = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString(array_sum($_POST['nombre_dossier']), "int"),
					   GetSQLValueString($decompte, "int"),
                       GetSQLValueString($_GET['mID'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($variable, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"),
					   GetSQLValueString($user_id, "int"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

	$compteur = $compteur + 1;
	$updateSQL = sprintf("UPDATE users SET  compteur=%s WHERE user_id=%s",
						   GetSQLValueString($compteur, "int"),
						   GetSQLValueString($user_id, "int"));
	
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

}

  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr"><!-- InstanceBegin template="/Templates/SessionTemp.dwt" codeOutsideHTMLIsLocked="false" -->
<?php

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

$MM_authorizedUsers = "1,2,3,4,5,6,7";
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

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "connexions.php";
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

?>
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<link href="css/file.css" rel="stylesheet" type="text/css">
<!-- InstanceBeginEditable name="head" -->
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


<script src="js/codeTest.js" language="JavaScript" type="text/javascript"></script>
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
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
        
          <li id="accueil"><a href="<?php echo htmlentities($row_rsMenus['lien']); ?>"><?php echo htmlentities($row_rsMenus['menu_lib']); ?></a></li>
        <?php } while ($row_rsMenus = mysql_fetch_assoc($rsMenus)); ?>
        </ul>
      </div>
      <!--/.menu-->
    </div>
    <!--/#header-->
    <!--/b002n1-->
    <!-- InstanceBeginEditable name="EditBoby" -->
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table>
    <tr align="left" valign="baseline">
      <td nowrap="nowrap"  class="error">        &nbsp;&nbsp;<?php echo $err_msg; ?></td>
      <td nowrap="nowrap" class="error">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="left" nowrap="nowrap"><table border="0" class="std2">
        <tr>
          <th scope="row">Mois:</th>
          <td><select name="mois" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
            <option value="" >[ Choisir ]</option>
            <?php combo_redirect("mois", "mois_id", "lib_mois", "mois_id", $link, $_GET['mID']) ?>
          </select></td>
          <td>&nbsp;</td>
          <th>Annee:</th>
          <td><select name="annee" id="annee" >
            <option value="" >[ Choisir ]</option>
            <?php combo_sel_annee("annee", "lib_annee", "lib_annee", "annee") ?>
          </select>&nbsp;</td>
        </tr>
        <tr>
          <td scope="row">&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>&nbsp;</td>
      <td align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr align="left" valign="baseline">
      <td nowrap="nowrap">
      <h1>Commission</h1>
      <table width="75%" border="1" align="left" class="std">
          <tr valign="baseline">
            <td colspan="4" nowrap="nowrap">Selectionner  une autre commission...
              <?php           
	$showGoTo = "search_commissions2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
              <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>
              <input type="hidden" name="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" />
              <input type="hidden" name="user_name" value="<?php echo $_SESSION['MM_Username']; ?>" size="32" />
              <span class="error"><?php echo $err_msg2; ?></span><br/>
              <strong>
              <?php if (isset($_GET['comID'])) { ?>
                <?php echo strtoupper($row_rsCommissions['commission_lib']); ?>
                <?php } ?>
              </strong>
              <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
              <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
            <?php }
            ?></td>
          </tr>
          <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Nature</th>
            <th>Localite</th>
          </tr>
          <tr>
            <td nowrap="nowrap"><img src="images/img/b_views.png" alt="" width="16" height="16" /></td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsCommissions['type_commission_lib']); ?>&nbsp;</td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsCommissions['lib_nature']); ?>&nbsp;</td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsCommissions['localite_lib']); ?>&nbsp;</td>
          </tr>
          <tr valign="baseline">
            <td colspan="4" align="right" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoTo2, "610", "300"); ?>">
              <input type="hidden" name="nbre_jours" value="<?php if ( isset($row_rsJourMois['nbre_jour'])) { echo $row_rsJourMois['nbre_jour'];} ?>" size="32" />
              <input type="hidden" name="mois2" value="<?php if ( isset($_GET['mID'])) { echo $_GET['mID'];} ?>" size="32" />
              <input type="hidden" name="jour2" value="" />
              <input type="hidden" name="nombre_dossier" value="1" />
            </a></td>
          </tr>
      </table></td>
      <td nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="left" nowrap="nowrap">
      <h1>
        <?php if (isset($_GET['comID']) && isset($_GET['mID'])) { // Show if recordset not empty ?>
        Dossiers</h1>  
        
        <table width="36%" border="1" class="std">
          <tr>
            <th colspan="3">Jours
            </th>
            <?php //$counter = 0; do { $counter++; ?>
            <th width="37" align="center"><?php echo $counter ?>&nbsp;</th>
            <?php //} while($counter < $row_rsJourMois['nbre_jour']); ?>
          </tr>
          <tr>
            <td colspan="3"><p>Jour Session</p></td>
            <?php //$counter = 0; do { $counter++; ?>
            <td><select name="<?php echo "nombre_dossier" ?>">
              <option value="0"></option>
              <?php $var = 0; do { $var++; ?>
              <!--<option value="<?php //echo $var ?>"><?php //echo $var ?></option>-->	
              <?php combo_sel_value2($var, $chmp) ?>		
              <?php } while ($var < $row_rsJourMois['nbre_jour']); ?>
            </select></td>
            <?php //} while($counter < $row_rsJourMois['nbre_jour']); ?>
          </tr>
          <tr>
            <td colspan="3">Dossier trait&eacute;</td>
            <?php //$counter = 0; do { $counter++; ?>
            <td align="center">
              <?php           
			$showGoTo = "dossiers.php?day=". $counter ;
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
              <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">
              <img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" />
              </a>
            </td>
            <?php //} while($counter < $row_rsJourMois['nbre_jour']); ?>
          </tr>
      </table> 
      <?php } ?>
      </td>
      <td align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="left" nowrap="nowrap"><input type="hidden" name="dossiers_dossier_id" value="3" size="32" />
        <?php if ($totalRows_RsDossiers > 0) { // Show if recordset not empty ?>
          <table border="1" class="std">
            <tr>
              <th>N°</th>
              <th>Reference</th>
              <th>Fichier Joint</th>
              <th>Jour</th>
              <th>Observations</th>
              <th>&nbsp;</th>
              <th>&nbsp;</th>
            </tr>
            <?php $counter=0; do { $counter++; ?>
            <tr>
              <td><a href="sample24.php?recordID=<?php echo $row_RsDossiers['dossier_id']; ?>"> <?php echo $counter; ?></a><a href="#" onclick="<?php popup($showGoTo2, "610", "300"); ?>">
                <input type="hidden" name="nbres_dossiers" value="<?php echo $counter; ?>" />
                <input type="hidden" name="<?php echo "dossier_id".$counter; ?>" value="<?php echo $row_RsDossiers['dossier_id']; ?>" />
              </a></td>
              <td><?php echo $row_RsDossiers['dossier_ref']; ?>&nbsp; </td>
              <td><a  href="upload/<?php echo $row_RsDossiers['dossier_nom'] ?>"><?php echo $row_RsDossiers['dossier_nom']; ?></a>&nbsp; </td>
              <td align="center"><?php echo $row_RsDossiers['dossiers_jour']; ?>&nbsp; </td>
              <td><?php echo $row_RsDossiers['dossier_observ']; ?>&nbsp; </td>
              <?php           
			$showGoTo2 = "sample25.php?dosID=". $row_RsDossiers['dossier_id'] ;
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo2 .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo2 .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
              <td><a href="" onclick="<?php popup($showGoTo2, "610", "300"); ?>"><img src="images/img/b_edit.png" width="16" height="16" /></a></td>
              <td>&nbsp;</td>
            </tr>
            <?php } while ($row_RsDossiers = mysql_fetch_assoc($RsDossiers)); ?>
          </table>
      <?php } // Show if recordset not empty ?></td>
      <td align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap"><input type="hidden" name="membres_personnes_personne_id" value="<?php show_content($err_msg, $_POST['membres_personnes_personne_id']) ?>" size="32" /></td>
      <td align="right" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="left" nowrap="nowrap"><input type="hidden" name="membres_fonctions_fonction_id" value="<?php show_content($err_msg, $row_rsMembres['fonctions_fonction_id']) ?>" size="32" />        
      <h1>
        <?php if (isset($_GET['comID']) && isset($_GET['mID'])) { // Show if recordset not empty ?>
        Sessions Membres</h1>
      <table border="1" class="std">
          <tr>
            <th>ID</th>
            <th>Noms et Prenoms</th>
            <th>Fonctions</th>
            <?php $counter = 0; do { $counter++; ?>
            <th align="center"><?php echo $counter ?>&nbsp;</th>
            <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
          </tr>
          <?php $compter = 0; do { $compter++; ?>
          <tr>
            <td><a href="show_personnes.php?txtChamp=personne_id&amp;txtSearch=<?php echo $row_rsMembres['personne_id']; ?>&amp;menuID=2&amp;action=upd" onclick="<?php popup($showGoToPersonne, "700", "300"); ?>"> <?php echo $compter; ?></a>&nbsp;</td>
            <td><?php echo strtoupper($row_rsMembres['personne_nom'] . " " . $row_rsMembres['personne_prenom']); ?>
              <input type="hidden" name="<?php echo "personne_id".$compter; ?>" value="<?php echo $row_rsMembres['personne_id']; ?>" size="32" />
            <input type="hidden" name="personne_id" id="personne_id" value="2129"/></td>
            <td><?php echo $row_rsMembres['fonction_lib']; ?>&nbsp;
              <input type="hidden" name="<?php echo "fonction_id".$compter; ?>" value="<?php echo $row_rsMembres['fonctions_fonction_id']; ?>" size="32" />
              &nbsp;<a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
              <input type="hidden" name="fonction_id" id="fonction_id" value="41"/>
            </a></td>
            <?php //$counter = 0; do { $counter++;
			foreach(GetValueList($_GET['mID']) as $value){
			switch($compter){
			case $compter :
			$liste = $_POST['jour'.$compter];
		  ?>
            <td><input type="checkbox" name="<?php echo "jour" . $compter . "[]" ?>" id="<?php echo "jour". $compter . $value ?>" value="<?php echo $value ?>"  
		  <?php echo ((in_array($value, $liste))? "checked='checked'":"") ?> /></td>
            <?php break; } } ?>
            <?php //} while($counter < $row_rsJourMois['nbre_jour']); ?>
          </tr>
          <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
          <input type="hidden" name="nombre_personne" id="nombre_personne" value="<?php echo $compter ?>"/>
      </table>
      <?php } ?>
      </td>
      <td align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="left" nowrap="nowrap"><input type="hidden" name="jour" value="<?php show_content($err_msg, $_POST['jour']) ?>" size="32" />        
      <h1>&nbsp;</h1></td>
      <td align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td align="right" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td align="left" nowrap="nowrap"><input type="submit" value="Ins&eacute;rer un enregistrement" /></td>
      <td align="left" nowrap="nowrap">&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="Nombre_jour" value="" />
  <input type="hidden" name="etat_appur" value="0" />
  <input type="hidden" name="dateCreation" value="<?php echo $date ?>" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
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

mysql_free_result($rsJourMois);

mysql_free_result($RsDossiers);

mysql_free_result($rsMembres);

mysql_free_result($rsRepresentant);

?>
