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
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_POST['txtMonth1'])) && ($_POST['txtMonth1'] != "") && (isset($_POST['txtMonth2'])) && ($_POST['txtMonth2'] != "") && (isset($_POST['txtYear'])) && ($_POST['txtYear'] != "")) {
$colname_rstxtMonth1 = "-1";
if (isset($_POST['txtMonth1'])) {
  $colname_rstxtMonth1 = $_POST['txtMonth1'];
}
$colname_rstxtMonth2 = "-1";
if (isset($_POST['txtMonth2'])) {
  $colname_rstxtMonth2 = $_POST['txtMonth2'];
}
$colname_rstxtYear = "-1";
if (isset($_POST['txtYear'])) {
  $colname_rstxtYear = $_POST['txtYear'];
}
$query_rsMontantIndemnite = sprintf("SELECT sum(nombre_jour * montant) as total FROM sessions, membres WHERE commissions_commission_id = membres_commissions_commission_id AND membres_personnes_personne_id = personnes_personne_id AND mois between %s AND %s AND annee = %s AND membres_fonctions_fonction_id = 41",GetSQLValueString($colname_rstxtMonth1, "text"),GetSQLValueString($colname_rstxtMonth2, "text"), GetSQLValueString($colname_rstxtYear, "text")); 
} else {
$query_rsMontantIndemnite = "SELECT sum(nombre_jour * montant) as total FROM sessions, membres WHERE commissions_commission_id = membres_commissions_commission_id AND membres_personnes_personne_id = personnes_personne_id AND membres_fonctions_fonction_id = 41";
}

