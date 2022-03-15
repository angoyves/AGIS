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
	  
		$commissionIsEmpty = false;
		$personneIsUnique = true;
		$personneIsEmpty1 = false;
		$fonctionIsEmpty2 = false;
		$fonctionIsEmpty3 = false;
		$fonctionIsEmpty4 = false;
		$fonctionIsEmpty5 = false;
		$fonctionIsEmpty6 = false;
		$fonctionIsEmpty7 = false;	
		$fonctionIsEmpty = false;
		$numberInsertIsLow = false;	
		$sessionIsUnique = true;
		$date = date('Y-m-d H:i:s');
	  ?>
<?php

	$counter = 0; do { $counter++;
		if (isset($_POST['fonction_id'.$counter]) && $_POST['fonction_id'.$counter]==""){	
			$fonctionIsEmpty = true;		
		} 
	} while ($counter<$_POST['nombre_personne']);
	
	if ($_POST['commission_id']==""){	
		$commissionIsEmpty = true;
	}
	if ($_POST['numberInsert'] < 4){	
		$numberInsertIsLow = true;
	}

    $sessionID = MinmapDB::getInstance()->get_session_id_by_value($_POST['annee'], $_GET['moisID'], $_GET['comID']);
    if ($sessionID) {
        $sessionIsUnique = false;
    }
	
  $typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($_POST['commission_id']);
	
	// montant indemnite

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if (!$commissionIsEmpty && !$fonctionIsEmpty && !$numberInsertIsLow ){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user_name']));

//if (isset($theCommission_type_id) && $theCommission_type_id != 6) {

$insertSQL = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position, dateCreation, display, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($_POST['personne_id'], "int"),
					   GetMontantIndemnite($typeCommission_id),
					   GetSQLValueString("six", "text"),
                       GetSQLValueString(8, "int"),
					   GetSQLValueString($date, "date"),
                       GetSQLValueString(0, "text"),
					   GetSQLValueString($user_id, "int"));  } //}

 $updateSQL = sprintf("UPDATE commissions SET membre_insert=%s, dateUpdate=%s, user_id=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['membre_insert'], "text"),
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"),
                       GetSQLValueString($_POST['commission_id'], "int"));
 
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
 

