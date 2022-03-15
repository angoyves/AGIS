<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('includes/MyFonction.php'); ?>
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

$maxRows_rsAffichePerson = 10;
$pageNum_rsAffichePerson = 0;
if (isset($_GET['pageNum_rsAffichePerson'])) {
  $pageNum_rsAffichePerson = $_GET['pageNum_rsAffichePerson'];
}
$startRow_rsAffichePerson = $pageNum_rsAffichePerson * $maxRows_rsAffichePerson;

$colname_rsPersonne = "nom";
if (isset($_POST['TxtChamp'])) {
  $colname_rsPersonne = $_POST['TxtChamp'];
}
$text_rsPersonne = "m";
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_POST['TxtSearch']))
{
$text_rsPersonne = $_POST['TxtSearch'];	
$query_rsAffichePerson = sprintf("SELECT * FROM personnes WHERE $colname_rsPersonne LIKE %s AND personne_matricule <> 'xxxxxx-x' AND display = '1' ORDER BY personne_nom ASC", GetSQLValueString("%" . $text_rsPersonne . "%", "text"));
} else {
$query_rsAffichePerson = "SELECT * FROM personnes WHERE personne_matricule <> 'xxxxxx-x' AND display = '1' ORDER BY date_creation DESC";
}
$query_limit_rsAffichePerson = sprintf("%s LIMIT %d, %d", $query_rsAffichePerson, $startRow_rsAffichePerson, $maxRows_rsAffichePerson);
$rsAffichePerson = mysql_query($query_limit_rsAffichePerson, $MyFileConnect) or die(mysql_error());
$row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson);
if (isset($_GET['totalRows_rsAffichePerson'])) {
  $totalRows_rsAffichePerson = $_GET['totalRows_rsAffichePerson'];
} else {
  $all_rsAffichePerson = mysql_query($query_rsAffichePerson);
  $totalRows_rsAffichePerson = mysql_num_rows($all_rsAffichePerson);
}
$totalPages_rsAffichePerson = ceil($totalRows_rsAffichePerson/$maxRows_rsAffichePerson)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowPersonInactiv = "SELECT * FROM personnes WHERE personne_matricule <> 'xxxxxx-x' AND display = '0' ORDER BY dateUpdate DESC";
$rsShowPersonInactiv = mysql_query($query_rsShowPersonInactiv, $MyFileConnect) or die(mysql_error());
$row_rsShowPersonInactiv = mysql_fetch_assoc($rsShowPersonInactiv);
$totalRows_rsShowPersonInactiv = mysql_num_rows($rsShowPersonInactiv);

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

