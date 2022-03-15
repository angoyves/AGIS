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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsComment = 10;
$pageNum_rsComment = 0;
if (isset($_GET['pageNum_rsComment'])) {
  $pageNum_rsComment = $_GET['pageNum_rsComment'];
}
$startRow_rsComment = $pageNum_rsComment * $maxRows_rsComment;

$colname_rsComment = "-1";
$userID = MinmapDB::getInstance()->get_user_id_by_name($_SESSION['MM_Username']);
if (isset($_SESSION['MM_UserID'])) {
  $colname_rsComment = $_SESSION['MM_UserID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if ($colname_rsComment==12) {
$query_rsComment = sprintf("SELECT * FROM commentaires ORDER BY comment_id DESC", GetSQLValueString($colname_rsComment, "int"));
} else {
$query_rsComment = sprintf("SELECT * FROM commentaires WHERE notation = 0 AND destinataire_id = %s ORDER BY comment_id DESC", GetSQLValueString($colname_rsComment, "int"));
}
$query_limit_rsComment = sprintf("%s LIMIT %d, %d", $query_rsComment, $startRow_rsComment, $maxRows_rsComment);
$rsComment = mysql_query($query_limit_rsComment, $MyFileConnect) or die(mysql_error());
$row_rsComment = mysql_fetch_assoc($rsComment);

if (isset($_GET['totalRows_rsComment'])) {
  $totalRows_rsComment = $_GET['totalRows_rsComment'];
} else {
  $all_rsComment = mysql_query($query_rsComment);
  $totalRows_rsComment = mysql_num_rows($all_rsComment);
}
$totalPages_rsComment = ceil($totalRows_rsComment/$maxRows_rsComment)-1;

$queryString_rsComment = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsComment") == false && 
        stristr($param, "totalRows_rsComment") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsComment = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsComment = sprintf("&totalRows_rsComment=%d%s", $totalRows_rsComment, $queryString_rsComment);
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
    <th nowrap="nowrap">N°</th>
    <th nowrap="nowrap">Detail</th>
    <th>Objet</th>
    <th>Expéditeur</th>
    <th>Etat</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><?php echo $counter; ?>&nbsp;</td>
      <td><a href="sample73.php?recordID=<?php echo $row_rsComment['comment_id']; ?>">Ouvrir </a></td>
      <td align="left"><?php echo $row_rsComment['objet']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php //echo $row_rsComment['expediteur_id']; ?><?php echo MinmapDB::getInstance()->get_user_name($row_rsComment['expediteur_id']); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsComment['notation']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsComment['dateCreation']; ?></td>
      <td nowrap="nowrap"><a href="sample72.php?expID=<?php echo $row_rsComment['expediteur_id']; ?>&comntID=<?php echo $row_rsComment['comment_id']; ?>"><img src="images/Icones/repondre_msg.jpg" width="50" height="50" border="0" /></a></td>
    </tr>
    <?php } while ($row_rsComment = mysql_fetch_assoc($rsComment)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsComment > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsComment=%d%s", $currentPage, 0, $queryString_rsComment); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsComment > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsComment=%d%s", $currentPage, max(0, $pageNum_rsComment - 1), $queryString_rsComment); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsComment < $totalPages_rsComment) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsComment=%d%s", $currentPage, min($totalPages_rsComment, $pageNum_rsComment + 1), $queryString_rsComment); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsComment < $totalPages_rsComment) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsComment=%d%s", $currentPage, $totalPages_rsComment, $queryString_rsComment); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsComment + 1) ?> à <?php echo min($startRow_rsComment + $maxRows_rsComment, $totalRows_rsComment) ?> sur <?php echo $totalRows_rsComment ?>
</body>
</html>
<?php
mysql_free_result($rsComment);
?>
