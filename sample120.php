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

$colname_Recordset1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_Recordset1 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_Recordset1 = sprintf("SELECT * FROM appurements WHERE personne_id = %s ORDER BY num_virement ASC", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $MyFileConnect) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <th>num_virement</th>
    <th>commission_id</th>
    <th>personne_id</th>
    <th>fonction_id</th>
    <th>ref_dossier</th>
    <th>nombre_jour</th>
    <th>etat_appur</th>
    <th>periode_debut</th>
    <th>periode_fin</th>
    <th>annee</th>
    <th>dateCreation</th>
    <th>dateUpdate</th>
    <th>display</th>
    <th>user_id</th>
    <th>etat_validation</th>
    <th>userValidate</th>
    <th>userControlate</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="sample121.php?recordID=<?php echo $row_Recordset1['num_virement']; ?>"> <?php echo $row_Recordset1['num_virement']; ?>&nbsp; </a></td>
      <td><?php echo $row_Recordset1['commission_id']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['personne_id']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['fonction_id']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['ref_dossier']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['nombre_jour']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['etat_appur']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['periode_debut']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['periode_fin']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['annee']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['dateUpdate']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['display']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['user_id']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['etat_validation']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['userValidate']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['userControlate']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
<br />
<?php echo $totalRows_Recordset1 ?> Records Total
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
