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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$_AGENCE_CLE = $_POST['banque_code'] . $_POST['agence_code'] . $_POST['cle'];
$DATE = date('Y-m-d H:i:s');

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL1 = sprintf("UPDATE personnes SET personne_matricule=%s, personne_nom=%s, personne_prenom=%s, personne_grade=%s, personne_telephone=%s, structure_id=%s, sous_groupe_id=%s, fonction_id=%s, domaine_id=%s, type_personne_id=%s, date_creation=%s, dateUpdate=%s, display=%s WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString($_POST['personne_nom'], "text"),
                       GetSQLValueString($_POST['personne_prenom'], "text"),
                       GetSQLValueString($_POST['personne_grade'], "text"),
                       GetSQLValueString($_POST['personne_telephone'], "text"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['sous_groupe_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($_POST['domaine_id'], "int"),
                       GetSQLValueString($_POST['type_personne_id'], "int"),
                       GetSQLValueString($_POST['date_creation'], "date"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"));
					   
  $updateSQL2 = sprintf("UPDATE rib SET personne_matricule=%s, agence_cle=%s, banque_code=%s, agence_code=%s, numero_compte=%s, dateUpdate=%s, cle=%s WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString($_AGENCE_CLE, "text"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['numero_compte'], "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['cle'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL1, $MyFileConnect) or die(mysql_error());
  $Result2 = mysql_query($updateSQL2, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "show_personnes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsUpdPersonnes = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdPersonnes = $_GET['recordID'];
}

$colname_rsUpdRIB = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdRIB = $_GET['recordID'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdPersonnes = sprintf("SELECT * FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsUpdPersonnes, "int"));
$rsUpdPersonnes = mysql_query($query_rsUpdPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsUpdPersonnes = mysql_fetch_assoc($rsUpdPersonnes);
$totalRows_rsUpdPersonnes = mysql_num_rows($rsUpdPersonnes);

//mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdRIB = sprintf("SELECT * FROM rib WHERE personne_id = %s", GetSQLValueString($colname_rsUpdRIB, "int"));
$rsUpdRIB = mysql_query($query_rsUpdRIB, $MyFileConnect) or die(mysql_error());
$row_rsUpdRIB = mysql_fetch_assoc($rsUpdRIB);
$totalRows_rsUpdRIB = mysql_num_rows($rsUpdRIB);

//mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructure = "SELECT structure_id, structure_lib FROM structures WHERE display = '1' ORDER BY structure_lib DESC";
$rsStructure = mysql_query($query_rsStructure, $MyFileConnect) or die(mysql_error());
$row_rsStructure = mysql_fetch_assoc($rsStructure);
$totalRows_rsStructure = mysql_num_rows($rsStructure);

//mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGroupe = "SELECT sous_groupe_id, sous_groupe_lib FROM sous_groupes WHERE display = '1' ORDER BY sous_groupe_lib DESC";
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);

//mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = "SELECT fonction_id, fonction_lib FROM fonctions WHERE display = '1' ORDER BY fonction_lib DESC";
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);

//mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsDomaine = "SELECT domaine_id, domaine_lib FROM domaines_activites WHERE display = '1' ORDER BY domaine_lib DESC";
$rsDomaine = mysql_query($query_rsDomaine, $MyFileConnect) or die(mysql_error());
$row_rsDomaine = mysql_fetch_assoc($rsDomaine);
$totalRows_rsDomaine = mysql_num_rows($rsDomaine);

//mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypePersonne = "SELECT type_personne_id, type_personne_lib FROM type_personnes WHERE display = '1' ORDER BY type_personne_lib DESC";
$rsTypePersonne = mysql_query($query_rsTypePersonne, $MyFileConnect) or die(mysql_error());
$row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
$totalRows_rsTypePersonne = mysql_num_rows($rsTypePersonne);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanque = "SELECT banque_id, banque_code, banque_lib FROM banques WHERE display = '1' ORDER BY banque_code, banque_lib ASC";
$rsBanque = mysql_query($query_rsBanque, $MyFileConnect) or die(mysql_error());
$row_rsBanque = mysql_fetch_assoc($rsBanque);
$totalRows_rsBanque = mysql_num_rows($rsBanque);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAgence = "SELECT agence_code, agence_lib, agences.banque_code, banque_lib FROM agences, banques WHERE agences.display = '1' ORDER BY banque_lib, agence_code ASC";
$rsAgence = mysql_query($query_rsAgence, $MyFileConnect) or die(mysql_error());
$row_rsAgence = mysql_fetch_assoc($rsAgence);
$totalRows_rsAgence = mysql_num_rows($rsAgence);

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
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);
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
    <h1>Mise a jour fichier</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std2">
    <tr valign="baseline">
      <td colspan="2" align="right" nowrap="nowrap"><h1>Information personnel</h1></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">MATRICULE :</th>
      <td><input type="text" name="personne_matricule" value="<?php echo htmlentities($row_rsUpdPersonnes['personne_matricule'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">NOM :</th>
      <td><input type="text" name="personne_nom" value="<?php echo htmlentities($row_rsUpdPersonnes['personne_nom'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">PRENOM :</th>
      <td><input type="text" name="personne_prenom" value="<?php echo htmlentities($row_rsUpdPersonnes['personne_prenom'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">GRADE :</th>
      <td><input type="text" name="personne_grade" value="<?php echo htmlentities($row_rsUpdPersonnes['personne_grade'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">TELEPHONE :</th>
      <td><input type="text" name="personne_telephone" value="<?php echo htmlentities($row_rsUpdPersonnes['personne_telephone'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">STRUCTURE :</th>
      <td>
      <select name="structure_id">
        <?php do {  ?>
        <option value="<?php echo $row_rsStructure['structure_id']?>" <?php if (!(strcmp($row_rsStructure['structure_id'], htmlentities($row_rsUpdPersonnes['structure_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsStructure['structure_lib']?></option>
        
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
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">GROUPE  :</th>
      <td><select name="sous_groupe_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsSousGroupe['sous_groupe_id']?>"<?php if (!(strcmp($row_rsSousGroupe['sous_groupe_id'], htmlentities($row_rsUpdPersonnes['sous_groupe_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rsSousGroupe['sous_groupe_lib']); ?></option>
        <?php
} while ($row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe));
  $rows = mysql_num_rows($rsSousGroupe);
  if($rows > 0) {
      mysql_data_seek($rsSousGroupe, 0);
	  $row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">FONCTION :</th>
      <td><select name="fonction_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonction['fonction_id']?>"<?php if (!(strcmp($row_rsFonction['fonction_id'], htmlentities($row_rsUpdPersonnes['fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rsFonction['fonction_lib']); ?></option>
        <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">DOMAINE :</th>
      <td><select name="domaine_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsDomaine['domaine_id']?>"<?php if (!(strcmp($row_rsDomaine['domaine_id'], htmlentities($row_rsUpdPersonnes['domaine_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsDomaine['domaine_lib']?></option>
        <?php
} while ($row_rsDomaine = mysql_fetch_assoc($rsDomaine));
  $rows = mysql_num_rows($rsDomaine);
  if($rows > 0) {
      mysql_data_seek($rsDomaine, 0);
	  $row_rsDomaine = mysql_fetch_assoc($rsDomaine);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th align="right" nowrap="nowrap">TYPE PERSONNE :</th>
      <td><select name="type_personne_id">
        <?php
do {  
?>
        <option value="<?php echo $row_rsTypePersonne['type_personne_id']?>"<?php if (!(strcmp($row_rsTypePersonne['type_personne_id'], htmlentities($row_rsUpdPersonnes['type_personne_id'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rsTypePersonne['type_personne_lib']); ?></option>
        <?php
} while ($row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne));
  $rows = mysql_num_rows($rsTypePersonne);
  if($rows > 0) {
      mysql_data_seek($rsTypePersonne, 0);
	  $row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline" bgcolor="#FFFFFF">
      <td colspan="2" align="right" nowrap="nowrap"><h1>Relévé d'Identité Bancaire (RIB)</h1></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">BANQUE :</th>
      <td><select name="banque_code">
        <?php
do {  
?>
        <option value="<?php echo $row_rsBanque['banque_code']?>"<?php if (!(strcmp($row_rsBanque['banque_code'], htmlentities($row_rsUpdRIB['banque_code'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo  $row_rsBanque['banque_code']." - ".$row_rsBanque['banque_lib']?></option>
        <?php
} while ($row_rsBanque = mysql_fetch_assoc($rsBanque));
  $rows = mysql_num_rows($rsBanque);
  if($rows > 0) {
      mysql_data_seek($rsBanque, 0);
	  $row_rsBanque = mysql_fetch_assoc($rsBanque);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">AGENCE :</th>
      <td><select name="agence_code">
        <?php
do {  
?>
        <option value="<?php echo $row_rsAgence['agence_code']?>"<?php if (!(strcmp($row_rsAgence['agence_code'], htmlentities($row_rsUpdRIB['agence_code'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAgence['banque_lib'] . " - " . $row_rsAgence['agence_code'] . " " . $row_rsAgence['agence_lib']?></option>
        <?php
} while ($row_rsAgence = mysql_fetch_assoc($rsAgence));
  $rows = mysql_num_rows($rsAgence);
  if($rows > 0) {
      mysql_data_seek($rsAgence, 0);
	  $row_rsAgence = mysql_fetch_assoc($rsAgence);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">NUMERO DE COMPTE :</th>
      <td><input name="numero_compte" type="text" value="<?php echo htmlentities($row_rsUpdRIB['numero_compte'], ENT_COMPAT, 'utf-8'); ?>" size="32" maxlength="11" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">CLE :</th>
      <td><input name="cle" type="text" value="<?php echo htmlentities($row_rsUpdRIB['cle'], ENT_COMPAT, 'utf-8'); ?>" size="32" maxlength="2" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Mise à jour" />
      <input name="Button" type="button" value="Annuler" /></td>
    </tr>
  </table>
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdPersonnes['personne_id']; ?>" />
  <input type="hidden" name="date_creation" value="<?php echo htmlentities($row_rsUpdPersonnes['date_creation'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="dateUpdate" value="<?php echo htmlentities($row_rsUpdPersonnes['dateUpdate'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="agence_cle" value="<?php echo htmlentities($row_rsUpdRIB['agence_cle'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdPersonnes['display'], ENT_COMPAT, 'utf-8'); ?>" />
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="personne_id" value="<?php echo $row_rsUpdPersonnes['personne_id']; ?>" />
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
mysql_free_result($rsUpdPersonnes);

mysql_free_result($rsUpdRIB);

mysql_free_result($rsBanque);

mysql_free_result($rsAgence);

mysql_free_result($rsSousMenu);

mysql_free_result($rsMenu);

mysql_free_result($rsUpdPersonnes);

mysql_free_result($rsStructure);

mysql_free_result($rsSousGroupe);

mysql_free_result($rsFonction);

mysql_free_result($rsDomaine);

mysql_free_result($rsTypePersonne);
?>
