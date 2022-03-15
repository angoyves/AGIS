<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><?php 
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

$maxRows_RsHistorique = 25;
$pageNum_RsHistorique = 0;
if (isset($_GET['pageNum_RsHistorique'])) {
  $pageNum_RsHistorique = $_GET['pageNum_RsHistorique'];
}
$startRow_RsHistorique = $pageNum_RsHistorique * $maxRows_RsHistorique;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_RsHistorique = "SELECT * FROM listing_action ORDER BY ListingId DESC";
$query_limit_RsHistorique = sprintf("%s LIMIT %d, %d", $query_RsHistorique, $startRow_RsHistorique, $maxRows_RsHistorique);
$RsHistorique = mysql_query($query_limit_RsHistorique, $MyFileConnect) or die(mysql_error());
$row_RsHistorique = mysql_fetch_assoc($RsHistorique);

if (isset($_GET['totalRows_RsHistorique'])) {
  $totalRows_RsHistorique = $_GET['totalRows_RsHistorique'];
} else {
  $all_RsHistorique = mysql_query($query_RsHistorique);
  $totalRows_RsHistorique = mysql_num_rows($all_RsHistorique);
}
$totalPages_RsHistorique = ceil($totalRows_RsHistorique/$maxRows_RsHistorique)-1;

$queryString_RsHistorique = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_RsHistorique") == false && 
        stristr($param, "totalRows_RsHistorique") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_RsHistorique = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_RsHistorique = sprintf("&totalRows_RsHistorique=%d%s", $totalRows_RsHistorique, $queryString_RsHistorique);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
<link href="css/v2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="60%" border="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="869" height="46" align="center" valign="bottom">&nbsp;</td>
    <td width="376"><a href="#" title="Imprimer cette page" class="Print_link" onclick="window.print()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
  </tr>
  <tr>
    <td><table>
      <tr>
        <th align="center" nowrap="nowrap">REPUBLIQUE  DU CAMEROUN</BR>
          Paix - Travail - Patrie</BR>
          ------*------</BR>
          PRESIDENCE  DE LA REPUBLIQUE</BR>
          ------*------</BR>
          MINISTERE  DES MARCHES PUBLICS</BR></th>
      </tr>
    </table></td>
    <td width="869" height="48" align="center" valign="bottom">&nbsp;</td>
    <td width="376"><table>
      <tr>
        <th align="center" nowrap="nowrap">REPUBLIC OF CAMEROON</BR>
          Peace - Work - Fatherland</BR>
          ------*------</BR>
          PRESIDENCY  OF THE REPUBLIC</BR>
          ------*------</BR>
          MINISTRY  OF PUBLIC CONTRACTS</th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="157">&nbsp;</td>
    <td align="center" valign="top">ETAT DES COMMISIONS DE PASSATION DES MARCHES</td>
    <td width="376">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center"><strong class="welcome"><?php echo htmlentities(strtoupper($row_rsSessions['commission_lib'])); ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">BILAN du mois de <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($txtMonth1)); ?></strong> à <strong><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($txtMonth2)); ?> <?php echo  $txtYear; ?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="left">
    <table border="1" align="center" class="print">
      <tr>
        <th><strong>ID</strong></th>
        <th><strong>Nom et Prenom</strong></th>
        <th><strong>Lien page</strong></th>
        <th><strong>Type Modification</strong></th>
        <th><strong>DateCreation</strong></th>
        <th><strong>DateUpdate</strong></th>
      </tr>
      <?php $counter=0; do  { $counter++; ?>
      <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
        <td><a href="details_historique.php?recordID=<?php echo $row_RsHistorique['ListingId']; ?>"> <?php echo $row_RsHistorique['ListingId']; ?>&nbsp; </a></td>
        <td nowrap="nowrap"><?php echo strtoupper(MinmapDB::getInstance()->get_user_name_by_id($row_RsHistorique['UserId'])); ?>&nbsp; </td>
        <td><a href="<?php echo $row_RsHistorique['PageLink']; ?>"><?php echo $row_RsHistorique['PageLink']; ?></a>&nbsp; </td>
        <td><?php echo $row_RsHistorique['ModificationType']; ?>&nbsp;</td>
        <td nowrap><?php echo $row_RsHistorique['DateCreation']; ?>&nbsp; </td>
        <td nowrap><?php echo $row_RsHistorique['DateUpdate']; ?>&nbsp; </td>
      </tr>
      <?php } while ($row_RsHistorique = mysql_fetch_assoc($RsHistorique)); ?>
    </table>      <h1>&nbsp;</h1></td>
  </tr>
  <tr>
    <td colspan="3" align="left">&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_RsHistorique > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_RsHistorique=%d%s", $currentPage, 0, $queryString_RsHistorique); ?>">First</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_RsHistorique > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_RsHistorique=%d%s", $currentPage, max(0, $pageNum_RsHistorique - 1), $queryString_RsHistorique); ?>">Previous</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_RsHistorique < $totalPages_RsHistorique) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_RsHistorique=%d%s", $currentPage, min($totalPages_RsHistorique, $pageNum_RsHistorique + 1), $queryString_RsHistorique); ?>">Next</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_RsHistorique < $totalPages_RsHistorique) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_RsHistorique=%d%s", $currentPage, $totalPages_RsHistorique, $queryString_RsHistorique); ?>">Last</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_RsHistorique + 1) ?> to <?php echo min($startRow_RsHistorique + $maxRows_RsHistorique, $totalRows_RsHistorique) ?> of <?php echo $totalRows_RsHistorique ?>
</body>
</html>
<?php
mysql_free_result($RsHistorique);
?>