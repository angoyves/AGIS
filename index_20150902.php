<?php 
	  require_once('Connections/MyFileConnect.php'); 
	  require_once("includes/db_1.php");
?>
<?php
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php


$currentPage = $_SERVER["PHP_SELF"];

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
$query_rsCountPersonnes = "SELECT count(personne_id) as Nombres FROM personnes";
$rsCountPersonnes = mysql_query($query_rsCountPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsCountPersonnes = mysql_fetch_assoc($rsCountPersonnes);
$totalRows_rsCountPersonnes = mysql_num_rows($rsCountPersonnes);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountExpert = "SELECT count(personne_id) as NombresExpert FROM personnes WHERE personne_matricule like '%XXX%'";
$rsCountExpert = mysql_query($query_rsCountExpert, $MyFileConnect) or die(mysql_error());
$row_rsCountExpert = mysql_fetch_assoc($rsCountExpert);
$totalRows_rsCountExpert = mysql_num_rows($rsCountExpert);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountCommissions = "SELECT count(commission_id) as NombreCommission FROM commissions";
$rsCountCommissions = mysql_query($query_rsCountCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCountCommissions = mysql_fetch_assoc($rsCountCommissions);
$totalRows_rsCountCommissions = mysql_num_rows($rsCountCommissions);

$maxRows_rsGroupByLocalite = 10;
$pageNum_rsGroupByLocalite = 0;
if (isset($_GET['pageNum_rsGroupByLocalite'])) {
  $pageNum_rsGroupByLocalite = $_GET['pageNum_rsGroupByLocalite'];
}
$startRow_rsGroupByLocalite = $pageNum_rsGroupByLocalite * $maxRows_rsGroupByLocalite;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupByLocalite = "SELECT count(commission_id) as Nombre, localite_lib FROM commissions, localites WHERE commissions.localite_id = localites.localite_id GROUP BY  localites.localite_id";
$query_limit_rsGroupByLocalite = sprintf("%s LIMIT %d, %d", $query_rsGroupByLocalite, $startRow_rsGroupByLocalite, $maxRows_rsGroupByLocalite);
$rsGroupByLocalite = mysql_query($query_limit_rsGroupByLocalite, $MyFileConnect) or die(mysql_error());
$row_rsGroupByLocalite = mysql_fetch_assoc($rsGroupByLocalite);

if (isset($_GET['totalRows_rsGroupByLocalite'])) {
  $totalRows_rsGroupByLocalite = $_GET['totalRows_rsGroupByLocalite'];
} else {
  $all_rsGroupByLocalite = mysql_query($query_rsGroupByLocalite);
  $totalRows_rsGroupByLocalite = mysql_num_rows($all_rsGroupByLocalite);
}
$totalPages_rsGroupByLocalite = ceil($totalRows_rsGroupByLocalite/$maxRows_rsGroupByLocalite)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupByNature = "SELECT lib_nature, count(commission_id) as Nombre FROM commissions, natures WHERE commissions.nature_id = natures.nature_id GROUP BY  natures.nature_id";
$rsGroupByNature = mysql_query($query_rsGroupByNature, $MyFileConnect) or die(mysql_error());
$row_rsGroupByNature = mysql_fetch_assoc($rsGroupByNature);
$totalRows_rsGroupByNature = mysql_num_rows($rsGroupByNature);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupType = "SELECT count(commission_id) as Nombre, type_commission_lib FROM commissions, type_commissions WHERE commissions.type_commission_id = type_commissions.type_commission_id GROUP BY  type_commissions.type_commission_id";
$rsGroupType = mysql_query($query_rsGroupType, $MyFileConnect) or die(mysql_error());
$row_rsGroupType = mysql_fetch_assoc($rsGroupType);
$totalRows_rsGroupType = mysql_num_rows($rsGroupType);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountPersonnel = "SELECT count(personne_id) as NbrPersonnel FROM personnes WHERE personne_matricule <> 'XXXXXX-X'";
$rsCountPersonnel = mysql_query($query_rsCountPersonnel, $MyFileConnect) or die(mysql_error());
$row_rsCountPersonnel = mysql_fetch_assoc($rsCountPersonnel);
$totalRows_rsCountPersonnel = mysql_num_rows($rsCountPersonnel);

$maxRows_rsCountByStructure = 10;
$pageNum_rsCountByStructure = 0;
if (isset($_GET['pageNum_rsCountByStructure'])) {
  $pageNum_rsCountByStructure = $_GET['pageNum_rsCountByStructure'];
}
$startRow_rsCountByStructure = $pageNum_rsCountByStructure * $maxRows_rsCountByStructure;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountByStructure = "SELECT structure_lib, count(personne_id) as NbrPersonnels FROM personnes, structures WHERE personnes.structure_id = structures.structure_id group by structure_lib";
$query_limit_rsCountByStructure = sprintf("%s LIMIT %d, %d", $query_rsCountByStructure, $startRow_rsCountByStructure, $maxRows_rsCountByStructure);
$rsCountByStructure = mysql_query($query_limit_rsCountByStructure, $MyFileConnect) or die(mysql_error());
$row_rsCountByStructure = mysql_fetch_assoc($rsCountByStructure);

if (isset($_GET['totalRows_rsCountByStructure'])) {
  $totalRows_rsCountByStructure = $_GET['totalRows_rsCountByStructure'];
} else {
  $all_rsCountByStructure = mysql_query($query_rsCountByStructure);
  $totalRows_rsCountByStructure = mysql_num_rows($all_rsCountByStructure);
}
$totalPages_rsCountByStructure = ceil($totalRows_rsCountByStructure/$maxRows_rsCountByStructure)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = "SELECT sum(nombre_jour) as nbreJours FROM SESSIONS";
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

$maxRows_rsSessionsAnnee = 10;
$pageNum_rsSessionsAnnee = 0;
if (isset($_GET['pageNum_rsSessionsAnnee'])) {
  $pageNum_rsSessionsAnnee = $_GET['pageNum_rsSessionsAnnee'];
}
$startRow_rsSessionsAnnee = $pageNum_rsSessionsAnnee * $maxRows_rsSessionsAnnee;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionsAnnee = "SELECT annee, sum(nombre_jour) as nbreJour FROM SESSIONS GROUP BY ANNEE";
$query_limit_rsSessionsAnnee = sprintf("%s LIMIT %d, %d", $query_rsSessionsAnnee, $startRow_rsSessionsAnnee, $maxRows_rsSessionsAnnee);
$rsSessionsAnnee = mysql_query($query_limit_rsSessionsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsSessionsAnnee = mysql_fetch_assoc($rsSessionsAnnee);

if (isset($_GET['totalRows_rsSessionsAnnee'])) {
  $totalRows_rsSessionsAnnee = $_GET['totalRows_rsSessionsAnnee'];
} else {
  $all_rsSessionsAnnee = mysql_query($query_rsSessionsAnnee);
  $totalRows_rsSessionsAnnee = mysql_num_rows($all_rsSessionsAnnee);
}
$totalPages_rsSessionsAnnee = ceil($totalRows_rsSessionsAnnee/$maxRows_rsSessionsAnnee)-1;

$queryString_rsGroupByLocalite = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsGroupByLocalite") == false && 
        stristr($param, "totalRows_rsGroupByLocalite") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsGroupByLocalite = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsGroupByLocalite = sprintf("&totalRows_rsGroupByLocalite=%d%s", $totalRows_rsGroupByLocalite, $queryString_rsGroupByLocalite);

$queryString_rsSessionsAnnee = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSessionsAnnee") == false && 
        stristr($param, "totalRows_rsSessionsAnnee") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSessionsAnnee = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSessionsAnnee = sprintf("&totalRows_rsSessionsAnnee=%d%s", $totalRows_rsSessionsAnnee, $queryString_rsSessionsAnnee);

$queryString_rsCountByStructure = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsCountByStructure") == false && 
        stristr($param, "totalRows_rsCountByStructure") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsCountByStructure = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsCountByStructure = sprintf("&totalRows_rsCountByStructure=%d%s", $totalRows_rsCountByStructure, $queryString_rsCountByStructure);
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
<?php
$maxRows_rsUsers = 100;
$pageNum_rsUsers = 0;
if (isset($_GET['pageNum_rsUsers'])) {
  $pageNum_rsUsers = $_GET['pageNum_rsUsers'];
}
$startRow_rsUsers = $pageNum_rsUsers * $maxRows_rsUsers;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUsers = "SELECT user_id, user_name, user_lastname, users.structure_id, structure_lib, compteur FROM users, structures WHERE users.structure_id = structures.structure_id AND users.display = '1'";
$query_limit_rsUsers = sprintf("%s LIMIT %d, %d", $query_rsUsers, $startRow_rsUsers, $maxRows_rsUsers);
$rsUsers = mysql_query($query_limit_rsUsers, $MyFileConnect) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);

if (isset($_GET['totalRows_rsUsers'])) {
  $totalRows_rsUsers = $_GET['totalRows_rsUsers'];
} else {
  $all_rsUsers = mysql_query($query_rsUsers);
  $totalRows_rsUsers = mysql_num_rows($all_rsUsers);
}
$totalPages_rsUsers = ceil($totalRows_rsUsers/$maxRows_rsUsers)-1;

$queryString_rsUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsUsers") == false && 
        stristr($param, "totalRows_rsUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsUsers = sprintf("&totalRows_rsUsers=%d%s", $totalRows_rsUsers, $queryString_rsUsers);
?>
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
    <p>&nbsp;</p>
    <h1>Tableau de Bord - Etat de saisie dans le système</h1><table border="1" align="center" class="std">
      <tr align="center">
        <th>ID</th>
        <th>Nom et prenom</th>
        <th>Structures</th>
        <th>Nombre d'enregistrements saisies</th>
      </tr>
      <?php $counter=0; do  { $counter++; ?>
		<tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
          <td><a href="detail_users.php?recordID=<?php echo $row_rsUsers['user_id']; ?>"> <?php echo $counter; ?>&nbsp; </a></td>
          <td><?php echo strtoupper($row_rsUsers['user_name']). " " . ucfirst($row_rsUsers['user_lastname']); ?>&nbsp; </td>
          <td><?php echo $row_rsUsers['structure_lib']; ?>&nbsp; </td>
          <td align="center"><?php echo $row_rsUsers['compteur']; ?>&nbsp; </td>
        </tr>
        <?php } while ($row_rsUsers = mysql_fetch_assoc($rsUsers)); ?>
    </table>
    <br>
    <table border="0">
      <tr>
        <td><?php if ($pageNum_rsUsers > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, 0, $queryString_rsUsers); ?>">Premier</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_rsUsers > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, max(0, $pageNum_rsUsers - 1), $queryString_rsUsers); ?>">Précédent</a>
            <?php } // Show if not first page ?></td>
        <td><?php if ($pageNum_rsUsers < $totalPages_rsUsers) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, min($totalPages_rsUsers, $pageNum_rsUsers + 1), $queryString_rsUsers); ?>">Suivant</a>
            <?php } // Show if not last page ?></td>
        <td><?php if ($pageNum_rsUsers < $totalPages_rsUsers) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsUsers=%d%s", $currentPage, $totalPages_rsUsers, $queryString_rsUsers); ?>">Dernier</a>
            <?php } // Show if not last page ?></td>
      </tr>
    </table>
