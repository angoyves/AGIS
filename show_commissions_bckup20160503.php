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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsCommissions = 200;
$pageNum_rsCommissions = 0;
if (isset($_GET['pageNum_rsCommissions'])) {
  $pageNum_rsCommissions = $_GET['pageNum_rsCommissions'];
}
$startRow_rsCommissions = $pageNum_rsCommissions * $maxRows_rsCommissions;

if ((isset($_GET['comID'])) && ($_GET['comID'] != "") && (isset($_GET['typID'])) && ($_GET['typID'] != "")) {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib, membre_insert FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id  AND commissions.type_commission_id = type_commissions.type_commission_id  AND commissions.localite_id = localites.localite_id AND commissions.commission_parent is null AND commissions.type_commission_id = %s AND commissions.commission_id = %s ORDER BY commissions.dateCreation, commissions.type_commission_id, commissions.commission_lib ASC", GetSQLValueString($_GET['typID'], "int"), GetSQLValueString($_GET['comID'], "int"));
} elseif (isset($_GET['scomID']) && ($_GET['scomID'] != "") && (isset($_GET['comID'])) && ($_GET['comID'] != "")) {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib, membre_insert FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id  AND commissions.type_commission_id = type_commissions.type_commission_id  AND commissions.localite_id = localites.localite_id AND commissions.commission_id = %s ORDER BY commissions.dateCreation, commissions.type_commission_id, commissions.commission_lib ASC", GetSQLValueString($_GET['scomID'], "int"));
} elseif (isset($_GET['comID']) && $_GET['comID'] != "") {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib, membre_insert FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id  AND commissions.type_commission_id = type_commissions.type_commission_id  AND commissions.localite_id = localites.localite_id AND commissions.commission_parent is null AND commissions.commission_id = %s ORDER BY commissions.dateCreation, commissions.type_commission_id, commissions.commission_lib ASC", GetSQLValueString($_GET['comID'], "int"));
} elseif (isset($_GET['typID']) && $_GET['typID'] != "") {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib, membre_insert FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id  AND commissions.type_commission_id = type_commissions.type_commission_id  AND commissions.localite_id = localites.localite_id AND commissions.commission_parent is null AND commissions.type_commission_id = %s ORDER BY commissions.dateCreation, commissions.type_commission_id, commissions.commission_lib ASC", GetSQLValueString($_GET['typID'], "int"));
} else {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = "SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib, membre_insert FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id  AND commissions.type_commission_id = type_commissions.type_commission_id  AND commissions.localite_id = localites.localite_id AND commissions.commission_parent is null ORDER BY commissions.dateCreation, commissions.type_commission_id, commissions.commission_lib ASC";
}

$query_limit_rsCommissions = sprintf("%s LIMIT %d, %d", $query_rsCommissions, $startRow_rsCommissions, $maxRows_rsCommissions);
$rsCommissions = mysql_query($query_limit_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);

if (isset($_GET['totalRows_rsCommissions'])) {
  $totalRows_rsCommissions = $_GET['totalRows_rsCommissions'];
} else {
  $all_rsCommissions = mysql_query($query_rsCommissions);
  $totalRows_rsCommissions = mysql_num_rows($all_rsCommissions);
}
$totalPages_rsCommissions = ceil($totalRows_rsCommissions/$maxRows_rsCommissions)-1;
$pageNum_rsCommissions = 0;
if (isset($_GET['pageNum_rsCommissions'])) {
  $pageNum_rsCommissions = $_GET['pageNum_rsCommissions'];
}
$startRow_rsCommissions = $pageNum_rsCommissions * $maxRows_rsCommissions;



