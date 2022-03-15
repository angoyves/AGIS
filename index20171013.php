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
if (!isset($_SESSION)) {
  session_start();
}

if (isset($_REQUEST['txtRegion']))
$_SESSION['select_value'] = $_REQUEST['txtRegion'];

$txtYear = 2014;
if (isset($_REQUEST['txtYear']))
$txtYear = $_REQUEST['txtYear'];

$txtMonth1 = 01;
if (isset($_REQUEST['txtMonth1']))
$txtMonth1 = $_REQUEST['txtMonth1'];

$txtMonth2 = 12;
if (isset($_REQUEST['txtMonth2']))
$txtMonth2 = $_REQUEST['txtMonth2']; 	

$txtRegion = $_SESSION['select_value'];
if (isset($_POST['txtRegion']))
$txtRegion = $_POST['txtRegion'];

$colname_rsSelSessions = "12";
if (isset($_SESSION['MM_UserID'])) {
  $colname_rsSelSessions = $_SESSION['MM_UserID'];
}

$etat_validate = (MinmapDB::getInstance()->afficher_etat_valide($_GET['comID'], $_POST['txt_Month'], $_POST['txt_Year']));
/*$user_validate = (MinmapDB::getInstance()->afficher_user($_GET['comID'], $_POST['txt_Month'], $_POST['txt_Year']));
$user_controlate = (MinmapDB::getInstance()->afficher_user($_GET['comID'], $_POST['txt_Month'], $_POST['txt_Year']));
$user_saisie = (MinmapDB::getInstance()->afficher_user($_GET['comID'], $_POST['txt_Month'], $_POST['txt_Year'])); */
$etat_controlate = (MinmapDB::getInstance()->afficher_etat_control($_GET['comID'], $_POST['txt_Month'], $_POST['txt_Year']));

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

if (!function_exists("GetRegionLibByTYpe")) {
function GetRegionLibByTYpe($theType) 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }
  switch ($theType) {
    case "1":
      $theValue = "CCPM";
      break;    
    case "2":
      $theValue = "CMPM";
      break;
    case "3":
      $theValue = "CRPM";
      break;
    case "4":
       $theValue = "CDPM";
      break;
  }
  return $theValue;
}
}

