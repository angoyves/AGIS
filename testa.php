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

$colname_rsSelSessions = "12";
if (isset($_SESSION['MM_UserID'])) {
  $colname_rsSelSessions = $_SESSION['MM_UserID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelSessions = sprintf("SELECT * FROM sessions WHERE user_id = %s AND etat_validation = 0 AND userValidate IS NOT NULL AND userControlate IS NOT NULL GROUP BY mois, annee, membres_commissions_commission_id", GetSQLValueString($colname_rsSelSessions, "int"));
$rsSelSessions = mysql_query($query_rsSelSessions, $MyFileConnect) or die(mysql_error());
$row_rsSelSessions = mysql_fetch_assoc($rsSelSessions);
$totalRows_rsSelSessions = mysql_num_rows($rsSelSessions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="1" align="center">
  <?php do { ?>
    <tr>
      <td><a href="detail_sessions_mod.php?recordID=<?php echo $row_rsSelSessions['membres_commissions_commission_id']; ?>"> <?php echo $row_rsSelSessions['membres_commissions_commission_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsSelSessions['mois']; ?>&nbsp; </td>
      <td><?php echo $row_rsSelSessions['annee']; ?>&nbsp; </td>
      <td><?php echo $row_rsSelSessions['user_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsSelSessions['etat_validation']; ?>&nbsp; </td>
      <td><?php echo $row_rsSelSessions['userValidate']; ?>&nbsp; </td>
      <td><?php echo $row_rsSelSessions['userControlate']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsSelSessions = mysql_fetch_assoc($rsSelSessions)); ?>
</table>
<br />
<?php echo $totalRows_rsSelSessions ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsSelSessions);
?>