$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("
SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id, montant
FROM commissions, membres, fonctions, personnes
WHERE membres.commissions_commission_id = commissions.commission_id   
AND fonctions_fonction_id = fonctions.fonction_id   
AND personnes_personne_id = personne_id  
AND (membres.fonctions_fonction_id BETWEEN 1 AND 3 OR membres.fonctions_fonction_id = 141)
AND personnes.display = 1  
AND commissions_commission_id = %s 
ORDER BY fonctions_fonction_id", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres2 = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_prenom, fonction_lib, montant, personnes.structure_id
FROM commissions, membres, fonctions, personnes
WHERE membres.commissions_commission_id = commissions.commission_id AND fonctions_fonction_id = fonctions.fonction_id AND personnes_personne_id = personne_id AND membres.fonctions_fonction_id = 40 AND commissions_commission_id = %s AND personnes.display = 1 ORDER BY membres.fonctions_fonction_id", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres2 = mysql_query($query_rsMembres2, $MyFileConnect) or die(mysql_error());
$row_rsMembres2 = mysql_fetch_assoc($rsMembres2);
$totalRows_rsMembres2 = mysql_num_rows($rsMembres2);

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

$maxRows_rsCommissionLib = 100;
$pageNum_rsCommissionLib = 0;
if (isset($_GET['pageNum_rsCommissionLib'])) {
  $pageNum_rsCommissionLib = $_GET['pageNum_rsCommissionLib'];
}
$startRow_rsCommissionLib = $pageNum_rsCommissionLib * $maxRows_rsCommissionLib;

$colname_rsCommissionLib = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissionLib = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissionLib = sprintf("SELECT commission_id, commission_lib, membre_insert FROM commissions WHERE commission_parent = %s", GetSQLValueString($colname_rsCommissionLib, "int"));
$query_limit_rsCommissionLib = sprintf("%s LIMIT %d, %d", $query_rsCommissionLib, $startRow_rsCommissionLib, $maxRows_rsCommissionLib);
$rsCommissionLib = mysql_query($query_limit_rsCommissionLib, $MyFileConnect) or die(mysql_error());
$row_rsCommissionLib = mysql_fetch_assoc($rsCommissionLib);

if (isset($_GET['totalRows_rsCommissionLib'])) {
  $totalRows_rsCommissionLib = $_GET['totalRows_rsCommissionLib'];
} else {
  $all_rsCommissionLib = mysql_query($query_rsCommissionLib);
  $totalRows_rsCommissionLib = mysql_num_rows($all_rsCommissionLib);
}
$totalPages_rsCommissionLib = ceil($totalRows_rsCommissionLib/$maxRows_rsCommissionLib)-1;

$queryString_rsCommissions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsCommissions") == false && 
        stristr($param, "totalRows_rsCommissions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsCommissions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsCommissions = sprintf("&totalRows_rsCommissions=%d%s", $totalRows_rsCommissions, $queryString_rsCommissions);

$queryString_rsCommissionLib = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsCommissionLib") == false && 
        stristr($param, "totalRows_rsCommissionLib") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsCommissionLib = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsCommissionLib = sprintf("&totalRows_rsCommissionLib=%d%s", $totalRows_rsCommissionLib, $queryString_rsCommissionLib);
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
<meta name="description" content="Le minist?re des Marches Publics du Cameroun">
<meta name="keywords" content="Abba SADOU, ministre, Marches, Minist?re des Marches Publics, ">
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
        window.location="<?php echo $logoutInactiv ?>"; //page de d?connexion
    }
	function window_onbeforeunload()
    {
       window.navigate('<?php echo $logoutInactiv ?>'); 
       //ne pas oublier de pr?ciser le chemin si vous mettez la page dans un autre r?pertoire
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
      <div id="logo"><img src="images/img/v2/amoirie.gif" alt="armoirie" width="78" height="52"><a href="#" target="_top"><img src="images/img/v2/flag.gif" alt="Minist?re des Finances et du Budget" width="89" border="0" height="52" hspace="2"></a>
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
    	    <td><a href="<?php echo $logoutAction ?>"><strong>Se d?connecter</strong></a></td>
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
				Hausse du ch?mage au 4e trimestre 2008 &agrave; 7,8  %</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>26 f&eacute;vrier 2009</span><br>
				L'?tat accompagne la naissance du 2e groupe bancaire fran?ais</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<a href="#" class="rss">Flux RSS</a>
				<div class="clear-both"></div>
			</div-->
      <!--div class="bloc">
				<a href="http://www.entreprises.gouv.fr/jeunesactifs/index.html" target="_blank"><img src="http://www.entreprises.gouv.fr/jeunesactifs/repository/images/embauche_250x250.gif" alt="Mesures jeunes actifs" width="180" height="180" border="0" title="Mesures jeunes actifs"></a>
			</div>
			<div class="bloc">
				<a href="http://www.nosentreprisesnosemplois.gouv.fr/" title="www.nosentreprisesnosemplois.gouv.fr nouvelle fen?tre" target="_blank"><img src="/images/actu/agir-entreprises180X150.gif" alt="Agir pour nos entreprises, c'est agir pour l'emploi" width="180" height="150" border="0" title="Agir pour nos entreprises, c'est agir pour l'emploi"></a>
			</div>
			<div class="bloc">
				<a href="http://www.prix-carburants.gouv.fr" title="Le site des prix des carburant nouvelle fen?tre" target="_blank"><img src="/images/actu/prixcarburants.jpg" alt="Le site des prix des carburant" width="180" height="130" border="0" title="Le site des prix des carburant"></a>
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
      <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand?mie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand?mie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pand?mie grippale" width="180" height="150" border="0"></a> </div> -->

	 
	  <!--/#tag_cloud-->

    </div>
    <!-- InstanceBeginEditable name="EditBoby" -->
<h1>Listes des Commissions</h1>
<table border="0">
  <tr>
    <th scope="row">Afficher les  :</th>
    <td><form name="form" id="form">
      <select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
        <option value="#">Seclect:::</option>
        <option value="<?php echo "show_commissions.php?typID=1&menuID=" . $_GET['menuID'] . "&action=" . $_GET['action'] ?>">Commission centrale</option>
        <option value="<?php echo "show_commissions.php?typID=2&menuID=" . $_GET['menuID'] . "&action=" . $_GET['action'] ?>">Commission minist?rielle</option>
        <option value="<?php echo "show_commissions.php?typID=3&menuID=" . $_GET['menuID'] . "&action=" . $_GET['action'] ?>">Commission r?gionale</option>
        <option value="<?php echo "show_commissions.php?typID=4&menuID=" . $_GET['menuID'] . "&action=" . $_GET['action'] ?>">Commission d?partementale</option>
        <option value="<?php echo "show_commissions.php?typID=5&menuID=" . $_GET['menuID'] . "&action=" . $_GET['action'] ?>">Commission communale</option>
      </select>
      <img src="images/img/b_search.png" width="16" height="16" />
    </form></td>
  </tr>
  <tr>
    <td scope="row">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<table>
  <tr>
    <td>
	  <?php $link = "etat_financier_recap.php?menuID=" .  $_GET['menuID']. "&action=" . $_GET['action'] . "&menuID=" . $_GET['menuID'] ?>
      <?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=". $_GET['menuID'] ?>
      <?php $link7 = "new_membre.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=". $_GET['menuID'] ?>
      <a href="new_commissions.php?menuID=3&action=new"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Cr&eacute;er une commission</a>&nbsp;<a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;&amp;mapid=personne_id&amp;action=add_com&amp;page=rep"></a>&nbsp;<a href="etat_financier_recap.php?menuID=<?php echo $_GET['menuID'];?>&amp;action=<?php echo $_GET['action'];?>&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>"><img src="images/img/b_print.png" width="16" height="16" />Imprimer recepitulatif</a>&nbsp; <!--<a href="display_commission.php">Charger les commissions d?partementales</a>-->&nbsp; <!--<a href="comissions_regionales.php">Charger les commissions r&eacute;gionales</a>-->&nbsp; <!--<a href="display_commission.php">Charger les commissions d?partementales</a>-->&nbsp; <!--<a href="comissions_regionales.php">Charger les commissions r&eacute;gionales</a>-->&nbsp; <!--<a href="commissions_ministerielles.php">Charger les commissions ministerielles</a>--></td>
  </tr>
</table>
<table border="1" align="center" class="std">
  <form id="form1" name="form1" method="post" action="">
<?php if (isset($_GET['action']) && $_GET['action'] == 'search') {?>
  <tr>
    <th nowrap="nowrap">&nbsp;</th>
    <th nowrap="nowrap"><input name="txtSearch1" type="text" id="txtSearch1" size="32" /></th>
    <th nowrap="nowrap"><input name="txtSearch2" type="text" id="txtSearch2" size="32" /></th>
    <th nowrap="nowrap"><input name="txtSearch3" type="text" id="txtSearch3" size="32" /></th>
    <th colspan="2" nowrap="nowrap"><input type="submit" name="button" id="button" value="Rechercher" /></th>
    <input type="hidden" name="MM_insert" value="form1" />
    <?php if (isset($_SESSION['MM_UserGroupID']) && $_GET['menuID'] == '1') {?>&amp;
    <th nowrap="nowrap">&nbsp;</th>
<?php } ?>
<?php if (isset($_GET['action']) && $_GET['action'] == 'upd') {?>
    <th nowrap="nowrap">&nbsp;</th>
<?php } ?>
<?php if (isset($_GET['action']) && $_GET['action'] == 'show') {?>
    <?php } ?>
  </tr>
<?php } ?>
</form>
  <tr>
    <th nowrap="nowrap">ID</th>
    <th colspan="2" nowrap="nowrap"> Nom     </th>
    <th nowrap="nowrap">Localit&eacute;</th>
    <th nowrap="nowrap">Sessions</th>
    <th nowrap="nowrap">Sous commission</th>
    <?php if (isset($_SESSION['MM_UserGroupID']) && $_GET['menuID'] == '1') {?>
    <th nowrap="nowrap">Delete</th>
<?php } ?>
<?php if (isset($_GET['action']) && $_GET['action'] == 'upd') {?>
    <th nowrap="nowrap">Update</th>
<?php } ?>
    </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td nowrap="nowrap" ><a href="show_commissions.php?menuID=<?php echo $_GET['menuID'];?>&amp;action=<?php echo $_GET['action'];?>&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>&amp;typID=<?php echo $_GET['typID']; ?>"> <img src="images/img/b_views.png" width="16" height="16" /></a></td>
      <td colspan="2" nowrap="nowrap"><?php echo $row_rsCommissions['commission_lib'] ?>&nbsp;
        <?php $commissionName = $row_rsCommissions['commission_lib']; ?>        &nbsp;</td>
      <td nowrap="nowrap"><?php echo strtoupper($row_rsCommissions['localite_lib']); ?>&nbsp;
        <?php 
	  
	$colname_RsShowSession = "-1";
	if (isset($row_rsCommissions['commission_id'])) {
	  $colname_RsShowSession = $row_rsCommissions['commission_id'];
	}
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$query_RsShowSession = sprintf("SELECT distinct(membres_commissions_commission_id) FROM sessions WHERE membres_commissions_commission_id = %s", GetSQLValueString($colname_RsShowSession, "text"));
	$RsShowSession = mysql_query($query_RsShowSession, $MyFileConnect) or die(mysql_error());
	$row_RsShowSession = mysql_fetch_assoc($RsShowSession);
	$totalRows_RsShowSession = mysql_num_rows($RsShowSession); ?>
        <?php 
		$colname_RsNombresSession = "-1";
if (isset($row_rsCommissions['commission_id'])) {
  $colname_RsNombresSession = $row_rsCommissions['commission_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_RsNombresSession = sprintf("SELECT count(commissions_commission_id) as Nombre FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id    AND fonctions_fonction_id = fonctions.fonction_id    AND personnes_personne_id = personne_id   AND membres.fonctions_fonction_id BETWEEN 1 AND 3  AND personnes.display = 1   AND commissions_commission_id = %s  AND personnes.add_commission = 2", GetSQLValueString($colname_RsNombresSession, "int"));
$RsNombresSession = mysql_query($query_RsNombresSession, $MyFileConnect) or die(mysql_error());
$row_RsNombresSession = mysql_fetch_assoc($RsNombresSession);
$totalRows_RsNombresSession = mysql_num_rows($RsNombresSession);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);
	   ?></td>
      <td align="center" nowrap="nowrap">
        <?php if (empty($_GET['scomID'])) { ?>
		<?php if (isset($row_RsNombresSession['Nombre']) && $row_RsNombresSession['Nombre'] > 0) { ?>
       <!-- <a href="sample16.php?&amp;comID=<?php //echo $row_rsCommissions['commission_id']; ?>&amp;menuID=<?php //echo $_GET['menuID']; ?>&amp;valid=mb"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/><span class="succes">Ajouter une session</span></a>--><span class="succes"><a href="add_membre.php?comID=<?php echo $row_rsCommissions['commission_id']; ?>&amp;menuID=<?php echo $_GET['menuID']; ?>&amp;valid=mo">Commission OK<img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></span>
        <?php } else { ?>
        <a href="new_membres.php?menuID=<?php echo $_GET['menuID']; ?>&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>&amp;action=new" class="control"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter des membres</a>
      <?php } ?>
      <?php } else { ?>
			<?php if (isset($row_rsCommissionLib['membre_insert']) && $row_rsCommissionLib['membre_insert'] == '1') { ?>
                      <a href="valid_sessions.php?&amp;comID=<?php echo $row_rsCommissionLib['commission_id']; ?>&amp;menuID=<?php echo $_GET['menuID']; ?>&amp;valid=mb" class="succes"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une session</a>
            <?php } else { ?>
                      <a href="new_membres2.php?menuID=<?php echo $_GET['menuID']; ?>&amp;comID=<?php echo $row_rsCommissionLib['commission_id']; ?>&amp;action=new" class="control"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter les membres</a>
			<?php } ?>
      <?php } ?>
</td>
      <td align="center" nowrap="nowrap">
      <?php if (empty($_GET['scomID'])) { ?>
   	  <?php $sousCommissionID = MinmapDB::getInstance()->get_value_id_by_value(commission_id, commissions, commission_parent, $row_rsCommissions['commission_id']);?>
      <?php if ($sousCommissionID){ ?>
      <a href="sous_commission_db.php?menuID=<?php echo $_GET['menuID']; ?>&comID=<?php echo $row_rsCommissions['commission_id']; ?>&action=new&typID=<?php echo $_GET['typID']; ?>" class="succes" onClick="return confirm('Etes vous sur de vouloir cr?er une nouvelle Sous-commission ?');"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une sous commission</a>
      <?php echo "(" . $countID = MinmapDB::getInstance()->get_count_sous_commission_by_commission_id($row_rsCommissions['commission_id']) . ")" ?>
      <?php } else { ?>
      <a href="sous_commission_db.php?menuID=<?php echo $_GET['menuID']; ?>&comID=<?php echo $row_rsCommissions['commission_id']; ?>&action=new&typID=<?php echo $_GET['typID']; ?>" class="control" onClick="return confirm('Etes vous sur de vouloir cr?er une nouvelle Sous-commission ?');"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une sous commission</a>
      <?php echo "(" . $countID = MinmapDB::getInstance()->get_count_sous_commission_by_commission_id($row_rsCommissions['commission_id']) . " )" ?>
      <?php } ?>
      <?php } ?>
      </td>

      <?php if (isset($_SESSION['MM_UserGroupID']) && $_SESSION['MM_UserGroupID'] == 1) {?>
      <td nowrap="nowrap"><a href="delete.php?menuID=<?php echo $_GET['menuID']; ?>&recordID=<?php echo $row_rsMbre_Par_Commission['commission_id']; ?>&amp;&amp;map=commissions"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a><a href="del_commissions.php?menuID=<?php echo $_GET['menuID'];?>&amp;&amp;action=<?php echo $_GET['action'];?>&amp;&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>"></a></td>
      <?php } ?>
	  <?php if (isset($_SESSION['MM_UserGroupID']) && $_SESSION['MM_UserGroupID'] == 1) {?>
      <td nowrap="nowrap"><a href="edit_commissions.php?menuID=<?php echo $_GET['menuID']; ?>&comID=<?php echo $row_rsCommissions['commission_id']; ?>&amp;map=commissions"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a><a href="maj_commissions.php?menuID=<?php echo $_GET['menuID'];?>&amp;&amp;action=<?php echo $_GET['action'];?>&amp;&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>"></a></td>
<?php } ?>
      </tr>
    <?php } while ($row_rsCommissions = mysql_fetch_assoc($rsCommissions)); ?>
</table>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, 0, $queryString_rsCommissions); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsCommissions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, max(0, $pageNum_rsCommissions - 1), $queryString_rsCommissions); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsCommissions < $totalPages_rsCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, min($totalPages_rsCommissions, $pageNum_rsCommissions + 1), $queryString_rsCommissions); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsCommissions < $totalPages_rsCommissions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsCommissions=%d%s", $currentPage, $totalPages_rsCommissions, $queryString_rsCommissions); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<p>Records <?php echo ($startRow_rsCommissions + 1) ?> to <?php echo min($startRow_rsCommissions + $maxRows_rsCommissions, $totalRows_rsCommissions) ?> of <?php echo $totalRows_rsCommissions ?></p>
<?php if ($totalRows_rsMembres > 0) { // Show if recordset not empty ?>

    <?php if (isset($_GET['comID'])) {?>
     <h1>Membres  <?php echo $commissionName; ?> </h1>
<table>
  <tr>
    <td><?php $link = "etat_financier_recap.php?menuID=" .  $_GET['menuID']. "&action=" . $_GET['action'] . "&menuID=" . $_GET['menuID'] ?>
      <?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=" . $_GET['menuID'] ?>
      <a href="etat_financier_recap.php?menuID=<?php echo $_GET['menuID'];?>&amp;action=<?php echo $_GET['action'];?>&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>"><img src="images/img/b_print.png" width="16" height="16" />Imprimer recepitulatif</a>&nbsp;<a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;&amp;mapid=personne_id&amp;action=add_com&amp;page=rep"></a>&nbsp;<a href="<?php echo $link6 ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter un membre</a></td>
  </tr>
</table>
  <table width="50%" border="1" align="center" class="std">
    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Prenom</th>
      <th>Fonction</th>
      <th>Montant</th>
      </tr>
    <?php $counter=0; do  { $counter++; ?>
      <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
        <td nowrap="nowrap"><a href="show_commissions.php?comID=<?php echo $row_rsCommissions['commission_id']; ?>"><img src="images/img/b_views.png" alt="" width="16" height="16" /></a></td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsMembres['personne_nom']); ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsMembres['personne_prenom']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsMembres['fonction_lib']); ?>&nbsp; </td>
        <td align="right" nowrap="nowrap">&nbsp;<?php echo number_format($row_rsMembres['montant'],0,' ',' '); ?></td>
        </tr>
      <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
  </table>
  <p>
    <?php echo $totalRows_rsMembres ?> Records Total </p>
  </p>
  <?php } ?>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsMembres2 > 0) { // Show if recordset not empty ?>
  <h1>
    <?php if (isset($_GET['comID'])) {?>
    Repr&eacute;sentants maitre d'ouvrage</h1>

<table>
  <tr>
    <td><?php $link = "etat_financier_recap.php?menuID=" .  $_GET['menuID']. "&action=" . $_GET['action'] . "&menuID=" . $_GET['menuID'] ?>
      <?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=" . $_GET['menuID'] ?>      <a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;&amp;mapid=personne_id&amp;action=add_com&amp;page=rep"></a>&nbsp; <a href="<?php echo $link6 ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter un representant</a><a href="etat_financier_recap.php?menuID=<?php echo $_GET['menuID'];?>&amp;action=<?php echo $_GET['action'];?>&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>"><img src="images/img/b_print.png" width="16" height="16" />Imprimer recepitulatif</a>&nbsp;</td>
  </tr>
</table>
  <table width="50%" border="1" align="center" class="std">
    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Prenom</th>
      <th>Fonction</th>
      <th>Structures</th>
      <th>Montant</th>
      <th>&nbsp;</th>
    </tr>
    <?php $counter=0; do  { $counter++; ?>
      <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
        <td nowrap="nowrap"><a href="show_commissions.php?comID=<?php echo $row_rsCommissions['commission_id']; ?>"><img src="images/img/b_views.png" alt="" width="16" height="16" /></a></td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsMembres2['personne_nom']); ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo $row_rsMembres2['personne_prenom']; ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsMembres2['fonction_lib']); ?>&nbsp; </td>
        <td nowrap="nowrap"><?php echo MinmapDB::getInstance()->get_value_by_value_id($row_rsMembres2['structure_id']); ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsMembres2['montant'],0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap">
		<?php if (isset($row_RsNombresSession['Nombre']) && $row_RsNombresSession['Nombre'] > 0) { ?>
                <a href="valid_representant.php?&amp;comID=<?php echo $_GET['comID']; ?>&amp;menuID=<?php echo $_GET['menuID']; ?>&amp;valid=mo"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/><span class="succes">Ajouter une session</span></a>
                <?php } else { ?>
                <a href="new_membres.php?menuID=<?php echo $_GET['menuID']; ?>&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>&amp;action=new" class="control"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter des membres</a>
              <?php } ?>
        &nbsp;</td>
      </tr>
      <?php } while ($row_rsMembres2 = mysql_fetch_assoc($rsMembres2)); ?>
  </table>
  <p>
    <?php echo $totalRows_rsMembres2 ?> Records Total </p>
  </p>
  <?php } ?>
  <?php } // Show if recordset not empty ?>