$counter=0; do { $counter++;
if (isset($_POST['personne_id'.$counter]) && isset($_POST['fonction_id'.$counter])) {
	
 $insertSQL = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position, dateCreation, user_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'.$counter], "int"),
                       GetSQLValueString($_POST['personne_id'.$counter], "int"),
					   GetMontantMembre($typeCommission_id, GetSQLValueString($_POST['fonction_id'.$counter], "int")),
					   GetSQLValueString("un", "text"),
                       GetSQLValueString(1, "int"),
					   GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"));  }
 
  $updateSQL = sprintf("UPDATE personnes SET add_commission='2', dateUpdate=%s, user_id=%s WHERE personne_id=%s",
                       GetSQLValueString($date, "date"),
					   GetSQLValueString($user_id, "int"),
					   GetSQLValueString($_POST['personne_id'.$counter], "int"));
  
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  
} while ($counter<$_POST['nombre_personne']);

  $insertGoTo = "new_membres.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$currentPage = $_SERVER["PHP_SELF"];

if (isset($_POST['txt_search']) && $_POST['txt_search'] != "" ) { 
  $text_rsPersonne = $_POST['txt_search'];
  
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = sprintf("SELECT * FROM personnes WHERE personne_nom LIKE %s AND display = '1' ORDER BY personne_nom ASC", GetSQLValueString("%" . $text_rsPersonne . "%", "text"));
$rsPersonnes = mysql_query($query_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);
$totalRows_rsPersonnes = mysql_num_rows($rsPersonnes); 

} else {

$maxRows_rsPersonnes = 10;
$pageNum_rsPersonnes = 0;
if (isset($_GET['pageNum_rsPersonnes'])) {
  $pageNum_rsPersonnes = $_GET['pageNum_rsPersonnes'];
}
$startRow_rsPersonnes = $pageNum_rsPersonnes * $maxRows_rsPersonnes;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = sprintf("SELECT personne_id, personne_matricule, personne_nom, personne_prenom, add_commission, display FROM personnes WHERE display = '1' AND add_commission = '0' ORDER BY personne_nom ASC");	
	
$query_limit_rsPersonnes = sprintf("%s LIMIT %d, %d", $query_rsPersonnes, $startRow_rsPersonnes, $maxRows_rsPersonnes);
$rsPersonnes = mysql_query($query_limit_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);

if (isset($_GET['totalRows_rsPersonnes'])) {
  $totalRows_rsPersonnes = $_GET['totalRows_rsPersonnes'];
} else {
  $all_rsPersonnes = mysql_query($query_rsPersonnes);
  $totalRows_rsPersonnes = mysql_num_rows($all_rsPersonnes);
}
$totalPages_rsPersonnes = ceil($totalRows_rsPersonnes/$maxRows_rsPersonnes)-1;

}

/*mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnesAdd = "SELECT personnes.personne_id, personnes.personne_matricule, personne_nom, personne_prenom, add_commission, display, numero_compte, cle FROM personnes, rib WHERE rib.personne_id = personnes.personne_id AND add_commission = '1' AND display = '1' ORDER BY personne_nom ASC";
$rsPersonnesAdd = mysql_query($query_rsPersonnesAdd, $MyFileConnect) or die(mysql_error());
$row_rsPersonnesAdd = mysql_fetch_assoc($rsPersonnesAdd);
$totalRows_rsPersonnesAdd = mysql_num_rows($rsPersonnesAdd);*/

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelFonction = "SELECT fonction_id, fonction_lib FROM fonctions WHERE groupe_fonction_id = '3' AND display = '1' ORDER BY fonction_id ASC";
$rsSelFonction = mysql_query($query_rsSelFonction, $MyFileConnect) or die(mysql_error());
$row_rsSelFonction = mysql_fetch_assoc($rsSelFonction);
$totalRows_rsSelFonction = mysql_num_rows($rsSelFonction);

$colname_rsCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = sprintf("SELECT commission_id, natures.lib_nature, type_commission_lib, localite_lib, commission_lib FROM natures, type_commissions, localites, commissions WHERE commissions.nature_id = natures.nature_id AND commissions.type_commission_id = type_commissions.type_commission_id AND commissions.localite_id = localites.localite_id AND commissions.commission_id = %s", GetSQLValueString($colname_rsCommission, "int"));
$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
$row_rsCommission = mysql_fetch_assoc($rsCommission);
$totalRows_rsCommission = mysql_num_rows($rsCommission);

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

$queryString_rsPersonnes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPersonnes") == false && 
        stristr($param, "totalRows_rsPersonnes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPersonnes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPersonnes = sprintf("&totalRows_rsPersonnes=%d%s", $totalRows_rsPersonnes, $queryString_rsPersonnes); ?>
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
$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_SESSION['MM_Username']));
$colname_rsPersonnesAdd = "-1";
if (isset($user_id)) {
  $colname_rsPersonnesAdd = $user_id;
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnesAdd = sprintf("SELECT personnes.personne_id, personnes.personne_matricule, personne_nom, personne_prenom, add_commission, display, numero_compte, cle FROM personnes, rib WHERE rib.personne_id = personnes.personne_id AND add_commission = '1' AND display = '1' AND personnes.user_id = %s ORDER BY personne_nom ASC", GetSQLValueString($colname_rsPersonnesAdd, "int"));
$rsPersonnesAdd = mysql_query($query_rsPersonnesAdd, $MyFileConnect) or die(mysql_error());
$row_rsPersonnesAdd = mysql_fetch_assoc($rsPersonnesAdd);
$totalRows_rsPersonnesAdd = mysql_num_rows($rsPersonnesAdd);

?>
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
<h1>Ajouter des membres à une commission</h1>
      <table width="60%" border="1" align="center" class="std">
        <tr>
          <td colspan="5">
            <form id="form1" name="form1" method="post" action="">
              Rechercher :<input type="text" name="txt_search" id="txt_search" />
              <input type="submit" name="button" id="button" value="Rechercher" />
          </form></td>
        </tr>
        <?php if (!$sessionIsUnique) { ?>
        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />
        This Session already exists. Please check the spelling and try again</div>
        <?php   } ?>
        <?php if ($totalRows_rsPersonnes > 0) { // Show if recordset not empty ?>
        <tr>
          <th>ID</th>
          <th>Matricule</th>
          <th>Nom</th>
          <th>Prenom</th>
          <th>Ajouter</th>
        </tr>
        <?php $counter=0; do  { $counter++; ?>
        <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
          <td>      <?php           
	$showGoToPersonne = "detail_personnes.php?recordID=" . $row_rsPersonnes['personne_id'];
/*  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoToPersonne .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoToPersonne .= $_SERVER['QUERY_STRING'];
  }*/
?>
          <a href="#" onclick="<?php popup($showGoToPersonne, "700", "400"); ?>"> <img src="images/img/b_views.png" width="16" height="16" /></a></td>
          <td><?php if (isset($row_rsPersonnes['personne_matricule']) && $row_rsPersonnes['personne_matricule'] != "XXXXXX-X") {echo $row_rsPersonnes['personne_matricule']; } else {echo 'Expert'; } ?>&nbsp; </td>
          <td>
              
          <?php if (isset($_POST['txt_search']) && $_POST['txt_search'] != "" ) {echo str_ireplace($_POST['txt_search'],'<span style="background-color:yellow">'. strtolower($_POST['txt_search']) .'</span>',ucfirst(strtolower(htmlentities($row_rsPersonnes['personne_nom']))));
	  } else { echo ucfirst(strtolower(htmlentities($row_rsPersonnes['personne_nom'])));} ?>&nbsp; </td>
          <td><?php echo $row_rsPersonnes['personne_prenom']; ?>&nbsp; </td>
          <td align="center"><?php if (isset($row_rsPersonnes['add_commission'])) { ?>
              <?php if (isset ($_GET['comID'])) { ?>
              <a href="change_etat_index.php?comID=<?php echo $_GET['comID']; ?>&recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&map=personnes&amp;mapid=personne_id&amp;action=add_com&page=addMbr4&menuID=<?php echo $_GET['menuID']; ?>&uID=<?php echo $_SESSION['MM_Username']; ?>&uID=<?php echo $_SESSION['MM_Username']; ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
              <?php } else {?>
              <a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;mapid=personne_id&amp;action=add_com&page=addMbr4&menuID=<?php echo $_GET['menuID']; ?>&uID=<?php echo $_SESSION['MM_Username']; ?>&uID=<?php echo $_SESSION['MM_Username']; ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
              <?php } ?>
            <?php }  ?>
            &nbsp;
          </td>
            
        </tr>
        <?php } while ($row_rsPersonnes = mysql_fetch_assoc($rsPersonnes)); ?>
          <?php } else {// Show if recordset not empty ?>
          <tr>
            <td colspan="5"><table width="100%" border="1" align="left">
              <tr>
                <td colspan="5">Aucun resultat trouvé pour la recherche sur :<strong><?php echo $_POST['txt_search'] ?></strong></td>
              </tr>
          <?php } ?>
          <?php if (isset($_POST['button'])) { ?>
              <tr>
                <td colspan="5"><?php           
			$showGoTo = "sample21.php?txt=".$_POST['txt_search']."&uID=".$_SESSION['MM_Username'];
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?><a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">Enregistrer  une personne au fichier...<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>&nbsp;</td>
              </tr>
           <?php } ?>
            </table></td>
          </tr>

      </table>
      <br />
      <table border="0">
        <tr>
          <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, 0, $queryString_rsPersonnes); ?>">First</a>
          <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, max(0, $pageNum_rsPersonnes - 1), $queryString_rsPersonnes); ?>">Previous</a>
          <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, min($totalPages_rsPersonnes, $pageNum_rsPersonnes + 1), $queryString_rsPersonnes); ?>">Next</a>
          <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, $totalPages_rsPersonnes, $queryString_rsPersonnes); ?>">Last</a>
          <?php } // Show if not last page ?></td>
        </tr>
      </table>
      <p>Records <?php echo ($startRow_rsPersonnes + 1) ?> to <?php echo min($startRow_rsPersonnes + $maxRows_rsPersonnes, $totalRows_rsPersonnes) ?> of <?php echo $totalRows_rsPersonnes ?>
      </p>
      <table width="60%" border="1" align="center" class="std">
        <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
          <tr>
            <td colspan="8">Ajouter une commission
              <?php           
	$showGoToPersonne = "search_commissions.php";