$queryString_rsAffichePerson = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAffichePerson") == false && 
        stristr($param, "totalRows_rsAffichePerson") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAffichePerson = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAffichePerson = sprintf("&totalRows_rsAffichePerson=%d%s", $totalRows_rsAffichePerson, $queryString_rsAffichePerson);
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
<meta name="description" content="Le minist�re des Marches Publics du Cameroun">
<meta name="keywords" content="Abba SADOU, ministre, Marches, Minist�re des Marches Publics, ">
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
        window.location="<?php echo $logoutInactiv ?>"; //page de d�connexion
    }
	function window_onbeforeunload()
    {
       window.navigate('<?php echo $logoutInactiv ?>'); 
       //ne pas oublier de pr�ciser le chemin si vous mettez la page dans un autre r�pertoire
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
      <div id="logo"><img src="images/img/v2/amoirie.gif" alt="armoirie" width="78" height="52"><a href="#" target="_top"><img src="images/img/v2/flag.gif" alt="Minist�re des Finances et du Budget" width="89" border="0" height="52" hspace="2"></a>
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
    	    <td><a href="<?php echo $logoutAction ?>"><strong>Se d�connecter</strong></a></td>
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
				Hausse du ch�mage au 4e trimestre 2008 &agrave; 7,8  %</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>26 f&eacute;vrier 2009</span><br>
				L'�tat accompagne la naissance du 2e groupe bancaire fran�ais</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<a href="#" class="rss">Flux RSS</a>
				<div class="clear-both"></div>
			</div-->
      <!--div class="bloc">
				<a href="http://www.entreprises.gouv.fr/jeunesactifs/index.html" target="_blank"><img src="http://www.entreprises.gouv.fr/jeunesactifs/repository/images/embauche_250x250.gif" alt="Mesures jeunes actifs" width="180" height="180" border="0" title="Mesures jeunes actifs"></a>
			</div>
			<div class="bloc">
				<a href="http://www.nosentreprisesnosemplois.gouv.fr/" title="www.nosentreprisesnosemplois.gouv.fr nouvelle fen�tre" target="_blank"><img src="/images/actu/agir-entreprises180X150.gif" alt="Agir pour nos entreprises, c'est agir pour l'emploi" width="180" height="150" border="0" title="Agir pour nos entreprises, c'est agir pour l'emploi"></a>
			</div>
			<div class="bloc">
				<a href="http://www.prix-carburants.gouv.fr" title="Le site des prix des carburant nouvelle fen�tre" target="_blank"><img src="/images/actu/prixcarburants.jpg" alt="Le site des prix des carburant" width="180" height="130" border="0" title="Le site des prix des carburant"></a>
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
      <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand�mie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand�mie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pand�mie grippale" width="180" height="150" border="0"></a> </div> -->

	 
	  <!--/#tag_cloud-->

    </div>
    <!-- InstanceBeginEditable name="EditBoby" -->
<form id="form1" name="form1" method="post" action="">
  <table border="0" class="std">
  <tr>
    <td><strong>Rechercher Par :</strong></td>
    <td><select name="TxtChamp" id="TxtChamp">
      <option value="personne_nom">Nom</option>
      <option value="personne_prenom">Prenom</option>
      <option value="rib">RIB</option>
      <option value="personne_matricule">Matricule</option>
      <option value="telephone">N� T�l�phone</option>
    </select></td>
    <td><input type="text" name="TxtSearch" id="TxtSearch" /></td>
    <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
  </tr>
  <tr>
    <td colspan="4"><?php if (isset($_POST['TxtSearch'])) { ?>
      Enregistrement trouv� pour votre recherche &quot; <strong> <?php echo $_POST['TxtSearch']; ?></strong> &quot; dans <strong> <?php echo $_POST['TxtChamp']; ?></strong>
      <?php } ?></td>
  </tr>
</table>
</form>
<h1>
  <?php if ($totalRows_rsAffichePerson> 0) { // Show if recordset not empty ?>
Personnes actives</h1>
  <table width="75%" border="1" align="center" class="std">
    <tr>
      <th>N�</th>
      <th>Rib</th>
      <th>Matricule</th>
      <th>Nom</th>
      <th>Prenom</th>
      <th>Grade</th>
      <th>Telephone</th>
      <th>Actif</th>
      <?php if (isset($_GET['action']) && $_GET['action']=='upd') { ?>
      <th>Edit</th>
     <?php } ?>
      <?php if (isset($_GET['action']) && $_GET['action']=='del') { ?>
      <th>Del</th>
     <?php } ?>
      <th>Show</th>
    </tr>
    <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td nowrap="nowrap"><?php echo $counter ?></td>
      <td nowrap="nowrap"><a href="detail_person.php?menuID=<?php echo $_GET['menuID'];?>&&action=<?php echo $_GET['action'];?>&&recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>">RIB&nbsp; </a></td>
      <td nowrap="nowrap"><?php echo $row_rsAffichePerson['personne_matricule']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo htmlentities($row_rsAffichePerson['personne_nom']); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsAffichePerson['personne_prenom']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo htmlentities($row_rsAffichePerson['personne_grade']); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsAffichePerson['personne_telephone']; ?>&nbsp; </td>
      <td align="center" nowrap="nowrap"><?php if (isset($row_rsAffichePerson['display']) && $row_rsAffichePerson['display'] == '1') { ?>
        <a href="change_etat_index.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes&&mapid=personne_id&&action=desactive"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } else { ?>
        <a href="change_etat_index.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes&&mapid=personne_id&&action=active"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } ?></td>
      <?php if (isset($_GET['action']) && $_GET['action']=='upd') { ?>
      <td align="center" nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td> 
      <?php } ?>
      <?php if (isset($_GET['action']) && $_GET['action']=='del') { ?>
      <td align="center" nowrap="nowrap"><a href="delete.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes&&mapid=personne_id"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <?php } ?>
      <td align="center" nowrap="nowrap"><a href="detail_personnes.php?menuID=<?php echo $_GET['menuID'];?>&&action=<?php echo $_GET['action'];?>&&recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle"/></a></td>

    </tr>
    <?php } while ($row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson)); ?>
