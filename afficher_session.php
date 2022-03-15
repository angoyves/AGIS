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

$maxRows_rsShowSession = 10;
$pageNum_rsShowSession = 0;
if (isset($_GET['pageNum_rsShowSession'])) {
  $pageNum_rsShowSession = $_GET['pageNum_rsShowSession'];
}
$startRow_rsShowSession = $pageNum_rsShowSession * $maxRows_rsShowSession;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowSession = "SELECT * FROM sessions";
$query_limit_rsShowSession = sprintf("%s LIMIT %d, %d", $query_rsShowSession, $startRow_rsShowSession, $maxRows_rsShowSession);
$rsShowSession = mysql_query($query_limit_rsShowSession, $MyFileConnect) or die(mysql_error());
$row_rsShowSession = mysql_fetch_assoc($rsShowSession);

if (isset($_GET['totalRows_rsShowSession'])) {
  $totalRows_rsShowSession = $_GET['totalRows_rsShowSession'];
} else {
  $all_rsShowSession = mysql_query($query_rsShowSession);
  $totalRows_rsShowSession = mysql_num_rows($all_rsShowSession);
}
$totalPages_rsShowSession = ceil($totalRows_rsShowSession/$maxRows_rsShowSession)-1;

$queryString_rsShowSession = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowSession") == false && 
        stristr($param, "totalRows_rsShowSession") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowSession = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowSession = sprintf("&totalRows_rsShowSession=%d%s", $totalRows_rsShowSession, $queryString_rsShowSession);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <th>Personne</th>
    <th>Commission</th>
    <th>Fonction</th>
    <th>dossiers traités</th>
    <th>nombre de dossiers</th>
    <th>Date</th>
    <th>dateCreation</th>
    <th>Etat</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="detail_session.php?recordID=<?php echo $row_rsShowSession['membres_personnes_personne_id']; ?>"> <?php echo $row_rsShowSession['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsShowSession['membres_commissions_commission_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSession['membres_fonctions_fonction_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSession['dossiers_dossier_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSession['nombre_dossier']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSession['jour'] . "/" . $row_rsShowSession['mois']  . "/" . $row_rsShowSession['annee']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSession['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowSession['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsShowSession = mysql_fetch_assoc($rsShowSession)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsShowSession > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowSession=%d%s", $currentPage, 0, $queryString_rsShowSession); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowSession > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowSession=%d%s", $currentPage, max(0, $pageNum_rsShowSession - 1), $queryString_rsShowSession); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowSession < $totalPages_rsShowSession) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowSession=%d%s", $currentPage, min($totalPages_rsShowSession, $pageNum_rsShowSession + 1), $queryString_rsShowSession); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsShowSession < $totalPages_rsShowSession) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowSession=%d%s", $currentPage, $totalPages_rsShowSession, $queryString_rsShowSession); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsShowSession + 1) ?> to <?php echo min($startRow_rsShowSession + $maxRows_rsShowSession, $totalRows_rsShowSession) ?> of <?php echo $totalRows_rsShowSession ?>
</body>
</html>
<?php
mysql_free_result($rsShowSession);
?>