/*  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoToPersonne .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoToPersonne .= $_SERVER['QUERY_STRING'];
  }*/
?>
              <a href="#" onclick="<?php popup($showGoToPersonne, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
              <strong>
              <?php if (isset($_GET['comID'])) { ?>
              <?php echo $row_rsCommission['type_commission_lib']; ?> de <?php echo $row_rsCommission['lib_nature']; ?> du (de)<?php echo $row_rsCommission['localite_lib']; ?>
              <?php } ?>
              </strong>
              <input name="commission_id" type="hidden" id="commission_id" value="<?php echo $row_rsCommission['commission_id']; ?>" />
              <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
              <div class="control"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
              <?php }
            ?>
              &nbsp;
              <?php
             /** Display error messages if the "password" field is empty */
            if ($fonctionIsEmpty) { ?>
              <div class="control"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />FONCTION (S) MANQUANTE (S)...Selectionnez une fonction pour tous les Personnes, SVP!</div>
              <?php }
            ?>
              <?php
             /** Display error messages if the "password" field is empty */
            if ($numberInsertIsLow) { ?>
              <div class="control"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />Le nombre de membre pour la cr&eacute;ation d'une commission doit etre sup&eacute;rieur ou &eacute;gale &agrave; 4...Ajoutez des membres SVP!</div>
            <?php }
            ?></td>
          </tr>
          <tr>
            <th>N&deg;</th>
            <th>RIB</th>
            <th>Nom</th>
            <th>Prenom</th>
            <th>Fonction</th>
            <th>&nbsp;</th>
          </tr>
          <?php $counter=0; do  { $counter++; ?>
          <?php if ($totalRows_rsPersonnesAdd > 0) { // Show if recordset not empty ?>
          <tr align="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
            <td nowrap><a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>"> <?php echo $counter; ?>
              <input type="hidden" name="<?php echo "personne_id" . $counter ?>" id="<?php echo "personne_id" . $counter ?>" value="<?php echo $row_rsPersonnesAdd['personne_id']; ?>"/>
            </a><a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
            <input type="hidden" name="personne_id" id="personne_id" value="2129"/>
            </a></td>
            <td nowrap><?php echo $row_rsPersonnesAdd['numero_compte'] . "-" . $row_rsPersonnesAdd['cle']; ?>&nbsp; </td>
            <td nowrap><?php echo strtoupper($row_rsPersonnesAdd['personne_nom']); ?>&nbsp; </td>
            <td nowrap><?php echo ucfirst($row_rsPersonnesAdd['personne_prenom']); ?>&nbsp; </td>
            <td nowrap> 
              <select name="<?php echo "fonction_id" . $counter ?>" id="<?php echo "fonction_id" . $counter ?>">
                <option value="">:: Select</option>
                <?php
do {  
?>
                <option value="<?php echo $row_rsSelFonction['fonction_id']?>" <?php if (!(strcmp($row_rsSelFonction['fonction_id'], htmlentities($_POST["fonction_id" . $counter], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsSelFonction['fonction_lib']); ?></option>
                <?php
} while ($row_rsSelFonction = mysql_fetch_assoc($rsSelFonction));
  $rows = mysql_num_rows($rsSelFonction);
  if($rows > 0) {
      mysql_data_seek($rsSelFonction, 0);
	  $row_rsSelFonction = mysql_fetch_assoc($rsSelFonction);
  }
?>
            </select>
              <a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
              <input type="hidden" name="fonction_id" id="fonction_id" value="41"/>
            </a>&nbsp;
            <input type="hidden" name="<?php echo "montant_id" . $counter ?>" id="<?php echo "personne_id" . $counter ?>" value="0" align="right"/>
            &nbsp;</td>
            <td nowrap>
              <?php if (isset ($_GET['comID'])) { ?> 
              <a href="change_etat_index.php?comID=<?php echo $_GET['comID'];?>&recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>&amp;map=personnes&amp;mapid=personne_id&amp;action=move_com&menuID=<?php echo $_GET['menuID']; ?>&uID=<?php echo $_SESSION['MM_Username']; ?>"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle" /></a>
              <?php } else { ?>
              <a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>&amp;map=personnes&amp;mapid=personne_id&amp;action=move_com&menuID=<?php echo $_GET['menuID']; ?>&uID=<?php echo $_SESSION['MM_Username']; ?>"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle" /></a>   
          <?php } ?>
          </tr>
          <?php } // Show if recordset not empty ?>
          <?php } while ($row_rsPersonnesAdd = mysql_fetch_assoc($rsPersonnesAdd)); ?>
          <input type="hidden" name="nombre_personne" id="nombre_personne" value="<?php echo $counter; ?>"/>
		  <tr>
            <td colspan="5">&nbsp;</td>
            <td><input type="submit" name="button2" id="button2" value="Enregistrer" /></td>
          </tr>
          <input type="hidden" name="numberInsert" value="<?php echo $totalRows_rsPersonnesAdd ?>" />
          <input type="hidden" name="membre_insert" value="1" />
          <input type="hidden" name="MM_insert" value="form1" />
        </form>
      </table>
      <br />
  <?php echo $totalRows_rsPersonnesAdd ?> Records Total
  <input type="hidden" name="user_name" value="<?php echo $_SESSION['MM_Username']; ?>" size="32" />
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
mysql_free_result($rsPersonnes);

mysql_free_result($rsPersonnesAdd);

mysql_free_result($rsSelFonction);

mysql_free_result($rsCommission);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>