</table>
  <?php } else { ?>
<table width="570" border="0" cellpadding="0" cellspacing="0" class="std">
    <tr class="TabAloneWhite">
      <th width="494" height="18">R�sultat de la recherche</th>
</tr>
    <tr bgcolor="#FFFFFF">
      <td height="36"><img src="images/img/s_attention.png" width="16" height="16" /> Aucun enregistrement trouv� pour votre recherche &quot; <strong> <?php echo $_POST['TxtSearch']; ?></strong> &quot; dans <strong> <?php echo $_POST['TxtChamp']; ?></strong></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="36"><p>Saisissez � nouveau votre recherche...</p></td>
    </tr>
</table>
  <?php }// Show if recordset not empty ?>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsAffichePerson > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, 0, $queryString_rsAffichePerson); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAffichePerson > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, max(0, $pageNum_rsAffichePerson - 1), $queryString_rsAffichePerson); ?>">Pr�c�dent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAffichePerson < $totalPages_rsAffichePerson) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, min($totalPages_rsAffichePerson, $pageNum_rsAffichePerson + 1), $queryString_rsAffichePerson); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsAffichePerson < $totalPages_rsAffichePerson) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, $totalPages_rsAffichePerson, $queryString_rsAffichePerson); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<p>Enregistrements <?php echo ($startRow_rsAffichePerson + 1) ?> � <?php echo min($startRow_rsAffichePerson + $maxRows_rsAffichePerson, $totalRows_rsAffichePerson) ?> sur <?php echo $totalRows_rsAffichePerson ?>
