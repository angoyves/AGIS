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
?>
<?php
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$variable1 = implode( "**", $_POST['deux'] ); 
$variable2 = implode( "**", $_POST['un'] ); 
$variable3 = implode( "**", $_POST['trois'] ); 
$variable4 = implode( "**", $_POST['quatre'] );

echo $variable1; 
echo "<br />";
echo $variable2; 
echo "<br />";
echo $variable3; 
echo "<br />";
echo $variable4; 
}

?>
<?php
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
<script type="text/javascript">
function MM_jumpMenuGo(objId,targ,restore){ //v9.0
  var selObj = null;  with (document) { 
  if (getElementById) selObj = getElementById(objId);
  if (selObj) eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0; }
}
</script>
</head>

<body>
<form action="" method="post" name="form1" id="form1">
<table border="1" align="center">
  <tr>
    <td>commissions_commission_id</td>
    <td>fonctions_fonction_id</td>
    <td>personnes_personne_id</td>
	<?php 
	$counter=0; do  { $counter++; ?>
    <td><?php echo $counter ?></td>
	<?php }while ($counter<30);
	?>
  </tr>
  <?php do { ?>
  <tr>
    <td><a href="detail_test3.php?recordID=<?php echo $row_rsShowMember['commissions_commission_id']; ?>"> <?php echo $row_rsShowMember['commissions_commission_id']; ?>&nbsp; </a></td>
    <td><?php echo $row_rsShowMember['fonctions_fonction_id']; ?>&nbsp; </td>
    <td><?php echo $row_rsShowMember['personnes_personne_id']; ?>&nbsp; </td>
<?php $counter=0; do  { $counter++; ?>
	<td>
    <?php echo $counter ?></br>
    <?php echo $row_rsShowMember['checboxName'].$counter; ?>
    <input name="<?php echo $row_rsShowMember['checboxName'] . "[]"; ?>" type="checkbox" id="<?php echo $row_rsShowMember['checboxName'].$counter; ?>" value="<?php echo $counter ?>" />
    </td>
<?php	}while ($counter<30);
?>
  </tr>
  <?php } while ($row_rsShowMember = mysql_fetch_assoc($rsShowMember)); ?>
</table>
<input type="hidden" name="MM_insert" value="form1" />
<input type="submit" value="Insert record" />
</form>
<p>&nbsp;
<br />
<form name="form" id="form">
  <input type="checkbox" name="jumpMenu" id="jumpMenu" />
  <input type="button" name="go_button" id= "go_button" value="Go" onclick="MM_jumpMenuGo('jumpMenu','parent',1)" />
</form>
<form name="form2" id="form2">
  <select name="jumpMenu2" id="jumpMenu2">
    <option value="index.php">index</option>
  </select>
  <input type="button" name="go_button2" id= "go_button2" value="Go" onclick="MM_jumpMenuGo('jumpMenu2','parent',0)" />
</form>
<p>
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
