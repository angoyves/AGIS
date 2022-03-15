<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php 
$dossierIsEmpty = false;
$anneeIsEmpty = false;
$moisIsEmpty = false;
//$logonSuccess = false;
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

if ($_POST['annee']=="" ){
        $anneeIsEmpty = true;
    }
	
if ($_POST['nombre_dossier']==""){
    	$dossierIsEmpty = true;
    }
if ($_GET['moisID']==""){
    	$moisIsEmpty = true;
    }

$DATE = date('Y-m-d H:i:s');


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$commission = $_POST['commission_id'];
$month = $_POST['mois'];
$year = $_POST['annee'];
// debut controle si un enregistrement de ce mois existe dans la base
$logonSuccess = (MinmapDB::getInstance()->verify_session_credentials($commission, $month, $year));
// debut controle de validit� des controles
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && !$logonSuccess && !$anneeIsEmpty && !$dossierIsEmpty && !$moisIsEmpty) {
	//debut de la boucle
$x = 0; $count = 0; $variable = 0; do { $x++;
// debut boucle
//if ((isset($_GET['valid']))&& ($_GET['valid']=='mo') && isset($_POST['jour'.$x]) && $_POST['jour'.$x] !="") {
if ((isset($_GET['valid']))&& ($_GET['valid']=='mb') && (isset($_POST['jour'.$x])) && ($_POST['jour'.$x] !="")) {
	//controle si l'enregistrement est � mo (maitre d'ouvrage) ou � mb (membre)

// ici on compare les valeurs pour assigner la plus grande valeur � la variable indemnit� minmap
if ($count<=count($_POST['jour'.$x])){
	$variable = implode( "**", $_POST['jour'.$x] );
	$count = count($_POST['jour'.$x]); }
//debut insertion des valeurs du formulaire
$insertSQL = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString(count($_POST['jour'.$x]), "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id'.$x], "int"),
                       GetSQLValueString($_POST['fonction_id'.$x], "int"),
                       GetSQLValueString(implode( "**", $_POST['jour'.$x] ), "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
}
//fin boucle insertion
} while ($x < $_POST['counter']);

// debut insertion Indemnite MINMAP
if ((isset($_GET['valid']))&& ($_GET['valid']=='mb') && $count != 0) {
//if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && $count != 0) {
$insertSQL1 = sprintf("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
					   GetSQLValueString($count, "int"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['personne_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($variable, "text"),
                       GetSQLValueString($DATE, "date"),
                       GetSQLValueString($_POST['display'], "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect); 
$Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error()); }
// fin insertion et debut redirection

  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));

}

$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_GET['valid']) && $_GET['valid']== "mo") {
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id 
FROM commissions, membres, fonctions, personnes 
WHERE membres.commissions_commission_id = commissions.commission_id  
AND fonctions_fonction_id = fonctions.fonction_id  
AND personnes_personne_id = personne_id 
AND membres.fonctions_fonction_id <> 1
AND membres.fonctions_fonction_id <> 2
AND membres.fonctions_fonction_id <> 3
AND personnes.display = 1 
AND commissions_commission_id = %s 
AND membres.display = 1
ORDER BY fonctions_fonction_id", GetSQLValueString($colname_rsMembres, "int"));
} else {
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id 
FROM commissions, membres, fonctions, personnes 
WHERE membres.commissions_commission_id = commissions.commission_id  
AND fonctions_fonction_id = fonctions.fonction_id  
AND personnes_personne_id = personne_id 
AND membres.fonctions_fonction_id <> 4
AND membres.fonctions_fonction_id <> 5
AND membres.fonctions_fonction_id <> 40
AND personnes.display = 1 
AND commissions_commission_id = %s 
ORDER BY fonctions_fonction_id", GetSQLValueString($colname_rsMembres, "int"));
	}
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsJourMois = "-1";
if (isset($_GET['moisID'])) {
  $colname_rsJourMois = $_GET['moisID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois ORDER BY mois_id ASC";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

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
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);
?>
<?php 
switch ($_GET['moisID']){
case 01 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 02 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');break;
case 03 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 04 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 05 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 06 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 07 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 8 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 9 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 10 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 11 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 12 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr"><!-- InstanceBegin template="/Templates/FilesTemplates.php" codeOutsideHTMLIsLocked="false" -->
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<link href="css/file.css" rel="stylesheet" type="text/css">
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
<meta name="description" content="Le minist�re des Finances et du Budget et met en �uvre les politiques du Gouvernement en mati�re  financi�re.">
<meta name="keywords" content="essimi menye, ministre, Finances, Industrie, Ministre Industrie, Minist�re des Finances, consommation, entreprises, PME, TPE, finances, Finances Cameroun">
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
        
          <li id="accueil"><a href="<?php echo htmlentities($row_rsMenu['lien']); ?>"><?php echo htmlentities($row_rsMenu['menu_lib']); ?></a></li>
        <?php } while ($row_rsMenu = mysql_fetch_assoc($rsMenu)); ?>
        </ul>
      </div>
      <!--/.menu-->
    </div>
    <!--/#header-->
    <!--/b002n1-->
    <div class="col_droite">
      <div class="bloc express">   <h4>Session active</h4>
<p>Connect&eacute; :</p>

<p>&nbsp;</p><div class="clear-both"></div>   </div>
      <div class="bloc lois_recentes">
        <h4>Sous Menu</h4>
        <ul class="tous_les">
    <?php do { ?>
	<a href="<?php echo $row_rsSousMenu['sous_menu_lien']; ?>?menuID=<?php echo $_GET['menuID']; ?>&&action=<?php echo $row_rsSousMenu['action']; ?>"><img src="images/img/		<?php echo $row_rsSousMenu['image']; ?>" width="16" height="16" align="middle" /><?php echo htmlentities($row_rsSousMenu['sous_menu_lib']); ?></a>&nbsp;</BR>
      <?php } while ($row_rsSousMenu = mysql_fetch_assoc($rsSousMenu)); ?>
        </ul>
      </div>
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
        <div class="bloc express">
          <h4><strong>Mon profil</strong></h4>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        </div>
        <div class="bloc express">
          <h4><strong>Statistiques</strong></h4>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
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
        <p>&nbsp;</p>
        <p>&nbsp;</p>
      </div>
      <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand�mie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pand�mie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pand�mie grippale" width="180" height="150" border="0"></a> </div> -->

	 
	  <!--/#tag_cloud-->

    </div>
    <!-- InstanceBeginEditable name="EditBoby" -->
    <h1>Enregistrer une session</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table width="75%" border="0" align="center">
    <tr>
      <td><table width="40%" align="left" class="std2">
      <tr valign="baseline">
        <th nowrap="nowrap" align="right">Mois:</th>
        <td><select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
          <option value="" <?php if (!(strcmp("", $_GET['moisID']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
          <?php
do {  
?>
          <option value="sample16.php?comID=<?php echo $_GET['comID'] ?>&amp;menuID=<?php echo $_GET['menuID'] ?>&amp;moisID=<?php echo $row_rsMois['mois_id']?>&amp;valid=<?php echo $_GET['valid'] ?>"<?php if (!(strcmp($row_rsMois['mois_id'], $_GET['moisID']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois['lib_mois'])?></option>
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
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" /><blink>Select month, please</blink></div>
          <?php  } ?></td>
        <th align="right">Annee :</th>
        <td><input name="annee" type="hidden" size="15" value="<?php if ( isset($_POST['annee'])) { echo $_POST['annee'];} ?>" />
          <select name="annee2">
            <option value="" <?php if (!(strcmp("", $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>>Select:::</option>
            <?php do {  ?>
            <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($_POST['annee2'], $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>>
			<?php echo $row_rsAnnee['lib_annee']?>
            </option>
            <?php } while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee)); ?>
          </select>
          <?php
             /** Display error messages if the "password" field is empty */
       if ($anneeIsEmpty) { ?>
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Enter the YEAR, please</div>
          <?php  } ?>
&nbsp;</td>
      </tr>
    </table></td>
    </tr>
    <tr>
      <td>
<table width="75%" border="1" align="left" class="std">
      <tr valign="baseline">
        <td colspan="4" nowrap="nowrap">Selectionner  une commission
          <?php           
	$showGoTo = "search_commissions2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
          <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
          <strong>
            <?php if (isset($_GET['comID'])) { ?>
            <?php echo $row_rsCommissions['commission_lib']; ?>
            <?php } ?>
            </strong>
          <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
          <?php }
            ?>
          <input type="hidden" name="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" /></td>
      </tr>
      <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Nature</th>
        <th>Localite</th>
      </tr>
      <tr>
        <td nowrap="nowrap"><img src="images/img/b_views.png" alt="" width="16" height="16" /></td>
        <td nowrap="nowrap"><?php echo $row_rsCommissions['type_commission_lib']; ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsCommissions['lib_nature']; ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsCommissions['localite_lib']; ?>&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td colspan="4" align="right" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoTo2, "610", "300"); ?>">
          <input type="hidden" name="mois" value="<?php if ( isset($_GET['moisID'])) { echo $_GET['moisID'];} ?>" size="32" />
          <input type="hidden" name="jour" value="" />
          <input type="hidden" name="dossier_id" value="3" />
        </a></td>
      </tr>
    </table>

      
      </td>
    </tr>
    <tr>
      <td><table width="50%" border="1" align="center" class="std">
        <tr>
          <th colspan="3">Jours
          <input type="hidden" name="nbre_jours" value="<?php if ( isset($row_rsJourMois['nbre_jour'])) { echo $row_rsJourMois['nbre_jour'];} ?>" size="32" />
          </th>
          <?php $counter = 0; do { $counter++; ?>
          <th width="37" align="center"><?php echo $counter ?>
            <input type="hidden" name="<?php echo "journee".$counter ?>" value="<?php echo $counter ?>"/>&nbsp;</th>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <tr>
          <td colspan="3"><p>Nombre</p></td>
          <?php 
	foreach($HTTP_POST_VARS['nombre_dossier'] as $value){ 
		$nombre_dossier .= $value."**"; //Concat�ne les ID car un agent peut recevoir plusieurs docsuments � la fois
		$docCount++;
	}
		  foreach($list as $value){ 
		    $listes = array('1','2','3','4','5','6','7','8','9');
		    $liste = implode('*',$_POST['nombre_dossier']);
			$values = explode('*',$liste);
		  ?>
          <td><?php echo $nombre_dossier ?><select name="<?php echo "nombre_dossier[]" ?>">
          	   <option value=""></option>
          	   <?php $var = 0; do { $var++;
			         echo '<option value="' . $var . '"' ?>
                <?php echo '>'.$var.'</option>';
			         } while ($var < 9);
			   ?>
          </select></td>
          <?php }//while ($countr < $row_rsJourMois['nbre_jour']);
	?>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="1" align="center" class="std">
        <tr>
          <th>ID</th>
          <th>Noms et Prenoms</th>
          <th>Fonctions</th>
          <?php $counter = 0; do { $counter++; ?>
          <th align="center"><?php echo $counter ?>
            <input type="hidden" name="<?php echo "jour_member".$counter ?>2" value="<?php echo $counter ?>"/>&nbsp;</th>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <?php $compter = 0; do { $compter++; ?>
        <tr>
          <td><a href="show_personnes.php?txtChamp=personne_id&txtSearch=<?php echo $row_rsMembres['personne_id']; ?>&menuID=2&action=upd" onclick="<?php popup($showGoToPersonne, "700", "300"); ?>"> <?php echo $compter; ?></a>&nbsp;</td>
          <td><?php echo $row_rsMembres['personne_nom'] . " " . $row_rsMembres['personne_prenom']; ?>
            <input type="hidden" name="<?php echo "personne_id".$compter; ?>" value="<?php echo $row_rsMembres['personne_id']; ?>" size="32" />
            <input type="hidden" name="personne_id" id="personne_id" value="2129"/>
            </td>
          <td><?php echo $row_rsMembres['fonction_lib']; ?>&nbsp;
            <input type="hidden" name="<?php echo "fonction_id".$compter; ?>" value="<?php echo $row_rsMembres['fonctions_fonction_id']; ?>" size="32" />
&nbsp;<a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
<input type="hidden" name="fonction_id" id="fonction_id" value="41"/>
</a></td>
          <?php //$counter = 0; do { $counter++;
			foreach($list as $value){
			switch($compter){
			case $compter :
			$liste = $_POST['jour'.$compter];
		  ?>          
          <td><input type="checkbox" name="<?php echo "jour" . $compter . "[]" ?>" id="<?php echo "jour". $compter . $value ?>" value="<?php echo $value ?>"  
		  <?php echo ((in_array($value, $liste))? "checked='checked'":"") ?> />
          </td>
          <?php break; } } ?>
          <?php //} while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
        
        <input type="hidden" name="nombre_personne" id="nombre_personne" value="<?php echo $compter ?>"/>
      </table>
      <table border="0">
        <tr>
          <td height="35"><?php if ($pageNum_rsMembres > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, 0, $queryString_rsMembres); ?>">Premier</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsMembres > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, max(0, $pageNum_rsMembres - 1), $queryString_rsMembres); ?>">Pr&eacute;c&eacute;dent</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsMembres < $totalPages_rsMembres) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, min($totalPages_rsMembres, $pageNum_rsMembres + 1), $queryString_rsMembres); ?>">Suivant</a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_rsMembres < $totalPages_rsMembres) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, $totalPages_rsMembres, $queryString_rsMembres); ?>">Dernier</a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table></td>
    </tr>
    <tr>
      <td><table border="1" align="center" class="std">
        <tr>
          <td colspan="<?php echo ($row_rsJourMois['nbre_jour']+4) ?>"><?php           
			$showGoTo = "sample14.php";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
            <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">Ajouter un representant...<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>&nbsp;
            <?php           
			$showGoTo = "sample14.php";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
            <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">Rechercher un repr&eacute;sentant...<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>&nbsp;<strong>&nbsp;</strong><strong>&nbsp;</strong></td>
          </tr>
        <tr>
          <th><strong>ID</strong></th>
          <th><strong>Noms et Prenoms</strong></th>
          <th><strong>Structures</strong></th>
          <?php $counter = 0; do { $counter++; ?>
          <th><strong><?php echo $counter ?>
            <input type="hidden" name="<?php echo "jour_rep".$counter ?>2" value="<?php echo $counter ?>" size="32" />
            &nbsp;</strong></th>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
          <th>&nbsp;</th>
        </tr>
        <?php $count = 0; do { $count++; ?>
		<?php if ($totalRows_rsRepresentant > 0) { // Show if recordset not empty ?>
        <tr>
          <td><a href="show_personnes.php?txtChamp=personne_id&txtSearch=<?php echo $row_rsRepresentant['personne_id']; ?>&menuID=2&action=upd"> <?php echo $count; ?></a>&nbsp; </td>
          <td><?php echo $row_rsRepresentant['personne_nom'] . " " . $row_rsRepresentant['personne_prenom']; ?>
          <input type="hidden" name="<?php echo "representant_id".$count; ?>" value="<?php echo $row_rsRepresentant['personne_id']; ?>" />&nbsp; </td>
          <td> <?php echo $row_rsRepresentant['code_structure']; ?>
            <input type="hidden" name="<?php echo "representant_fonction_id".$count; ?>" value="<?php echo $row_rsRepresentant['fonctions_fonction_id']; ?>" />            &nbsp; </td>
          <?php //$counter = 0; do { $counter++;
			foreach($list as $value){
			switch($count){
			case $count :
			$liste = $_POST['day'.$count];
		  ?> 
          <td><input type="checkbox" name="<?php echo "day". $count . "[]" ?>" id="<?php echo "day". $count . $value ?>" value="<?php echo $value ?>"  
          <?php echo ((in_array($value, $liste))? "checked='checked'":"") ?> /></td>
          <?php break; } 
		  } ?>
          <?php //} while($counter < $row_rsJourMois['nbre_jour']); ?>
<td><a href="change_membre.php?recordID=<?php echo $row_rsRepresentant['personne_id']; ?>&page=sample16&map=membres&mapid=personnes_personne_id&action=active&menuID=<?php echo $_GET['menuID']; ?>"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
</tr>
<?php } // Show if recordset not empty ?>
<input type="hidden" name="nombre_representant" id="nombre_representant" value="<?php echo $count ?>"/>
<?php } while ($row_rsRepresentant = mysql_fetch_assoc($rsRepresentant)); ?>
</table></td>
    </tr>
  </table>
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
xtn2 = "