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
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$variable = implode( "**", $_POST['jour'] ); 

echo $variable; 
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsShowMember = 10;
$pageNum_rsShowMember = 0;
if (isset($_GET['pageNum_rsShowMember'])) {
  $pageNum_rsShowMember = $_GET['pageNum_rsShowMember'];
}
$startRow_rsShowMember = $pageNum_rsShowMember * $maxRows_rsShowMember;

$colname_rsShowMember = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsShowMember = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowMember = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s", GetSQLValueString($colname_rsShowMember, "int"));
$query_limit_rsShowMember = sprintf("%s LIMIT %d, %d", $query_rsShowMember, $startRow_rsShowMember, $maxRows_rsShowMember);
$rsShowMember = mysql_query($query_limit_rsShowMember, $MyFileConnect) or die(mysql_error());
$row_rsShowMember = mysql_fetch_assoc($rsShowMember);

if (isset($_GET['totalRows_rsShowMember'])) {
  $totalRows_rsShowMember = $_GET['totalRows_rsShowMember'];
} else {
  $all_rsShowMember = mysql_query($query_rsShowMember);
  $totalRows_rsShowMember = mysql_num_rows($all_rsShowMember);
}
$totalPages_rsShowMember = ceil($totalRows_rsShowMember/$maxRows_rsShowMember)-1;

$queryString_rsShowMember = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowMember") == false && 
        stristr($param, "totalRows_rsShowMember") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowMember = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowMember = sprintf("&totalRows_rsShowMember=%d%s", $totalRows_rsShowMember, $queryString_rsShowMember);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>


</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <p>&nbsp;  </p>
  <table border="1" align="center">
    <tr>
      <td>commissions_commission_id</td>
      <td>fonctions_fonction_id</td>
      <td>personnes_personne_id</td>
      <td>1</td>
      <td>2</td>
      <td>3</td>
      <td>4</td>
      <td>5</td>
      <td>6</td>
      <td>7</td>
      <td>8</td>
      <td>9</td>
      <td>10</td>
      <td>11</td>
      <td>12</td>
      <td>13</td>
      <td>14</td>
      <td>15</td>
      <td>16</td>
      <td>17</td>
      <td>18</td>
      <td>19</td>
      <td>20</td>
      <td>21</td>
      <td>22</td>
      <td>23</td>
      <td>24</td>
      <td>25</td>
      <td>26</td>
      <td>27</td>
      <td>28</td>
      <td>29</td>
      <td>30</td>
      <td>31</td>
    </tr>
    <?php do { ?>
    <tr>
      <td><a href="details_mmbre.php?recordID=<?php echo $row_rsShowMember['commissions_commission_id']; ?>"> <?php echo $row_rsShowMember['commissions_commission_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsShowMember['fonctions_fonction_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowMember['personnes_personne_id']; ?>&nbsp; </td>
      <td><input name="jour[]" type="checkbox" id="jour32" value="01" /></td>
      <td><input name="jour[]" type="checkbox" id="jour33" value="02" /></td>
      <td><input name="jour[]" type="checkbox" id="jour34" value="03" /></td>
      <td><input name="jour[]" type="checkbox" id="jour35" value="04" /></td>
      <td><input name="jour[]" type="checkbox" id="jour36" value="05" /></td>
      <td><input name="jour[]" type="checkbox" id="jour37" value="06" /></td>
      <td><input name="jour[]" type="checkbox" id="jour38" value="07" /></td>
      <td><input name="jour[]" type="checkbox" id="jour39" value="08" /></td>
      <td><input name="jour" type="checkbox" id="jour40" value="09" /></td>
      <td><input name="jour" type="checkbox" id="jour41" value="10" /></td>
      <td><input name="jour" type="checkbox" id="jour42" value="11" /></td>
      <td><input name="jour" type="checkbox" id="jour43" value="12" /></td>
      <td><input name="jour" type="checkbox" id="jour44" value="13" /></td>
      <td><input name="jour" type="checkbox" id="jour45" value="14" /></td>
      <td><input name="jour" type="checkbox" id="jour46" value="15" /></td>
      <td><input name="jour" type="checkbox" id="jour47" value="16" /></td>
      <td><input name="jour" type="checkbox" id="jour48" value="17" /></td>
      <td><input name="jour" type="checkbox" id="jour49" value="18" /></td>
      <td><input name="jour" type="checkbox" id="jour50" value="19" /></td>
      <td><input name="jour" type="checkbox" id="jour51" value="20" /></td>
      <td><input name="jour" type="checkbox" id="jour52" value="21" /></td>
      <td><input name="jour" type="checkbox" id="jour53" value="22" /></td>
      <td><input name="jour" type="checkbox" id="jour54" value="23" /></td>
      <td><input name="jour" type="checkbox" id="jour55" value="24" /></td>
      <td><input name="jour" type="checkbox" id="jour56" value="25" /></td>
      <td><input name="jour" type="checkbox" id="jour57" value="26" /></td>
      <td><input name="jour" type="checkbox" id="jour58" value="27" /></td>
      <td><input name="jour" type="checkbox" id="jour59" value="28" /></td>
      <td><input name="jour" type="checkbox" id="jour60" value="29" /></td>
      <td><input name="jour" type="checkbox" id="jour61" value="30" /></td>
      <td><input name="jour" type="checkbox" id="jour62" value="31" /></td>
    </tr>
    <?php } while ($row_rsShowMember = mysql_fetch_assoc($rsShowMember)); ?>
  </table>
  <p>
    <input type="hidden" name="MM_insert" value="form1" />
    <input type="submit" value="Insert record" />
  </p>
  <br />
</form>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsShowMember > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, 0, $queryString_rsShowMember); ?>">First</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowMember > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, max(0, $pageNum_rsShowMember - 1), $queryString_rsShowMember); ?>">Previous</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowMember < $totalPages_rsShowMember) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, min($totalPages_rsShowMember, $pageNum_rsShowMember + 1), $queryString_rsShowMember); ?>">Next</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsShowMember < $totalPages_rsShowMember) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, $totalPages_rsShowMember, $queryString_rsShowMember); ?>">Last</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsShowMember + 1) ?> to <?php echo min($startRow_rsShowMember + $maxRows_rsShowMember, $totalRows_rsShowMember) ?> of <?php echo $totalRows_rsShowMember ?>
</p>
</body>
</html>
<?php
mysql_free_result($rsShowMember);
?>
