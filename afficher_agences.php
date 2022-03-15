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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO agences (agence_id, banque_code, agence_code, agence_lib, agence_cle, date_creation, display) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['agence_id'], "int"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['agence_lib'], "text"),
                       GetSQLValueString($_POST['agence_cle'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "new_personnes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form2")) {
  $updateSQL = sprintf("UPDATE agences SET agence_code=%s, agence_lib=%s, banque_code=%s, agence_cle=%s, date_creation=%s, display=%s WHERE agence_id=%s",
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['agence_lib'], "text"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_cle'], "text"),
                       GetSQLValueString($_POST['date_creation'], "date"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['agence_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "agence.php";
/*   if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  } */
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanques = "SELECT banque_code, banque_lib FROM banques WHERE display = '1'";
$rsBanques = mysql_query($query_rsBanques, $MyFileConnect) or die(mysql_error());
$row_rsBanques = mysql_fetch_assoc($rsBanques);
$totalRows_rsBanques = mysql_num_rows($rsBanques);

$colname_rsUpdAgences = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsUpdAgences = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUpdAgences = sprintf("SELECT * FROM agences WHERE agence_id = %s", GetSQLValueString($colname_rsUpdAgences, "int"));
$rsUpdAgences = mysql_query($query_rsUpdAgences, $MyFileConnect) or die(mysql_error());
$row_rsUpdAgences = mysql_fetch_assoc($rsUpdAgences);
$totalRows_rsUpdAgences = mysql_num_rows($rsUpdAgences);

$maxRows_rsAgences = 10;
$pageNum_rsAgences = 0;
if (isset($_GET['pageNum_rsAgences'])) {
  $pageNum_rsAgences = $_GET['pageNum_rsAgences'];
}
$startRow_rsAgences = $pageNum_rsAgences * $maxRows_rsAgences;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAgences = "SELECT * FROM agences ORDER BY dateUpdate DESC";
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
<h1>Inserer une nouvelle agence...</h1>
<table align="center">
  <?php if ($totalRows_rsUpdAgences == 0) { // Show if recordset empty ?>
  <tr>
    <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
      <table align="center" class="std2">
        <?php //if ($totalRows_rsBanques == 0) { // Show if recordset empty ?>
          <tr valign="baseline">
            <th align="right" valign="top" nowrap="nowrap">Banque:</th>
            <td><select name="banque_code">
              <?php do {  ?>
						<option value="<?php echo $row_rsBanques['banque_code']?>"><?php echo $row_rsBanques['banque_lib']?></option>
					<?php
					} while ($row_rsBanques = mysql_fetch_assoc($rsBanques));
					  $rows = mysql_num_rows($rsBanques);
					  if($rows > 0) {
						  mysql_data_seek($rsBanques, 0);
						  $row_rsBanques = mysql_fetch_assoc($rsBanques);
					  }
				?>
            </select></td>
            <td>&nbsp;</td>
          </tr>
          <?php } // Show if recordset empty ?>
        <?php// if ($totalRows_rsBanques > 0) { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" nowrap="nowrap"> Banque: </th>
    <td colspan="2">&nbsp;</td>
  </tr>
          <tr valign="baseline">
            <th align="right" nowrap="nowrap">Code agence :</th>
            <td colspan="2"><input name="agence_code2" type="text" value="" size="10" maxlength="5" /></td>
          </tr>
          <tr valign="baseline">
            <th nowrap="nowrap" align="right">Libelle agence:</th>
            <td colspan="2"><input type="text" name="agence_lib" value="" size="32" /></td>
          </tr>
          <tr valign="baseline">
            <th nowrap="nowrap" align="right">Cle agence:</th>
            <td colspan="2"><input name="agence_cle" type="text" value="" size="20" maxlength="10" /></td>
          </tr>
          <tr valign="baseline">
            <td nowrap="nowrap" align="right">&nbsp;</td>
            <td colspan="2"><input type="submit" value="Insérer un enregistrement" /></td>
          </tr>
      </table>
      <input type="hidden" name="agence_id" value="" />
      <input type="hidden" name="date_creation" value="" />
      <input type="hidden" name="display" value="1" />
      <input type="hidden" name="MM_insert" value="form1" />
    </form></td>
  </tr>
  <tr>
    <td>&nbsp;
<h1>Afficher Agences...</h1>
      <table border="0" align="center" class="std">
        <tr>
          <th>N&deg;</th>
          <th>CODE AGENCE</th>
          <th>NOM AGENCE</th>
          <th>CODE BANQUE</th>
          <th>CLE</th>
          <th>Activer</th>
          <th>Modifier</th>
          <th>Supprimer</th>
          <th>Detail</th>
        </tr>
  		<?php $counter=0; do  { $counter++; ?>
    	<tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
            <td><a href="agence.php?recordID=<?php echo $row_rsAgences['agence_id']; ?>"> <?php echo $row_rsAgences['agence_id']; ?>&nbsp; </a></td>
            <td><?php echo $row_rsAgences['agence_code']; ?>&nbsp; </td>
            <td><?php echo $row_rsAgences['agence_lib']; ?>&nbsp; </td>
            <td><?php echo $row_rsAgences['banque_code']; ?>&nbsp; </td>
            <td><?php echo $row_rsAgences['agence_cle']; ?>&nbsp; </td>
            <td><?php if (isset($row_rsAgences['display']) && $row_rsAgences['display'] == '1') { ?>
              <a href="change_etat.php?recordID=<?php echo $row_rsAgences['agence_id']; ?>&&map=agences&&mapid=agence_id&&action=desactive"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
              <?php } else { ?>
              <a href="change_etat.php?recordID=<?php echo $row_rsAgences['agence_id']; ?>&&map=agences&&mapid=agence_id&&action=active"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
              <?php } ?></td>
            <td><a href="edit_agences.php?recordID=<?php echo $row_rsAgences['agence_id']; ?>&&map=agences"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
            <td><a href="delete.php?recordID=<?php echo $row_rsAgences['agence_id']; ?>&&map=agences"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
            <td><a href="detail_agences.php?recordID=<?php echo $row_rsAgences['agence_id']; ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
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
      Enregistrements <?php echo ($startRow_rsAgences + 1) ?> à <?php echo min($startRow_rsAgences + $maxRows_rsAgences, $totalRows_rsAgences) ?> sur <?php echo $totalRows_rsAgences ?></td>
  </tr>
  <?php } // Show if recordset empty ?>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?php if ($totalRows_rsUpdAgences > 0) { // Show if recordset not empty ?>
    <tr>
      <td>&nbsp;
        <form action="<?php echo $editFormAction; ?>" method="post" name="form2" id="form2">
          <table align="center">
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Agence_code:</td>
              <td><input type="text" name="agence_code" value="<?php echo htmlentities($row_rsUpdAgences['agence_code'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Agence_lib:</td>
              <td><input type="text" name="agence_lib" value="<?php echo htmlentities($row_rsUpdAgences['agence_lib'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Banque_code:</td>
              <td><select name="banque_code">
                <?php 
do {  
?>
                <option value="<?php echo $row_rsBanques['banque_code']?>" <?php if (!(strcmp($row_rsBanques['banque_code'], htmlentities($row_rsUpdAgences['banque_code'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsBanques['banque_code'] . " : " . $row_rsBanques['banque_lib']?></option>
                <?php
} while ($row_rsBanques = mysql_fetch_assoc($rsBanques));
?>
              </select></td>
            </tr>
            <tr> </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">Agence_cle:</td>
              <td><input type="text" name="agence_cle" value="<?php echo htmlentities($row_rsUpdAgences['agence_cle'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
            </tr>
            <tr valign="baseline">
              <td nowrap="nowrap" align="right">&nbsp;</td>
              <td><input type="submit" value="Mettre &agrave; jour l'enregistrement" /></td>
            </tr>
          </table>
          <input type="hidden" name="agence_id" value="<?php echo $row_rsUpdAgences['agence_id']; ?>" />
          <input type="hidden" name="date_creation" value="<?php echo htmlentities($row_rsUpdAgences['date_creation'], ENT_COMPAT, 'utf-8'); ?>" />
          <input type="hidden" name="display" value="<?php echo htmlentities($row_rsUpdAgences['display'], ENT_COMPAT, 'utf-8'); ?>" />
          <input type="hidden" name="MM_update" value="form2" />
          <input type="hidden" name="agence_id" value="<?php echo $row_rsUpdAgences['agence_id']; ?>" />
        </form></td>
    </tr>
    <?php } // Show if recordset not empty ?>
<tr>
    <td>&nbsp;</td>
  </tr>

  
</table>
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
mysql_free_result($rsBanques);

mysql_free_result($rsUpdAgences);

mysql_free_result($rsAgences);

mysql_free_result($rsSousMenu);

mysql_free_result($rsMenu);
?>