</p>
<?php if ($totalRows_rsShowPersonInactiv > 0) { // Show if recordset not empty ?>
<h1>Personnes � l'etat desactiv�</h1>
  <table width="75%" border="1" align="center" class="std">
    <tr>
      <th scope="col">N�</th>
      <th scope="col">RIB</th>
      <th scope="col">MATRICULE</th>
      <th scope="col">NOM</th>
      <th scope="col">PRENOM</th>
      <th scope="col">GRADE</th>
      <th scope="col">TELEPHONE</th>
      <th align="center" scope="col">Etat</th>
      <?php if (isset($_GET['action']) && $_GET['action']=='upd') { ?>
      <th align="center" scope="col">Edit</th>
      <?php }  ?>
      <?php if (isset($_GET['action']) && $_GET['action']=='del') { ?>
      <th align="center" scope="col">Delete</th>
      <?php }  ?>
      <th align="center" scope="col">Show</th>
    </tr>
<?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><?php echo $counter ?></td>
      <td><a href="detail_person.php?menuID=<?php echo $_GET['menuID'];?>&&action=<?php echo $_GET['action'];?>&&menuID=<?php echo $_GET['menuID'];?>&&action=<?php echo $_GET['action'];?>&&recordID=<?php echo $row_rsShowPersonInactiv['personne_id']; ?>">RIB</a>&nbsp;</td>
      <td><?php echo $row_rsShowPersonInactiv['personne_matricule']; ?></td>
      <td><?php echo $row_rsShowPersonInactiv['personne_nom']; ?></td>
      <td><?php echo $row_rsShowPersonInactiv['personne_prenom']; ?></td>
      <td><?php echo $row_rsShowPersonInactiv['personne_grade']; ?></td>
      <td><?php echo $row_rsShowPersonInactiv['personne_telephone']; ?></td>
      <td align="center"><?php if (isset($row_rsShowPersonInactiv['display']) && $row_rsShowPersonInactiv['display'] == '1') { ?>
        <a href="change_etat_index.php?recordID=<?php echo $row_rsShowPersonInactiv['personne_id']; ?>&amp;&amp;map=personnes&amp;&amp;mapid=personne_id&amp;&amp;action=desactive"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } else { ?>
        <a href="change_etat_index.php?recordID=<?php echo $row_rsShowPersonInactiv['personne_id']; ?>&amp;&amp;map=personnes&amp;&amp;mapid=personne_id&amp;&amp;action=active"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php } ?></td>
      <?php if (isset($_GET['action']) && $_GET['action']=='upd') { ?>
      <td><a href="maj_personnes.php?recordID=<?php echo $row_rsShowPersonInactiv['personne_id']; ?>&amp;&amp;map=personnes"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <?php } ?>
      <?php if (isset($_GET['action']) && $_GET['action']=='del') { ?>
      <td><a href="delete.php?recordID=<?php echo $row_rsShowPersonInactiv['personne_id']; ?>&amp;&amp;map=personnes&amp;&amp;mapid=personne_id"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <?php } ?>
      <td><a href="detail_personnes.php"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <?php } while ($row_rsShowPersonInactiv = mysql_fetch_assoc($rsShowPersonInactiv)); ?>
  </table>
<?php } // Show if recordset not empty ?>
<!-- InstanceEndEditable -->
    <!--div class="bloc express">
				<h4>Informations express</h4>
				<p><span>9 mars 2009</span><br>
				Observatoire des prix et des marges : mise en place d'outils op&eacute;rationnels de suivi des marges</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>5 mars 2009</span><br>
				Hausse du ch�mage au 4e trimestre 2008 &agrave; 7,8  %</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<hr>
				<p><span>26 f&eacute;vrier 2009</span><br>
				L'�tat accompagne la naissance du 2e groupe bancaire fran�ais</p>
				<a href="#" class="tous_les">Le communiqu&eacute;</a>
				<a href="#" class="rss">Flux RSS</a>
				<div class="clear-both"></div>
			</div-->
    <!--div class="bloc">
				<a href="http://www.entreprises.gouv.fr/jeunesactifs/index.html" target="_blank"><img src="http://www.entreprises.gouv.fr/jeunesactifs/repository/images/embauche_250x250.gif" alt="Mesures jeunes actifs" width="180" height="180" border="0" title="Mesures jeunes actifs"></a>
			</div>
			<div class="bloc">
				<a href="http://www.nosentreprisesnosemplois.gouv.fr/" title="www.nosentreprisesnosemplois.gouv.fr nouvelle fen�tre" target="_blank"><img src="/images/actu/agir-entreprises180X150.gif" alt="Agir pour nos entreprises, c'est agir pour l'emploi" width="180" height="150" border="0" title="Agir pour nos entreprises, c'est agir pour l'emploi"></a>
			</div>
			<div class="bloc">
				<a href="http://www.prix-carburants.gouv.fr" title="Le site des prix des carburant nouvelle fen�tre" target="_blank"><img src="/images/actu/prixcarburants.jpg" alt="Le site des prix des carburant" width="180" height="130" border="0" title="Le site des prix des carburant"></a>
			</div-->
    <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand�mie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand�mie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pand�mie grippale" width="180" height="150" border="0"></a> </div> -->

	 
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
mysql_free_result($rsAffichePerson);

mysql_free_result($rsShowStructure);

mysql_free_result($rsShowSousGroupe);

mysql_free_result($rsShowFonction);

mysql_free_result($rsShowTypePersonne);

mysql_free_result($rsShowPersonInactiv);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>
