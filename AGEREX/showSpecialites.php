<?php require_once('../Connections/AGEREX.php'); ?>
<?php
	  require_once('includes/db.php');
?>
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

mysql_select_db($database_AGEREX, $AGEREX);
$query_rsDomaines = "SELECT * FROM domaine";
$rsDomaines = mysql_query($query_rsDomaines, $AGEREX) or die(mysql_error());
$row_rsDomaines = mysql_fetch_assoc($rsDomaines);
$totalRows_rsDomaines = mysql_num_rows($rsDomaines);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<p><a href="specialites.php">Ajouter</a></p>
<table border="1" align="center">
  <tr>
    <td>domaineCompetenceID</td>
    <td>TypeDomaines_typeDomaineID</td>
    <td>domaineName</td>
    <td>display</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="detailsSpecialites.php?recordID=<?php echo $row_rsDomaines['domaineCompetenceID']; ?>"> <?php echo $row_rsDomaines['domaineCompetenceID']; ?>&nbsp; </a></td>
      <td><?php echo MinmapDB::getInstance()->get_domaine_lib($row_rsDomaines['TypeDomaines_typeDomaineID']); ?>&nbsp; </td>
      <td><?php echo $row_rsDomaines['domaineName']; ?>&nbsp; </td>
      <td><?php echo $row_rsDomaines['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsDomaines = mysql_fetch_assoc($rsDomaines)); ?>
</table>
<br />
<?php echo $totalRows_rsDomaines ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsDomaines);
?>
