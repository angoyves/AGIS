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

$aID = $_REQUEST['aID'];
$comID = $_REQUEST['comID'];
$m1ID = $_REQUEST['m1ID'];
$m2ID = $_REQUEST['m2ID'];

$colname_rsAppurements = "-1";
if (isset($_GET['ovID'])) {
  $colname_rsAppurements = $_GET['ovID'];
}
$colname_rsAppurement = $_GET['txtSearch'];
if (isset($_POST['txtSearch'])) {
  $colname_rsAppurement = $_POST['txtSearch'];
}
$colname_rsAppurement2 = $_GET['txtChamp'];
if (isset($_POST['txtChamp'])) {
  $colname_rsAppurement2 = $_POST['txtChamp'];
}

	$colname_rsAppurement3 = (empty($_POST['txtOrder'])?"nom_beneficiaire":$_POST['txtOrder']);
	$txtOrder = (isset($_GET['txtOrder'])?$_GET['txtOrder']:"motif");
	$txtSearch = (isset($_POST['txtSearch'])?$_POST['txtSearch']:$_GET['txtSearch']);
	$txtChamp = (isset($_POST['txtChamp'])?$_POST['txtChamp']:$_GET['txtChamp']);
	$txtLimit = (isset($_POST['txtLimit'])?$_POST['txtLimit']:$_GET['txtLimit']);
	

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_POST['txtSearch']) || isset($_GET['txtSearch'])){
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE %s LIKE %s ORDER BY %s ASC LIMIT %s", $colname_rsAppurement2, GetSQLValueString("%" . $colname_rsAppurement . "%", "text"), $txtOrder, $txtLimit);
} else {
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE ref_dossier = %s ORDER BY %s ASC", GetSQLValueString($colname_rsAppurements, "text"), $txtOrder);
}
$rsAppurements = mysql_query($query_rsAppurements, $MyFileConnect) or die(mysql_error());
$row_rsAppurements = mysql_fetch_assoc($rsAppurements);
$totalRows_rsAppurements = mysql_num_rows($rsAppurements);
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
  <table border="0">
    <tr>
    <td><strong>Rechercher Par :</strong></td>
    <td><select name="txtChamp" id="txtChamp">
      <option value="motif" <?php if (!(strcmp("motif", $_POST['txtChamp']))) {echo "selected=\"selected\"";} ?>>Motif virement</option>
      <option value="nom_beneficiaire" <?php if (!(strcmp("nom_beneficiaire", $_POST['txtChamp']))) {echo "selected=\"selected\"";} ?>>Nom beneficiaire</option>
    </select></td>
    <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo $txtSearch; ?>" /></td>
    <td><select name="txt" id="txt">
    </select></td>
    <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
  </tr>
  <tr>
    <td colspan="5"><strong><?php echo $query_rsAppurements; ?></strong></td>
  </tr>
  <tr>
    <td colspan="5" align="right">Nombre d'enregistrements
      <select name="txtLimit" id="txtLimit">
        <option value="10" <?php if (!(strcmp(10, $txtLimit))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="30" <?php if (!(strcmp(30, $txtLimit))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="60" <?php if (!(strcmp(60, $txtLimit))) {echo "selected=\"selected\"";} ?>>60</option>
        <option value="100" <?php if (!(strcmp(100, $txtLimit))) {echo "selected=\"selected\"";} ?>>100</option>
      </select>
&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><?php if (isset($_POST['txtSearch'])) { ?>
      Enregistrement trouv&eacute; pour votre recherche &quot;<strong><?php echo $_POST['txtSearch']; ?></strong>&quot; dans <strong> <?php echo $_POST['txtChamp']; ?></strong>
      <?php } ?></td>
  </tr>
</table>
</form>
<?php
echo '<a href="view_appurements.php?ovID=' . trim($_REQUEST['ovID']) . '&comID=' . $comID . '&m1ID=' . $m1ID . '&m2ID=' . $m2ID . '&aID=' . $aID . '">Retour � l\'ecran precedent</a>'; ?>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>
      <p>
        <?php if (isset($txtOrder) && ($txtOrder == "nom_beneficiaire")){ ?>
        <a href="detail_ov.php?ovID=<?php echo $_GET['ovID'] ?>&txtChamp=<?php echo $txtChamp ?>&txtSearch=<?php echo $txtSearch ?>&amp;txtOrder=num_virement&txtLimit=<?php echo $txtLimit; ?>">N&deg; Virement</a>
        <?php } else { ?>
      N&deg; Virement
      <?php } ?>
      </p>
</th>
    <th>RIB Beneficiaire</th>

    <th><?php if (isset($txtOrder) && ($txtOrder == "num_virement")){ ?>
    <a href="detail_ov.php?ovID=<?php echo $_GET['ovID'] ?>&amp;txtChamp=<?php echo $txtChamp ?>&amp;txtSearch=<?php echo $txtSearch ?>&amp;txtOrder=nom_beneficiaire&txtLimit=<?php echo $txtLimit; ?>">Nom Beneficiaire</a>
      <?php } else { ?>
Nom Beneficiaire
<?php } ?></th>
    <th>Ref Dossier</th>
    <th>Motif Paiement</th>
    <th>Montant</th>
    <th>S*</th>
    <th>RIB</th>
  </tr>
  <?php $counter=0; $i = 0; do { $i++; $counter++;?>
  <?php 
  		$tatAppurement = MinmapDB::getInstance()->verify_appurement_credentials_2($comID, $row_rsAppurements['rib_beneficiaire'], $m1ID, $m2ID, $aID); 
  		$etatRib = MinmapDB::getInstance()->verify_rib_credentials($row_rsAppurements['rib_beneficiaire']); 
  ?>
  
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>"> 	
      <td><a href="detail_appurements.php?recordID=<?php echo $row_rsAppurements['appurement_id']; ?>"> <?php echo $i; ?>&nbsp; </a></td>
      <td><?php echo $row_rsAppurements['num_virement']; ?>&nbsp; </td>
      <td><?php echo substr($row_rsAppurements['rib_beneficiaire'],0,5) . '-' . substr($row_rsAppurements['rib_beneficiaire'],5,5) . '-' . substr($row_rsAppurements['rib_beneficiaire'],10,11).'-' . substr($row_rsAppurements['rib_beneficiaire'],21,2); ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['nom_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['ref_dossier']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['motif']; ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['montant'],0,' ',' '); ?>&nbsp; </td>
      <td align="center">
      <?php if ($tatAppurement) {?>
        <img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/>
      <?php } else { ?>
      <?php           
			$showGoTo = "search_prson.php?pID=".trim($row_rsAppurements['nom_beneficiaire'])."&rID=".trim($row_rsAppurements['rib_beneficiaire'])."&modID=2";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
            <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
        
      <?php } ?>&nbsp;
	  </td>
      <td align="center"><?php if ($etatRib) {?>
        <img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/>
        <?php } else { ?>
        <?php           
			$showGoTo = "search_prson.php?pID=".trim($row_rsAppurements['nom_beneficiaire'])."&rID=".trim($row_rsAppurements['rib_beneficiaire'])."&modID=2";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } ?>
      &nbsp; </td>
    </tr>
    <?php } while ($row_rsAppurements = mysql_fetch_assoc($rsAppurements)); ?>
    <tr>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>

    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th colspan="2">&nbsp;</th>
  </tr>
</table>
<strong><?php echo $totalRows_rsAppurements ?></strong> Enregistrements Total </br><strong>Legende</strong> : 
<strong>S*</strong>= Session Existantante? <strong>RIB</strong> = Releve d'Identit&eacute; Bancaire existante?<br /> 
NB : Cliquez sur<a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a> pour ajouter le RIB </br>
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
mysql_free_result($rsAppurements);
?>
