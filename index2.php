<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php'); ?>
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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountPersonnes = "SELECT count(personne_id) as Nombres FROM personnes";
$rsCountPersonnes = mysql_query($query_rsCountPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsCountPersonnes = mysql_fetch_assoc($rsCountPersonnes);
$totalRows_rsCountPersonnes = mysql_num_rows($rsCountPersonnes);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountExpert = "SELECT count(personne_id) as NombresExpert FROM personnes WHERE personne_matricule like '%XXX%'";
$rsCountExpert = mysql_query($query_rsCountExpert, $MyFileConnect) or die(mysql_error());
$row_rsCountExpert = mysql_fetch_assoc($rsCountExpert);
$totalRows_rsCountExpert = mysql_num_rows($rsCountExpert);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountCommissions = "SELECT count(commission_id) as NombreCommission FROM commissions";
$rsCountCommissions = mysql_query($query_rsCountCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCountCommissions = mysql_fetch_assoc($rsCountCommissions);
$totalRows_rsCountCommissions = mysql_num_rows($rsCountCommissions);

$maxRows_rsGroupByLocalite = 10;
$pageNum_rsGroupByLocalite = 0;
if (isset($_GET['pageNum_rsGroupByLocalite'])) {
  $pageNum_rsGroupByLocalite = $_GET['pageNum_rsGroupByLocalite'];
}
$startRow_rsGroupByLocalite = $pageNum_rsGroupByLocalite * $maxRows_rsGroupByLocalite;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupByLocalite = "SELECT count(commission_id) as Nombre, localite_lib FROM commissions, localites WHERE commissions.localite_id = localites.localite_id GROUP BY  localites.localite_id";
$query_limit_rsGroupByLocalite = sprintf("%s LIMIT %d, %d", $query_rsGroupByLocalite, $startRow_rsGroupByLocalite, $maxRows_rsGroupByLocalite);
$rsGroupByLocalite = mysql_query($query_limit_rsGroupByLocalite, $MyFileConnect) or die(mysql_error());
$row_rsGroupByLocalite = mysql_fetch_assoc($rsGroupByLocalite);

if (isset($_GET['totalRows_rsGroupByLocalite'])) {
  $totalRows_rsGroupByLocalite = $_GET['totalRows_rsGroupByLocalite'];
} else {
  $all_rsGroupByLocalite = mysql_query($query_rsGroupByLocalite);
  $totalRows_rsGroupByLocalite = mysql_num_rows($all_rsGroupByLocalite);
}
$totalPages_rsGroupByLocalite = ceil($totalRows_rsGroupByLocalite/$maxRows_rsGroupByLocalite)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupByNature = "SELECT lib_nature, count(commission_id) as Nombre FROM commissions, natures WHERE commissions.nature_id = natures.nature_id GROUP BY  natures.nature_id";
$rsGroupByNature = mysql_query($query_rsGroupByNature, $MyFileConnect) or die(mysql_error());
$row_rsGroupByNature = mysql_fetch_assoc($rsGroupByNature);
$totalRows_rsGroupByNature = mysql_num_rows($rsGroupByNature);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupType = "SELECT count(commission_id) as Nombre, type_commission_lib FROM commissions, type_commissions WHERE commissions.type_commission_id = type_commissions.type_commission_id GROUP BY  type_commissions.type_commission_id";
$rsGroupType = mysql_query($query_rsGroupType, $MyFileConnect) or die(mysql_error());
$row_rsGroupType = mysql_fetch_assoc($rsGroupType);
$totalRows_rsGroupType = mysql_num_rows($rsGroupType);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountPersonnel = "SELECT count(personne_id) as NbrPersonnel FROM personnes WHERE personne_matricule <> 'XXXXXX-X'";
$rsCountPersonnel = mysql_query($query_rsCountPersonnel, $MyFileConnect) or die(mysql_error());
$row_rsCountPersonnel = mysql_fetch_assoc($rsCountPersonnel);
$totalRows_rsCountPersonnel = mysql_num_rows($rsCountPersonnel);

$maxRows_rsCountByStructure = 10;
$pageNum_rsCountByStructure = 0;
if (isset($_GET['pageNum_rsCountByStructure'])) {
  $pageNum_rsCountByStructure = $_GET['pageNum_rsCountByStructure'];
}
$startRow_rsCountByStructure = $pageNum_rsCountByStructure * $maxRows_rsCountByStructure;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCountByStructure = "SELECT structure_lib, count(personne_id) as NbrPersonnels FROM personnes, structures WHERE personnes.structure_id = structures.structure_id group by structure_lib";
$query_limit_rsCountByStructure = sprintf("%s LIMIT %d, %d", $query_rsCountByStructure, $startRow_rsCountByStructure, $maxRows_rsCountByStructure);
$rsCountByStructure = mysql_query($query_limit_rsCountByStructure, $MyFileConnect) or die(mysql_error());
$row_rsCountByStructure = mysql_fetch_assoc($rsCountByStructure);

if (isset($_GET['totalRows_rsCountByStructure'])) {
  $totalRows_rsCountByStructure = $_GET['totalRows_rsCountByStructure'];
} else {
  $all_rsCountByStructure = mysql_query($query_rsCountByStructure);
  $totalRows_rsCountByStructure = mysql_num_rows($all_rsCountByStructure);
}
$totalPages_rsCountByStructure = ceil($totalRows_rsCountByStructure/$maxRows_rsCountByStructure)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = "SELECT sum(nombre_jour) as nbreJours FROM SESSIONS";
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

$maxRows_rsSessionsAnnee = 10;
$pageNum_rsSessionsAnnee = 0;
if (isset($_GET['pageNum_rsSessionsAnnee'])) {
  $pageNum_rsSessionsAnnee = $_GET['pageNum_rsSessionsAnnee'];
}
$startRow_rsSessionsAnnee = $pageNum_rsSessionsAnnee * $maxRows_rsSessionsAnnee;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionsAnnee = "SELECT annee, sum(nombre_jour) as nbreJour FROM SESSIONS GROUP BY ANNEE";
$query_limit_rsSessionsAnnee = sprintf("%s LIMIT %d, %d", $query_rsSessionsAnnee, $startRow_rsSessionsAnnee, $maxRows_rsSessionsAnnee);
$rsSessionsAnnee = mysql_query($query_limit_rsSessionsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsSessionsAnnee = mysql_fetch_assoc($rsSessionsAnnee);

if (isset($_GET['totalRows_rsSessionsAnnee'])) {
  $totalRows_rsSessionsAnnee = $_GET['totalRows_rsSessionsAnnee'];
} else {
  $all_rsSessionsAnnee = mysql_query($query_rsSessionsAnnee);
  $totalRows_rsSessionsAnnee = mysql_num_rows($all_rsSessionsAnnee);
}
$totalPages_rsSessionsAnnee = ceil($totalRows_rsSessionsAnnee/$maxRows_rsSessionsAnnee)-1;

$queryString_rsGroupByLocalite = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsGroupByLocalite") == false && 
        stristr($param, "totalRows_rsGroupByLocalite") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsGroupByLocalite = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsGroupByLocalite = sprintf("&totalRows_rsGroupByLocalite=%d%s", $totalRows_rsGroupByLocalite, $queryString_rsGroupByLocalite);

$queryString_rsSessionsAnnee = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSessionsAnnee") == false && 
        stristr($param, "totalRows_rsSessionsAnnee") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSessionsAnnee = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSessionsAnnee = sprintf("&totalRows_rsSessionsAnnee=%d%s", $totalRows_rsSessionsAnnee, $queryString_rsSessionsAnnee);

$queryString_rsCountByStructure = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsCountByStructure") == false && 
        stristr($param, "totalRows_rsCountByStructure") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsCountByStructure = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsCountByStructure = sprintf("&totalRows_rsCountByStructure=%d%s", $totalRows_rsCountByStructure, $queryString_rsCountByStructure);
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
      <table width="200" border="0" cellspacing="20">
        <tr>
          <td valign="top"><h1>Enregistrement saisies...</h1><table width="100%" border="1" class="std">
            <tr>
              <th colspan="2">Nombre d'enregistrement saisis</th>
              </tr>
            <tr>
              <td align="right" nowrap="nowrap">Nombre d'enregistrements dans le Fichier MINMAP</td>
              <td nowrap="nowrap"><?php echo $row_rsCountPersonnes['Nombres']; ?>&nbsp;</td>
              </tr>
            <tr>
              <td align="right" nowrap="nowrap">Nombre d'experts</td>
              <td nowrap="nowrap"><?php echo $row_rsCountExpert['NombresExpert']; ?></td>
              </tr>
            <tr>
              <td align="right" nowrap="nowrap">Nombres de personnels saisie</td>
              <td nowrap="nowrap"><?php echo $row_rsCountPersonnel['NbrPersonnel']; ?></td>
            </tr>
          </table>
         <h1>Personnels par structure</h1>
         <table width="100%" border="1" align="center" class="std">
           <tr>
             <th nowrap="nowrap">Structure Personnels</th>
             <th nowrap="nowrap">Enregistrés</th>
           </tr>
           <?php do { ?>
             <tr>
               <td nowrap="nowrap"><?php echo $row_rsCountByStructure['structure_lib']; ?></td>
               <td nowrap="nowrap"><?php echo $row_rsCountByStructure['NbrPersonnels']; ?>&nbsp; </td>
             </tr>
             <?php } while ($row_rsCountByStructure = mysql_fetch_assoc($rsCountByStructure)); ?>
         </table>
         <br />
         <table border="0">
           <tr>
             <td><?php if ($pageNum_rsCountByStructure > 0) { // Show if not first page ?>
                 <a href="<?php printf("%s?pageNum_rsCountByStructure=%d%s", $currentPage, 0, $queryString_rsCountByStructure); ?>">First</a>
                <?php } // Show if not first page ?></td>
             <td><?php if ($pageNum_rsCountByStructure > 0) { // Show if not first page ?>
                 <a href="<?php printf("%s?pageNum_rsCountByStructure=%d%s", $currentPage, max(0, $pageNum_rsCountByStructure - 1), $queryString_rsCountByStructure); ?>">Previous</a>
                <?php } // Show if not first page ?></td>
             <td><?php if ($pageNum_rsCountByStructure < $totalPages_rsCountByStructure) { // Show if not last page ?>
                 <a href="<?php printf("%s?pageNum_rsCountByStructure=%d%s", $currentPage, min($totalPages_rsCountByStructure, $pageNum_rsCountByStructure + 1), $queryString_rsCountByStructure); ?>">Next</a>
                <?php } // Show if not last page ?></td>
             <td><?php if ($pageNum_rsCountByStructure < $totalPages_rsCountByStructure) { // Show if not last page ?>
                 <a href="<?php printf("%s?pageNum_rsCountByStructure=%d%s", $currentPage, $totalPages_rsCountByStructure, $queryString_rsCountByStructure); ?>">Last</a>
                <?php } // Show if not last page ?></td>
           </tr>
         </table>
Records <?php echo ($startRow_rsCountByStructure + 1) ?> to <?php echo min($startRow_rsCountByStructure + $maxRows_rsCountByStructure, $totalRows_rsCountByStructure) ?> of <?php echo $totalRows_rsCountByStructure ?></td>
          <td><table width="200">
            <tr>
              <th colspan="3" align="left"><h1>NOMBRE TOTAL Commission de passation de marchés: <?php echo $row_rsCountCommissions['NombreCommission']; ?></h1></th>
            </tr>
            <tr align="left">
              <td nowrap="nowrap">Commission par localites</td>
              <td align="left" nowrap="nowrap"><h1>PAR LOCALITE</h1>&nbsp;
                <table border="1" align="left" class="std">
                  <tr class="std">
                    <th nowrap="nowrap">Localites commissions</th>
                    <th nowrap="nowrap">Nombres de commissions</th>
                  </tr>
                  <?php do { ?>
                    <tr>
                      <td nowrap="nowrap"><?php echo $row_rsGroupByLocalite['localite_lib']; ?></td>
                      <td align="right" nowrap="nowrap"><?php echo $row_rsGroupByLocalite['Nombre']; ?>&nbsp; </td>
                    </tr>
                    <?php } while ($row_rsGroupByLocalite = mysql_fetch_assoc($rsGroupByLocalite)); ?>
                </table>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p><br />
                </p>
                <table border="0" align="left">
                  <tr>
                    <td><?php if ($pageNum_rsGroupByLocalite > 0) { // Show if not first page ?>
                      <a href="<?php printf("%s?pageNum_rsGroupByLocalite=%d%s", $currentPage, 0, $queryString_rsGroupByLocalite); ?>">First</a>
                      <?php } // Show if not first page ?></td>
                    <td><?php if ($pageNum_rsGroupByLocalite > 0) { // Show if not first page ?>
                      <a href="<?php printf("%s?pageNum_rsGroupByLocalite=%d%s", $currentPage, max(0, $pageNum_rsGroupByLocalite - 1), $queryString_rsGroupByLocalite); ?>">Previous</a>
                      <?php } // Show if not first page ?></td>
                    <td><?php if ($pageNum_rsGroupByLocalite < $totalPages_rsGroupByLocalite) { // Show if not last page ?>
                      <a href="<?php printf("%s?pageNum_rsGroupByLocalite=%d%s", $currentPage, min($totalPages_rsGroupByLocalite, $pageNum_rsGroupByLocalite + 1), $queryString_rsGroupByLocalite); ?>">Next</a>
                      <?php } // Show if not last page ?></td>
                    <td><?php if ($pageNum_rsGroupByLocalite < $totalPages_rsGroupByLocalite) { // Show if not last page ?>
                      <a href="<?php printf("%s?pageNum_rsGroupByLocalite=%d%s", $currentPage, $totalPages_rsGroupByLocalite, $queryString_rsGroupByLocalite); ?>">Last</a>
                      <?php } // Show if not last page ?></td>
                  </tr>
                </table>
                Records <?php echo ($startRow_rsGroupByLocalite + 1) ?> to <?php echo min($startRow_rsGroupByLocalite + $maxRows_rsGroupByLocalite, $totalRows_rsGroupByLocalite) ?> of <?php echo $totalRows_rsGroupByLocalite ?></td>
              <td nowrap="nowrap">&nbsp;</td>
            </tr>
            <tr align="left">
              <td nowrap="nowrap">&nbsp;</td>
              <td align="left" nowrap="nowrap"><h1>PAR Natures des marches...</h1>&nbsp;
                <table border="1" class="std">
                  <tr>
                    <th>Nature des commissions</th>
                    <th>Nombre</th>
                  </tr>
                  <?php do { ?>
                    <tr>
                      <td><?php echo ucfirst($row_rsGroupByNature['lib_nature']); ?><a href="rien.php?recordID=<?php echo $row_rsGroupByNature['lib_nature']; ?>"></a></td>
                      <td align="right"><?php echo $row_rsGroupByNature['Nombre']; ?>&nbsp; </td>
                    </tr>
                    <?php } while ($row_rsGroupByNature = mysql_fetch_assoc($rsGroupByNature)); ?>
                </table>
                <br />
                <?php echo $totalRows_rsGroupByNature ?> Records Total </td>
              <td nowrap="nowrap">&nbsp;</td>
            </tr>
            <tr align="left">
              <td nowrap="nowrap">&nbsp;</td>
              <td align="left" nowrap="nowrap"><h1>PAR TYPE DE COMMISSIONS</h1>&nbsp;
                <table border="1" align="left" class="std">
                  <tr>
                    <th>Type commission</th>
                    <th>Nombre</th>
                  </tr>
                  <?php do { ?>
                    <tr>
                      <td nowrap="nowrap"><?php echo ucfirst($row_rsGroupType['type_commission_lib']); ?>&nbsp; </td>
                      <td align="right" nowrap="nowrap"><?php echo $row_rsGroupType['Nombre']; ?></td>
                    </tr>
                    <?php } while ($row_rsGroupType = mysql_fetch_assoc($rsGroupType)); ?>
                </table>
                <br />
                <?php echo $totalRows_rsGroupType ?> Records Total </td>
              <td nowrap="nowrap">&nbsp;</td>
            </tr>
          </table></td>
          <td valign="top"><table width="100%" align="left">
            <tr>
              <td colspan="3"><h1>Nombre de sessions</h1><table width="100%" border="1" class="std">
                <tr>
                  <th nowrap="nowrap">Nombres des sessions engistrées :</th>
                </tr>
                <tr>
                  <td><?php echo $row_rsSessions['nbreJours']; ?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="3">&nbsp;
                <h1>Saisie session par Exercice</h1><table border="1" align="center" class="std">
                  <tr>
                    <th nowrap="nowrap">Exercice</th>
                    <th nowrap="nowrap">Nombres des sessions engistrées</th>
                  </tr>
                  <?php do { ?>
                    <tr>
                      <td><a href="to_delete.php?recordID=<?php echo $row_rsSessionsAnnee['annee']; ?>"> <?php echo $row_rsSessionsAnnee['annee']; ?>&nbsp; </a></td>
                      <td>&nbsp; <?php echo $row_rsSessionsAnnee['nbreJour']; ?>&nbsp;</td>
                    </tr>
                    <?php } while ($row_rsSessionsAnnee = mysql_fetch_assoc($rsSessionsAnnee)); ?>
                </table>
                <br />
                <table border="0">
                  <tr>
                    <td><?php if ($pageNum_rsSessionsAnnee > 0) { // Show if not first page ?>
                        <a href="<?php printf("%s?pageNum_rsSessionsAnnee=%d%s", $currentPage, 0, $queryString_rsSessionsAnnee); ?>">First</a>
                      <?php } // Show if not first page ?></td>
                    <td><?php if ($pageNum_rsSessionsAnnee > 0) { // Show if not first page ?>
                        <a href="<?php printf("%s?pageNum_rsSessionsAnnee=%d%s", $currentPage, max(0, $pageNum_rsSessionsAnnee - 1), $queryString_rsSessionsAnnee); ?>">Previous</a>
                      <?php } // Show if not first page ?></td>
                    <td><?php if ($pageNum_rsSessionsAnnee < $totalPages_rsSessionsAnnee) { // Show if not last page ?>
                        <a href="<?php printf("%s?pageNum_rsSessionsAnnee=%d%s", $currentPage, min($totalPages_rsSessionsAnnee, $pageNum_rsSessionsAnnee + 1), $queryString_rsSessionsAnnee); ?>">Next</a>
                      <?php } // Show if not last page ?></td>
                    <td><?php if ($pageNum_rsSessionsAnnee < $totalPages_rsSessionsAnnee) { // Show if not last page ?>
                        <a href="<?php printf("%s?pageNum_rsSessionsAnnee=%d%s", $currentPage, $totalPages_rsSessionsAnnee, $queryString_rsSessionsAnnee); ?>">Last</a>
                      <?php } // Show if not last page ?></td>
                  </tr>
                </table>
Records <?php echo ($startRow_rsSessionsAnnee + 1) ?> to <?php echo min($startRow_rsSessionsAnnee + $maxRows_rsSessionsAnnee, $totalRows_rsSessionsAnnee) ?> of <?php echo $totalRows_rsSessionsAnnee ?></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
          </table>
          &nbsp;</td>
        </tr>
        <tr>
          <td><table border="0">
  <tr im>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
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

mysql_free_result($rsCountPersonnes);

mysql_free_result($rsCountExpert);

mysql_free_result($rsCountCommissions);

mysql_free_result($rsGroupByLocalite);

mysql_free_result($rsGroupByNature);

mysql_free_result($rsGroupType);

mysql_free_result($rsCountPersonnel);

mysql_free_result($rsCountByStructure);

mysql_free_result($rsSessions);

mysql_free_result($rsSessionsAnnee);
?>
