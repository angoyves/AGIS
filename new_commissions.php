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

$typecommissionIsEmpty = false;
$natureIsEmpty = false;
$localiteIsEmpty = false;

if (isset($_POST['type_commission_id']) && $_POST['type_commission_id'] == ""){
$typecommissionIsEmpty = true; }
if (isset($_POST['nature_id']) && $_POST['nature_id'] == ""){
$natureIsEmpty = true; }
if (isset($_POST['localite_id']) && $_POST['localite_id'] == ""){
$localiteIsEmpty = true; }


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
$DATE = date('Y-m-d H:i:s');
$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && !$typecommissionIsEmpty && !$natureIsEmpty && !$localiteIsEmpty ) {

  $countID = MinmapDB::getInstance()->get_count_id_by_localite_id($_POST['localite_id'], $_POST['type_commission_id'], $_POST['nature_id']);
  $localite_lib = MinmapDB::getInstance()->get_localite_lib_by_localite_id($_POST['localite_id']);
  $type_commission_lib = MinmapDB::getInstance()->get_type_commission_lib_by_commission_id($_POST['type_commission_id']);
  $nature_lib = MinmapDB::getInstance()->get_nature_lib_by_nature_id($_POST['nature_id']);
  $Number = $countID + 1;
  if ((isset($_GET['typID'])) && ($_GET['typID']) == 3) {
  $champ = 'code_structure';
  $structure_lib = MinmapDB::getInstance()->get_structure_lib_by_structure_id($champ, $_POST['structure_id']);
  $commission_lib =  ucfirst($type_commission_lib) . " de passation des marchés du (de) " . $structure_lib;
  } elseif ((isset($_GET['typID'])) && ($_GET['typID']) == 1) {
  $structure_lib = MinmapDB::getInstance()->get_structure_lib_by_structure_id('structure_lib', $_POST['structure_id']);
  $commission_lib =  ucfirst($type_commission_lib) . " de " . strtolower($nature_lib);
  } elseif ((isset($_GET['typID'])) && ($_GET['typID']) == 4) {
  $commission_lib =  ucfirst($type_commission_lib) . " de " . strtolower($nature_lib) . " de " . $localite_lib;
  } elseif ((isset($_GET['typID'])) && ($_GET['typID']) == 2) {
  $structure_lib = MinmapDB::getInstance()->get_structure_lib_by_structure_id('structure_lib', $_POST['structure_id']);
  $commission_lib =  ucfirst($type_commission_lib) . " de " . strtolower($nature_lib) . " du " . $structure_lib; 
  } elseif ((isset($_GET['typID'])) && ($_GET['typID']) == 3) {
  $structure_lib = MinmapDB::getInstance()->get_structure_lib_by_structure_id('structure_lib', $_POST['structure_id']);
  $commission_lib =  ucfirst($type_commission_lib) . " de " . strtolower($nature_lib) . " de " . $localite_lib . "_" . $Number; 
  }

$insertSQL = sprintf("INSERT INTO commissions (commission_id, localite_id, type_commission_id, nature_id, commission_parent, commission_lib, dateCreation, structure_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString($_POST['type_commission_id'], "int"),
                       GetSQLValueString($_POST['nature_id'], "int"),
					   GetSQLValueString($_POST['commission_parent'], "int"),
					   GetSQLValueString($commission_lib, "text"),
					   GetSQLValueString($DATE, "date"),
					   GetSQLValueString($_POST['structure_id'], "int"));


  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());


 /* $insertGoTo = "new_commissions.php?valid=ok";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
   $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/
  echo "<script type='".text/javascript."'>
			if (confirm('Créer une nouvelle Commission???')) 
			{".
				  $insertGoTo = "new_commissions.php?valid=ok";
				  if (isset($_SERVER['QUERY_STRING'])) {
					$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
				   $insertGoTo .= $_SERVER['QUERY_STRING'];
				  }
				  header(sprintf("Location: %s", $insertGoTo)) ."
			else
			{".
				  $insertGoTo = "new_commissions.php?valid=ok";
				  if (isset($_SERVER['QUERY_STRING'])) {
					$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
				   $insertGoTo .= $_SERVER['QUERY_STRING'];
				  }
				  header(sprintf("Location: %s", $insertGoTo)) ."
			}
		</script>";
}

$colname_rsLocalisation = "-1";
if ((isset($_GET['typID'])) && ($_GET['typID']) == 3){
  $colname_rsLocalisation = 1;	
	}
if ((isset($_GET['typID'])) && ($_GET['typID']) == 4){
  $colname_rsLocalisation = 2;	
	}
if ((isset($_GET['typID'])) && ($_GET['typID']) == 5){
  $colname_rsLocalisation = 3;	
	}
/*$colname_rsLocalisation = "-1";
if (isset($_GET['type_localite_value'])) {
  $colname_rsLocalisation = $_GET['type_localite_value'];
}*/
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocalisation = sprintf("SELECT localite_id, localite_lib, type_localite FROM localites WHERE type_localite = %s AND display = '1' ORDER BY localite_lib ASC", GetSQLValueString($colname_rsLocalisation, "int"));
$rsLocalisation = mysql_query($query_rsLocalisation, $MyFileConnect) or die(mysql_error());
$row_rsLocalisation = mysql_fetch_assoc($rsLocalisation);
$totalRows_rsLocalisation = mysql_num_rows($rsLocalisation);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsNature = "SELECT nature_id, lib_nature FROM natures WHERE display = '1'";
$rsNature = mysql_query($query_rsNature, $MyFileConnect) or die(mysql_error());
$row_rsNature = mysql_fetch_assoc($rsNature);
$totalRows_rsNature = mysql_num_rows($rsNature);

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
if (isset($_GET['comID']) && $_GET['comID'] != "") {
$query_rsCommisions = sprintf("SELECT * FROM commissions WHERE commission_id = %s",GetSQLValueString($_GET['comID'], "int"));
} else {
$query_rsCommisions = "SELECT * FROM commissions WHERE commission_parent is null";	
	}
