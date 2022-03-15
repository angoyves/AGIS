<?php require_once('Connections/MyFileConnect.php'); ?>
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
//$editFormAction = $_SERVER['PHP_SELF'];
$editFormAction = "upd_sessions_db.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

/*if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
$updateSQL = sprintf("UPDATE sessions SET membres_commissions_commission_id=%s, membres_fonctions_fonction_id=%s, dossiers_dossier_id=%s, nombre_dossier=%s, Nombre_jour=%s, jour=%s, mois=%s, annee=%s, dateCreation=%s, display=%s WHERE membres_personnes_personne_id=%s",
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($_POST['Nombre_jour'], "int"),
                       GetSQLValueString($_POST['jour'], "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($_POST['dateCreation'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['membres_personnes_personne_id'], "int"));



  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}*/

$colname_rsSessions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsSessions = $_GET['comID'];
}
$colname_rsSessions1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsSessions1 = $_GET['mID'];
}
$colname_rsSessions2 = "-1";
if (isset($_GET['aID'])) {
  $colname_rsSessions2	 = $_GET['aID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_GET['valid'])) && ($_GET['valid'] == 'mb')) {
$query_rsSessions = sprintf("SELECT commission_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  fonctions, sessions
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND sessions.membres_fonctions_fonction_id <> 4 
AND sessions.membres_fonctions_fonction_id <> 5
AND sessions.membres_fonctions_fonction_id <> 40
AND sessions.membres_fonctions_fonction_id <> 42
AND commissions.commission_id = %s 
AND mois = %s 
AND annee = %s 
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
} elseif ((isset($_GET['valid'])) && ($_GET['valid'] == 'mo')){
$query_rsSessions = sprintf("SELECT commission_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  fonctions, sessions
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND sessions.membres_fonctions_fonction_id <> 1 
AND sessions.membres_fonctions_fonction_id <> 2
AND sessions.membres_fonctions_fonction_id <> 3
AND sessions.membres_fonctions_fonction_id <> 42
AND commissions.commission_id = %s 
AND mois = %s 
AND annee = %s 
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
} elseif ((isset($_GET['valid'])) && ($_GET['valid'] == 'scom')){
$query_rsSessions = sprintf("SELECT commission_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total
FROM commissions, membres, personnes,  fonctions, sessions
WHERE membres.commissions_commission_id = commissions.commission_id
AND membres.personnes_personne_id = personnes.personne_id
AND membres.fonctions_fonction_id = fonctions.fonction_id
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id
AND sessions.membres_fonctions_fonction_id <> 4 
AND sessions.membres_fonctions_fonction_id <> 5
AND sessions.membres_fonctions_fonction_id <> 40
AND sessions.membres_fonctions_fonction_id <> 42
AND commissions.commission_id = %s 
AND mois = %s 
AND annee = %s 
ORDER BY fonctions.fonction_id", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text")); }
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

$colname_rsJourMois = "-1";
if (isset($_GET['mID'])) {
  $colname_rsJourMois = $_GET['mID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

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

$colname_rsUpdSessions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsUpdSessions = $_GET['comID'];
}
$colname_rsUpdSessions1 = "-1";
if (isset($_GET['mID'])) {
  $colname_rsUpdSessions1 = $_GET['mID'];
}
$colname_rsUpdSessions2 = "-1";
if (isset($_GET['aID'])) {
  $colname_rsUpdSessions2 = $_GET['aID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdSessions = sprintf("SELECT * FROM sessions WHERE membres_commissions_commission_id = %s
							   AND mois = %s
							   AND annee = %s", 
							   GetSQLValueString($colname_rsUpdSessions, "int"),
							   GetSQLValueString($colname_rsUpdSessions1, "text"),
							   GetSQLValueString($colname_rsUpdSessions2, "text"));
$rsUpdSessions = mysql_query($query_rsUpdSessions, $MyFileConnect) or die(mysql_error());
$row_rsUpdSessions = mysql_fetch_assoc($rsUpdSessions);
$totalRows_rsUpdSessions = mysql_num_rows($rsUpdSessions);
?>
<?php 
switch ($row_rsJourMois['mois_id']){
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
<?php 
$jour1 = $_POST['jour1'];
$jour2 = $_POST['jour2'];
$jour3 = $_POST['jour3'];
$jour1 = implode('**',$jour1);
$liste1 = explode('**',$jour1);
$jour2 = implode('**',$jour2);
$liste2 = explode('**',$jour2);
$jour3 = implode('**',$jour3);
$liste3 = explode('**',$jour3);
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
<meta name="description" content="Le ministère des Finances et du Budget et met en œuvre les politiques du Gouvernement en matière  financière.">
<meta name="keywords" content="essimi menye, ministre, Finances, Industrie, Ministre Industrie, Ministère des Finances, consommation, entreprises, PME, TPE, finances, Finances Cameroun">
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
      <div id="logo"><img src="images/img/v2/amoirie.gif" alt="armoirie" width="78" height="52"><a href="#" target="_top"><img src="images/img/v2/flag.gif" alt="Ministère des Finances et du Budget" width="89" border="0" height="52" hspace="2"></a>
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
      <!--<div class="logos"> <a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><a href="http://www.pandemie-grippale.gouv.fr" title="Info'pandémie grippale"><img src="images/actu/grippe_a.gif" alt="Info'pandémie grippale" width="180" height="150" border="0"></a> </div> -->

	 
	  <!--/#tag_cloud-->

    </div>
    <!-- InstanceBeginEditable name="EditBoby" -->
    <h1>Mise à jour commission</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std2">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">ID Commission :</th>
      <td><input name="membres_commissions_commission_id" type="text" value="<?php echo htmlentities($row_rsUpdSessions['membres_commissions_commission_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" readonly="readonly" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Reférence Dossiers :</th>
      <td><input type="text" name="dossiers_dossier_id" value="<?php echo htmlentities($row_rsUpdSessions['dossiers_dossier_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Nombre de dossier :</th>
      <td><input type="text" name="nombre_dossier" value="<?php echo htmlentities($row_rsUpdSessions['nombre_dossier'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Mois :</th>
      <td><select name="mois">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsMois['mois_id']?>" <?php if (!(strcmp($row_rsUpdSessions['mois'], $row_rsMois['mois_id']))) {echo "SELECTED";} ?>><?php echo ucfirst($row_rsMois['lib_mois'])?></option>
        <?php
} while ($row_rsMois = mysql_fetch_assoc($rsMois));
?>
      </select>
        <input type="hidden" name="mois2" value="<?php echo htmlentities($row_rsUpdSessions['mois'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Annee :</th>
      <td><select name="annee">
        <?php 
do {  
?>
        <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsUpdSessions['annee'], $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
        <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
?>
      </select>
        <input type="hidden" name="annee2" value="<?php echo htmlentities($row_rsUpdSessions['annee'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">&nbsp;</th>
      <td><input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdSessions['display'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <input type="hidden" name="dateCreation" value="<?php echo htmlentities($row_rsUpdSessions['dateCreation'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <input type="hidden" name="Nombre_jour" value="<?php echo htmlentities($row_rsUpdSessions['Nombre_jour'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
      <input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="membres_personnes_personne_id" value="<?php echo $row_rsUpdSessions['membres_personnes_personne_id']; ?>" />
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Fonction</th>
    <?php $counter=0; do  { $counter++; ?>
    <th><?php echo $counter ?></th>
    <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
    <th>Total</th>
    <th>Taux</th>
    <th>Montant</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
  <tr>
    <td nowrap="nowrap"><a href="to_delete12.php?recordID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>"> <?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&nbsp;
      <input type="hidden" name="<?php echo "membres_personnes_personne_id" . $counter ?>" value="<?php echo htmlentities($row_rsSessions['membres_personnes_personne_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" />
    </a></td>
    <td nowrap="nowrap"><?php echo htmlentities($row_rsSessions['personne_nom']); ?>&nbsp; </td>
    <td nowrap="nowrap"><?php echo htmlentities($row_rsSessions['personne_prenom']); ?>&nbsp; </td>
    <td nowrap="nowrap"><?php echo htmlentities(strtoupper($row_rsSessions['fonction_lib'])); ?>&nbsp; <input type="hidden" name="<?php echo "membres_fonctions_fonction_id".$counter ?>" value="<?php echo htmlentities($row_rsSessions['membres_fonctions_fonction_id'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    <?php foreach($list as $value){ ?>
    <td nowrap="nowrap">
	<?php
	switch($counter){
	case 1 :
	$jour1 = $row_rsSessions['jour'];
	$liste1 = explode('**',$jour1);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste1))? "checked='checked'":"") ?> >
    <?php
	break;
	case 2 :
	$jour2 = $row_rsSessions['jour'];
	$liste2 = explode('**',$jour2);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste2))? "checked='checked'":"") ?> >
    <?php
	break;
	case 3 :
	$jour3 = $row_rsSessions['jour'];
	$liste3 = explode('**',$jour3);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste3))? "checked='checked'":"") ?> >
    <?php
	break;
	case 4 :
	$jour4 = $row_rsSessions['jour'];
	$liste4 = explode('**',$jour4);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste4))? "checked='checked'":"") ?> >
    <?php
	break;
	case 5 :
	$jour5 = $row_rsSessions['jour'];
	$liste5 = explode('**',$jour5);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste5))? "checked='checked'":"") ?> >
    <?php
	break;
	case 6 :
	$jour6 = $row_rsSessions['jour'];
	$liste6 = explode('**',$jour6);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste6))? "checked='checked'":"") ?> >
    <?php
	break;
	case 7 :
	$jour7 = $row_rsSessions['jour'];
	$liste7 = explode('**',$jour7);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste7))? "checked='checked'":"") ?> >
    <?php
	break;
	case 8 :
	$jour8 = $row_rsSessions['jour'];
	$liste8 = explode('**',$jour8);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste8))? "checked='checked'":"") ?> >
    <?php
	break;
	case 9 :
	$jour9 = $row_rsSessions['jour'];
	$liste9 = explode('**',$jour9);
	?>
	<input type="checkbox" name=<?php echo "jour" . $counter . "[]" ?> id=<?php echo "jour". $counter . $value ?> value="<?php echo $value ?>"  
	<?php echo ((in_array($value, $liste9))? "checked='checked'":"") ?> >
    <?php
	break;
	}
	?>
	</td>
	<?php } ?>
    <td align="right" nowrap="nowrap"><?php echo $row_rsSessions['nombre_jour']; ?></td>
    <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
    <td align="right" nowrap="nowrap"><strong><?php echo number_format($row_rsSessions['total'],0,' ',' '); ?></strong></td>	
  </tr>
  <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
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

mysql_free_result($rsMois);

mysql_free_result($rsAnnee);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsUpdSessions);
?>