if (!function_exists("GetLibCentralCommissionByTYpe")) {
function GetLibCentralCommissionByComID($theComID) 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }
  switch ($theComID) {
    case "677":
      $theValue = "AG";
      break;    
    case "676":
      $theValue = "AI";
      break;
    case "1302":
      $theValue = "BEC";
      break;
    case "1301":
       $theValue = "SPI";
      break;
	case "1300":
       $theValue = "TR";
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelSessions = sprintf("SELECT membres_commissions_commission_id, mois, annee, localite_id, commission_lib FROM sessions, commissions WHERE membres_commissions_commission_id = commission_id AND sessions.user_id = %s AND etat_validation = 0 AND userValidate IS NOT NULL AND userControlate IS NOT NULL GROUP BY mois, annee, membres_commissions_commission_id", GetSQLValueString($colname_rsSelSessions, "int"));
$rsSelSessions = mysql_query($query_rsSelSessions, $MyFileConnect) or die(mysql_error());
$row_rsSelSessions = mysql_fetch_assoc($rsSelSessions);
$totalRows_rsSelSessions = mysql_num_rows($rsSelSessions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois1 = "SELECT * FROM mois";
$rsMois1 = mysql_query($query_rsMois1, $MyFileConnect) or die(mysql_error());
$row_rsMois1 = mysql_fetch_assoc($rsMois1);
$totalRows_rsMois1 = mysql_num_rows($rsMois1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois2 = "SELECT * FROM mois";
$rsMois2 = mysql_query($query_rsMois2, $MyFileConnect) or die(mysql_error());
$row_rsMois2 = mysql_fetch_assoc($rsMois2);
$totalRows_rsMois2 = mysql_num_rows($rsMois2);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee ORDER BY lib_annee ASC";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (trim($txtRegion) == ''){
$query_rsRegions = "SELECT * FROM regions WHERE display = '1' ORDER BY region_lib ASC";
} else {
$query_rsRegions = sprintf("SELECT * FROM regions WHERE region_id = %s AND display = '1' ORDER BY region_lib ASC", GetSQLValueString($txtRegion, "int"));	
}
$rsRegions = mysql_query($query_rsRegions, $MyFileConnect) or die(mysql_error());
$row_rsRegions = mysql_fetch_assoc($rsRegions);
$totalRows_rsRegions = mysql_num_rows($rsRegions);


mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = "SELECT * FROM commissions
WHERE type_commission_id = 1 OR type_commission_id = 3
AND display = 1";
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);


mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRegion = "SELECT region_id, region_lib, display FROM regions WHERE display = '1'";
$rsRegion = mysql_query($query_rsRegion, $MyFileConnect) or die(mysql_error());
$row_rsRegion = mysql_fetch_assoc($rsRegion);
$totalRows_rsRegion = mysql_num_rows($rsRegion);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsControlate = "SELECT region_lib, localite_lib, membres_commissions_commission_id, commission_lib, mois, annee, etat_validation, userValidate, userControlate, sessions.user_id FROM sessions, commissions, localites, regions WHERE sessions.membres_commissions_commission_id = commissions.commission_id AND localites.localite_id = commissions.localite_id AND regions.region_id = commissions.region_id AND etat_validation BETWEEN 0 AND 1  AND type_commission_id BETWEEN 3 AND 4 AND userValidate IS NOT NULL AND annee = 2014 GROUP BY mois, annee, membres_commissions_commission_id ORDER BY annee, region_lib, localite_lib, mois";
$rsControlate = mysql_query($query_rsControlate, $MyFileConnect) or die(mysql_error());
$row_rsControlate = mysql_fetch_assoc($rsControlate);
$totalRows_rsControlate = mysql_num_rows($rsControlate);

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
<br><form action="index.php" method="post">
  <table border="0" align="center" class="">
    <tr>
      <th align="right" scope="row">Exercice:</th>
      <td><select name="txtYear" id="txtYear">
        <option value=""<?php if (!(strcmp("", $txtYear))) {echo "selected=\"selected\"";} ?>>Select:::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], $txtYear))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
        <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
  $rows = mysql_num_rows($rsAnnee);
  if($rows > 0) {
      mysql_data_seek($rsAnnee, 0);
	  $row_rsAnnee = mysql_fetch_assoc($rsAnnee);
  }
?>
      </select></td>
      <td>&nbsp;</td>
      <td><input type="hidden" name="comID" id="comID" value="<?php echo $_GET['comID'] ?>"/></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th align="right" scope="row">Periode allant de :</th>
      <td><select name="txtMonth1" id="txtMonth1">
        <?php
do {  
?>
        <option value="<?php echo $row_rsMois1['mois_id']?>" <?php if (!(strcmp($row_rsMois1['mois_id'], $txtMonth1))) {echo "selected=\"selected\"";} ?>><?php echo Ucfirst($row_rsMois1['lib_mois']);?></option>
        <?php
} while ($row_rsMois1 = mysql_fetch_assoc($rsMois1));
  $rows = mysql_num_rows($rsMois1);
  if($rows > 0) {
      mysql_data_seek($rsMois1, 0);
	  $row_rsMois1 = mysql_fetch_assoc($rsMois1);
  }
?>
      </select></td>
      <th>à</th>
      <td><select name="txtMonth2" id="txtMonth2">
        <option value="" <?php if (!(strcmp("", $txtMonth2))) {echo "selected=\"selected\"";} ?>>Select:::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsMois2['mois_id']?>" <?php if (!(strcmp($row_rsMois2['mois_id'], $txtMonth2))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois2['lib_mois']); ?></option>
        <?php
} while ($row_rsMois2 = mysql_fetch_assoc($rsMois2));
  $rows = mysql_num_rows($rsMois2);
  if($rows > 0) {
      mysql_data_seek($rsMois2, 0);
	  $row_rsMois2 = mysql_fetch_assoc($rsMois2);
  }