$rsMontantIndemnite = mysql_query($query_rsMontantIndemnite, $MyFileConnect) or die(mysql_error());
$row_rsMontantIndemnite = mysql_fetch_assoc($rsMontantIndemnite);
$totalRows_rsMontantIndemnite = mysql_num_rows($rsMontantIndemnite);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_POST['txtGroup'])) && ($_POST['txtGroup'] != "")) {
	
$colname_rstxtGroup = "-1";
if (isset($_POST['txtGroup'])) {
  $colname_rstxtGroup = $_POST['txtGroup'];
}
$query_rsSousGroupe = sprintf("SELECT * FROM sous_groupes WHERE groupe_id = %s",GetSQLValueString($colname_rstxtGroup, "int"));
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);
} else {
$query_rsSousGroupe = "SELECT * FROM sous_groupes WHERE groupe_id = 1";
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);
}

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
$query_rsYear = "SELECT * FROM annee";
$rsYear = mysql_query($query_rsYear, $MyFileConnect) or die(mysql_error());
$row_rsYear = mysql_fetch_assoc($rsYear);
$totalRows_rsYear = mysql_num_rows($rsYear);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMonth2 = "SELECT mois_id, lib_mois FROM mois";
$rsMonth2 = mysql_query($query_rsMonth2, $MyFileConnect) or die(mysql_error());
$row_rsMonth2 = mysql_fetch_assoc($rsMonth2);
$totalRows_rsMonth2 = mysql_num_rows($rsMonth2);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMonth1 = "SELECT mois_id, lib_mois FROM mois";
$rsMonth1 = mysql_query($query_rsMonth1, $MyFileConnect) or die(mysql_error());
$row_rsMonth1 = mysql_fetch_assoc($rsMonth1);
$totalRows_rsMonth1 = mysql_num_rows($rsMonth1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupeSelect = "SELECT groupe_id, groupe_lib FROM groupes WHERE display = '1'";
$rsGroupeSelect = mysql_query($query_rsGroupeSelect, $MyFileConnect) or die(mysql_error());
$row_rsGroupeSelect = mysql_fetch_assoc($rsGroupeSelect);
$totalRows_rsGroupeSelect = mysql_num_rows($rsGroupeSelect);
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
<h1>Affichage</h1>
<form id="form1" name="form1" method="post" action="">
  <table width="30%" border="0" class="std2">
    <tr>
      <th align="right">Periode allant de :</th>
      <td><select name="txtMonth1" id="txtMonth1">
        <option value="" <?php if (!(strcmp("", $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsMonth1['mois_id']?>"<?php if (!(strcmp($row_rsMonth1['mois_id'], $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMonth1['lib_mois']); ?></option>
        <?php
} while ($row_rsMonth1 = mysql_fetch_assoc($rsMonth1));
  $rows = mysql_num_rows($rsMonth1);
  if($rows > 0) {
      mysql_data_seek($rsMonth1, 0);
	  $row_rsMonth1 = mysql_fetch_assoc($rsMonth1);
  }
?>
      </select></td>
      <th>à</th>
      <td><select name="txtMonth2" id="txtMonth2">
        <option value="" <?php if (!(strcmp("", $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsMonth2['mois_id']?>"<?php if (!(strcmp($row_rsMonth2['mois_id'], $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMonth2['lib_mois']); ?></option>
        <?php
} while ($row_rsMonth2 = mysql_fetch_assoc($rsMonth2));
  $rows = mysql_num_rows($rsMonth2);
  if($rows > 0) {
      mysql_data_seek($rsMonth2, 0);
	  $row_rsMonth2 = mysql_fetch_assoc($rsMonth2);
  }
?>
      </select></td>
    </tr>
    <tr>
      <th align="right">Exercice :</th>
      <td><select name="txtYear" id="txtYear">
        <option value="" <?php if (!(strcmp("", $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsYear['lib_annee']?>"<?php if (!(strcmp($row_rsYear['lib_annee'], $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsYear['lib_annee']?></option>
        <?php
} while ($row_rsYear = mysql_fetch_assoc($rsYear));
  $rows = mysql_num_rows($rsYear);
  if($rows > 0) {
      mysql_data_seek($rsYear, 0);
	  $row_rsYear = mysql_fetch_assoc($rsYear);
  }
?>
      </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th align="right">Groupe :</th>
      <td><select name="txtGroup" id="txtGroup">
        <option value="" <?php if (!(strcmp("", $_POST['txtGroup']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsGroupeSelect['groupe_id']?>"<?php if (!(strcmp($row_rsGroupeSelect['groupe_id'], $_POST['txtGroup']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsGroupeSelect['groupe_lib']?></option>
        <?php
} while ($row_rsGroupeSelect = mysql_fetch_assoc($rsGroupeSelect));
  $rows = mysql_num_rows($rsGroupeSelect);
  if($rows > 0) {
      mysql_data_seek($rsGroupeSelect, 0);
	  $row_rsGroupeSelect = mysql_fetch_assoc($rsGroupeSelect);
  }
?>
      </select></td>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="Rechercher" /></td>
    </tr>
    <tr>
      <td colspan="4"><input type="hidden" name="MM_update" value="form1" />&nbsp;</td>
      </tr>
  </table>
</form>
<table>
  <tr>
    <td>
      <?php echo "Le montant total de l'indemnite de " .$_POST['txtMonth1']." à " .$_POST['txtMonth2']. " " .$_POST['txtYear']. " est de " . number_format($row_rsMontantIndemnite['total'],0,' ',' '). "F CFA "  ?>
	  <?php $link = "etat_paiement.php?aID=" . $_POST['txtYear']. "&mID1=" . $_POST['txtMonth1']. "&mID2=" . $_POST['txtMonth2'] . "&gID=" . $_POST['txtGroup']; ?>
      <?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=". $_GET['menuID'] ?>
      <?php $link7 = "new_membre.php?aID=" . $_POST['txtYear']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=". $_GET['menuID'] ?>
      <a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Imprimer</a>&nbsp; </td>
  </tr>
</table>

<table border="1" class="std" align="center">
<?php do { 

$colname_rsSousGroup = "-1";
if (isset($row_rsSousGroupe['sous_groupe_id'])) {
  $colname_rsSousGroup = $row_rsSousGroupe['sous_groupe_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGroupes = sprintf("SELECT count(personne_id) Nombres
FROM sous_groupes, personnes
WHERE personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND sous_groupes.sous_groupe_id = %s
AND personnes.type_personne_id BETWEEN 1 AND 2
AND personnes.display = 1
group by sous_groupes.sous_groupe_id",GetSQLValueString($colname_rsSousGroup, "int"));
$rsSousGroupes = mysql_query($query_rsSousGroupes, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupes = mysql_fetch_assoc($rsSousGroupes);
$totalRows_rsSousGroupes = mysql_num_rows($rsSousGroupes);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_POST['txtGroup'])) && ($_POST['txtGroup'] != "")) {
	
$colname_rstxtGroup = "-1";
if (isset($_POST['txtGroup'])) {
  $colname_rstxtGroup = $_POST['txtGroup'];
}

$query_rsPersonnesRib = sprintf("SELECT groupes.groupe_id, personnes.sous_groupe_id, personnes.personne_id, personne_nom, personne_grade, 
personnes.personne_matricule, personne_telephone, fonction_lib, structure_lib, sous_groupe_lib, sous_groupes.pourcentage as taux, banque_code,
agence_code, numero_compte, cle, sous_groupes.pourcentage as taux
FROM personnes, sous_groupes, groupes, fonctions, rib, structures
WHERE personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND personnes.personne_id = rib.personne_id
AND sous_groupes.groupe_id = groupes.groupe_id
AND personnes.fonction_id = fonctions.fonction_id
AND personnes.structure_id = structures.structure_id
AND personnes.type_personne_id BETWEEN 1 AND 2
AND sous_groupes.sous_groupe_id = %s
AND groupes.groupe_id = %s
AND personnes.display = 1
GROUP BY sous_groupes.sous_groupe_id, personne_nom, structure_lib, fonction_lib", GetSQLValueString($colname_rsSousGroup, "int"), GetSQLValueString($colname_rstxtGroup, "int"));
$rsPersonnesRib = mysql_query($query_rsPersonnesRib, $MyFileConnect) or die(mysql_error());
$row_rsPersonnesRib = mysql_fetch_assoc($rsPersonnesRib);
$totalRows_rsPersonnesRib = mysql_num_rows($rsPersonnesRib); 
} else {
$query_rsPersonnesRib = sprintf("SELECT groupes.groupe_id, personnes.sous_groupe_id, personnes.personne_id, personne_nom, personne_grade, 
personnes.personne_matricule, personne_telephone, fonction_lib, structure_lib, sous_groupe_lib, sous_groupes.pourcentage as taux, banque_code,
agence_code, numero_compte, cle, sous_groupes.pourcentage as taux
FROM personnes, sous_groupes, groupes, fonctions, rib, structures
WHERE personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND personnes.personne_id = rib.personne_id
AND sous_groupes.groupe_id = groupes.groupe_id
AND personnes.fonction_id = fonctions.fonction_id
AND personnes.structure_id = structures.structure_id
AND personnes.type_personne_id = 1
AND sous_groupes.groupe_id BETWEEN 1 AND 4
AND sous_groupes.sous_groupe_id = %s
AND personnes.type_personne_id = 1
AND personnes.display = 1
GROUP BY sous_groupes.sous_groupe_id, personne_nom, structure_lib, fonction_lib", GetSQLValueString($colname_rsPersonnesRib, "int"));
$rsPersonnesRib = mysql_query($query_rsPersonnesRib, $MyFileConnect) or die(mysql_error());
$row_rsPersonnesRib = mysql_fetch_assoc($rsPersonnesRib);
$totalRows_rsPersonnesRib = mysql_num_rows($rsPersonnesRib); 
} ?>
  <tr>
    <td colspan="12"><?php echo $row_rsSousGroupe['sous_groupe_lib'] ?>&nbsp;</td>
    </tr>
  <tr>
    <th nowrap="nowrap"><strong>ID</strong></th>
    <th nowrap="nowrap"><strong>Nom</strong></th>
    <th nowrap="nowrap"><strong>Matricules </strong></th>
    <th nowrap="nowrap"><strong>Grade</strong></th>
    <th nowrap="nowrap"><strong>Telephone</strong></th>
    <th nowrap="nowrap"><strong>Structure</strong></th>
    <th nowrap="nowrap"><strong>Fonction</strong></th>
    <th nowrap="nowrap"><strong>Banque</strong></th>
    <th nowrap="nowrap"><strong>Agence</strong></th>
    <th nowrap="nowrap"><strong>N&deg; de compte</strong></th>
    <th nowrap="nowrap"><strong>cle</strong></th>
    <th nowrap="nowrap"><strong>Montant</strong></th>	
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="todelete.php?recordID=<?php echo $row_rsPersonnesRib['personne_id']; ?>"> <?php echo $row_rsPersonnesRib['personne_id']; ?>&nbsp; </a></td>
      <td><?php echo htmlentities($row_rsPersonnesRib['personne_nom']." " . $row_rsPersonnesRib['personne_prenom']); ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['personne_matricule']; ?>&nbsp; </td>
      <td><?php //echo htmlentities($row_rsPersonnesRib['personne_grade']); ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['personne_telephone']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['structure_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['fonction_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['banque_code']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['agence_code']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['numero_compte']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnesRib['cle']; ?>&nbsp; </td>
      <td align="right"><?php echo number_format((($row_rsPersonnesRib['taux']*$row_rsMontantIndemnite['total'])/$row_rsSousGroupes['Nombres']),0,' ',' '); ?>&nbsp; </td>
      </tr>
    <?php } while ($row_rsPersonnesRib = mysql_fetch_assoc($rsPersonnesRib)); ?>
<tr>
    <td colspan="10" nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">Sous total</td>
    <td nowrap="nowrap"><strong><?php echo number_format($somme,0,' ',' '); ?></strong></td>
  </tr>
<br />
<?php //echo $totalRows_rsPersonnesRib ?> <!--Enregistrements Total-->
<?php } while ($row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe)); ?>
<br />
</table>

<?php //echo $totalRows_rsSousGroupe ?> <!--Enregistrements Total-->
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
mysql_free_result($rsSousGroupe);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsYear);

mysql_free_result($rsMonth2);

mysql_free_result($rsMonth1);

mysql_free_result($rsGroupeSelect);

mysql_free_result($rsPersonnesRib);
?>
