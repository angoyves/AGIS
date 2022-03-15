<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('includes/MyFonction.php'); ?>
<?php
$date = date('Y-m-d H:i:s');
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if (isset($_POST[commission_lib]) && isset($_POST['count']))
{
	$_Libelle = $_POST[commission_lib]."_".$_POST['count'];
	}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO commissions (commission_id, commission_parent, localite_id, type_commission_id, nature_id, commission_lib, membre_insert, dateCreation, dateUpdate, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['commission_parent'], "int"),
                       GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString($_POST['type_commission_id'], "int"),
                       GetSQLValueString($_POST['nature_id'], "int"),
                       GetSQLValueString($_Libelle, "text"),
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "show_commissions.php";
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
$query_rsCommissions = sprintf("SELECT commission_id, localite_id, type_commission_id, nature_id, commission_lib FROM commissions WHERE commission_id = %s", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname_rsCommissionLib = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissionLib = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissionLib = sprintf("SELECT commission_id, commission_lib, commissions.nature_id, lib_nature, commissions.localite_id, localite_lib, commission_parent, commissions.type_commission_id, type_commission_lib FROM commissions, natures, localites, type_commissions WHERE commissions.nature_id = natures.nature_id AND commissions.localite_id = localites.localite_id AND commissions.type_commission_id = type_commissions.type_commission_id AND commissions.commission_id = %s", GetSQLValueString($colname_rsCommissionLib, "int"));
$rsCommissionLib = mysql_query($query_rsCommissionLib, $MyFileConnect) or die(mysql_error());
$row_rsCommissionLib = mysql_fetch_assoc($rsCommissionLib);
$totalRows_rsCommissionLib = mysql_num_rows($rsCommissionLib);

$colname_rsCount = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCount = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCount = sprintf("SELECT count(commission_id) FROM commissions WHERE commission_parent = %s", GetSQLValueString($colname_rsCount, "int"));
$rsCount = mysql_query($query_rsCount, $MyFileConnect) or die(mysql_error());
$row_rsCount = mysql_fetch_assoc($rsCount);
$totalRows_rsCount = mysql_num_rows($rsCount);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

$colname_Recordset1 = "-1";
if (isset($_GET['menuID'])) {
  $colname_Recordset1 = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_Recordset1 = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $MyFileConnect) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);
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
    <h1>Crer une sous commission</h1>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<table border="0" align="center">
        <tr>
          <td><form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
            <h1>Cr&eacute;er un sous commissions</h1>
            <table align="center" class="std2">
              <tr valign="baseline">
                <th align="right" valign="middle" nowrap="nowrap">Type commission <span class="error">* </span>:</th>
                <td>
                <select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
                  <option value="">Selectionner...</option>
                  <?php
