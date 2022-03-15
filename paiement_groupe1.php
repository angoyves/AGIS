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

$currentPage = $_SERVER["PHP_SELF"];

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_POST['txtMonth1'])) && ($_POST['txtMonth1'] != "") && (isset($_POST['txtMonth2'])) && ($_POST['txtMonth2'] != "") && (isset($_POST['txtYear'])) && ($_POST['txtYear'] != "")) {
$colname_rstxtMonth1 = "-1";
if (isset($_POST['txtMonth1'])) {
  $colname_rstxtMonth1 = $_POST['txtMonth1'];
}
$colname_rstxtMonth2 = "-1";
if (isset($_POST['txtMonth2'])) {
  $colname_rstxtMonth2 = $_POST['txtMonth2'];
}
$colname_rstxtYear = "-1";
if (isset($_POST['txtYear'])) {
  $colname_rstxtYear = $_POST['txtYear'];
}


$query_rsMontantIndemnite = sprintf("SELECT sum(nombre_jour * montant) as total FROM sessions, membres WHERE commissions_commission_id = membres_commissions_commission_id AND membres_personnes_personne_id = personnes_personne_id AND mois between %s AND %s AND annee = %s AND membres_fonctions_fonction_id = 41",GetSQLValueString($colname_rstxtMonth1, "text"),GetSQLValueString($colname_rstxtMonth2, "text"), GetSQLValueString($colname_rstxtYear, "text")); 
} else {
$query_rsMontantIndemnite = "SELECT sum(nombre_jour * montant) as total FROM sessions, membres WHERE commissions_commission_id = membres_commissions_commission_id AND membres_personnes_personne_id = personnes_personne_id AND membres_fonctions_fonction_id = 41";
}

$rsMontantIndemnite = mysql_query($query_rsMontantIndemnite, $MyFileConnect) or die(mysql_error());
$row_rsMontantIndemnite = mysql_fetch_assoc($rsMontantIndemnite);
$totalRows_rsMontantIndemnite = mysql_num_rows($rsMontantIndemnite);

$maxRows_rsGroupe = 10;
$pageNum_rsGroupe = 0;
if (isset($_GET['pageNum_rsGroupe'])) {
  $pageNum_rsGroupe = $_GET['pageNum_rsGroupe'];
}
$startRow_rsGroupe = $pageNum_rsGroupe * $maxRows_rsGroupe;

mysql_select_db($database_MyFileConnect, $MyFileConnect);


$query_rsGroupe = "SELECT * FROM groupes WHERE display = '1'"; 
$query_limit_rsGroupe = sprintf("%s LIMIT %d, %d", $query_rsGroupe, $startRow_rsGroupe, $maxRows_rsGroupe);
$rsGroupe = mysql_query($query_limit_rsGroupe, $MyFileConnect) or die(mysql_error());
$row_rsGroupe = mysql_fetch_assoc($rsGroupe);

