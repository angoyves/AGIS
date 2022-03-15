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

$maxRows_rsDossiers = 10;
$pageNum_rsDossiers = 0;
if (isset($_GET['pageNum_rsDossiers'])) {
  $pageNum_rsDossiers = $_GET['pageNum_rsDossiers'];
}
$startRow_rsDossiers = $pageNum_rsDossiers * $maxRows_rsDossiers;

$colname_rsDossiers = "a";
if (isset($_POST['txtSearch'])) {
  $colname_rsDossiers = $_POST['txtSearch'];
}
$colname1_rsDossiers = "a";
if (isset($_POST['txtSearch'])) {
  $colname1_rsDossiers = $_POST['txtSearch'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsDossiers = sprintf("SELECT * FROM dossiers WHERE dossier_nom LIKE %s OR dossier_ref LIKE %s", GetSQLValueString("%" . $colname_rsDossiers . "%", "text"),GetSQLValueString("%" . $colname1_rsDossiers . "%", "text"));
$query_limit_rsDossiers = sprintf("%s LIMIT %d, %d", $query_rsDossiers, $startRow_rsDossiers, $maxRows_rsDossiers);
$rsDossiers = mysql_query($query_limit_rsDossiers, $MyFileConnect) or die(mysql_error());
$row_rsDossiers = mysql_fetch_assoc($rsDossiers);

if (isset($_GET['totalRows_rsDossiers'])) {
  $totalRows_rsDossiers = $_GET['totalRows_rsDossiers'];
} else {
  $all_rsDossiers = mysql_query($query_rsDossiers);
  $totalRows_rsDossiers = mysql_num_rows($all_rsDossiers);
}
$totalPages_rsDossiers = ceil($totalRows_rsDossiers/$maxRows_rsDossiers)-1;

$queryString_rsDossiers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsDossiers") == false && 
        stristr($param, "totalRows_rsDossiers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsDossiers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsDossiers = sprintf("&totalRows_rsDossiers=%d%s", $totalRows_rsDossiers, $queryString_rsDossiers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td>dossier_id</td>
    <td>dossier_ref</td>
    <td>dossier_nom</td>
    <td>dossiers_jour</td>
    <td>dossier_observ</td>
    <td>dateCreation</td>
    <td>display</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="todele_dossier.php?recordID=<?php echo $row_rsDossiers['dossier_id']; ?>"> <?php echo $row_rsDossiers['dossier_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsDossiers['dossier_ref']; ?>&nbsp; </td>
      <td><?php echo $row_rsDossiers['dossier_nom']; ?>&nbsp; </td>
      <td><?php echo $row_rsDossiers['dossiers_jour']; ?>&nbsp; </td>
      <td><?php echo $row_rsDossiers['dossier_observ']; ?>&nbsp; </td>
      <td><?php echo $row_rsDossiers['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_rsDossiers['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsDossiers = mysql_fetch_assoc($rsDossiers)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsDossiers > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsDossiers=%d%s", $currentPage, 0, $queryString_rsDossiers); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsDossiers > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsDossiers=%d%s", $currentPage, max(0, $pageNum_rsDossiers - 1), $queryString_rsDossiers); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsDossiers < $totalPages_rsDossiers) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsDossiers=%d%s", $currentPage, min($totalPages_rsDossiers, $pageNum_rsDossiers + 1), $queryString_rsDossiers); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsDossiers < $totalPages_rsDossiers) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsDossiers=%d%s", $currentPage, $totalPages_rsDossiers, $queryString_rsDossiers); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsDossiers + 1) ?> à <?php echo min($startRow_rsDossiers + $maxRows_rsDossiers, $totalRows_rsDossiers) ?> sur <?php echo $totalRows_rsDossiers ?>
</body>
</html>
<?php
mysql_free_result($rsDossiers);
?>