Enregistrements <?php echo ($startRow_rsUsers + 1) ?> à <?php echo min($startRow_rsUsers + $maxRows_rsUsers, $totalRows_rsUsers) ?> sur <?php echo $totalRows_rsUsers ?>
<!--/.bloc-->
  <!--<div class="bulle2 com_presse2">
		
		
		<p class="type"><em>&Eacute;conomie &amp; Finances  - 29 septembre 2010</em></p>

        <h4>Présentation du projet de loi de finances pour 2011</h4>
               
                  <p>        <a href="/actus/10/100929presentation-plf2011.html"><img src="/images/img/min/100929plf6.jpg" alt="essimi menye" width="180" height="120" border="0" / title="essimi menye"></a> essimi menye et François BAROIN,  ministre du Budget, des Comptes Publics et de la
        R&eacute;forme de l&rsquo;&Eacute;tat, ont présenté en Conseil des ministres et devant le Parlement le <acronym title="Projet de loi de finances" lang="FR">PLF</acronym>2011. 
        Le budget 2011 met en œuvre un effort sans précédent de maîtrise des dépenses. </p>
       <div class="clear-left"></div> <ul>
         
          <li><a href="/actus/10/100929presentation-plf2011.html" class="tous_les">Lire l'article et voir les documents</a></li>
        </ul>
                  </div> -->
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
mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsUsers);

mysql_free_result($rsCountPersonnes);

mysql_free_result($rsCountExpert);

mysql_free_result($rsCountCommissions);

mysql_free_result($rsGroupByLocalite);

mysql_free_result($rsGroupByNature);

mysql_free_result($rsGroupType);

mysql_free_result($rsCountPersonnel);

mysql_free_result($rsCountByStructure);

mysql_free_result($rsSessions);

mysql_free_result($rsSessionsAnnee);
?>
