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

$maxRows_rsAppurements = 50;
$pageNum_rsAppurements = 0;
if (isset($_GET['pageNum_rsAppurements'])) {
  $pageNum_rsAppurements = $_GET['pageNum_rsAppurements'];
}
$startRow_rsAppurements = $pageNum_rsAppurements * $maxRows_rsAppurements;

$txtComID = "";
if (isset($_REQUEST['comID']))
$txtComID = $_REQUEST['comID'];

$colname_rsAppurements = "";
if (isset($_REQUEST['chpID'])) 
  $colname_rsAppurements = $_REQUEST['chpID'];

mysql_select_db($database_MyFileConnect, $MyFileConnect);

isset($_POST['chpID'])?
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE (motif LIKE %s OR nom_beneficiaire LIKE %s OR rib_beneficiaire LIKE %s)  ORDER BY nom_beneficiaire, motif ASC", GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString("%" . $colname_rsAppurements . "%", "text")):
$query_rsAppurements = sprintf("SELECT * FROM appurements WHERE motif LIKE %s AND commission_id = %s ORDER BY nom_beneficiaire, motif ASC", GetSQLValueString("%" . $colname_rsAppurements . "%", "text"), GetSQLValueString($txtComID, "int"));

$query_limit_rsAppurements = sprintf("%s LIMIT %d, %d", $query_rsAppurements, $startRow_rsAppurements, $maxRows_rsAppurements);
$rsAppurements = mysql_query($query_limit_rsAppurements, $MyFileConnect) or die(mysql_error());
$row_rsAppurements = mysql_fetch_assoc($rsAppurements);

if (isset($_GET['totalRows_rsAppurements'])) {
  $totalRows_rsAppurements = $_GET['totalRows_rsAppurements'];
} else {
  $all_rsAppurements = mysql_query($query_rsAppurements);
  $totalRows_rsAppurements = mysql_num_rows($all_rsAppurements);
}
$totalPages_rsAppurements = ceil($totalRows_rsAppurements/$maxRows_rsAppurements)-1;

$queryString_rsAppurements = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAppurements") == false && 
        stristr($param, "totalRows_rsAppurements") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAppurements = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAppurements = sprintf("&totalRows_rsAppurements=%d%s", $totalRows_rsAppurements, $queryString_rsAppurements);
?>
<link href="css/mktree.css" rel="stylesheet" type="text/css">
<link href="css/mktree2.css" rel="stylesheet" type="text/css">
<script src="js/tools.js" language="JavaScript" type="text/javascript"></script>
<table border="1" align="center" class="std">
  <tr align="center">
    <td colspan="8" align="left"><form id="form1" name="form1" method="post" action="">
      <table width="200" border="0">
        <tr>
          <th scope="row">&nbsp;</th>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <th nowrap="nowrap" scope="row">Motif Paiement : </th>
          <td><input type="text" name="chpID" id="chpID" /></td>
          <td><input type="submit" name="button" id="button" value="Envoyer" /></td>
        </tr>
        <tr>
          <th scope="row">&nbsp;</th>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
    </form></td>
  </tr>
  <tr align="center">
    <th nowrap="nowrap">N° VIREMENT</th>
    <th nowrap="nowrap">RIB BENEFICIAIRE</th>
    <th nowrap="nowrap">NOM BENEFICIAIRE</th>
    <th nowrap="nowrap">REFERENCE DOSSIER</th>
    <th nowrap="nowrap">MOTIF PAIEMENT</th>
    <th nowrap="nowrap">MONTANT</th>
    <th nowrap="nowrap">NOM</th>
    <th nowrap="nowrap">RIB </th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
  <?php 
  		//$nameAll = $row_rsAppurements['personne_nom'] . ' ' . $row_rsAppurements['personne_prenom'];
		//$nomSessions = strtoupper(trim(substr($nameAll,0,6)));
		$nomOV = strtoupper(trim(substr($row_rsAppurements['nom_beneficiaire'],0,5)));
		//$tatAppurement = MinmapDB::getInstance()->verify_appurement_credentials_2($comID, $row_rsAppurements['rib_beneficiaire'], $m1ID, $m2ID, $aID); 
  		$etatRib = MinmapDB::getInstance()->verify_rib_credentials($row_rsAppurements['rib_beneficiaire']); 
		$etatVerifName = MinmapDB::getInstance()->verify_person_credentials($nomOV);
  ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><?php echo $row_rsAppurements['num_virement']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['rib_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['nom_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['ref_dossier']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['motif']; ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsAppurements['montant'],0,' ',' '); ?>&nbsp; </td>
      <?php           
			$showGoTo = "search_prson.php?pID=".trim($row_rsAppurements['nom_beneficiaire'])."&rID=".trim($row_rsAppurements['rib_beneficiaire'])."&modID=2";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
      <td align="center"><?php if ($etatVerifName) {?>
        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } else { ?>
        <img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/>
      <?php } ?></td>
      <td align="center"><?php if ($etatRib) {?>
        <img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/>
      <?php } else { ?>
        <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } ?>
  &nbsp; </td>
    </tr>
    <?php } while ($row_rsAppurements = mysql_fetch_assoc($rsAppurements)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsAppurements > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAppurements=%d%s", $currentPage, 0, $queryString_rsAppurements); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAppurements > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAppurements=%d%s", $currentPage, max(0, $pageNum_rsAppurements - 1), $queryString_rsAppurements); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAppurements < $totalPages_rsAppurements) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAppurements=%d%s", $currentPage, min($totalPages_rsAppurements, $pageNum_rsAppurements + 1), $queryString_rsAppurements); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsAppurements < $totalPages_rsAppurements) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAppurements=%d%s", $currentPage, $totalPages_rsAppurements, $queryString_rsAppurements); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsAppurements + 1) ?> à <?php echo min($startRow_rsAppurements + $maxRows_rsAppurements, $totalRows_rsAppurements) ?> sur <?php echo $totalRows_rsAppurements ?>

<?php
mysql_free_result($rsAppurements);
?>
