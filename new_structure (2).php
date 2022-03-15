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
$structureLibIsEmpty = false;
$typeStructureIsEmpty = false;
$localisationIsEmpty = false;
$date = date('Y-m-d H:i:s');

$currentPage = $_SERVER["PHP_SELF"];

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO structures (structure_id, structure_lib, type_structure_id, code_structure, localite_id, date_creation, display) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString(strtoupper($_POST['structure_lib']), "text"),
                       GetSQLValueString($_POST['type_structure_id'], "int"),
                       GetSQLValueString($_POST['code_structure'], "text"),
                       GetSQLValueString($_POST['localite_id'], "int"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  /*$insertGoTo = "show_structures.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/
  echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeStructure = "SELECT type_structure_id, type_structure_lib FROM type_structure WHERE display = '1'";
$rsTypeStructure = mysql_query($query_rsTypeStructure, $MyFileConnect) or die(mysql_error());
$row_rsTypeStructure = mysql_fetch_assoc($rsTypeStructure);
$totalRows_rsTypeStructure = mysql_num_rows($rsTypeStructure);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocalite = "SELECT localite_id, localite_lib FROM localites WHERE display = '1' ORDER BY localite_lib ASC";
$rsLocalite = mysql_query($query_rsLocalite, $MyFileConnect) or die(mysql_error());
$row_rsLocalite = mysql_fetch_assoc($rsLocalite);
$totalRows_rsLocalite = mysql_num_rows($rsLocalite);

$maxRows_rsStructures = 15;
$pageNum_rsStructures = 0;
if (isset($_GET['pageNum_rsStructures'])) {
  $pageNum_rsStructures = $_GET['pageNum_rsStructures'];
}
$startRow_rsStructures = $pageNum_rsStructures * $maxRows_rsStructures;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructures = "SELECT * FROM structures WHERE display = '1' ORDER BY date_creation DESC";
$query_limit_rsStructures = sprintf("%s LIMIT %d, %d", $query_rsStructures, $startRow_rsStructures, $maxRows_rsStructures);
$rsStructures = mysql_query($query_limit_rsStructures, $MyFileConnect) or die(mysql_error());
$row_rsStructures = mysql_fetch_assoc($rsStructures);

if (isset($_GET['totalRows_rsStructures'])) {
  $totalRows_rsStructures = $_GET['totalRows_rsStructures'];
} else {
  $all_rsStructures = mysql_query($query_rsStructures);
  $totalRows_rsStructures = mysql_num_rows($all_rsStructures);
}
$totalPages_rsStructures = ceil($totalRows_rsStructures/$maxRows_rsStructures)-1;

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

$queryString_rsStructures = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsStructures") == false && 
        stristr($param, "totalRows_rsStructures") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsStructures = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsStructures = sprintf("&totalRows_rsStructures=%d%s", $totalRows_rsStructures, $queryString_rsStructures);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
<link href="css/file.css" rel="stylesheet" type="text/css">
<head>
<meta name="google-site-verification" content="UTnSYJLBKJIOsm6MUJcJBkF7fv-Cm7koeofeyBHwBRA" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>AGIS :  Accueil</title>
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
<link href="css/mktree.css" rel="stylesheet" type="text/css">
<link href="css/mktree2.css" rel="stylesheet" type="text/css">
<script src="js/tools.js" language="JavaScript" type="text/javascript"></script>
<script language="Javascript">
	function Fermer()
	{
	opener=self;
	self.close();
	}
</script>
</head>
<body class="home">
</BR>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std2">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Type structure :</th>
      <td align="left"><select name="type_structure_id">
        <option value="" <?php if (!(strcmp("", 4))) {echo "selected=\"selected\"";} ?>>:: Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsTypeStructure['type_structure_id']?>"<?php if (!(strcmp($row_rsTypeStructure['type_structure_id'], 4))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTypeStructure['type_structure_lib']?></option>
        <?php
} while ($row_rsTypeStructure = mysql_fetch_assoc($rsTypeStructure));
  $rows = mysql_num_rows($rsTypeStructure);
  if($rows > 0) {
      mysql_data_seek($rsTypeStructure, 0);
	  $row_rsTypeStructure = mysql_fetch_assoc($rsTypeStructure);
  }
?>
        </select>
        <a href="#" onclick="<?php popup("type_structure.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Localisation structure :</th>
      <td align="left"><select name="localite_id">
        <option value="" <?php if (!(strcmp("", $_GET['locID']))) {echo "selected=\"selected\"";} ?>>:: Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsLocalite['localite_id']?>"<?php if (!(strcmp($row_rsLocalite['localite_id'], $_GET['lID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsLocalite['localite_lib']?></option>
        <?php
} while ($row_rsLocalite = mysql_fetch_assoc($rsLocalite));
  $rows = mysql_num_rows($rsLocalite);
  if($rows > 0) {
      mysql_data_seek($rsLocalite, 0);
	  $row_rsLocalite = mysql_fetch_assoc($rsLocalite);
  }
?>
        </select>
        <a href="#" onclick="<?php popup("new_localites.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      </tr>
    <tr valign="baseline">
      <th align="right" nowrap="nowrap">Code structure :</th>
      <td align="left"><input type="text" name="code_structure" value="" size="32" /> (ex : C. DJOUM)</td>
      </tr>
    <tr valign="baseline">
      <th align="right" nowrap="nowrap">Libelle structure :</th>
      <td align="left"><input type="text" name="structure_lib" value="" size="60" /></BR>
        (ex : Commune de DJOUM)</td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Enregistrer" /></td>
      </tr>
    </table>
  <input type="hidden" name="structure_id" value="" />
  <input type="hidden" name="date_creation" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</body>
</html>
<?php
mysql_free_result($rsTypeStructure);

mysql_free_result($rsLocalite);

mysql_free_result($rsTypeStructure);

mysql_free_result($rsLocalite);

mysql_free_result($rsStructures);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>