<?php if ($totalRows_rsCommissionLib > 0) { // Show if recordset not empty ?>
  <?php if (isset($_GET['comID'])) {?>
  <h1>Sous Commission D?pendante de la <?php echo $commissionName; ?></h1>
<table>
  <tr>
    <td><?php $link = "etat_financier_recap.php?menuID=" .  $_GET['menuID']. "&action=" . $_GET['action'] . "&menuID=" . $_GET['menuID'] ?>
      <?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=" . $_GET['menuID'] ?>
      <a href="etat_financier_recap.php?menuID=<?php echo $_GET['menuID'];?>&amp;action=<?php echo $_GET['action'];?>&amp;comID=<?php echo $row_rsCommissions['commission_id']; ?>"><img src="images/img/b_print.png" width="16" height="16" />Imprimer recepitulatif</a>&nbsp; <a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;&amp;mapid=personne_id&amp;action=add_com&amp;page=rep"></a>&nbsp;</td>
  </tr>
</table>
  <table width="50%" border="1" align="center" class="std">
    <tr>
      <th>ID</th>
      <th>Libelle sous commission</th>
      <th>Etat (Mois)</th>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      </tr>
    <?php $counter=0; do  { $counter++;?>
      <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
        <td nowrap="nowrap"><a href="show_commissions.php?scomID=<?php echo $row_rsCommissionLib['commission_id']; ?>&comID=<?php echo $row_rsCommissionLib['commission_id']; ?>"><img src="images/img/b_views.png" alt="" width="16" height="16" /></a></td>
        <td nowrap="nowrap"><?php echo $row_rsCommissionLib['commission_lib']; ?>&nbsp;</td>
        <td nowrap="nowrap"><?php 
		$countID = MinmapDB::getInstance()->get_count_session_id_by_commission_id($row_rsCommissionLib['commission_id']);
		if(isset($countID)){
				echo 'Session Existante ('.$countID.')' ;
			   }
		elseif (isset($row_rsCommissionLib['membre_insert']) && $row_rsCommissionLib['membre_insert'] == '1') { ?>
          <a href="valid_sessions.php?&amp;comID=<?php echo $row_rsCommissionLib['commission_id']; ?>&amp;menuID=<?php echo $_GET['menuID']; ?>&amp;valid=mb" class="succes"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une session</a>
          <?php echo "(" . $countID . " )" ?>
<?php } else { ?>
          <a href="new_membres2.php?menuID=<?php echo $_GET['menuID']; ?>&amp;comID=<?php echo $row_rsCommissionLib['commission_id']; ?>&amp;action=new" class="control"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter les membres</a>
          <?php } ?>
          &nbsp;</td>
        <td nowrap="nowrap"><a href="del_commissions.php?menuID=<?php echo $_GET['menuID'];?>&amp;&amp;action=<?php echo $_GET['action'];?>&amp;comID=<?php echo $_GET['comID']; ?>&amp;scomID=<?php echo $row_rsCommissionLib['commission_id']; ?>" onClick="return confirm('Etes vous sur de vouloir supprimer cette Sous-commission ?');"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a>&nbsp;</td>
        <td nowrap="nowrap"><a href="maj_commissions.php?menuID=<?php echo $_GET['menuID'];?>&amp;&amp;action=<?php echo $_GET['action'];?>&amp;&amp;comID=<?php echo $row_rsCommissionLib['commission_id']; ?>"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a>&nbsp;</td>
        </tr>
      <?php } while ($row_rsCommissionLib = mysql_fetch_assoc($rsCommissionLib)); ?>
  </table>
  <table border="0">
    <tr>
      <td><?php if ($pageNum_rsCommissionLib > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsCommissionLib=%d%s", $currentPage, 0, $queryString_rsCommissionLib); ?>">First</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsCommissionLib > 0) { // Show if not first page ?>
          <a href="<?php printf("%s?pageNum_rsCommissionLib=%d%s", $currentPage, max(0, $pageNum_rsCommissionLib - 1), $queryString_rsCommissionLib); ?>">Previous</a>
          <?php } // Show if not first page ?></td>
      <td><?php if ($pageNum_rsCommissionLib < $totalPages_rsCommissionLib) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsCommissionLib=%d%s", $currentPage, min($totalPages_rsCommissionLib, $pageNum_rsCommissionLib + 1), $queryString_rsCommissionLib); ?>">Next</a>
          <?php } // Show if not last page ?></td>
      <td><?php if ($pageNum_rsCommissionLib < $totalPages_rsCommissionLib) { // Show if not last page ?>
          <a href="<?php printf("%s?pageNum_rsCommissionLib=%d%s", $currentPage, $totalPages_rsCommissionLib, $queryString_rsCommissionLib); ?>">Last</a>
          <?php } // Show if not last page ?></td>
    </tr>
  </table>
  Records <?php echo ($startRow_rsCommissionLib + 1) ?> to <?php echo min($startRow_rsCommissionLib + $maxRows_rsCommissionLib, $totalRows_rsCommissionLib) ?> of <?php echo $totalRows_rsCommissionLib ?>
  <?php } ?>
  <?php } // Show if recordset not empty ?>
