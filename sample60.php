<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>

<?php
/*if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,5,6";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False;

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings arrays. 
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

$MM_restrictGoTo = "acces_denied.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}*/
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

$colname_rsSessions = "-1";
if (isset($_POST['comID'])) {
  $colname_rsSessions = $_POST['comID'];
}
$colname_rsSessions1 = "-1";
if (isset($_POST['txtMonth1'])) {
  $colname_rsSessions1 = $_POST['txtMonth1'];
}
$colname_rsSessions3 = "-1";
if (isset($_POST['txtMonth2'])) {
  $colname_rsSessions3 = $_POST['txtMonth2'];
}
$colname_rsSessions2 = "-1";
if (isset($_POST['txtYear'])) {
  $colname_rsSessions2 = $_POST['txtYear'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte, cle
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND personnes.personne_id = rib.personne_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id BETWEEN 1 AND 3
AND membres_commissions_commission_id = %s 
AND annee = %s 
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_id
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_sCom = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, nombre_jour, annee, montant, sum(nombre_jour * montant) as total
FROM sessions, membres, commissions, personnes
WHERE sessions.membres_commissions_commission_id = membres.commissions_commission_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.commission_id = membres.commissions_commission_id
AND personnes.personne_id = membres.personnes_personne_id
AND personnes.display = '1'
AND commission_parent = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_id
ORDER BY personne_nom, annee, mois", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessions_sCom = mysql_query($query_rsSessions_sCom, $MyFileConnect) or die(mysql_error());
$row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom);
$totalRows_rsSessions_sCom = mysql_num_rows($rsSessions_sCom);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_rep = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte
FROM commissions, membres, personnes,  fonctions, sessions, rib
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND personnes.personne_id = rib.personne_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id = 40
AND membres_commissions_commission_id = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_nom 
ORDER BY personnes.personne_nom",  GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessions_rep = mysql_query($query_rsSessions_rep, $MyFileConnect) or die(mysql_error());
$row_rsSessions_rep = mysql_fetch_assoc(rsSessions_rep);
$totalRows_rsSessions_rep = mysql_num_rows($rsSessions_rep);



$colname_rsCountIndemnit2 = "-1";
if (isset($_POST['comID'])) {
  $colname_rsCountIndemnity = $_POST['comID'];
}
$colname_rsCountIndemnity1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsCountIndemnity1 = $_GET['mID'];
}
$colname_rsCountIndemnity2 = "-1";
if (isset($_POST['txtYear'])) {
  $colname_rsCountIndemnity2 = $_POST['txtYear'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountIndemnity = sprintf("SELECT (sum(nombre_jour) * montant) as total
FROM sessions, membres
WHERE `membres`.personnes_personne_id = `sessions`.`membres_personnes_personne_id`
AND membres_commissions_commission_id = %s 
AND mois between 1 AND 12 
AND annee = %s
AND sessions.membres_personnes_personne_id = %s ", GetSQLValueString($colname_rsCountIndemnity, "int"),GetSQLValueString($colname_rsCountIndemnity2, "text") , GetSQLValueString(2129, "int"));
$rsCountIndemnity = mysql_query($query_rsCountIndemnity, $MyFileConnect) or die(mysql_error());
$row_rsCountIndemnity = mysql_fetch_assoc($rsCountIndemnity);
$totalRows_rsCountIndemnity = mysql_num_rows($rsCountIndemnity);

$colname_rsJourMois = "-1";
if (isset($_GET['mID'])) {
  $colname_rsJourMois = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

$colname_rsCommissions = "-1";
if (isset($_POST['comID'])) {
  $colname_rsCommissions = $_POST['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname_rsMembres = "-1";
if (isset($_POST['comID'])) {
  $colname_rsMembres = $_POST['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id  AND fonctions_fonction_id = fonctions.fonction_id  AND personnes_personne_id = personne_id  AND commissions_commission_id = %s", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsSessionRepresentant = "-1";
if (isset($_POST['comID'])) {
  $colname_rsSessionRepresentant = $_POST['comID'];
}
$colname1_rsSessionRepresentant = "-1";
if (isset($_POST['txtYear'])) {
  $colname1_rsSessionRepresentant = $_POST['txtYear'];
}
$colname2_rsSessionRepresentant = "-1";
if (isset($_GET['mID'])) {
  $colname2_rsSessionRepresentant = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionRepresentant = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, personne_prenom, code_structure, sum(nombre_jour) as nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  sessions, structures 
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND  personnes.structure_id = structures.structure_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND membres.fonctions_fonction_id = 40
AND membres_commissions_commission_id = %s 
AND annee = %s
AND mois BETWEEN %s AND %s 
GROUP BY personnes.personne_nom 
ORDER BY personnes.personne_nom", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions2, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions3, "text"));
$rsSessionRepresentant = mysql_query($query_rsSessionRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant);
$totalRows_rsSessionRepresentant = mysql_num_rows($rsSessionRepresentant);
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


<link href="css/file.css" rel="stylesheet" type="text/css" />
<link href="css/v2.css" rel="stylesheet" type="text/css" />
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
<form id="form1" name="form1" method="post" action="">
  <table width="80%" border="0" align="center">
    <tr>
      <td><table width="75%" border="1" align="left" class="std2">
        <tr valign="baseline">
          <td colspan="5" nowrap="nowrap">Selectionner  une commission
            <?php           
	$showGoTo = "search_commissions4.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
            <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
            <strong>
              <?php if (isset($_GET['comID'])) { ?>
                  <?php } ?>
              </strong>
            <input name="txt_Com" type="hidden" id="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" />
            <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
            <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
            <?php }
            ?>
            <input type="hidden" name="txt_Com" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" /></td>
        </tr>
        <tr>
          <th>ID</th>
          <th>&nbsp;</th>
          <th>Type</th>
          <th>Nature </th>
          <th>Localite</th>
        </tr>
        <tr>
          <td nowrap="nowrap"><img src="images/img/b_views.png" alt="" width="16" height="16" /></td>
          <td nowrap="nowrap"><a href="#"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a>&nbsp;</td>
          <td nowrap="nowrap"><?php echo $row_rsCommissions['type_commission_lib']; ?>&nbsp;</td>
          <td nowrap="nowrap"><?php echo $row_rsCommissions['lib_nature']; ?>&nbsp;</td>
          <td nowrap="nowrap"><?php echo $row_rsCommissions['localite_lib']; ?>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="5" align="right" nowrap="nowrap"><input type="hidden" name="MM_insert" value="form1" /></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><?php if ($totalRows_rsCommissions > 0) { // Show if recordset not empty ?>
        <table align="left" class="std2">
          <tr valign="baseline">
            <th nowrap="nowrap" align="right">Mois:</th>
            <td><select name="txt_Month" id="txt_Month">
              <option value="" <?php if (!(strcmp("", $_POST['txt_Month']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
              <?php do {  ?>
              <option value="<?php echo $row_rsMois['mois_id']?>"<?php if (!(strcmp($row_rsMois['mois_id'], $_POST['txt_Month']))) {echo "selected=\"selected\"";} ?>> <?php echo ucfirst($row_rsMois['lib_mois']) ?></option>
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
              <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Select month, please</div>
              <?php  } ?></td>
          </tr>
          <tr valign="baseline">
            <th nowrap="nowrap" align="right">Ann&eacute;e : </th>
            <td><select name="txt_Year" id="txt_Year">
              <option value="" <?php if (!(strcmp("", $_POST['txt_Year']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
              <?php
do {  
?>
              <option value="<?php echo $row_rsSelYear['lib_annee']?>"<?php if (!(strcmp($row_rsSelYear['lib_annee'], $_POST['txt_Year']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsSelYear['lib_annee']?></option>
              <?php
} while ($row_rsSelYear = mysql_fetch_assoc($rsSelYear));
  $rows = mysql_num_rows($rsSelYear);
  if($rows > 0) {
      mysql_data_seek($rsSelYear, 0);
	  $row_rsSelYear = mysql_fetch_assoc($rsSelYear);
  }
?>
            </select>
              <?php
             /** Display error messages if the "password" field is empty */
       if ($anneeIsEmpty) { ?>
              <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Enter the YEAR, please</div>
              <?php  } ?></td>
          </tr>
        </table>
        <?php } // Show if recordset not empty ?></td>
    </tr>
    <tr>
      <td><input type="submit" value="Rechercher..." /></td>
    </tr>
  </table>
</form>
<table width="60%" border="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="869" align="center" valign="top">&nbsp;</td>
    <td width="376">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><strong class="welcome"><?php echo htmlentities(strtoupper($row_rsSessions['commission_lib'])); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">BILAN du mois de <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($_POST['txtMonth1'])); ?></strong> à <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($_POST['txtMonth2'])); ?> <?php echo  $_POST['txtYear']; ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left">
    <h1>Commissions</h1>
    <table border="1" align="center" class="std">
	<tr>
        <th nowrap="nowrap">N&deg;</th>
        <th nowrap="nowrap">NomPrenom</th>
        <th nowrap="nowrap">Fonction</th>
        <th nowrap="nowrap">RIB</th>
		<?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
        <th nowrap="nowrap"><?php echo GetValueMonth($counter);//ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
        <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
        <th nowrap="nowrap">Total</th>
        <th nowrap="nowrap">Taux</th>
        <th nowrap="nowrap">Montant</th>
        <th nowrap="nowrap">Retenu 16,50%</th>
        <th nowrap="nowrap">Net &agrave; percevoir</th>
      </tr>
      <tr>
        <td colspan="4" align="center" nowrap="nowrap">Nombre de dossiers trait&eacute;s</td>
        <?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?>&nbsp;</td>
        <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
        <td nowrap="nowrap"><?php echo $row_rsSessions['nombre_dossier']; ?></td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
        <td nowrap="nowrap">&nbsp;</td>
      </tr>
      <?php $counter=0; $Montant_global1 = 0; $user_rib = null; do  { $counter++; ?>
      <?php           
		$showGoToPersonnes1 = "upd_rib.php?recordID=". $row_rsSessions['personne_id'];
	  ?>
      <tr>
        <td nowrap="nowrap"><?php echo $counter ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">
		<?php echo htmlentities(strtoupper($row_rsSessions['personne_nom'].' '.$row_rsSessions['personne_prenom'])); ?>&nbsp; </a></td>
        <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
        <td align="left" nowrap="nowrap"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions['personne_id']);
		if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ } ?></td>
        <?php $count = $_POST['txtMonth1']; $sommer=0; $montant2=0; do  { GetValueMonth($count); ?>
        <td align="right" nowrap="nowrap"><?php 
					//$countDoc = 0;
					//foreach(explode("**", $row_rsSessions['jour']) as $val){
						//$countDoc++; 
						//$val == $count ? (print "1") : $countDoc--; 
						
						
					//}
				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessions['commission_id'], $row_rsSessions['personne_id'], $count, $_POST['txtYear']);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsSessions['commission_id']. "personne_id : " . $row_rsSessions['personne_id'] . " mois : " . $count . " annee : " . $_POST['txtYear'] . "</BR>";
					
			?></td>
        <?php $count++; } while ($count <= $_POST['txtMonth2']) ?>
        <td align="right" nowrap="nowrap"><?php echo $sommer //$row_rsSessions['nombre_jour']; ?></td>
        <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
        <td align="right" nowrap="nowrap">
		<?php 
		$somme = $row_rsSessions['montant']*$sommer; 
		$Montant_global1 = $Montant_global1 + $somme; 
		$montant2=$montant; 
		$montant = $montant + $somme;  //echo number_format(($somme),0,' ',' '); ?><?php echo number_format($somme,0,' ',' '); ?><strong>
          
          </strong></td>
        <td align="right" nowrap="nowrap"><?php echo number_format(($retenu=$somme*0.165),0,' ',' '); ?><?php $retenu_global_1 = $retenu_global_1 + $retenu; ?>&nbsp;</td>
        <td align="right" nowrap="nowrap"><strong><?php echo number_format(($net_percu_1 = $somme-$retenu),0,' ',' '); ?></strong><?php $net_global_1 = $net_global_1 + $net_percu_1; ?><?php $somme1 = $somme; $somme = $somme + $row_rsSessions['total']; ?></td>
      </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
<tr>
        <td colspan="4" nowrap="nowrap"> <a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>">&nbsp; </a> </td>
        <?php $counter = $_POST['txtMonth1']; $sommer=0; $montant2=0; do  { GetValueMonth($counter); ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <?php $counter++; } while ($counter <= $_POST['txtMonth2']) ?>
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
        <th align="right" nowrap="nowrap"><strong>
          <?php $total = -($montant-($row_rsCountIndemnity['total'])); echo number_format(($Montant_global1),0,' ',' ')//$somme = $somme - $row_rsCountIndemnity['total']; echo number_format($somme,0,' ',' '); ?>
        </strong></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($retenu_global_1,0,' ',' '); ?></th>
        <th align="right" nowrap="nowrap"><?php echo number_format($net_global_1,0,' ',' '); ?></th>
      </tr>

    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><?php if ($totalRows_rsSessions_sCom > 0) { // Show if recordset not empty ?>
    <h1>Sous Commissions D&eacute;pendante</h1>
  <table width="50%" border="1" align="center" class="print">
    <tr>
      <th>ID</th>
      <th>NomPrenom</th>
      <th>Fonction</th>
      <th>RIB</th>
      <?php $counter = $_POST['txtMonth1']; do {   ?>
      <th><?php echo GetValueMonth($counter) ?></th>
      <?php //}while ($counter < 12);?>
      <?php $counter++; } while ($counter <= $_POST['txtMonth2']) ?>
      <th nowrap="nowrap">Total</th>
      <th>Taux</th>
      <th>Montant</th>
      <th nowrap="nowrap">Retenu 16,50%</th>
      <th nowrap="nowrap">Net &agrave; percevoir</th>      
      </tr>
    <?php $compter=0; $Montant_global2 = 0; $user_rib = null; do  { $compter++; ?>
      <?php           
		$showGoToPersonnes2 = "upd_rib.php?recordID=". $row_rsSessions_sCom['personne_id'];
	  ?>
    <tr>
      <td nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions_sCom['membres_personnes_personne_id']; ?>&amp;map=personnes"> <?php echo $compter; ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap">
      <a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>">
	  <?php echo strtoupper($row_rsSessions_sCom['personne_nom']). " " . $row_rsSessions_sCom['personne_prenom']; ?>&nbsp; </a></td>
      <td align="left" nowrap="nowrap">Expert&nbsp; </td>
      <td align="left" nowrap="nowrap"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions_sCom['personne_id']);
	  if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ } ?></td>
      <?php //$count=0; $sommer=0; do  { $count++; ?>
      <?php $count = $_POST['txtMonth1']; $sommer=0;  do  {  ?>
      <td align="right" nowrap="nowrap"><?php /*echo $_POST['comID'] ."-" . $row_rsSessions_sCom['personne_id'] ."-" . $row_rsSessions_sCom['fonction_id'] ."-" . $count ."-" .$_POST['txtYear'];/*
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions_sCom['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}*/
			?>
			<?php 

				$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_sCom_by_values($_POST['comID'], $row_rsSessions_sCom['personne_id'], $count, $_POST['txtYear']);
				if (isset($nombre_jours))
				{ 
					echo $nombre_jours;
					$sommer = $sommer + $nombre_jours;
					
				}
				else
				{echo 0;}	
			?>
            
            </td>
      <?php //}while ($count < 12); ?>
      <?php $count++; } while ($count <= $_POST['txtMonth2']) ?>
      <td align="right" nowrap="nowrap"><?php echo $sommer;//$row_rsSessions_sCom['nombre_jour']; ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions_sCom['montant'],0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; ?></td>
      <td align="right" nowrap="nowrap"><?php $somme2 = $row_rsSessions_sCom['montant']*$sommer; echo number_format($row_rsSessions_sCom['total'],0,' ',' '); ?>
        <?php  $Montant_global2 = $Montant_global2 + $row_rsSessions_sCom['total'];?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format(($retenu_3 = $row_rsSessions_sCom['total']*0.165),0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; 
	  $retenu_global_3 = $retenu_global_3 + $retenu_3;
	  ?></td>
      <td align="right" nowrap="nowrap">
	  <strong><?php echo number_format(($net_percu_3 = $row_rsSessions_sCom['total']-$retenu_3),0,' ',' ');//$row_rsSessions_sCom['nombre_jour']; 
	  $net_percu_global_3 = $net_percu_global_3+$net_percu_3; ?></strong>
      </td>
      </tr>
    <?php } while ($row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom)); ?>
<tr>
      <td colspan="4" nowrap="nowrap">
        <a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "400"); ?>">&nbsp; </a></td>
      <?php //$count=0; $sommer=0; do  { $count++; ?>
      <?php $count = $_POST['txtMonth1']; $sommer=0;  do  { $count++; ?>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <?php //}while ($count < 12); ?>
      <?php } while ($count <= $_POST['txtMonth2']) ?>
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <th align="right" nowrap="nowrap"><strong>Sous total</strong></th>
      <th align="right" nowrap="nowrap"><strong>
        <?php //echo number_format($somme3,0,' ',' '); ?>
        <?php echo number_format($Montant_global2,0,' ',' '); ?>      </strong></th>
      <th align="right" nowrap="nowrap"><strong><?php echo number_format($retenu_global_3,0,' ',' '); ?></strong>&nbsp; </th>
      <th align="right" nowrap="nowrap"><strong><?php echo number_format($net_percu_global_3,0,' ',' '); ?></strong></th>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3" align="left">&nbsp;
      <?php if ($totalRows_rsSessionRepresentant > 0) { // Show if recordset not empty ?>
  <h1>Repr&eacute;sentant Maitre d'Ouvrage</h1>
        <table border="1" align="center" class="print">
          <tr>
            <th>ID</th>
            <th>Nom et Prenom</th>
            <th>Representant structure</th>
            <th>RIB</th>
            <?php //$count=0; do { $count++ ?>
            <?php $count = $_POST['txtMonth1']; do {   ?>
            <th><?php echo GetValueMonth($count); ?>&nbsp;</th>
            <?php //} while($count<12) ?>
            <?php $count++; } while ($count <= $_POST['txtMonth2']) ?>
            <th>Total</th>
            <th>Taux</th>
            <th>Montant</th>
            <th>retenu 16,50%</th>
            <th>Net &agrave; percevoir</th>
          </tr>
          <?php $compter=0; $Montant_global3 = 0; $user_rib = null; do { $sommes=0; $compter++ ?>
		  <?php           
            $showGoToPersonnes3 = "upd_rib.php?recordID=". $row_rsSessionRepresentant['personne_id'];
          ?>
          <tr>
            <td><a href="detail_to_delete.php?recordID=<?php echo $row_rsSessionRepresentant['commission_id']; ?>"> <?php echo $compter ?>&nbsp; </a></td>
            <td align="left" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoToPersonnes3, "700", "400"); ?>">
			<?php echo strtoupper($row_rsSessionRepresentant['personne_nom'] . " " . $row_rsSessionRepresentant['personne_prenom']); ?>&nbsp; </a></td>
            <td align="left" nowrap="nowrap"><?php echo strtoupper($row_rsSessionRepresentant['code_structure']); ?>&nbsp; </td>
            <td align="left"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessionRepresentant['personne_id']);
			if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  	echo $user_rib; }else{ } ?></td>
            <?php //$count=0; $sommer=0; do { $count++ ?>
            <?php $count = $_POST['txtMonth1']; $sommer=0; do {  ?>
            <td align="right">
              <?php 
				$nombre_jour = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSessionRepresentant['commission_id'], $row_rsSessionRepresentant['personne_id'], $count, $_POST['txtYear']);
				if (isset($nombre_jour)){ echo $nombre_jour; $sommes = $sommes + $nombre_jour;} else {echo 0;}		
			?>
            &nbsp;</td>
            <?php //} while($count<12) ?>
            <?php $count++;  } while ($count <= $_POST['txtMonth2']) ?>
            <td align="right"><?php echo $sommes //$row_rsSessionRepresentant['mois']; ?>&nbsp; </td>
            <td align="right"><?php echo number_format(($row_rsSessionRepresentant['montant']),0,' ',' '); ?>&nbsp; </td>
            <td align="right">
				<?php $Montant = $sommes*$row_rsSessionRepresentant['montant']; 
					  $Montant_global3 = $Montant_global3 + $Montant; 
					  echo number_format($Montant,0,' ',' '); 
				?>
                &nbsp; </td>
            <td align="right">
				<?php echo number_format(($retenu_2 = $Montant*0.1650),0,' ',' '); ?><?php $retenu_global_2 = $retenu_global_2 + $retenu_2; ?></td>
            <td align="right">
				<strong><?php echo number_format($net_percu_2 = $Montant-$retenu_2,0,' ',' '); ?></strong><?php $net_percu_global_2 = $net_percu_global_2+$net_percu_2; ?></td>
          </tr>
          <?php } while ($row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant)); ?>
<tr>
            <td colspan="4"><a href="#" onclick="<?php popup($showGoToPersonnes3, "700", "400"); ?>">&nbsp; </a> </td>
            <?php //$count=0; $sommer=0; do { $count++ ?>
            <?php $count = $_POST['txtMonth1']; $sommer=0; do {  $count++ ?>
            <td align="right">&nbsp;</td>
            <?php //} while($count<12) ?>
            <?php } while ($count <= $_POST['txtMonth2']) ?>
            <td align="right">&nbsp; </td>
            <th align="right" nowrap="nowrap"><strong>Sous Tota</strong>l&nbsp; </th>
            <th align="right" nowrap="nowrap"><strong>
              <?php  //echo number_format($montants,0,' ',' '); ?>
            <?php echo number_format($Montant_global3,0,' ',' '); ?>            </strong>&nbsp; </th>
            <th align="right" nowrap="nowrap"><strong><?php echo number_format($retenu_global_2,0,' ',' '); ?></strong> </th>
            <th align="right" nowrap="nowrap"><strong><?php echo number_format($net_percu_global_2,0,' ',' '); ?></strong></th>
          </tr>      
        </table>
        <br />
        <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3"><table border="0" align="center" class="print">
      <tr>
        <td>Total des representants des maitres d'ouvrages</td>
        <td align="right"><strong><?php echo number_format($Montant_global3,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
      <tr>
        <td>Total de la Sous Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
	  <tr>
        <td>Total de la Commission</td>
        <td align="right"><strong><?php echo number_format($Montant_global1,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>
	  <tr>
        <td>Montant retenu global (16,50%)</td>
        <td align="right"><strong><?php echo number_format($retenu_global_1+$retenu_global_2+$retenu_global_3,0,' ',' ') //echo number_format($montant2,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>

      <tr>
        <th>Total G&eacute;n&eacute;ral</th>
        <th align="right"><strong>
          <?php  echo number_format(($Montant_global1 + $Montant_global2 + $Montant_global3),0,' ',' '); ?>
          </strong></th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="3" align="left">Etat imprim&eacute; par : <strong><?php echo strtoupper($_SESSION['MM_Username']); ?></strong> le <?php echo date("d-m-Y"); ?> à <?php echo date("H:i") ?></td>
  </tr>
</table>
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

mysql_free_result($rsCommissions);

mysql_free_result($rsMembres);

mysql_free_result($rsSessionRepresentant);
?>
