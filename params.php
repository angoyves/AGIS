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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_RsParams = "SELECT * FROM params";
$RsParams = mysql_query($query_RsParams, $MyFileConnect) or die(mysql_error());
$row_RsParams = mysql_fetch_assoc($RsParams);
$totalRows_RsParams = mysql_num_rows($RsParams);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td>param_id</td>
    <td>retenu</td>
    <td>Exercice</td>
    <td>Taux</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="insparams.php?recordID=<?php echo $row_RsParams['param_id']; ?>"> <?php echo $row_RsParams['param_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_RsParams['retenu']; ?>&nbsp; </td>
      <td><?php echo $row_RsParams['Exercice']; ?>&nbsp; </td>
      <td><?php echo $row_RsParams['Taux']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_RsParams = mysql_fetch_assoc($RsParams)); ?>
</table>
<br />
<?php echo $totalRows_RsParams ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($RsParams);
?>