if (isset($_GET['totalRows_rsGroupe'])) {
  $totalRows_rsGroupe = $_GET['totalRows_rsGroupe'];
} else {
  $all_rsGroupe = mysql_query($query_rsGroupe);
  $totalRows_rsGroupe = mysql_num_rows($all_rsGroupe);
}
$totalPages_rsGroupe = ceil($totalRows_rsGroupe/$maxRows_rsGroupe)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ((isset($_POST['txtGroup'])) && ($_POST['txtGroup'] != "")) {
	
$colname_rstxtGroup = "-1";
if (isset($_POST['txtGroup'])) {
  $colname_rstxtGroup = $_POST['txtGroup'];
}
// debut valeur formulaire	
$query_rsSousGroupe = sprintf("SELECT sous_groupe_id, sous_groupe_lib , sous_groupes.pourcentage as taux, groupe_lib, groupes.pourcentage  FROM sous_groupes, groupes WHERE sous_groupes.groupe_id = groupes.groupe_id AND sous_groupes.groupe_id = %s",GetSQLValueString($colname_rstxtGroup, "int"));
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);
//fin valeur formulaire
} else {
$query_rsSousGroupe = "SELECT groupes.groupe_id, personnes.sous_groupe_id, personnes.personne_id, personne_nom, personne_grade, fonction_lib,
personne_telephone, sous_groupe_lib, sous_groupes.pourcentage as taux, banque_code, agence_code, numero_compte, cle, sous_groupes.pourcentage as taux
FROM personnes, sous_groupes, groupes, fonctions, rib
WHERE personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND personnes.personne_id = rib.personne_id
AND sous_groupes.groupe_id = groupes.groupe_id
AND personnes.fonction_id = fonctions.fonction_id
AND personnes.type_personne_id BETWEEN 1 AND 2
AND sous_groupes.groupe_id = 1
AND personnes.type_personne_id = 1
AND personnes.display = 1
GROUP BY sous_groupes.sous_groupe_id, personne_id";
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupeSelect = "SELECT groupe_id, groupe_lib FROM groupes WHERE display = '1'";
$rsGroupeSelect = mysql_query($query_rsGroupeSelect, $MyFileConnect) or die(mysql_error());
$row_rsGroupeSelect = mysql_fetch_assoc($rsGroupeSelect);
$totalRows_rsGroupeSelect = mysql_num_rows($rsGroupeSelect);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsYear = "SELECT * FROM annee";
$rsYear = mysql_query($query_rsYear, $MyFileConnect) or die(mysql_error());
$row_rsYear = mysql_fetch_assoc($rsYear);
$totalRows_rsYear = mysql_num_rows($rsYear);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMonth1 = "SELECT mois_id, lib_mois FROM mois";
$rsMonth1 = mysql_query($query_rsMonth1, $MyFileConnect) or die(mysql_error());
$row_rsMonth1 = mysql_fetch_assoc($rsMonth1);
$totalRows_rsMonth1 = mysql_num_rows($rsMonth1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMonth2 = "SELECT mois_id, lib_mois FROM mois";
$rsMonth2 = mysql_query($query_rsMonth2, $MyFileConnect) or die(mysql_error());
$row_rsMonth2 = mysql_fetch_assoc($rsMonth2);
$totalRows_rsMonth2 = mysql_num_rows($rsMonth2);

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

$queryString_rsGroupe = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsGroupe") == false && 
        stristr($param, "totalRows_rsGroupe") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsGroupe = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsGroupe = sprintf("&totalRows_rsGroupe=%d%s", $totalRows_rsGroupe, $queryString_rsGroupe);

$queryString_rsSousGroupe = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSousGroupe") == false && 
        stristr($param, "totalRows_rsSousGroupe") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSousGroupe = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSousGroupe = sprintf("&totalRows_rsSousGroupe=%d%s", $totalRows_rsSousGroupe, $queryString_rsSousGroupe);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr"><!-- InstanceBegin template="/Templates/FilesTemplates.php" codeOutsideHTMLIsLocked="false" -->
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<link href="css/file.css" rel="stylesheet" type="text/css">
<head>
<meta name="google-site-verification" content="UTnSYJLBKJIOsm6MUJcJBkF7fv-Cm7koeofeyBHwBRA" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<!-- InstanceBeginEditable name="doctitle" -->
<title>AGIS :: Application de Gestion des Indemnites de session</title>
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
<h1>Affichage</h1>
<form id="form1" name="form1" method="post" action="">
  <table width="30%" border="0" class="std2">
    <tr>
      <th align="right">Periode allant de :</th>
      <td><select name="txtMonth1" id="txtMonth1">
        <option value="" <?php if (!(strcmp("", $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsMonth1['mois_id']?>"<?php if (!(strcmp($row_rsMonth1['mois_id'], $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMonth1['lib_mois']); ?></option>
        <?php
} while ($row_rsMonth1 = mysql_fetch_assoc($rsMonth1));
  $rows = mysql_num_rows($rsMonth1);
  if($rows > 0) {
      mysql_data_seek($rsMonth1, 0);
	  $row_rsMonth1 = mysql_fetch_assoc($rsMonth1);
  }
?>
      </select></td>
      <th>à</th>
      <td><select name="txtMonth2" id="txtMonth2">
        <option value="" <?php if (!(strcmp("", $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsMonth2['mois_id']?>"<?php if (!(strcmp($row_rsMonth2['mois_id'], $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMonth2['lib_mois']); ?></option>
        <?php
} while ($row_rsMonth2 = mysql_fetch_assoc($rsMonth2));
  $rows = mysql_num_rows($rsMonth2);
  if($rows > 0) {
      mysql_data_seek($rsMonth2, 0);
	  $row_rsMonth2 = mysql_fetch_assoc($rsMonth2);
  }
?>
      </select></td>
    </tr>
    <tr>
      <th align="right">Exercice :</th>
      <td><select name="txtYear" id="txtYear">
        <option value="" <?php if (!(strcmp("", $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsYear['lib_annee']?>"<?php if (!(strcmp($row_rsYear['lib_annee'], $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsYear['lib_annee']?></option>
        <?php
} while ($row_rsYear = mysql_fetch_assoc($rsYear));
  $rows = mysql_num_rows($rsYear);
  if($rows > 0) {
      mysql_data_seek($rsYear, 0);
	  $row_rsYear = mysql_fetch_assoc($rsYear);
  }
?>
      </select></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th align="right">Groupe :</th>
      <td><select name="txtGroup" id="txtGroup">
        <option value="" <?php if (!(strcmp("", $_POST['txtGroup']))) {echo "selected=\"selected\"";} ?>>Select::</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsGroupeSelect['groupe_id']?>"<?php if (!(strcmp($row_rsGroupeSelect['groupe_id'], $_POST['txtGroup']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsGroupeSelect['groupe_lib']?></option>
        <?php
} while ($row_rsGroupeSelect = mysql_fetch_assoc($rsGroupeSelect));
  $rows = mysql_num_rows($rsGroupeSelect);
  if($rows > 0) {
      mysql_data_seek($rsGroupeSelect, 0);
	  $row_rsGroupeSelect = mysql_fetch_assoc($rsGroupeSelect);
  }
?>
      </select></td>
      <td>&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="Rechercher" /></td>
    </tr>
    <tr>
      <td colspan="4"><input type="hidden" name="MM_update" value="form1" />&nbsp;</td>
      </tr>
  </table>
</form>
<h1>indemnite forfitaire speciale par groupe</h1>
<table width="70%" border="1" align="center" class="std">
  <tr>
    <th>N&deg;</th>
    <th>Groupe</th>
    <th>Description</th>
    <th>Taux (Pourcentage)</th>
    <th>Montant indemnite</th>
  </tr>
  <?php $counter = 0; do { $counter++; ?>
    <tr>
      <td nowrap><a href="todelete.php?recordID=<?php echo $row_rsGroupe['groupe_id']; ?>"> <?php echo $counter; ?>&nbsp; </a></td>
      <td nowrap><?php echo $row_rsGroupe['groupe_lib']; ?>&nbsp;</td>
      <td nowrap><?php echo htmlentities($row_rsGroupe['groupe_desc']); ?></td>
      <td align="right" nowrap><?php $taux1 = $taux1 + ($row_rsGroupe['pourcentage']*100); echo ($row_rsGroupe['pourcentage']*100) . "%"; ?></td>
      <td align="right" nowrap><strong><?php $somme = $somme + ($row_rsGroupe['pourcentage']*$row_rsMontantIndemnite['total']); echo number_format(($row_rsGroupe['pourcentage']*$row_rsMontantIndemnite['total']),0,' ',' '); ?></strong></td>
    </tr>
    <?php } while ($row_rsGroupe = mysql_fetch_assoc($rsGroupe)); ?>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td align="right"><strong>Total</strong></td>
        <td align="right"><strong><?php echo $taux1 . "%" ?>&nbsp;</strong></td>
        <td align="right"><strong><?php echo number_format($somme,0,' ',' '); ?>&nbsp;</strong></td>
      </tr>    
</table>
<p>&nbsp;
<p>
<strong><h1>indemnite forfitaire speciale par sous-groupe</h1>
<p>&nbsp;</p>
</strong>
<table width="70%" border="1" align="center" class="std">
  <tr>
    <th nowrap>N&deg;</th>
    <th nowrap>Groupe</th>
    <th nowrap>Sous groupe</th>
    <th nowrap>Taux (Pourcentage)</th>
    <th nowrap>Indemnite</th>
    <th nowrap>Nbre de pers</th>
    <th nowrap>Montant par personne</th>
    </tr>
  <?php $counter = 0; $somme2 = 0; do { $counter++ ?>
    <tr>
      <td nowrap>&nbsp;</td>
      <td nowrap>&nbsp; <?php echo $row_rsSousGroupe['groupe_lib']; ?></td>
      <td nowrap><?php echo htmlentities($row_rsSousGroupe['sous_groupe_lib']); ?></td>
      <td align="right" nowrap><?php $taux = $taux + ($row_rsSousGroupe['taux']*100); echo ($row_rsSousGroupe['taux']*100) . "%"; ?>&nbsp; </td>
      <td align="right" nowrap><?php $somme2 = $somme2 +($row_rsSousGroupe['taux']*$row_rsMontantIndemnite['total']); echo number_format(($row_rsSousGroupe['taux']*$row_rsMontantIndemnite['total']),0,' ',' '); ?></td>
      <td align="right" nowrap><?php echo htmlentities($row_rsSousGroupe['Nombres']); ?>&nbsp;</td>
      <td align="right" nowrap><?php echo number_format((($row_rsSousGroupe['taux']*$row_rsMontantIndemnite['total'])/$row_rsSousGroupe['Nombres']),0,' ',' '); ?>&nbsp;</td>
      </tr>
    <?php } while ($row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe)); ?>
<tr>
    <td colspan="2">&nbsp;</td>
    <td align="right"><strong>Total</strong></td>
    <td align="right"><strong><?php echo $taux . "%" ?>&nbsp;</strong></td>
    <td colspan="3" align="right"><strong><?php echo number_format($somme2,0,' ',' '); ?>&nbsp;</strong></td>
  </tr>
</table>
<br />
<?php echo $totalRows_rsSousGroupe ?> Enregistrements Total
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
mysql_free_result($rsMontantIndemnite);

mysql_free_result($rsGroupe);

mysql_free_result($rsSousGroupe);

mysql_free_result($rsGroupeSelect);

mysql_free_result($rsYear);

mysql_free_result($rsMonth1);

mysql_free_result($rsMonth2);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>
