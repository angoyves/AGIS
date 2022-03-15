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

$maxRows_rsBanques = 10;
$pageNum_rsBanques = 0;
if (isset($_GET['pageNum_rsBanques'])) {
  $pageNum_rsBanques = $_GET['pageNum_rsBanques'];
}
$startRow_rsBanques = $pageNum_rsBanques * $maxRows_rsBanques;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanques = "SELECT * FROM banques";
$query_limit_rsBanques = sprintf("%s LIMIT %d, %d", $query_rsBanques, $startRow_rsBanques, $maxRows_rsBanques);
$rsBanques = mysql_query($query_limit_rsBanques, $MyFileConnect) or die(mysql_error());
$row_rsBanques = mysql_fetch_assoc($rsBanques);

if (isset($_GET['totalRows_rsBanques'])) {
  $totalRows_rsBanques = $_GET['totalRows_rsBanques'];
} else {
  $all_rsBanques = mysql_query($query_rsBanques);
  $totalRows_rsBanques = mysql_num_rows($all_rsBanques);
}
$totalPages_rsBanques = ceil($totalRows_rsBanques/$maxRows_rsBanques)-1;

$queryString_rsBanques = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsBanques") == false && 
        stristr($param, "totalRows_rsBanques") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsBanques = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsBanques = sprintf("&totalRows_rsBanques=%d%s", $totalRows_rsBanques, $queryString_rsBanques);

//	Toutes les infos concernant le Header
/*include '../../includes/config.jun.php';
include '../../includes/infos_header.jun.php';*/

if(empty($id_region))
{
$id_region=2;
}
?>
<script type="text/javascript">
window.name="maman";
</script>
<html>
<title>Fenetre Mere</title>
<h1>Page mère</h1>

<table border="1" align="center">
  <tr>
    <td>banque_id</td>
    <td>banque_code</td>
    <td>banque_lib</td>
    <td>date_creation</td>
    <td>display</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="detail_page.php?recordID=<?php echo $row_rsBanques['banque_id']; ?>"> <?php echo $row_rsBanques['banque_id']; ?>&nbsp; </a>
      <?php echo '<p><a href="popup.php" onclick="window.open(\'popup.php?banqueID='.$row_rsBanques['banque_id'].'\', \'Popup\', config=\'height=400, width=400, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no\'); return false;">
Ajouter une ville</a></p>'
?>
</td>
      <td><?php echo $row_rsBanques['banque_code']; ?>&nbsp; </td>
      <td><?php echo $row_rsBanques['banque_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsBanques['date_creation']; ?>&nbsp; </td>
      <td><?php echo $row_rsBanques['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsBanques = mysql_fetch_assoc($rsBanques)); ?>
</table>
<br>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsBanques > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, 0, $queryString_rsBanques); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsBanques > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, max(0, $pageNum_rsBanques - 1), $queryString_rsBanques); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsBanques < $totalPages_rsBanques) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, min($totalPages_rsBanques, $pageNum_rsBanques + 1), $queryString_rsBanques); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsBanques < $totalPages_rsBanques) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsBanques=%d%s", $currentPage, $totalPages_rsBanques, $queryString_rsBanques); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsBanques + 1) ?> to <?php echo min($startRow_rsBanques + $maxRows_rsBanques, $totalRows_rsBanques) ?> of <?php echo $totalRows_rsBanques ?>
</html>
<?php
mysql_free_result($rsBanques);
?>
