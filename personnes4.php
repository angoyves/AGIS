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

$colname_rsPersonnes = "-1";
if (isset($_POST['txtSearch'])) {
  $colname_rsPersonnes = $_POST['txtSearch'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = sprintf("SELECT personne_id, personne_matricule, personne_nom, personne_prenom FROM personnes WHERE personne_nom LIKE %s OR personne_prenom LIKE %s ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname_rsPersonnes . "%", "text"), GetSQLValueString("%" . $colname_rsPersonnes . "%", "text"));
$rsPersonnes = mysql_query($query_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);
$totalRows_rsPersonnes = mysql_num_rows($rsPersonnes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="1" align="center" class="std">
  <tr>
    <td colspan="4"><form id="form1" name="form1" method="post" action="">
      Rechercher : 
      <input type="text" name="txtSearch" id="txtSearch" />
      <input type="submit" name="BtnSearch" id="BtnSearch" value="Submit" />
    </form></td>
  </tr>
<tr>
    <td colspan="4">Resultat de la recherche pour : <?php echo $_POST['txtSearch'] ?></td>
  </tr> 
  <tr>
    <th>ID</th>
    <th>MATRICULE</th>
    <th>NOM</th>
    <th>PRENOM</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
<tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><p><a href="" onclick="window.opener.location.href='<?php 
	  $insertGoTo = "affecter_membres.php?" . $_GET['link4'] . "=". $row_rsPersonnes['personne_id'];
	  if (isset($_SERVER['QUERY_STRING'])) {
		$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
		$insertGoTo .= $_SERVER['QUERY_STRING'];
	  }
	  echo $insertGoTo; ?>';self.close();"> <?php echo $row_rsPersonnes['personne_id']; ?></a></p></td>
      <td><?php echo $row_rsPersonnes['personne_matricule']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['personne_nom']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['personne_prenom']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsPersonnes = mysql_fetch_assoc($rsPersonnes)); ?>
</table>
<br />
<?php echo $totalRows_rsPersonnes ?> Records Total
</body>
</html>
<?php
mysql_free_result($rsPersonnes);
?>
