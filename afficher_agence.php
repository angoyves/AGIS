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
$maxRows_rsAgences = 10;
if (isset($_POST['txtNbre'])){
	$maxRows_rsAgences = $_POST['txtNbre'];
	}
$pageNum_rsAgences = 0;
if (isset($_GET['pageNum_rsAgences'])) {
  $pageNum_rsAgences = $_GET['pageNum_rsAgences'];
}
$startRow_rsAgences = $pageNum_rsAgences * $maxRows_rsAgences;

$colname_rsAgences = "a";
if (isset($_POST['txtSearch'])) {
  $colname_rsAgences = $_POST['txtSearch'];
}
$colname_rsAgences2 = "agence_lib";
if (isset($_POST['txtChamp'])) {
  $colname_rsAgences2 = $_POST['txtChamp'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAgences = sprintf("SELECT * FROM agences, banques WHERE agences.agence_id = banques.banque_id AND agences.display = 1 AND %s LIKE %s ORDER BY agence_lib ASC", GetSQLValueString($colname_rsAgences2, "text"), GetSQLValueString("%" . $colname_rsAgences . "%", "text"));
$query_limit_rsAgences = sprintf("%s LIMIT %d, %d", $query_rsAgences, $startRow_rsAgences, $maxRows_rsAgences);
$rsAgences = mysql_query($query_limit_rsAgences, $MyFileConnect) or die(mysql_error());
$row_rsAgences = mysql_fetch_assoc($rsAgences);

if (isset($_GET['totalRows_rsAgences'])) {
  $totalRows_rsAgences = $_GET['totalRows_rsAgences'];
} else {
  $all_rsAgences = mysql_query($query_rsAgences);
  $totalRows_rsAgences = mysql_num_rows($all_rsAgences);
}
$totalPages_rsAgences = ceil($totalRows_rsAgences/$maxRows_rsAgences)-1;

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
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

$queryString_rsAgences = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAgences") == false && 
        stristr($param, "totalRows_rsAgences") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAgences = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAgences = sprintf("&totalRows_rsAgences=%d%s", $totalRows_rsAgences, $queryString_rsAgences);
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
<h1>Rechercher...</h1>
<form id="form1" name="form1" method="post" action="afficher_agence.php?col=<?php $_GET['txtChamp'] ?>&txt=<?php $_GET['txtSearch'] ?>">
  <table border="0" class="std">
    <tr>
      <td><strong>Rechercher Par :</strong></td>
      <td><select name="txtChamp" id="txtChamp">
        <option value="agence_id" <?php if (!(strcmp("agence_id", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>ID</option>
        <option value="code_agence" <?php if (!(strcmp("code_agence", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Code Agence</option>
        <option value="agence_lib" <?php if (!(strcmp("agence_lib", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Nom Agence</option>
        <option value="code_banque" <?php if (!(strcmp("code_banque", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Code Banque</option>
        <option value="banque_lib" <?php if (!(strcmp("banque_lib", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Nom Banque</option>
      </select></td>
      <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo $_GET['txtSearch']; ?>" />
      <input name="menuID" type="hidden" id="menuID" value="<?php echo $_GET['menuID']; ?>" />
      <input name="action" type="hidden" id="action" value="<?php echo $_GET['action']; ?>" /></td>
      <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
    </tr>
    <tr>
      <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="right">Nombre d'enregistrements :
        <select name="txtNbre">
        <?php $count = 0; do { $count++; ?>
        <option value="<?php echo $count ?>" <?php if (!(strcmp($_POST['txtNbre'], $count))) {echo "selected=\"selected\"";} ?>><?php echo $count ?></option>
        <?php } while($count<50)//fin ?>
        <option value="30">30</option>
        <option value="60">60</option>
        <option value="100">100</option>
      </select>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><?php if (isset($_GET['txtSearch'])) { ?>
        Enregistrement trouvé pour votre recherche &quot; <strong> <?php echo $_GET['txtSearch']; ?></strong> &quot; dans <strong> <?php echo $_GET['txtChamp']; ?></strong>
        <?php } ?></td>
    </tr>
  </table>
</form><h1>Affichage Agence...</h1>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>Code Agence</th>
    <th>Nom Agence</th>
    <th>Cle Agence</th>
    <th>Code Banque</th>
    <th>Nom Banque</th>
  </tr>
  	<?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="detail_agence.php?recordID=<?php echo $row_rsAgences['agence_id']; ?>"> <?php echo $counter; ?>&nbsp; </a></td>
      <td><?php echo $row_rsAgences['agence_code']; ?>&nbsp; </td>
      <td><?php echo $row_rsAgences['agence_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsAgences['agence_cle']; ?>&nbsp; </td>
      <td><?php echo $row_rsAgences['banque_code']; ?>&nbsp; </td>
      <td><?php echo $row_rsAgences['banque_lib']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsAgences = mysql_fetch_assoc($rsAgences)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsAgences > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAgences=%d%s", $currentPage, 0, $queryString_rsAgences); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAgences > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAgences=%d%s", $currentPage, max(0, $pageNum_rsAgences - 1), $queryString_rsAgences); ?>">Pr&eacute;c&eacute;dent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAgences < $totalPages_rsAgences) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAgences=%d%s", $currentPage, min($totalPages_rsAgences, $pageNum_rsAgences + 1), $queryString_rsAgences); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsAgences < $totalPages_rsAgences) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAgences=%d%s", $currentPage, $totalPages_rsAgences, $queryString_rsAgences); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsAgences + 1) ?> à <?php echo min($startRow_rsAgences + $maxRows_rsAgences, $totalRows_rsAgences) ?> sur <?php echo $totalRows_rsAgences ?>
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
mysql_free_result($rsAgences);

mysql_free_result($rsSousMenu);

mysql_free_result($rsMenu);
?>
