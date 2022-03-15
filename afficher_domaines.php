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

$currentPage = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $currentPage .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

  $insertGoTo = "new_personnes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }

$maxRows_rsShowDomaines = 25;
$pageNum_rsShowDomaines = 0;
if (isset($_GET['pageNum_rsShowDomaines'])) {
  $pageNum_rsShowDomaines = $_GET['pageNum_rsShowDomaines'];
}
$startRow_rsShowDomaines = $pageNum_rsShowDomaines * $maxRows_rsShowDomaines;

$colname_rsShowDomaines = "";
if (isset($_POST['txt_search'])) {
  $colname_rsShowDomaines = $_POST['txt_search'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowDomaines = sprintf("SELECT domaine_id, domaine_lib FROM domaines_activites WHERE domaine_lib LIKE %s ORDER BY dateCreate DESC", GetSQLValueString("%" . $colname_rsShowDomaines . "%", "text"));
$rsShowDomaines = mysql_query($query_rsShowDomaines, $MyFileConnect) or die(mysql_error());
$row_rsShowDomaines = mysql_fetch_assoc($rsShowDomaines);
$totalRows_rsShowDomaines = mysql_num_rows($rsShowDomaines);

$queryString_rsShowDomaines = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowDomaines") == false && 
        stristr($param, "totalRows_rsShowDomaines") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowDomaines = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowDomaines = sprintf("&totalRows_rsShowDomaines=%d%s", $totalRows_rsShowDomaines, $queryString_rsShowDomaines);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table align="center">
  <tr>
    <th><form id="form1" name="form1" method="post" action="">
      <table align="left">
        <tr>
          <th scope="col">Rechercher : </th>
          <th scope="col"><input type="text" name="txt_search" id="txt_search" /></th>
          <th scope="col"><input type="submit" name="button" id="button" value="Submit" /></th>
        </tr>
      </table>
    </form></th>
  </tr>
  <tr>
    <th align="left">Resultat de la recherche pour : <span class="error"><?php echo $_POST['txt_search'] ?></span></th>
  </tr>
  <tr>
    <th><table width="100%" border="1" class="std">
      <tr>
        <th>ID</th>
        <th>LIBELLE DOMAINE</th>
      </tr>
      <?php do { ?>
      <tr>
        <td><a href="#" onclick="window.opener.location.href='<?php echo $insertGoTo; ?>&&domID=<?php echo $row_rsShowDomaines['domaine_id']; ?>';self.close();">[Select::]&nbsp; </a></td>
        <td align="left"><?php echo $row_rsShowDomaines['domaine_lib']; ?>&nbsp; </td>
      </tr>
  	 <?php } while ($row_rsShowDomaines = mysql_fetch_assoc($rsShowDomaines)); ?>
    </table></th>
  </tr>
  <tr>
    <th align="left"><br />
      <table border="0">
        <tr>
          <td><?php if ($pageNum_rsShowDomaines > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsShowDomaines=%d%s", $currentPage, 0, $queryString_rsShowDomaines); ?>">First</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsShowDomaines > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsShowDomaines=%d%s", $currentPage, max(0, $pageNum_rsShowDomaines - 1), $queryString_rsShowDomaines); ?>">Previous</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsShowDomaines < $totalPages_rsShowDomaines) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsShowDomaines=%d%s", $currentPage, min($totalPages_rsShowDomaines, $pageNum_rsShowDomaines + 1), $queryString_rsShowDomaines); ?>">Next</a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_rsShowDomaines < $totalPages_rsShowDomaines) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsShowDomaines=%d%s", $currentPage, $totalPages_rsShowDomaines, $queryString_rsShowDomaines); ?>">Last</a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table>
Records <?php echo ($startRow_rsShowDomaines + 1) ?> to <?php echo min($startRow_rsShowDomaines + $maxRows_rsShowDomaines, $totalRows_rsShowDomaines) ?> of <?php echo $totalRows_rsShowDomaines ?></th>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsShowDomaines);
?>
