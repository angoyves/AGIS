<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('includes/MyFonction.php'); ?>
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

$maxRows_rsShowCommission = 10;
$pageNum_rsShowCommission = 0;
if (isset($_GET['pageNum_rsShowCommission'])) {
  $pageNum_rsShowCommission = $_GET['pageNum_rsShowCommission'];
}
$startRow_rsShowCommission = $pageNum_rsShowCommission * $maxRows_rsShowCommission;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowCommission = "SELECT * FROM commissions";
$query_limit_rsShowCommission = sprintf("%s LIMIT %d, %d", $query_rsShowCommission, $startRow_rsShowCommission, $maxRows_rsShowCommission);
$rsShowCommission = mysql_query($query_limit_rsShowCommission, $MyFileConnect) or die(mysql_error());
$row_rsShowCommission = mysql_fetch_assoc($rsShowCommission);

if (isset($_GET['totalRows_rsShowCommission'])) {
  $totalRows_rsShowCommission = $_GET['totalRows_rsShowCommission'];
} else {
  $all_rsShowCommission = mysql_query($query_rsShowCommission);
  $totalRows_rsShowCommission = mysql_num_rows($all_rsShowCommission);
}
$totalPages_rsShowCommission = ceil($totalRows_rsShowCommission/$maxRows_rsShowCommission)-1;


$queryString_rsShowCommission = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowCommission") == false && 
        stristr($param, "totalRows_rsShowCommission") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowCommission = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowCommission = sprintf("&totalRows_rsShowCommission=%d%s", $totalRows_rsShowCommission, $queryString_rsShowCommission);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0">
  <tr>
    <th align="left"><a href="accueil.php"><img src="images/img/b_home.png" alt="" width="16" height="16" align="absmiddle"/></a>Retour à l'accueil</th>
  </tr>
  <tr>
    <th width="185" align="left" scope="col"><a href="new_personnes.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter une personne</th>
  </tr>
  <tr>
    <th align="left" scope="col"><a href="create_membres.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter des membres à une commission</th>
  </tr>
</table>
<p>&nbsp;</p>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>Localité</th>
    <th>Type Commission</th>
    <th>Nature</th>
    <th>Add</th>
    <th>Date Creation</th>
    <th>dateUpdate</th>
    <th>Etat</th>
    <th>Edit</th>
    <th>Del</th>
    <th>Detail</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="afficher_membres.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>"> <?php echo $row_rsShowCommission['commission_id']; ?>&nbsp; </a></td>
      <td><?php 
	  
	  	$colname_rsShowLocalite = "-1";
		if (isset($row_rsShowCommission['localite_id'])) {
		  $colname_rsShowLocalite = $row_rsShowCommission['localite_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowLocalite = sprintf("SELECT localite_id, localite_lib FROM localites WHERE localite_id = %s", GetSQLValueString($colname_rsShowLocalite, "int"));
		$rsShowLocalite = mysql_query($query_rsShowLocalite, $MyFileConnect) or die(mysql_error());
		$row_rsShowLocalite = mysql_fetch_assoc($rsShowLocalite);
		$totalRows_rsShowLocalite = mysql_num_rows($rsShowLocalite);
	  
	  echo htmlentities($row_rsShowLocalite['localite_lib']); ?>&nbsp; </td>
      <td><?php 
	  
		$colname_rsShowTypeCommission = "-1";
		if (isset($row_rsShowCommission['type_commission_id'])) {
		  $colname_rsShowTypeCommission = $row_rsShowCommission['type_commission_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowTypeCommission = sprintf("SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE type_commission_id = %s", GetSQLValueString($colname_rsShowTypeCommission, "int"));
		$rsShowTypeCommission = mysql_query($query_rsShowTypeCommission, $MyFileConnect) or die(mysql_error());
		$row_rsShowTypeCommission = mysql_fetch_assoc($rsShowTypeCommission);
		$totalRows_rsShowTypeCommission = mysql_num_rows($rsShowTypeCommission);	  
	  
	  echo htmlentities($row_rsShowTypeCommission['type_commission_lib']); ?>&nbsp; </td>
      <td><?php 
	  
		$colname_rsShowNature = "-1";
		if (isset($row_rsShowCommission['nature_id'])) {
		  $colname_rsShowNature = $row_rsShowCommission['nature_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowNature = sprintf("SELECT nature_id, lib_nature FROM natures WHERE nature_id = %s", GetSQLValueString($colname_rsShowNature, "int"));
		$rsShowNature = mysql_query($query_rsShowNature, $MyFileConnect) or die(mysql_error());
		$row_rsShowNature = mysql_fetch_assoc($rsShowNature);
		$totalRows_rsShowNature = mysql_num_rows($rsShowNature);

	  echo htmlentities($row_rsShowNature['lib_nature']); ?>&nbsp; </td>
      <td><?php if (isset($row_rsShowCommission['membre_insert']) && $row_rsShowCommission['membre_insert'] == '1') { ?>
        <img src="images/img/b_usrlist.png" alt="" width="16" height="16" align="absmiddle"/>
        <?php } else { ?>
        <a href="create_membres.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>"><img src="images/img/b_usradd.png" alt="" width="16" height="16" align="absmiddle"/></a><a href="affecter_membres.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>"></a>
      <?php } ?>        &nbsp; </td>
      <td><?php echo $row_rsShowCommission['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_rsShowCommission['dateUpdate']; ?>&nbsp; </td>
      <td bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>" class="std"><?php if (isset($row_rsShowCommission['display']) && $row_rsShowCommission['display'] == '1') { ?>
        <a href="change_etat.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>&&map=commissions&&mapid=commission_id&&action=desactive"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } else { ?>
        <a href="change_etat.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>&&map=commissions&&mapid=commission_id&&action=active"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } ?></td>
      <td bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>" class="std"><a href="edit_commissions.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>&&map=commissions"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>" class="std"><a href="delete.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>&&map=commissions"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>" class="std"><a href="afficher_membres.php?recordID=<?php echo $row_rsShowCommission['commission_id']; ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <?php } while ($row_rsShowCommission = mysql_fetch_assoc($rsShowCommission)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsShowCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommission=%d%s", $currentPage, 0, $queryString_rsShowCommission); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowCommission > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommission=%d%s", $currentPage, max(0, $pageNum_rsShowCommission - 1), $queryString_rsShowCommission); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowCommission < $totalPages_rsShowCommission) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommission=%d%s", $currentPage, min($totalPages_rsShowCommission, $pageNum_rsShowCommission + 1), $queryString_rsShowCommission); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsShowCommission < $totalPages_rsShowCommission) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowCommission=%d%s", $currentPage, $totalPages_rsShowCommission, $queryString_rsShowCommission); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<blockquote>
  <p>  Records <?php echo ($startRow_rsShowCommission + 1) ?> to <?php echo min($startRow_rsShowCommission + $maxRows_rsShowCommission, $totalRows_rsShowCommission) ?> of <?php echo $totalRows_rsShowCommission ?>
  </p>
</blockquote>
</body>
</html>
<?php
mysql_free_result($rsShowCommission);

mysql_free_result($rsShowLocalite);

mysql_free_result($rsShowNature);

mysql_free_result($rsShowTypeCommission);
?>
