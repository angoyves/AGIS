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

$colname4_rsSessionsCommite = "-1";
if (isset($_POST['txtType'])) {
  $colname4_rsSessionsCommite = $_POST['txtType'];
}
$colname2_rsSessionsCommite = "-1";
if (isset($_POST['txtMonth2'])) {
  $colname2_rsSessionsCommite = $_POST['txtMonth2'];
}
$colname3_rsSessionsCommite = "-1";
if (isset($_POST['txtYear'])) {
  $colname3_rsSessionsCommite = $_POST['txtYear'];
}
$colname1_rsSessionsCommite = "-1";
if (isset($_POST['txtMonth1'])) {
  $colname1_rsSessionsCommite = $_POST['txtMonth1'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionsCommite = sprintf("SELECT distinct(membres_commissions_commission_id), commission_lib, localite_lib, taux
FROM commissions, sessions, localites, type_commissions WHERE membres_commissions_commission_id = commission_id AND commissions.localite_id = localites.localite_id AND commissions.type_commission_id = type_commissions.type_commission_id AND mois between %s AND %s AND annee = %s AND commissions.type_commission_id = %s", GetSQLValueString($colname1_rsSessionsCommite, "text"),GetSQLValueString($colname2_rsSessionsCommite, "text"),GetSQLValueString($colname3_rsSessionsCommite, "text"),GetSQLValueString($colname4_rsSessionsCommite, "text"));
$rsSessionsCommite = mysql_query($query_rsSessionsCommite, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSessionsCommite);
$totalRows_rsSessionsCommite = mysql_num_rows($rsSessionsCommite);

$colname5_rsSousCommission = "-1";
if (isset($_POST['txtYear'])) {
  $colname5_rsSousCommission = $_POST['txtYear'];
}
$colname6_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname6_rsSousCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom, banque_code, agence_code, numero_compte, cle,    membres_commissions_commission_id,  membres_fonctions_fonction_id, fonction_lib,    lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee,   montant,(nombre_jour * montant) as total					FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, rib						 WHERE sessions.mois = mois.mois_id			 AND sessions.membres_personnes_personne_id = personnes.personne_id		 AND sessions.membres_commissions_commission_id = commissions.commission_id	 AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id			 AND sessions.membres_personnes_personne_id = membres.personnes_personne_id						 AND commissions.nature_id = natures.nature_id			 AND commissions.type_commission_id = type_commissions.type_commission_id AND personnes.personne_id = rib.personne_id					AND commissions.localite_id = localites.localite_id   AND annee = %s AND commission_parent = %s GROUP BY membres_personnes_personne_id", GetSQLValueString($colname5_rsSousCommission, "text"),GetSQLValueString($colname6_rsSousCommission, "int"));
$rsSousCommission = mysql_query($query_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);
$totalRows_rsSousCommission = mysql_num_rows($rsSousCommission);



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
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);

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
<table border="0">
  <tr>
    <td scope="row">
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
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th align="right" scope="row">Type de commission:</th>
    <td colspan="3"><select name="txtType" id="txtType">
      <option value="" <?php if (!(strcmp("", $_POST['txtType']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsTypeCommission['type_commission_id']?>"<?php if (!(strcmp($row_rsTypeCommission['type_commission_id'], $_POST['txtType']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTypeCommission['type_commission_lib']?></option>
      <?php
} while ($row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission));
  $rows = mysql_num_rows($rsTypeCommission);
  if($rows > 0) {
      mysql_data_seek($rsTypeCommission, 0);
	  $row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
  }
?>
      </select></td>
    <td><input type="submit" name="button" id="button" value="Rechercher" /></td>
    </tr>
</table>

</form>
<?php if ($totalRows_rsSessionsCommite > 0) { // Show if recordset not empty ?>
  <table>
    <tr>
      <td><?php $link = "print_etat_financier_recap.php?aID=" . $_POST['txtYear']. "&mID1=" . $_POST['txtMonth1'] . "&mID2=" . $_POST['txtMonth2'] . "&comID=" . $_GET['comID']. "&menuID=" . $_GET['menuID'] . "&typID=" . $_POST['txtType'] ?>
        <?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=6" ?>
        <a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Version imprimable</a>&nbsp; <a href="<?php echo $link2 ?>"><img src="images/img/b_edit.png" width="16" height="16" /> Mettre à jour</a><a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;&amp;mapid=personne_id&amp;action=add_com&amp;page=rep"></a>&nbsp;<a href="<?php echo $link6 ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter un représentant</a></td>
    </tr>
  </table>
  <table border="1" align="center" class="std">
    <tr>
      <th nowrap="nowrap">N°</th>
      <th nowrap="nowrap">Commission</th>
      <th nowrap="nowrap">Localite</th>
      <?php $counter = $_POST['txtMonth1']; do {  
    switch ($counter) {
	case 1:
      $counter = '01';
      break;	
    case 2:
      $counter = '02';
      break; 
    case 3:
      $counter = '03';
      break; 
    case 4:
      $counter = '04';
      break; 
    case 5:
      $counter = '05';
      break; 
    case 6:
      $counter = '06';
      break; 
    case 7:
      $counter = '07';
      break; 
    case 8:
      $counter = '08';
      break;
    case 9:
      $counter = '09';
      break; 	  
	}
	?>
      <th nowrap="nowrap"><?php echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</th>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <th nowrap="nowrap">Taux</th>
      <th nowrap="nowrap">Montant</th>
  </tr>
    <?php $compter = 0; do { $compter++; ?>
      <tr>
        <td nowrap="nowrap"><?php echo $compter; ?>&nbsp;</td>
        <td nowrap="nowrap">&nbsp; <?php echo htmlentities($row_rsSessionsCommite['commission_lib']); ?></td>
        <td nowrap="nowrap"><?php echo $row_rsSessionsCommite['localite_lib']; ?></td>
        <?php $counter = $_POST['txtMonth1'];  $total_montant = 0; do {    
		switch ($counter) {
		case 2:
		  $counter = '02';
		  break; 
		case 3:
		  $counter = '03';
		  break; 
		case 4:
		  $counter = '04';
		  break; 
		case 5:
		  $counter = '05';
		  break; 
		case 6:
		  $counter = '06';
		  break; 
		case 7:
		  $counter = '07';
		  break; 
		case 8:
		  $counter = '08';
		  break;
		case 9:
		  $counter = '09';
		  break; 	  
		}
	  
	  ?>
        <td align="right" nowrap="nowrap">
		<?php 
		$colname_rsCountIndemnit2 = "-1";
		if (isset($row_rsSessionsCommite['membres_commissions_commission_id'])) {
		  $colname_rsCountIndemnity = $row_rsSessionsCommite['membres_commissions_commission_id'];
		}
		$colname_rsCountIndemnity1 = "-1";
		if (isset($counter)) {
		  $colname_rsCountIndemnity1 = $counter;
		}
		$colname_rsCountIndemnity2 = "-1";
		if (isset($_POST['txtYear'])) {
		  $colname_rsCountIndemnity2 = $_POST['txtYear'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsCountIndemnity = sprintf("SELECT sum(nombre_jour) as number_day FROM sessions WHERE membres_personnes_personne_id = %s
		AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s",  GetSQLValueString(2129, "int"), GetSQLValueString($colname_rsCountIndemnity, "int"),GetSQLValueString($colname_rsCountIndemnity1, "text"),GetSQLValueString($colname_rsCountIndemnity2, "text"));
		$rsCountIndemnity = mysql_query($query_rsCountIndemnity, $MyFileConnect) or die(mysql_error());
		$row_rsCountIndemnity = mysql_fetch_assoc($rsCountIndemnity);
		$totalRows_rsCountIndemnity = mysql_num_rows($rsCountIndemnity);
		
		$montant_session = $row_rsCountIndemnity['number_day'] * $row_rsSessionsCommite['taux'];
		$total_montant = $total_montant + $montant_session;
		echo number_format($montant_session,0,' ',' ');
		
		?>&nbsp;</td>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <td nowrap="nowrap"><?php echo number_format($row_rsSessionsCommite['taux'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($total_montant,0,' ',' '); $somme = $somme + $total_montant; ?>&nbsp;</td>
      </tr>
      <?php } while ($row_rsSessionsCommite = mysql_fetch_assoc($rsSessionsCommite)); ?>
<tr>
      <td colspan="3" nowrap="nowrap">&nbsp;</td>
      <?php $counter = $_POST['txtMonth1']; do {  
    switch ($counter) {
    case 2:
      $counter = '02';
      break; 
    case 3:
      $counter = '03';
      break; 
    case 4:
      $counter = '04';
      break; 
    case 5:
      $counter = '05';
      break; 
    case 6:
      $counter = '06';
      break; 
    case 7:
      $counter = '07';
      break; 
    case 8:
      $counter = '08';
      break;
    case 9:
      $counter = '09';
      break; 	  
	}
	?>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $counter++;} while ($counter <= $_POST['txtMonth2']) ?>
      <td nowrap="nowrap">S<strong>ous total sessions</strong></td>
      <td align="right" nowrap="nowrap"><?php echo number_format($somme,0,' ',' '); ?></td>
  </tr>
  </table>
  <?php } // Show if recordset not empty ?>
<p><br />
<?php echo $totalRows_rsSessionsCommite ?> Enregistrements Total </p>
<p>&nbsp;
<?php if ($totalRows_rsSousCommission > 0) { // Show if recordset not empty ?>
<table border="1" align="left">
  <tr>
    <td width="381">Commission_id</td>
    <td width="237">commission_lib</td>
    <td width="204">lib_nature</td>
    <td width="271">type_commission_lib</td>
    <td width="209">localite_lib</td>
    <td width="223">nombre_jour</td>
    <td width="169">jour</td>
    <td width="173">mois</td>
    <td width="195">lib_mois</td>
    <td width="180">annee</td>
    <td width="193">montant</td>
    <td width="172">total</td>
   <?php $counter = $_POST['txtMonth1']; do { $counter++; ?>
    <td width="172"><?php echo $counter ?>&nbsp;</td>
   <?php } while ($counter <= $_POST['txtMonth2']) ?>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsSousCommission['membres_commissions_commission_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['commission_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['lib_nature']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['type_commission_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['localite_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['nombre_jour']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['jour']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['mois']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['lib_mois']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['annee']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['montant']; ?>&nbsp; </td>
      <td><?php echo $row_rsSousCommission['total']; ?>&nbsp; </td>
      <td>&nbsp;</td>
    </tr>
    <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
</table>
<?php } // Show if recordset not empty ?>
<br />
<?php echo $totalRows_rsSessionsCommite ?> Enregistrements Total
    
    </td>
  </tr>
</table>

</p>
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
mysql_free_result($rsSessionsCommite);

mysql_free_result($rsSessionsCommite);

mysql_free_result($rsSousCommission);

mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($rsAnnee);

mysql_free_result($rsTypeCommission);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>