do {  
?>
                  <option value="new_commissions.php?typID=<?php echo $row_rsTypeCommission['type_commission_id']; ?>&menuID=<?php echo $_GET['menuID'] ?>" <?php if (!(strcmp($row_rsTypeCommission['type_commission_id'], $_GET['typID']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rsTypeCommission['type_commission_lib']);?></option>
                  <?php
} while ($row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission));
  $rows = mysql_num_rows($rsTypeCommission);
  if($rows > 0) {
      mysql_data_seek($rsTypeCommission, 0);
	  $row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
  }
?>
                </select>
                  <a href="#" onclick="<?php popup("new_type_commissions.php", "450", "300"); ?>"> <img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>        
			      <input type="hidden" name="type_commission_id" id="type_commission_id" value="<?php echo $_GET['typID'] ?>"/>
			      <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($typecommissionIsEmpty) { ?>
			<div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selectionner le type de la commission, SVP!</div>
        	<?php } ?>
        </td>
              </tr>
              <tr valign="baseline">
                <th align="right" valign="middle" nowrap="nowrap">Nature <span class="error">* </span>:</th>
                <td>
				<?php if (isset($_GET['typID']) && $_GET['typID'] != "2") { ?>
                <input type="hidden" name="localite_id" id="localite_id" value="2" />
                <?php } ?>
                  <select name="nature_id">
                    <option value="">Choisir...</option>
                    <?php
do {  
?>
                    <option value="<?php echo $row_rsNature['nature_id']?>" <?php if (!(strcmp($row_rsNature['nature_id'], $_POST['nature_id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities(ucfirst(strtolower($row_rsNature['lib_nature'])));?></option>
                    <?php
} while ($row_rsNature = mysql_fetch_assoc($rsNature));
  $rows = mysql_num_rows($rsNature);
  if($rows > 0) {
      mysql_data_seek($rsNature, 0);
	  $row_rsNature = mysql_fetch_assoc($rsNature);
  }
?>
                  </select>
                  <a href="#" onclick="<?php popup("new_natures.php", "450", "300"); ?>">
                  <img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
			<?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($natureIsEmpty) { ?>
        	<div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selectionner la nature , SVP!</div>
        	<?php } ?>
                  </td>
              </tr>
              <?php if (empty($_GET['typID']) || $_GET['typID'] != "2") { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">Localité <span class="error">* </span>:</th>
    <td><select name="localite_id">
      <option value="">Seletionner...</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsLocalisation['localite_id']?>" <?php if (!(strcmp($row_rsLocalisation['localite_id'], $_POST['localite_id']))) {echo "selected=\"selected\"";} ?>><?php echo htmlentities($row_rsLocalisation['localite_lib']);?></option>
      <?php
} while ($row_rsLocalisation = mysql_fetch_assoc($rsLocalisation));
  $rows = mysql_num_rows($rsLocalisation);
  if($rows > 0) {
      mysql_data_seek($rsLocalisation, 0);
	  $row_rsLocalisation = mysql_fetch_assoc($rsLocalisation);
  }
?>
    </select>
      <a href="#" onclick="<?php popup("new_localites.php", "450", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($localiteIsEmpty) { ?>
      <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selectionner la localite, SVP!</div>
      <?php } ?>
      </td>
  </tr>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>	  
  <?php  }// Show if recordset not empty ?>
<?php if (isset($_GET['typID']) && $_GET['typID'] == "2") { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">Ministère : </th>
    <td><select name="structure_id" id="structure_id">
      <option value="">Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsStructure['structure_id']?>"><?php echo $row_rsStructure['structure_lib']?></option>
      <?php
} while ($row_rsStructure = mysql_fetch_assoc($rsStructure));
  $rows = mysql_num_rows($rsStructure);
  if($rows > 0) {
      mysql_data_seek($rsStructure, 0);
	  $row_rsStructure = mysql_fetch_assoc($rsStructure);
  }
?>
    </select></td>
  </tr>
  <?php } // Show if recordset not empty ?>
  <?php if (isset($_GET['typID']) && $_GET['typID'] == "6") { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">Commission Parent  :</th>
    <td>
    <select name="commission_parent">
<?php if (empty($_GET['comID'])) { ?>
      <option value="">Choisir...</option>
<?php } ?>	
      <?php
do {  
?>
      <option value="<?php echo $row_rsCommisions['commission_id']?>" <?php if (!(strcmp($_GET['comID'], $_POST['commission_id']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst(strtolower($row_rsCommisions['commission_lib'])) ?></option>
      <?php
} while ($row_rsCommisions = mysql_fetch_assoc($rsCommisions));
  $rows = mysql_num_rows($rsCommisions);
  if($rows > 0) {
      mysql_data_seek($rsCommisions, 0);
	  $row_rsCommisions = mysql_fetch_assoc($rsCommisions);
  }
?>
    </select></td>
  </tr>
  <?php } // Show if recordset not empty ?>
<tr valign="baseline">
  <td nowrap="nowrap" align="right">&nbsp;</td>
                <td><input name="Submit" type="submit" value="Enregistrer" /></td>
              </tr>
            </table>
            <input type="hidden" name="commission_id" value="" />
            <input type="hidden" name="MM_insert" value="form1" />
          </form>
          <?php if (isset($_GET['valid']) && $_GET['valid'] == 'ok'){ ?> <span class="succes">Commission insérée avec succès!!!</span><?php } ?></td>
        </tr>
      </table>
  <input type="hidden" name="count" value="<?php echo $row_rsCount['count(commission_id)'] + 1; ?>" size="32" />
  <input type="hidden" name="localite_id" value="<?php echo $row_rsCommissions['localite_id']; ?>" size="32" />
  <input type="hidden" name="type_commission_id2" value="<?php echo $row_rsCommissions['type_commission_id']; ?>" size="32" />
  <input type="hidden" name="nature_id" value="<?php echo $row_rsCommissions['nature_id']; ?>" size="32" />
  <input type="hidden" name="commission_id" value="" />
  <input type="hidden" name="membre_insert" value="0" />
  <input type="hidden" name="dateCreation" value="" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
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
mysql_free_result($rsCommissions);

mysql_free_result($rsCommissionLib);

mysql_free_result($rsCount);

mysql_free_result($rsSousMenu);

mysql_free_result($Recordset1);

mysql_free_result($rsMenu);

mysql_free_result($rsTypeCommission);
?>
