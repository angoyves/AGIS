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
$query_rsAffichePerson = "SELECT * FROM personnes WHERE display = '1'";
$rsAffichePerson = mysql_query($query_rsAffichePerson, $MyFileConnect) or die(mysql_error());
$row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson);
$totalRows_rsAffichePerson = mysql_num_rows($rsAffichePerson);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td>personne_id</td>
    <td>personne_matricule</td>
    <td>personne_nom</td>
    <td>personne_prenom</td>
    <td>personne_grade</td>
    <td>personne_telephone</td>
    <td>structure_id</td>
    <td>sous_groupe_id</td>
    <td>fonction_id</td>
    <td>domaine_id</td>
    <td>type_personne_id</td>
    <td>add_commission</td>
    <td>date_creation</td>
    <td>dateUpdate</td>
    <td>display</td>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="todelete.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>"> <?php echo $row_rsAffichePerson['personne_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsAffichePerson['personne_matricule']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['personne_nom']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['personne_grade']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['personne_telephone']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['structure_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['sous_groupe_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['fonction_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['domaine_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['type_personne_id']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['add_commission']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['date_creation']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['dateUpdate']; ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson)); ?>
</table>
<br />
<?php echo $totalRows_rsAffichePerson ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsAffichePerson);
?>
