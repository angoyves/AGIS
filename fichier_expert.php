<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php');?>
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

$maxRows_rsFichierExpert = 20;
$pageNum_rsFichierExpert = 0;
if (isset($_GET['pageNum_rsFichierExpert'])) {
  $pageNum_rsFichierExpert = $_GET['pageNum_rsFichierExpert'];
}
$startRow_rsFichierExpert = $pageNum_rsFichierExpert * $maxRows_rsFichierExpert;

$colname_rsFichierExpert = "-1";
if ((isset($_GET['txtSearch'])) && (isset($_GET['txtChamp']))) {
  $colname1_rsFichierExpert = $_GET['txtChamp'];
  $colname2_rsFichierExpert = $_GET['txtSearch'];

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFichierExpert = sprintf("SELECT * FROM personnes, rib, domaine_expert, localite_expert WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = domaine_expert.personne_id AND personnes.personne_id = localite_expert.personne_id AND personnes.type_personne_id = 3 AND personnes.$colname1_rsFichierExpert LIKE  %s ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname2_rsFichierExpert . "%", "text"));
$query_limit_rsFichierExpert = sprintf("%s LIMIT %d, %d", $query_rsFichierExpert, $startRow_rsFichierExpert, $maxRows_rsFichierExpert);
$rsFichierExpert = mysql_query($query_limit_rsFichierExpert, $MyFileConnect) or die(mysql_error());
$row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert);
} else {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFichierExpert = "SELECT * FROM personnes, rib, domaine_expert, localite_expert WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = domaine_expert.personne_id AND personnes.personne_id = localite_expert.personne_id AND personnes.type_personne_id = 3 ORDER BY personne_nom ASC";
$query_limit_rsFichierExpert = sprintf("%s LIMIT %d, %d", $query_rsFichierExpert, $startRow_rsFichierExpert, $maxRows_rsFichierExpert);
$rsFichierExpert = mysql_query($query_limit_rsFichierExpert, $MyFileConnect) or die(mysql_error());
$row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert);
}

if (isset($_GET['totalRows_rsFichierExpert'])) {
  $totalRows_rsFichierExpert = $_GET['totalRows_rsFichierExpert'];
} else {
  $all_rsFichierExpert = mysql_query($query_rsFichierExpert);
  $totalRows_rsFichierExpert = mysql_num_rows($all_rsFichierExpert);
}
$totalPages_rsFichierExpert = ceil($totalRows_rsFichierExpert/$maxRows_rsFichierExpert)-1;

