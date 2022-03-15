<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/MyFonction.php'); ?>
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

$maxRows_rsSessions = 10;
$pageNum_rsSessions = 0;
if (isset($_GET['pageNum_rsSessions'])) {
  $pageNum_rsSessions = $_GET['pageNum_rsSessions'];
}
$startRow_rsSessions = $pageNum_rsSessions * $maxRows_rsSessions;

$colname_rsSessions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsSessions = $_GET['comID'];
}
$colname_rsSessions1 = "-1";
if (isset($_POST['txt_Month'])) {
  $colname_rsSessions1 = $_POST['txt_Month'];
}
$colname_rsSessions2 = "-1";
if (isset($_POST['txt_Year'])) {
  $colname_rsSessions2 = $_POST['txt_Year'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom, banque_code, agence_code, numero_compte, cle,  
membres_commissions_commission_id, commission_lib, membres_fonctions_fonction_id, fonction_lib,  
lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee, 
montant,(nombre_jour * montant) as total 
FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, rib
WHERE sessions.mois = mois.mois_id
AND sessions.membres_personnes_personne_id = personnes.personne_id  
AND sessions.membres_commissions_commission_id = commissions.commission_id  
AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id  
AND commissions.nature_id = natures.nature_id   
AND commissions.type_commission_id = type_commissions.type_commission_id 
AND personnes.personne_id = rib.personne_id  
AND commissions.localite_id = localites.localite_id 
AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s", GetSQLValueString($colname_rsSessions, "text"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
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

$colname_rsJourMois = "-1";
if (isset($_POST['txt_Month'])) {
  $colname_rsJourMois = $_POST['txt_Month'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

$query_rsSelYear = "SELECT * FROM annee";
$rsSelYear = mysql_query($query_rsSelYear, $MyFileConnect) or die(mysql_error());
$row_rsSelYear = mysql_fetch_assoc($rsSelYear);
$totalRows_rsSelYear = mysql_num_rows($rsSelYear);

$query_rsMois = "SELECT * FROM mois";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

$maxRows_rsSousCommission = 10;
$pageNum_rsSousCommission = 0;
if (isset($_GET['pageNum_rsSousCommission'])) {
  $pageNum_rsSousCommission = $_GET['pageNum_rsSousCommission'];
}
$startRow_rsSousCommission = $pageNum_rsSousCommission * $maxRows_rsSousCommission;


$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname1_rsSousCommission = "-1";
if (isset($_POST['txt_Year'])) {
  $colname1_rsSousCommission = $_POST['txt_Year'];
}
$colname2_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname2_rsSousCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom, banque_code, agence_code, numero_compte, cle,   membres_commissions_commission_id,  membres_fonctions_fonction_id, fonction_lib,   lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, lib_mois, annee,  montant,(nombre_jour * montant) as total																																																																																	FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres, mois, rib																																																																																			WHERE sessions.mois = mois.mois_id																																																																																			AND sessions.membres_personnes_personne_id = personnes.personne_id																																																																																AND sessions.membres_commissions_commission_id = commissions.commission_id																																																																																			AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id																																																																																			AND sessions.membres_personnes_personne_id = membres.personnes_personne_id																																																																																			AND commissions.nature_id = natures.nature_id																																																																																			AND commissions.type_commission_id = type_commissions.type_commission_id
AND personnes.personne_id = rib.personne_id																																																																																			AND commissions.localite_id = localites.localite_id  AND annee = %s AND commission_parent = %s GROUP BY membres_personnes_personne_id", GetSQLValueString($colname1_rsSousCommission, "text"),GetSQLValueString($colname2_rsSousCommission, "text"));
$query_limit_rsSousCommission = sprintf("%s LIMIT %d, %d", $query_rsSousCommission, $startRow_rsSousCommission, $maxRows_rsSousCommission);
$rsSousCommission = mysql_query($query_limit_rsSousCommission, $MyFileConnect) or die(mysql_error());
$row_rsSousCommission = mysql_fetch_assoc($rsSousCommission);

if (isset($_GET['totalRows_rsSousCommission'])) {
  $totalRows_rsSousCommission = $_GET['totalRows_rsSousCommission'];
} else {
  $all_rsSousCommission = mysql_query($query_rsSousCommission);
  $totalRows_rsSousCommission = mysql_num_rows($all_rsSousCommission);
}
$totalPages_rsSousCommission = ceil($totalRows_rsSousCommission/$maxRows_rsSousCommission)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois4 = "SELECT * FROM mois";
$rsMois4 = mysql_query($query_rsMois4, $MyFileConnect) or die(mysql_error());
$row_rsMois4 = mysql_fetch_assoc($rsMois4);
$totalRows_rsMois4 = mysql_num_rows($rsMois4);

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

$queryString_rsSousCommission = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSousCommission") == false && 
        stristr($param, "totalRows_rsSousCommission") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSousCommission = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSousCommission = sprintf("&totalRows_rsSousCommission=%d%s", $totalRows_rsSousCommission, $queryString_rsSousCommission);

$queryString_rsMois = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsMois") == false && 
        stristr($param, "totalRows_rsMois") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsMois = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsMois = sprintf("&totalRows_rsMois=%d%s", $totalRows_rsMois, $queryString_rsMois);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr"><!-- InstanceBegin template="/Templates/FilesTemplates.php" codeOutsideHTMLIsLocked="false" -->
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<link href="css/file.css" rel="stylesheet" type="text/css">
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
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" align="center">
    <tr>
      <td>
      <table width="75%" border="1" align="left" class="std2">
        <tr valign="baseline">
          <td colspan="4" nowrap="nowrap">Selectionner  une commission
            <?php           
	$showGoTo = "search_commissions4.php?page=fin";
  //if (isset($_SERVER['QUERY_STRING'])) {
   // $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    //$showGoTo .= $_SERVER['QUERY_STRING'];
  //}
?>
            <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
            <strong>
              <?php if (isset($_GET['comID'])) { ?>
              <?php echo $row_rsCommissions['type_commission_lib']; ?> de <?php echo $row_rsCommissions['lib_nature']; ?> du (de)<?php echo $row_rsCommissions['localite_lib']; ?>
              <?php } ?>
              </strong>
            <input name="txt_Com" type="hidden" id="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" />
            <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
            <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
            <?php }
            ?>
            <input type="hidden" name="txt_Com2" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" /></td>
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
          <td colspan="4" align="right" nowrap="nowrap"><input type="hidden" name="MM_insert" value="form1" /></td>
        </tr>
      </table>
      </td>
    </tr>
    <tr>
      <td>
		<?php if ($totalRows_rsCommissions > 0) { // Show if recordset not empty ?>
        <table align="left" class="std">
          <tr valign="baseline">
            <th nowrap="nowrap" align="right">Mois:</th>
            <td>
            <select name="txt_Month" id="txt_Month">
              <option value="" <?php if (!(strcmp("", $_POST['txt_Month']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
              <?php do {  ?>
              <option value="<?php echo $row_rsMois['mois_id']?>"<?php if (!(strcmp($row_rsMois['mois_id'], $_POST['txt_Month']))) {echo "selected=\"selected\"";} ?>>
              <?php echo ucfirst($row_rsMois['lib_mois']) ?></option>
              <?php } while ($row_rsMois = mysql_fetch_assoc($rsMois));
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
              <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Select month, please</div>
              <?php  } ?>
            </td>
          </tr>
          <tr valign="baseline">
            <th nowrap="nowrap" align="right">Ann&eacute;e : </th>
            <td>
            <select name="txt_Year" id="txt_Year">
              <option value="" <?php if (!(strcmp("", $_POST['txt_Year']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
              <?php do {  ?>
              <option value="<?php echo $row_rsSelYear['lib_annee']?>"<?php if (!(strcmp($row_rsSelYear['lib_annee'], $_POST['txt_Year']))) {echo "selected=\"selected\"";} ?>>
              <?php echo $row_rsSelYear['lib_annee']?>
              </option>
              <?php } while ($row_rsSelYear = mysql_fetch_assoc($rsSelYear));
                $rows = mysql_num_rows($rsSelYear);
                if($rows > 0) {
                mysql_data_seek($rsSelYear, 0);
                $row_rsSelYear = mysql_fetch_assoc($rsSelYear);
                }
                ?>
            </select>
              <?php
                 /** Display error messages if the "password" field is empty */
           if ($anneeIsEmpty) { ?>
              <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Enter the YEAR, please</div>
              <?php  } ?>
            </td>
          </tr>
        </table>
        <?php } // Show if recordset not empty ?>
      </td>
    </tr>
    <tr>
      <td><input type="submit" value="Rechercher..." /></td>
    </tr>
    <tr>
      <td>	
	<?php $link = "print_etat_financier.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID']. "&menuID=" . $_GET['menuID'] ?>
    <?php //$link2 = "upd_sessions.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=" . $_GET['menuID']?>
    <a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Version imprimable</a>&nbsp;</td>
    </tr>
  </table>
</form>
</BR>
<table border="1" class="std">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Fonction</th>
    <th>Releve d'Identite Bancaire</th>
 	<?php $count = 0; do { $count++;?>
    <th><?php echo ucfirst($row_rsMois['lib_mois']); ?></th>
    <?php } while ($row_rsMois = mysql_fetch_assoc($rsMois)); ?>
    <th>Montant</th>
    <th>Total</th>
  </tr>
  <?php $countj = 0; $somme2 = 0; do { $countj++; ?>
  <?php 
  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
    $query_rsMois2 = "SELECT * FROM mois";
    $rsMois2 = mysql_query($query_rsMois2, $MyFileConnect) or die(mysql_error());
    $row_rsMois2 = mysql_fetch_assoc($rsMois2);
    $totalRows_rsMois2 = mysql_num_rows($rsMois2); ?>
    <tr>
      <td><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>"> <?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsSessions['personne_nom'] . " " . $row_rsSessions['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo $row_rsSessions['fonction_lib']; ?>&nbsp; </td>
      <td><?php if ($row_rsSessions['banque_code'] == 'XXXXX'){ echo 'Absent'; } else { echo strtoupper($row_rsSessions['banque_code'] . "-" . $row_rsSessions['agence_code'] . "-" . $row_rsSessions['numero_compte'] . "-" . $row_rsSessions['cle']);} ?>&nbsp; </td>
      <?php $counti = 0; $somme = 0; do { $counti++; ?>
		<?php 
        mysql_select_db($database_MyFileConnect, $MyFileConnect);
        $query_rsTotal = sprintf("SELECT (Nombre_jour * montant) as Total 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 	
		AND sessions.membres_personnes_personne_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($row_rsSessions['membres_personnes_personne_id'], "text"),
		GetSQLValueString($row_rsMois2['mois_id'], 	"text"),
		GetSQLValueString($row_rsSessions['annee'], "text"));
        $rsTotal = mysql_query($query_rsTotal, $MyFileConnect) or die(mysql_error());
        $row_rsTotal = mysql_fetch_assoc($rsTotal);
        $totalRows_rsTotal = mysql_num_rows($rsTotal);
		?>
      <td align="right"><?php echo number_format($row_rsTotal['Total'],0,' ',' '); ?>&nbsp;<strong>
      <?php $somme = $somme + $row_rsTotal['Total']; ?>
      <?php 
	  	$comissionValue = $row_rsSessions['membres_commissions_commission_id'];
		$anneeValue = $row_rsSessions['annee']
	  
	  ?>
      </strong></td>
      <?php } while ($row_rsMois2 = mysql_fetch_assoc($rsMois2)); ?>

      <td align="right"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?>&nbsp; </td>
      <td align="right"><strong><?php echo number_format($somme,0,' ',' '); ?>&nbsp;</strong> </td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <?php  
	  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
        $query_rsMois3 = "SELECT * FROM mois";
        $rsMois3 = mysql_query($query_rsMois3, $MyFileConnect) or die(mysql_error());
        $row_rsMois3 = mysql_fetch_assoc($rsMois3);
        $totalRows_rsMois3 = mysql_num_rows($rsMois3); ?>
      <?php $counter = 0; do { $counter++; ?>  
      <?php
		$query_rsTotalMois = sprintf("SELECT sum(Nombre_jour * montant) as TotalMois 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 
		AND membres_commissions_commission_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($comissionValue, "int"),
		GetSQLValueString($row_rsMois3['mois_id'], 	"text"),
		GetSQLValueString($anneeValue, "text"));
		$rsTotalMois = mysql_query($query_rsTotalMois, $MyFileConnect) or die(mysql_error());
		$row_rsTotalMois = mysql_fetch_assoc($rsTotalMois);
		$totalRows_rsTotalMois = mysql_num_rows($rsTotalMois);
		?>  
      <td align="right"><strong>
        <?php 
	  		echo number_format($row_rsTotalMois['TotalMois'],0,' ',' '); 
			$Totaux = $Totaux + $row_rsTotalMois['TotalMois'];
	  ?>
      </strong></td>
      <?php } while ($row_rsMois3 = mysql_fetch_assoc($rsMois3)); ?>      <td align="right">&nbsp;</td>
      <td align="right"><?php echo number_format($Totaux,0,' ',' '); ?>&nbsp;</td>
    </tr>
</table>
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
<p>Enregistrements <?php echo ($startRow_rsSessions + 1) ?> à <?php echo min($startRow_rsSessions + $maxRows_rsSessions, $totalRows_rsSessions) ?> sur <?php echo $totalRows_rsSessions ?></p>
<table border="1" class="std">
  <tr>
    <th nowrap="nowrap">ID</th>
    <th nowrap="nowrap">Nom</th>
    <th nowrap="nowrap">Fonction</th>    
    <th nowrap="nowrap">Releve d'Identite Bancaire</th>
    <?php $count = 0; do { $count++;?>
    <th><?php echo ucfirst($row_rsMois4['lib_mois']); ?></th>
    <?php } while ($row_rsMois4 = mysql_fetch_assoc($rsMois4)); ?>
    <th nowrap="nowrap">Montant</th>
    <th nowrap="nowrap">Total</th>
  </tr>
  <?php $countj = 0; $somme2 = 0; do { $countj++; ?>
  <?php
	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	$query_rsMois5 = "SELECT * FROM mois";
	$rsMois5 = mysql_query($query_rsMois5, $MyFileConnect) or die(mysql_error());
	$row_rsMois5 = mysql_fetch_assoc($rsMois5);
	$totalRows_rsMois5 = mysql_num_rows($rsMois5);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois6 = "SELECT * FROM mois";
$rsMois6 = mysql_query($query_rsMois6, $MyFileConnect) or die(mysql_error());
$row_rsMois6 = mysql_fetch_assoc($rsMois6);
$totalRows_rsMois6 = mysql_num_rows($rsMois6);
	?>
  
    <tr>
      <td nowrap="nowrap"><a href="todele.php?recordID=<?php echo $row_rsSousCommission['membres_personnes_personne_id']; ?>"> <?php echo $row_rsSousCommission['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td nowrap="nowrap"><?php echo strtoupper($row_rsSousCommission['personne_nom']) . " " . ucfirst($row_rsSousCommission['personne_prenom']); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSousCommission['fonction_lib']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php if ($row_rsSousCommission['banque_code'] == 'XXXXX'){ echo 'Absent'; } else { echo strtoupper($row_rsSousCommission['banque_code'] . "-" . $row_rsSousCommission['agence_code'] . "-" . $row_rsSousCommission['numero_compte'] . "-" . $row_rsSousCommission['cle']);} ?>&nbsp; </td>
      <?php $counti = 0; $somme2 = 0; do { $counti++; ?>
		<?php 
        mysql_select_db($database_MyFileConnect, $MyFileConnect);
        $query_rsTotal2 = sprintf("SELECT (Nombre_jour * montant) as Total 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 	
		AND sessions.membres_personnes_personne_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($row_rsSousCommission['membres_personnes_personne_id'], "text"),
		GetSQLValueString($row_rsMois5['mois_id'], 	"text"),
		GetSQLValueString($row_rsSousCommission['annee'], "text"));
        $rsTotal2 = mysql_query($query_rsTotal2, $MyFileConnect) or die(mysql_error());
        $row_rsTotal2 = mysql_fetch_assoc($rsTotal2);
        $totalRows_rsTotal2 = mysql_num_rows($rsTotal2);
		?>
    <td><?php echo number_format($row_rsTotal2['Total'],0,' ',' '); ?>&nbsp;<strong>
      <?php $somme2 = $somme2 + $row_rsTotal2['Total']; ?>
      <?php 
	  	$comissionValue2 = $row_rsSousCommission['membres_commissions_commission_id'];
		$anneeValue2 = $row_rsSousCommission['annee']
	  ?>
      </strong></td>
    <?php } while ($row_rsMois5 = mysql_fetch_assoc($rsMois5)); ?>
      <td nowrap="nowrap"><?php echo number_format($row_rsSousCommission['montant'],0,' ',' '); ?>&nbsp; </td>
      <td align="right" nowrap="nowrap"><?php echo number_format($somme2,0,' ',' '); ?>&nbsp;&nbsp; </td>
    </tr>
    <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
    <tr>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <?php $count = 0; do { $count++;?>
      <?php
		$query_rsTotalMois2 = sprintf("SELECT sum(Nombre_jour * montant) as TotalMois 
		FROM sessions, membres 
		WHERE membres.personnes_personne_id = sessions.membres_personnes_personne_id 
		AND membres_commissions_commission_id = %s 
		AND mois = %s 
		AND annee = %s", 
        GetSQLValueString($comissionValue, "int"),
		GetSQLValueString($row_rsMois6['mois_id'], 	"text"),
		GetSQLValueString($anneeValue, "text"));
		$rsTotalMois2 = mysql_query($query_rsTotalMois2, $MyFileConnect) or die(mysql_error());
		$row_rsTotalMois2 = mysql_fetch_assoc($rsTotalMois2);
		$totalRows_rsTotalMois2 = mysql_num_rows($rsTotalMois2);
		?> 
      <td><strong>
        <?php 
	  		echo number_format($row_rsTotalMois2['TotalMois'],0,' ',' '); 
			$Totaux = $Totaux + $row_rsTotalMois2['TotalMois'];
	  ?>
      </strong></td>
      <?php } while ($row_rsMois6 = mysql_fetch_assoc($rsMois6)); ?>
      <td nowrap="nowrap">&nbsp;</td>
      <td align="right" nowrap="nowrap"><?php echo number_format($Totaux,0,' ',' '); ?></td>
    </tr>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSousCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, 0, $queryString_rsSousCommission); ?>">Premier</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSousCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, max(0, $pageNum_rsSousCommission - 1), $queryString_rsSousCommission); ?>">Pr&eacute;c&eacute;dent</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSousCommission < $totalPages_rsSousCommission) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, min($totalPages_rsSousCommission, $pageNum_rsSousCommission + 1), $queryString_rsSousCommission); ?>">Suivant</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSousCommission < $totalPages_rsSousCommission) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, $totalPages_rsSousCommission, $queryString_rsSousCommission); ?>">Dernier</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsSousCommission + 1) ?> à <?php echo min($startRow_rsSousCommission + $maxRows_rsSousCommission, $totalRows_rsSousCommission) ?> sur <?php echo $totalRows_rsSousCommission ?>
</p>
<p>&nbsp; </p>
<p>&nbsp;
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

mysql_free_result($rsSousCommission);

mysql_free_result($rsMois4);

mysql_free_result($rsMois5);

mysql_free_result($rsMois6);

mysql_free_result($rsSelYear);

mysql_free_result($rsCommissions);

mysql_free_result($rsTotalMois);

mysql_free_result($rsTotal);

mysql_free_result($rsMois2);
?>