<p>&nbsp; </p>
    <!-- InstanceEndEditable -->
    <!--div class="bloc express">
				<h4>Informations express</h4>
				<p><span>9 mars 2009</span><br>
				Observatoire des prix et des marges : mise en place d'outils op&eacute;rationnels de suivi des marges</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>5 mars 2009</span><br>
				Hausse du ch?mage au 4e trimestre 2008 &agrave; 7,8  %</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>26 f&eacute;vrier 2009</span><br>
				L'?tat accompagne la naissance du 2e groupe bancaire fran?ais</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<a href="#" class="rss">Flux RSS</a>
				<div class="clear-both"></div>
			</div-->
    <!--div class="bloc">
				<a href="http://www.entreprises.gouv.fr/jeunesactifs/index.html" target="_blank"><img src="http://www.entreprises.gouv.fr/jeunesactifs/repository/images/embauche_250x250.gif" alt="Mesures jeunes actifs" width="180" height="180" border="0" title="Mesures jeunes actifs"></a>
			</div>
			<div class="bloc">
				<a href="http://www.nosentreprisesnosemplois.gouv.fr/" title="www.nosentreprisesnosemplois.gouv.fr nouvelle fen?tre" target="_blank"><img src="/images/actu/agir-entreprises180X150.gif" alt="Agir pour nos entreprises, c'est agir pour l'emploi" width="180" height="150" border="0" title="Agir pour nos entreprises, c'est agir pour l'emploi"></a>
			</div>
			<div class="bloc">
				<a href="http://www.prix-carburants.gouv.fr" title="Le site des prix des carburant nouvelle fen?tre" target="_blank"><img src="/images/actu/prixcarburants.jpg" alt="Le site des prix des carburant" width="180" height="130" border="0" title="Le site des prix des carburant"></a>
			</div-->
    <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand?mie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand?mie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pand?mie grippale" width="180" height="150" border="0"></a> </div> -->

	 
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

mysql_free_result($rsMembres2);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsCommissionLib);

mysql_free_result($RsNombresSession);

mysql_free_result($RsNombresSession2);

mysql_free_result($rsTypeCommission);

mysql_free_result($RsShowSession);
?>