$rsCommisions = mysql_query($query_rsCommisions, $MyFileConnect) or die(mysql_error());
$row_rsCommisions = mysql_fetch_assoc($rsCommisions);
$totalRows_rsCommisions = mysql_num_rows($rsCommisions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructure = "SELECT structure_id, code_structure, structure_lib FROM structures WHERE type_structure_id = 4 AND display = 1 AND minister = 1 ORDER BY structure_lib";
$rsStructure = mysql_query($query_rsStructure, $MyFileConnect) or die(mysql_error());
$row_rsStructure = mysql_fetch_assoc($rsStructure);
$totalRows_rsStructure = mysql_num_rows($rsStructure);

$queryString_rsComissionInsert = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsComissionInsert") == false && 
        stristr($param, "totalRows_rsComissionInsert") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsComissionInsert = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsComissionInsert = sprintf("&totalRows_rsComissionInsert=%d%s", $totalRows_rsComissionInsert, $queryString_rsComissionInsert);
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
      <table border="0" align="center">
        <tr>
          <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
            <h1>Insertion des Commissions</h1>
            <table align="center" class="std2">
              <tr valign="baseline">
                <th align="right" valign="middle" nowrap="nowrap">Type commission <span class="error">* </span>:</th>
                <td>
                <select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
                  <option value="">Selectionner...</option>
                  <?php
do {  
?>
                  <option value="new_commissions.php?typID=<?php echo $row_rsTypeCommission['type_commission_id']; ?>&menuID=<?php echo $_GET['menuID'] ?>" <?php if (!(strcmp($row_rsTypeCommission['type_commission_id'], $_GET['typID']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rsTypeCommission['type_commission_lib']);?></option>
                  <?php
} while ($row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission));
  $rows = mysql_num_rows($rsTypeCommission);
  if($rows > 0) {
      mysql_data_seek($rsTypeCommission, 0);
	  $row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
  }
?>
                </select>
                  <a href="#" onclick="<?php popup("new_type_commissions.php", "450", "300"); ?>"> <img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>        
			      <input type="hidden" name="type_commission_id" id="type_commission_id" value="<?php echo $_GET['typID'] ?>"/>
			      <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($typecommissionIsEmpty) { ?>
			<div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selectionner le type de la commission, SVP!</div>
        	<?php } ?>
            <?php if ((isset($_GET['typID']) && $_GET['typID'] == "2")) { ?>
            <input type="hidden" name="localite_id" id="localite_id" value="1" />
            <?php } ?>
            <?php if ((isset($_GET['typID']) && $_GET['typID'] == "2")) { ?>
            <input type="hidden" name="nature_id" id="nature_id" value="9" />
            <?php } ?>
        </td>
              </tr>
              <?php if (isset($_GET['typID']) && $_GET['typID'] != 2) { ?>
              <tr valign="baseline">
                <th align="right" valign="middle" nowrap="nowrap">Nature <span class="error">* </span>:</th>
                <td>
				<?php if (isset($_GET['typID']) && $_GET['typID'] != "2") { ?>
                <input type="hidden" name="localite_id" id="localite_id" value="1" />
                <input type="hidden" name="structure_id" id="structure_id" value="426" />
                <?php } ?>
                  <select name="nature_id">
                    <option value="">Choisir...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_rsNature['nature_id']?>" <?php if (!(strcmp($row_rsNature['nature_id'], $_GET['nature_id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities(ucfirst(strtolower($row_rsNature['lib_nature'])));?></option>
                    <?php
} while ($row_rsNature = mysql_fetch_assoc($rsNature));
  $rows = mysql_num_rows($rsNature);
  if($rows > 0) {
      mysql_data_seek($rsNature, 0);
	  $row_rsNature = mysql_fetch_assoc($rsNature);
  }
?>
                  </select>
                  <a href="#" onclick="<?php popup("new_natures.php", "450", "300"); ?>">
                  <img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
			<?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($natureIsEmpty) { ?>
        	<div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selectionner la nature , SVP!</div>
        	<?php } ?>
                  </td>
              </tr>
              <?php } ?>
              <?php if ((isset($_GET['typID'])) && ($_GET['typID'] == "3") || (isset($_GET['typID'])) && ($_GET['typID'] == "4") || (isset($_GET['typID'])) && ($_GET['typID'] == "5")) { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">Localité <span class="error">* </span>:</th>
    <td><select name="localite_id">
      <option value="">Seletionner...</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsLocalisation['localite_id']?>" <?php if (!(strcmp($row_rsLocalisation['localite_id'], $_POST['localite_id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rsLocalisation['localite_lib']);?></option>
      <?php
} while ($row_rsLocalisation = mysql_fetch_assoc($rsLocalisation));
  $rows = mysql_num_rows($rsLocalisation);
  if($rows > 0) {
      mysql_data_seek($rsLocalisation, 0);
	  $row_rsLocalisation = mysql_fetch_assoc($rsLocalisation);
  }
?>
    </select>
      <a href="#" onclick="<?php popup("new_localites.php", "450", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($localiteIsEmpty) { ?>
      <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selectionner la localite, SVP!</div>
      <?php } ?>
      </td>
  </tr>	  
  <?php  }// Show if recordset not empty ?>