$queryString_rsFichierExpert = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsFichierExpert") == false && 
        stristr($param, "totalRows_rsFichierExpert") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsFichierExpert = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsFichierExpert = sprintf("&totalRows_rsFichierExpert=%d%s", $totalRows_rsFichierExpert, $queryString_rsFichierExpert);
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
<form id="form1" name="form1" method="get" action="">
<table border="0" class="std">
  <tr>
    <td><strong>Rechercher par :</strong></td>
    <td><select name="txtChamp" id="txtChamp">
      <option value="personne_nom" <?php if (!(strcmp("personne_nom", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Nom</option>
      <option value="personne_prenom" <?php if (!(strcmp("personne_prenom", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Prenom</option>
      <option value="personne_telephone" <?php if (!(strcmp("personne_telephone", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>N&deg; T&eacute;l&eacute;phone</option>
      <option value="personne_specialisation" <?php if (!(strcmp("personne_specialisation", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Specialisation</option>
      <option value="personne_qualification" <?php if (!(strcmp("personne_qualification", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Qualification</option>
    </select></td>
    <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo $_GET['txtSearch']; ?>" />
      <input name="menuID" type="hidden" id="menuID" value="<?php echo $_GET['menuID']; ?>" />
      <input name="action" type="hidden" id="action" value="<?php echo $_GET['action']; ?>" /></td>
    <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
  </tr>
  <tr>
    <td colspan="3"><?php if (isset($_GET['txtSearch'])) { ?>
      Enregistrement trouv&eacute; pour votre recherche &quot; <strong> <?php echo $_GET['txtSearch']; ?></strong> &quot; dans <strong> <?php echo $_GET['txtChamp']; ?></strong></BR>
      <?php $link = "print_expert.php?txtChamp=" . $_GET['txtChamp']. "&txtSearch=" . $_GET['txtSearch']; ?>
    	 <a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Imprimer le resultat</a>
      <?php } ?></td>
    <td> </td>
  </tr>
</table>
<h1>Detail</h1>
</form>
<table border="1" align="center" class="std">
  <tr>
    <th nowrap="nowrap">N&deg;</th>
    <th nowrap="nowrap">Rib</th>
    <th nowrap="nowrap">Nom et Prenom</th>
    <th nowrap="nowrap">Telephone</th>
    <th nowrap="nowrap">Specialisation</th>
    <th nowrap="nowrap">Qualification</th>
    <th nowrap="nowrap">Domaines</th>
    <th nowrap="nowrap">Localit&eacute;s</th>
  </tr>
  <?php $counter = 0; do { $counter++; ?>
  <tr>
    <?php           
	$showGoToPersonne = "show_rib.php?recordID=". $row_rsFichierExpert['personne_id'];  
	$showGoToPersonne2 = "show_domaine.php?recordID=". $row_rsFichierExpert['personne_id']; 
	$showGoToPersonne3 = "show_localite.php?recordID=". $row_rsFichierExpert['personne_id'];
	$showGoToPersonnes = "detail_personnes.php?menuID=". $_GET['menuID']."&action=".$_GET['action']."&recordID=".$row_rsAffichePerson['personne_id'];
	?>
    <td><a href="" > <?php echo $counter; ?></a></td>
    <td><a href="" onclick="<?php popup($showGoToPersonne, "700", "300"); ?>">RIB</a></td>
    <td><?php echo $row_rsFichierExpert['personne_nom'] . " " . $row_rsFichierExpert['personne_prenom']; ?>&nbsp; </td>
    <td><?php echo $row_rsFichierExpert['personne_telephone']; ?>&nbsp; </td>
    <td><?php echo $row_rsFichierExpert['personne_specialisation']; ?>&nbsp; </td>
    <td><?php echo $row_rsFichierExpert['personne_qualification']; ?>&nbsp; </td>
    <td><a href="" onclick="<?php popup($showGoToPersonne2, "700", "350"); ?>">Domaines</a></td>
    <td><a href="" onclick="<?php popup($showGoToPersonne3, "700", "400"); ?>">localites</a></td>
  </tr>
  <?php } while ($row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert)); ?>
</table>
<br />
<?php if (isset($_POST['txtChamp'])) { ?>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", 0, $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Premier</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", max(0, $pageNum_rsFichierExpert - 1), $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Pr&eacute;c&eacute;dent</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", min($totalPages_rsFichierExpert, $pageNum_rsFichierExpert + 1), $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Suivant</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", $totalPages_rsFichierExpert, $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Dernier</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>

<?php } else { ?>

<table border="0">
  <tr>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, 0, $queryString_rsFichierExpert); ?>">Premier</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, max(0, $pageNum_rsFichierExpert - 1), $queryString_rsFichierExpert); ?>">Pr&eacute;c&eacute;dent</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, min($totalPages_rsFichierExpert, $pageNum_rsFichierExpert + 1), $queryString_rsFichierExpert); ?>">Suivant</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, $totalPages_rsFichierExpert, $queryString_rsFichierExpert); ?>">Dernier</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
<?php } // End condition isset $_POST['txtChamp'] ?>
Enregistrements <?php echo ($startRow_rsFichierExpert + 1) ?> à <?php echo min($startRow_rsFichierExpert + $maxRows_rsFichierExpert, $totalRows_rsFichierExpert) ?> sur <?php echo $totalRows_rsFichierExpert ?>
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

mysql_free_result($rsFichierExpert);
?>