?>
      </select></td>
      <td><input type="submit" name="button" id="button" value="Rechercher" />
        &nbsp;</td>
    </tr>
    <tr>
      <th align="right" scope="row">Region :</th>
      <td colspan="3">

        
<select name="txtRegion">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsRegion['region_id']?>" <?php if (!(strcmp($_SESSION['select_value'], htmlentities($row_rsRegion['region_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsRegion['region_lib'] ?></option>
        <?php
} while ($row_rsRegion = mysql_fetch_assoc($rsRegion));
?>
      </select>        
        &nbsp;        
        </td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>

<p>
  <?php
		
/*		do  { 
      	<h2>	MODIFICATION DEMANDE PAR LES SERVICES DE CONTROLE POUR LE MOIS DE ... ANNEE ... MOTIF...</h2></br>
		} while (isset($etat_validate) && $etat_validate=='0' && isset($user_validate) && isset($user_controlate) && ($user_saisie==$_SESSION['MM_UserID']));*/
		?>
</p>
<p>&nbsp;</p>
<hr>
<table border="0" align="center">
  <?php if ($_SESSION['MM_UserGroup']==7 ) { // Show if recordset not empty ?>
<table border="1" align="center"  class="std">
        <tr>
          <th>REGION</th>
          <th>LOCALITE</th>
          <th>COMMISSION</th>
          <th>MOIS</th>
          <th>ANNEE</th>
          <th>VALIDATION</th>
          <th>VALIDATEUR</th>
          <th>CONTROLEUR</th>
  </tr>
        <?php $counter=0;  do  { $counter++;  ?>
          <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
            <td><?php echo $row_rsControlate['region_lib']; ?>&nbsp; </td>
            <td><?php echo $row_rsControlate['localite_lib']; ?>&nbsp; </td>
            <td><a href="sample57.php?menuID=4&action=&comID=<?php echo $row_rsControlate['membres_commissions_commission_id']; ?>&mID=<?php echo $row_rsControlate['mois']; ?>&aID=<?php echo $row_rsControlate['annee']; ?>&lID=2"> <?php echo $row_rsControlate['commission_lib']; ?>&nbsp; </a></td>
            <td><?php echo $row_rsControlate['mois']; ?>&nbsp; </td>
            <td><?php echo $row_rsControlate['annee']; ?>&nbsp; </td>
            <td><?php echo ($row_rsControlate['etat_validation']==0?'Etat en attente de Modification':'Etat en attente de controle'); ?>&nbsp; </td>
            <td><?php echo MinmapDB::getInstance()->get_user_name_by_id($row_rsControlate['user_id']); ?>&nbsp; </td>
            <td><?php echo MinmapDB::getInstance()->get_user_name_by_id($row_rsControlate['userControlate']); ?>&nbsp; </td>
          </tr>
          <?php } while ($row_rsControlate = mysql_fetch_assoc($rsControlate)); ?>
</table>
<?php echo $totalRows_rsControlate ?> Records Total 
  <?php } ?>
  <?php if ($totalRows_rsSelSessions > 0) { // Show if recordset not empty ?>
  <?php do { ?>
    <tr class="control">
      <td>
        <a href="sample38.php?menuID=4&action=&comID=<?php echo $row_rsSelSessions['membres_commissions_commission_id']; ?>&lID=<?php echo $row_rsSelSessions['localite_id']; ?>&aID=<?php echo $row_rsSelSessions['annee']; ?>&mID=<?php echo $row_rsSelSessions['mois']; ?>"><h3><img src="images/Icones/ico_forum.png" width="32" height="30">Demande de modification de l'etat du mois de <?php echo MinmapDB::getInstance()->get_mois_lib($row_rsSelSessions['mois']); ?>&nbsp;<?php echo $row_rsSelSessions['annee']; ?>&nbsp; de la <?php echo MinmapDB::getInstance()->get_commission_lib_by_commission_id($row_rsSelSessions['membres_commissions_commission_id']); ?> par les services du controle.</h3></a></td>
    </tr>
    <?php } while ($row_rsSelSessions = mysql_fetch_assoc($rsSelSessions)); ?>
    <?php } // Show if recordset not empty ?>
