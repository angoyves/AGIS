<?php require_once('Connections/MyFileConnect.php'); ?>
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

$maxRows_rsHistorikMessage = 10;
$pageNum_rsHistorikMessage = 0;
if (isset($_GET['pageNum_rsHistorikMessage'])) {
  $pageNum_rsHistorikMessage = $_GET['pageNum_rsHistorikMessage'];
}
$startRow_rsHistorikMessage = $pageNum_rsHistorikMessage * $maxRows_rsHistorikMessage;

$colname_rsHistorikMessage = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_rsHistorikMessage = $_SESSION['MM_UserID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsHistorikMessage = sprintf("SELECT * FROM commentaires WHERE expediteur_id = %s OR destinataire_id = %s ORDER BY dateCreation ASC", GetSQLValueString($colname_rsHistorikMessage, "int"), GetSQLValueString($colname_rsHistorikMessage, "int"));
$query_limit_rsHistorikMessage = sprintf("%s LIMIT %d, %d", $query_rsHistorikMessage, $startRow_rsHistorikMessage, $maxRows_rsHistorikMessage);
$rsHistorikMessage = mysql_query($query_limit_rsHistorikMessage, $MyFileConnect) or die(mysql_error());
$row_rsHistorikMessage = mysql_fetch_assoc($rsHistorikMessage);

if (isset($_GET['totalRows_rsHistorikMessage'])) {
  $totalRows_rsHistorikMessage = $_GET['totalRows_rsHistorikMessage'];
} else {
  $all_rsHistorikMessage = mysql_query($query_rsHistorikMessage);
  $totalRows_rsHistorikMessage = mysql_num_rows($all_rsHistorikMessage);
}
$totalPages_rsHistorikMessage = ceil($totalRows_rsHistorikMessage/$maxRows_rsHistorikMessage)-1;

$queryString_rsHistorikMessage = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsHistorikMessage") == false && 
        stristr($param, "totalRows_rsHistorikMessage") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsHistorikMessage = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsHistorikMessage = sprintf("&totalRows_rsHistorikMessage=%d%s", $totalRows_rsHistorikMessage, $queryString_rsHistorikMessage);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
<link href="css/v2.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1" align="center" class="std2">
  <tr>
    <th>N°</th>
    <th>Destinataire</th>
    <th>Content</th>
    <th>Date dernier message</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="sample68.php?recordID=<?php echo $row_rsHistorikMessage['comment_id']; ?>"> 
	  <?php if(isset($_GET['uID']) && ($_GET['uID']==$row_rsHistorikMessage['expediteur_id'])){ ?>
      	  <img src="images/Icones/envoyer-icone.png" width="30" height="30" border="0" />
		  <?php } else { ?>
		  <img src="images/Icones/mail_repondre.png" width="30" height="30" border="0" />
          <?php } ?></a></td>
      <td align="left" nowrap="nowrap"><?php echo strtoupper(MinmapDB::getInstance()->get_user_name_by_id($row_rsHistorikMessage['destinataire_id'])); ?>&nbsp; </td>
      <td align="left">
      <textarea name="textarea" id="textarea" cols="45" rows="3"><?php echo $row_rsHistorikMessage['comment']; ?></textarea>&nbsp;</td>
      <td nowrap="nowrap"><?php echo $row_rsHistorikMessage['dateCreation']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsHistorikMessage = mysql_fetch_assoc($rsHistorikMessage)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsHistorikMessage > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsHistorikMessage=%d%s", $currentPage, 0, $queryString_rsHistorikMessage); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsHistorikMessage > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsHistorikMessage=%d%s", $currentPage, max(0, $pageNum_rsHistorikMessage - 1), $queryString_rsHistorikMessage); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsHistorikMessage < $totalPages_rsHistorikMessage) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsHistorikMessage=%d%s", $currentPage, min($totalPages_rsHistorikMessage, $pageNum_rsHistorikMessage + 1), $queryString_rsHistorikMessage); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsHistorikMessage < $totalPages_rsHistorikMessage) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsHistorikMessage=%d%s", $currentPage, $totalPages_rsHistorikMessage, $queryString_rsHistorikMessage); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_rsHistorikMessage + 1) ?> to <?php echo min($startRow_rsHistorikMessage + $maxRows_rsHistorikMessage, $totalRows_rsHistorikMessage) ?> of <?php echo $totalRows_rsHistorikMessage ?>
</body>
</html>
<?php
mysql_free_result($rsHistorikMessage);
?>