<?php if (isset($_GET['typID']) && $_GET['typID'] == "2") { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">Ministère : </th>
    <td><select name="structure_id" id="structure_id">
      <option value="">Select:::</option>
      <?php do {  ?>
      <option value="<?php echo $row_rsStructure['structure_id']?>"><?php echo $row_rsStructure['code_structure'] . " - " . $row_rsStructure['structure_lib']?></option>
      <?php
		} while ($row_rsStructure = mysql_fetch_assoc($rsStructure));
		  $rows = mysql_num_rows($rsStructure);
		  if($rows > 0) {
			  mysql_data_seek($rsStructure, 0);
			  $row_rsStructure = mysql_fetch_assoc($rsStructure);
		  }
	?>
    </select></td>
  </tr>
  <?php } else { ?>
	<input type="hidden" name="structure_id" id="structure_id" value="588"/>  
  <?php  }// Show if recordset not empty ?>
  <?php if (isset($_GET['typID']) && $_GET['typID'] == "6") { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">Commission Parent  :</th>
    <td>
    <select name="commission_parent">
<?php if (empty($_GET['comID'])) { ?>
      <option value="">Choisir...</option>
<?php } ?>	
      <?php
do {  
?>
      <option value="<?php echo $row_rsCommisions['commission_id']?>" <?php if (!(strcmp($_GET['comID'], $_POST['commission_id']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst(strtolower($row_rsCommisions['commission_lib'])) ?></option>
      <?php
} while ($row_rsCommisions = mysql_fetch_assoc($rsCommisions));
  $rows = mysql_num_rows($rsCommisions);
  if($rows > 0) {
      mysql_data_seek($rsCommisions, 0);
	  $row_rsCommisions = mysql_fetch_assoc($rsCommisions);
  }
?>
    </select></td>
  </tr>
  <?php } // Show if recordset not empty ?>
<tr valign="baseline">
  <td nowrap="nowrap" align="right">&nbsp;</td>
                <td><input name="Submit" type="submit" value="Enregistrer" onClick="return confirm('Créer la commission ?')";/></td>
              </tr>
            </table>
            <input type="hidden" name="commission_id" value="" />
            <input type="hidden" name="MM_insert" value="form1" />
          </form>
          <?php if (isset($_GET['valid']) && $_GET['valid'] == 'ok'){ ?> <span class="succes">Commission insérée avec succès!!!</span><?php } ?></td>
        </tr>
      </table>
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
mysql_free_result($rsLocalite);

mysql_free_result($rsTypeCommissionLib);

mysql_free_result($rsNatureLib);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsCommisions);

mysql_free_result($rsStructure);

mysql_free_result($rsLocalisation);

mysql_free_result($rsTypeCommission);

mysql_free_result($rsNature);
?>