</table>
<?php if ($totalRows_rsSelSessions == 0) { // Show if recordset not empty ?>
  <table width="958" border="0" align="center">
    <tr>
      <th width="488" align="center" scope="col"><p><strong>BORDEREAU RECAPITULATIF DES ETATS DES INDEMNITES DES COMMISSIONS DE PASSATION DE MARCHES DE <?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($txtMonth1)); ?> A <?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($txtMonth2)); ?> <?php echo $txtYear ?></strong></p></th>
      </tr>
  </table>
  <table border="1" align="center" class="std">
    <tr>
      <th>N&deg;</th>
      <th>COMMISSIONS</th>
      <th nowrap>Commissions</th>
      <th nowrap>Sous Commissions</th>
      <th nowrap>Representants MO</th>
      <th nowrap>Montant Total</th>
      <th nowrap>Retenu <?php echo ($Retenu = MinmapDB::getInstance()->get_retenu($txtYear))*100; ?>%</th>
      <th nowrap>Net &agrave; percevoir</th>
      <th nowrap>Montant Pay&eacute;</th>
    </tr>
    <?php $somme_total=0; $count=0; do { $count++; ?>
    <tr>
      <td height="23" colspan="9" align="center"><strong>REGION : <?php echo $row_rsRegions['region_lib']; ?></strong>&nbsp;   </td>
    </tr>
    <?php 
    mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$query_rsCommissionsR = sprintf("SELECT commission_id, commissions.localite_id, localite_lib, commissions.region_id, commissions.type_commission_id FROM commissions, localites
	WHERE commissions.localite_id = localites.localite_id
	AND commissions.region_id = %s AND (type_commission_id BETWEEN 1 AND 4) 
	AND commissions.display = 1 ORDER BY commissions.type_commission_id, localites.localite_lib", GetSQLValueString($row_rsRegions['region_id'], "int"));
	$rsCommissionsR = mysql_query($query_rsCommissionsR, $MyFileConnect) or die(mysql_error());
	$row_rsCommissionsR = mysql_fetch_assoc($rsCommissionsR);
	$totalRows_rsCommissionsR = mysql_num_rows($rsCommissionsR);
	?>
    <?php $counter=0; $somme_brut=0; $somme_retenu=0; do  { $counter++; $count++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="commission_detail.php?recordID=<?php echo $row_rsCommissionsR['commission_id']; ?>"><?php echo $count; //$row_rsCommissionsR['commission_id']; ?></a></td>
      <?php $link = 'sample53.php?txtMonth1='. $txtMonth1 .'&txtMonth2='. $txtMonth2 .'&txtYear='. $txtYear .'&comID='. $row_rsCommissionsR['commission_id']; 
	  		$link2 = 'etat_appurements.php?m1ID='. $txtMonth1 .'&m2ID='. $txtMonth2 .'&aID='. $txtYear .'&comID='. $row_rsCommissionsR['commission_id'];
	  ?>
      <td nowrap><a href="" onClick="<?php popup($link, "1200", "700"); ?>">
        
        <?php 
	  if (isset($row_rsCommissionsR['type_commission_id']) && $row_rsCommissionsR['type_commission_id']==1){
	  echo GetRegionLibByTYpe($row_rsCommissionsR['type_commission_id']) .' '.strtoupper(GetLibCentralCommissionByComID($row_rsCommissionsR['commission_id'])); 
	  } elseif (isset($row_rsCommissionsR['type_commission_id']) && $row_rsCommissionsR['type_commission_id']==2) {
	  echo GetRegionLibByTYpe($row_rsCommissionsR['type_commission_id']) .' '.strtoupper($row_rsCommissionsR['commission_lib']); 
	  } else {
	  echo GetRegionLibByTYpe($row_rsCommissionsR['type_commission_id']) .' '.strtoupper($row_rsCommissionsR['localite_lib']); 
	  }?>
        
        
        </a>
        <?php if (isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup']==1 || $_SESSION['MM_UserGroup']==8)) { ?>
          <a href="" onClick="<?php popup($link2, "1200", "700"); ?>">Print recap<img src="images/img/b_print.png" width="16" height="16" border="0" /></a>
          <?php } ?>
      </td>
      <td align="right"><?php echo number_format($montant_brut1 = MinmapDB::getInstance()->get_montant_commissions($row_rsCommissionsR['commission_id'], $txtYear, $txtMonth1, $txtMonth2),0,' ',' ');?>&nbsp;</td>
      <td align="right"><?php echo number_format($montant_brut2 = MinmapDB::getInstance()->get_montant_sous_commissions($row_rsCommissionsR['commission_id'], $txtYear, $txtMonth1, $txtMonth2),0,' ',' ');?>&nbsp;</td>
      <td align="right"><?php echo number_format($montant_brut3 = MinmapDB::getInstance()->get_montant_representants($row_rsCommissionsR['commission_id'], $txtYear, $txtMonth1, $txtMonth2, 40),0,' ',' ');?>&nbsp;</td>
      <td align="right" nowrap><?php echo number_format($montant_brut = $montant_brut1 + $montant_brut2 + $montant_brut3,0,' ',' '); 
	  $somme_brut = $somme_brut + $montant_brut;?>&nbsp;</td>
      <td align="right" nowrap><?php echo number_format($somme_retenu = $montant_brut*$Retenu ,0,' ',' '); 
	  $somme_brut = $somme_brut + $montant_brut;?></td>
      <td align="right" nowrap><strong><?php echo number_format($montant_brut - $somme_retenu ,0,' ',' '); 
	  $somme_brut = $somme_brut + $montant_brut;?></strong>&nbsp;</td>
      <td align="right" nowrap>&nbsp;</td>
    </tr>
    <?php } while ($row_rsCommissionsR = mysql_fetch_assoc($rsCommissionsR)); ?>
    <tr>
      <td colspan="2"><strong>Sous Total Commission Region du <?php echo $row_rsRegions['region_lib']; ?>&nbsp;</strong></td>
      <td align="right"></td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right" nowrap><?php echo number_format($somme_brut,0,' ',' '); $somme_total = $somme_total + $somme_brut; ?>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php } while ($row_rsRegions = mysql_fetch_assoc($rsRegions)); ?>
    <tr>
      <td colspan="9">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><strong>TOTAL GENERAL</strong></td>
      <td colspan="4" align="right"><strong><?php echo number_format($somme_total,0,' ',' '); ?></strong></td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2"><strong>INDEMNITES MINMAP</strong></td>
      <td colspan="4" align="right"><strong><?php echo number_format($Indemnite_Minmap = MinmapDB::getInstance()->get_montant_indemnite_minmap($txtYear, $txtMonth1, $txtMonth2, 41),0,' ',' ');?></strong></td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
    </tr>
  </table>
  <?php } // Show if recordset not empty ?>
</p>
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
mysql_free_result($rsRegions);
mysql_free_result($rsCommissionsD);
mysql_free_result($rsCommissionsR);
mysql_free_result($rsCommissions);
mysql_free_result($rsMois1);
mysql_free_result($rsMois2);
mysql_free_result($rsAnnee);

mysql_free_result($rsRegion);

mysql_free_result($rsSelSessions);

mysql_free_result($rsControlate);
?>
