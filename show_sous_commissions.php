<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');?>
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

$maxRows_rsSousCommission = 10;
$pageNum_rsSousCommission = 0;
if (isset($_GET['pageNum_rsSousCommission'])) {
  $pageNum_rsSousCommission = $_GET['pageNum_rsSousCommission'];
}
$startRow_rsSousCommission = $pageNum_rsSousCommission * $maxRows_rsSousCommission;

$colname_rsSousCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsSousCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousCommission = sprintf("SELECT commission_id, commissions.localite_id, localite_lib, commissions.nature_id, lib_nature, commission_lib  FROM commissions, localites, natures WHERE commissions.localite_id = localites.localite_id AND commissions.nature_id = natures.nature_id AND commission_parent = %s", GetSQLValueString($colname_rsSousCommission, "int"));
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1" align="center" class="std">
  <tr>
    <th nowrap="nowrap">ID</th>
    <th nowrap="nowrap">Localite</th>
    <th nowrap="nowrap">Nature</th>
    <th nowrap="nowrap">Type commission</th>
    <th nowrap="nowrap">Libelle</th>
  </tr>
  <?php do { ?>
    <tr>
   	<?php $membreID = MinmapDB::getInstance()->get_value_id_by_value(personnes_personne_id, membres, commissions_commission_id, $row_rsSous_Commissions['commission_id']);?>
      <td nowrap="nowrap"> 
      <?php if ($membreID) { ?>
      <a href="Details_sous_commissions.php?comID=<?php echo $row_rsSousCommission['commission_id']; ?>&menu=<?php echo $_GET['menuID']; ?>">Afficher les membres&nbsp; </a>
      <?php } else { ?>
      <a href="new_membres.php?comID=<?php echo $row_rsSousCommission['commission_id']; ?>&amp;menuID=<?php echo $_GET['menuID']; ?>">Ajouter des membres&nbsp; </a>
      <?php } ?>
      </td>
      <td nowrap="nowrap"><?php echo ucfirst(strtolower($row_rsSousCommission['localite_lib'])); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo ucfirst(strtolower($row_rsSousCommission['lib_nature'])); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo ucfirst(strtolower($row_rsSousCommission['type_commission_lib'])); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo ucfirst(strtolower($row_rsSousCommission['commission_lib'])); ?>&nbsp;</td>
    </tr>
    <?php } while ($row_rsSousCommission = mysql_fetch_assoc($rsSousCommission)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSousCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, 0, $queryString_rsSousCommission); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSousCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSousCommission=%d%s", $currentPage, max(0, $pageNum_rsSousCommission - 1), $queryString_rsSousCommission); ?>">Précédent</a>
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
</body>
</html>
<?php
mysql_free_result($rsSousCommission);
?>
