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

$maxRows_rsSessions = 10;
$pageNum_rsSessions = 0;
if (isset($_GET['pageNum_rsSessions'])) {
  $pageNum_rsSessions = $_GET['pageNum_rsSessions'];
}
$startRow_rsSessions = $pageNum_rsSessions * $maxRows_rsSessions;

$colname1_rsSessionsCommite = "-1";
if (isset($_GET['comID'])) {
  $colname1_rsSessionsCommite = $_GET['comID'];
}
$colname2_rsSessionsCommite = "01";
if (isset($_POST['txtMonth1'])) {
  $colname2_rsSessionsCommite = $_POST['txtMonth1'];
}
$colname3_rsSessionsCommite = "12";
if (isset($_POST['txtMonth2'])) {
  $colname3_rsSessionsCommite = $_POST['txtMonth2'];
}
$colname4_rsSessionsCommite = "-1";
if (isset($_POST['txtYear'])) {
  $colname4_rsSessionsCommite = $_POST['txtYear'];
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
GROUP BY personnes.personne_id
ORDER BY fonctions.fonction_id", GetSQLValueString($colname1_rsSessionsCommite, "int"),GetSQLValueString($colname4_rsSessionsCommite, "text"));
$query_limit_rsSessions = sprintf("%s LIMIT %d, %d", $query_rsSessions, $startRow_rsSessions, $maxRows_rsSessions);
$rsSessions = mysql_query($query_limit_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);

if (isset($_GET['totalRows_rsSessions'])) {
  $totalRows_rsSessions = $_GET['totalRows_rsSessions'];
} else {
  $all_rsSessions = mysql_query($query_rsSessions);
  $totalRows_rsSessions = mysql_num_rows($all_rsSessions);
}
$totalPages_rsSessions = ceil($totalRows_rsSessions/$maxRows_rsSessions)-1;


mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, nombre_jour, annee, montant, sum(nombre_jour * montant) as total
FROM sessions, membres, commissions, personnes
WHERE sessions.membres_commissions_commission_id = membres.commissions_commission_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND commissions.commission_id = membres.commissions_commission_id
AND personnes.personne_id = membres.personnes_personne_id
AND personnes.display = '1'
AND commission_parent = %s AND annee = %s
GROUP BY personnes.personne_id
ORDER BY personne_nom, annee, mois",GetSQLValueString($colname4_rsSessionsCommite, "text"), GetSQLValueString($colname1_rsSessionsCommite, "int"));
$rsSousCommission = mysql_query($query_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);
$totalRows_rsSousCommission = mysql_num_rows($rsSousCommission);

$colname5_rsSousCommission = "-1";
if (isset($_POST['txtYear'])) {
  $colname5_rsSousCommission = $_POST['txtYear'];
}
$colname6_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname6_rsSousCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRepresentant = sprintf("SELECT commission_id, personnes.personne_id, personne_nom, fonction_lib, sum(nombre_jour) as nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, agence_cle, banque_code, agence_code, numero_compte
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
GROUP BY personnes.personne_nom 
ORDER BY personnes.personne_nom", GetSQLValueString($colname4_rsSessionsCommite, "text"), GetSQLValueString($colname1_rsSessionsCommite, "int"));
$rsRepresentant = mysql_query($query_rsRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsRepresentant = mysql_fetch_assoc($rsRepresentant);
$totalRows_rsRepresentant = mysql_num_rows($rsRepresentant);

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

$queryString_rsSessions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSessions") == false && 
        stristr($param, "totalRows_rsSessions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSessions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSessions = sprintf("&totalRows_rsSessions=%d%s", $totalRows_rsSessions, $queryString_rsSessions);
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

<form action="" method="POST">
<p>&nbsp;</p>
<table border="1" class="std2">
<tr>
    <th align="right" scope="row">Exercice:</th>
    <td><select name="txtYear" id="txtYear">
      <option value=""<?php if (!(strcmp("", $_POST['txtType']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
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
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th align="right" scope="row">Periode allant de :</th>
    <td><select name="txtMonth1" id="txtMonth1">
        <option value="" <?php if (!(strcmp("", $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsMois1['mois_id']?>" <?php if (!(strcmp($row_rsMois1['mois_id'], $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>><?php echo Ucfirst($row_rsMois1['lib_mois']);?></option>
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
       <option value="" <?php if (!(strcmp("", $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsMois2['mois_id']?>" <?php if (!(strcmp($row_rsMois2['mois_id'], $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois2['lib_mois']); ?></option>
      <?php
} while ($row_rsMois2 = mysql_fetch_assoc($rsMois2));
  $rows = mysql_num_rows($rsMois2);
  if($rows > 0) {
      mysql_data_seek($rsMois2, 0);
	  $row_rsMois2 = mysql_fetch_assoc($rsMois2);
  }
?>
    </select></td>
    <td><input type="submit" name="button" id="button" value="Rechercher" />&nbsp;</td>
    </tr>
</table>
<p>&nbsp;</p>
</form>
<h1>Etat commission</h1>
<table>
    <tr>
      <td>
	  <?php $link = "print_etat_financier_recap.php?aID=" . $_POST['txtYear']. "&mID1=" . $_POST['txtMonth1'] . "&mID2=" . $_POST['txtMonth2'] . "&comID=" . $_GET['comID']. "&menuID=" . $_GET['menuID'] . "&typID=" . $_POST['txtType'] ?>
      <?php $link2 = "sample10_print.php?aID=" . $_POST['txtYear']. "&mID1=" . $_POST['txtMonth1'] . "&mID2=" . $_POST['txtMonth2'] . "&comID=" . $_GET['comID']. "&menuID=" . $_GET['menuID'] . "&typID=" . $_POST['txtType'] ?>
        <?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=6" ?>
        <a href="#" onclick="<?php popup($link2, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Version imprimable</a>&nbsp; <a href="<?php echo $link2 ?>"><img src="images/img/b_edit.png" width="16" height="16" /> Mettre &agrave; jour</a><a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;&amp;mapid=personne_id&amp;action=add_com&amp;page=rep"></a>&nbsp;<a href="<?php echo $link6 ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter un repr&eacute;sentant</a></td>
    </tr>
  </table>
<table border="1" align="center" class="std">
  <tr>
    <th>N&deg;</th>
    <th>Nom et Prenom</th>
    <th>Fonction</th>
    <th nowrap>RIB</th>
    <?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
    <th nowrap="nowrap"><?php echo GetValueMonth($counter);//ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
    <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
    <th>Nbre  Jours</th>
    <th>Montant</th>
    <th>Total</th>
  </tr>
  <?php $count = 0; $somme2 = 0; do { $count++; ?>
    <tr>
      <td nowrap="nowrap"><?php echo $count; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo strtoupper($row_rsSessions['personne_nom']); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['fonction_lib']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo strtoupper($row_rsSessions['banque_code'].'-'.$row_rsSessions['agence_code'].'-'.$row_rsSessions['numero_compte'].'-'.$row_rsSessions['cle']); ?>&nbsp;</td>
      <?php $counter = $_POST['txtMonth1'];  $total_montant = 0; do {   GetValueMonth($counter);  ?>
      <td align="right" nowrap="nowrap">
      <?php 
		$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($_GET['comID'], $row_rsSessions['personne_id'], $counter, $_POST['txtYear']);
		echo $somme2 = GetSomme($count, $somme2, $nombre_jours);
		//echo $somme;
		//echo $count;
				/*if (isset($nombre_jours))
				{ echo $nombre_jours; }
				else
				{echo 0;}*/
				//echo "commission_id : " . $row_rsSessionsCommite['commission_id']. "personne_id : " . $row_rsSessionsCommite['personne_id'] . " mois : " . $count . " annee : " . $_GET['aID'] . "</BR>";
					
			?>
        
        &nbsp;</td>
      <?php $counter++; } while ($counter <= $_POST['txtMonth2']) ?>
      <td align="right" nowrap="nowrap"><?php echo $row_rsSessions['nombre_jours']; //$nbr_jours = MinmapDB::getInstance()->get_somme_nombre_jour_by_values(1, $row_rsSessions['personne_id'], 1, 12, 2014); ?>&nbsp;   </td>
      <td align="right" nowrap="nowrap">&nbsp; <?php echo number_format($row_rsSessions['montant'],0,' ',' ')?>&nbsp; </td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['total'],0,' ',' '); $somme = $somme + $row_rsSessions['total']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
<tr>
    <td colspan="4" nowrap></td>
    <?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
    <td nowrap="nowrap"><?php //echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</td>
    <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
    <td nowrap>&nbsp;</td>
    <td nowrap>S<strong>ous total sessions</strong></td>
    <td nowrap><strong><?php echo number_format($somme,0,' ',' '); ?></strong></td>
  </tr>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSessions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, 0, $queryString_rsSessions); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, max(0, $pageNum_rsSessions - 1), $queryString_rsSessions); ?>">Pr&eacute;c&eacute;dent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessions < $totalPages_rsSessions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, min($totalPages_rsSessions, $pageNum_rsSessions + 1), $queryString_rsSessions); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSessions < $totalPages_rsSessions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, $totalPages_rsSessions, $queryString_rsSessions); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsSessions + 1) ?> à <?php echo min($startRow_rsSessions + $maxRows_rsSessions, $totalRows_rsSessions) ?> sur <?php echo $totalRows_rsSessions ?>

<?php if ($totalRows_rsSousCommission > 0) { // Show if recordset not empty ?>
<h1>Etat sous commission</h1>
<table border="1" align="center" class="std">
    <tr>
      <th nowrap="nowrap">N&deg;</th>
      <th nowrap="nowrap">Nom et Prenom</th>
      <th nowrap="nowrap">Fonction</th>
      <th nowrap="nowrap">Numero compte</th>
      <?php $counter = $_POST['txtMonth1']; do { GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php echo GetValueMonth($counter); //ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities(strtoupper($row_rsSousCommission['personne_nom'] . " " . $row_rsSousCommission['personne_prenom'])); ?></td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsSousCommission['fonction_lib']); ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsSousCommission['numero_compte']; ?>&nbsp;</td>
        <?php $counter = $_POST['txtMonth1'];  $total_montant = 0; do {   ?>
        <td align="right" nowrap="nowrap">
		<?php 
		$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsSousCommission['commission_id'], $row_rsSousCommission['personne_id'], $counter, $_POST['txtYear']);
				if (isset($nombre_jours))
				{ echo $nombre_jours; }
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsSousCommission['commission_id']. "personne_id : " . $row_rsSousCommission['personne_id'] . " mois : " . $counter . " annee : " . $_POST['txtYear'] . "</BR>";
					
			?>&nbsp;</td>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSousCommission['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSousCommission['total'],0,' ',' '); 
	  $somme = $somme + $row_rsSousCommission['total'];
	  $somme2 = $somme2 + $row_rsSousCommission['total'];?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
<tr>
      <td colspan="4" nowrap="nowrap">&nbsp;</td>
      <?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter);	?>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <td nowrap="nowrap">S<strong>ous total sessions</strong></td>
      <td align="right" nowrap="nowrap"><strong><?php echo number_format($somme2,0,' ',' '); ?></strong></td>
  </tr>
  </table>
<br />
<?php echo $totalRows_rsSousCommission ?> Enregistrements Total
<?php } // Show if recordset not empty ?>

<?php if ($totalRows_rsRepresentant > 0) { // Show if recordset not empty ?>
<h1>Etat representant maitre d'ouvrage</h1>
<table border="1" align="center" class="std">
    <tr>
      <th nowrap="nowrap">N&deg;</th>
      <th nowrap="nowrap">Nom et Prenom</th>
      <th nowrap="nowrap">Administration</th>
      <th nowrap="nowrap">RIB</th>
      <?php $counter = $_POST['txtMonth1']; do { GetValueMonth($counter); ?>
      <th nowrap="nowrap"><?php echo GetValueMonth($counter);//ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id('0'.$counter));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities(strtoupper($row_rsRepresentant['personne_nom'] . " " . $row_rsRepresentant['personne_prenom'])); ?></td>
        <td nowrap="nowrap"><?php echo strtoupper($row_rsRepresentant['code_structure']); ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsRepresentant['numero_compte']; ?>&nbsp;</td>
        <?php $counter = $_POST['txtMonth1'];  $total_montant = 0; do {   ?>
        <td align="right" nowrap="nowrap">
		<?php 
		$nombre_jours = MinmapDB::getInstance()->get_nombre_jour_by_values($row_rsRepresentant['commission_id'], $row_rsRepresentant['personne_id'], $counter, $_POST['txtYear']);
				if (isset($nombre_jours))
				{ echo $nombre_jours; }
				else
				{echo 0;}
				//echo "commission_id : " . $row_rsRepresentant['commission_id']. "personne_id : " . $row_rsRepresentant['personne_id'] . " mois : " . $counter . " annee : " . $_POST['txtYear'] . "</BR>";
					
			?>&nbsp;</td>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsRepresentant['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($row_rsRepresentant['total'],0,' ',' '); 
	  $somme = $somme + $row_rsRepresentant['total'];
	  $somme3 = $somme3 + $row_rsRepresentant['total']; ?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsRepresentant = mysql_fetch_assoc($rsRepresentant)); ?>
<tr>
      <td colspan="4" nowrap="nowrap">&nbsp;</td>
      <?php $counter = $_POST['txtMonth1']; do {  GetValueMonth($counter); ?>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <td nowrap="nowrap">S<strong>ous total sessions</strong></td>
      <td align="right" nowrap="nowrap"><strong><?php echo number_format($somme3,0,' ',' '); ?></strong></td>
  </tr>
  </table>

<br />
<?php echo $totalRows_rsRepresentant ?> Enregistrements Total
<?php } // Show if recordset not empty ?>
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

mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($rsAnnee);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>
