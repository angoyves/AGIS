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

$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
  $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
  $colname_DetailRS1 = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_DetailRS1 = sprintf("SELECT * FROM commissions WHERE commission_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysql_query($query_limit_DetailRS1, $MyFileConnect) or die(mysql_error());
$row_DetailRS1 = mysql_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
  $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
  $all_DetailRS1 = mysql_query($query_DetailRS1);
  $totalRows_DetailRS1 = mysql_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1/$maxRows_DetailRS1)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowMembres = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
$rsShowMembres = mysql_query($query_rsShowMembres, $MyFileConnect) or die(mysql_error());
$row_rsShowMembres = mysql_fetch_assoc($rsShowMembres);
$totalRows_rsShowMembres = mysql_num_rows($rsShowMembres);

$colname_rsShowLocalite = "-1";
if (isset($row_DetailRS1['localite_id'])) {
  $colname_rsShowLocalite = $row_DetailRS1['localite_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowLocalite = sprintf("SELECT localite_id, localite_lib FROM localites WHERE localite_id = %s", GetSQLValueString($colname_rsShowLocalite, "int"));
$rsShowLocalite = mysql_query($query_rsShowLocalite, $MyFileConnect) or die(mysql_error());
$row_rsShowLocalite = mysql_fetch_assoc($rsShowLocalite);
$totalRows_rsShowLocalite = mysql_num_rows($rsShowLocalite);

$colname_rsShowTypeCommission = "-1";
if (isset($row_DetailRS1['type_commission_id'])) {
  $colname_rsShowTypeCommission = $row_DetailRS1['type_commission_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowTypeCommission = sprintf("SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE type_commission_id = %s", GetSQLValueString($colname_rsShowTypeCommission, "int"));
$rsShowTypeCommission = mysql_query($query_rsShowTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsShowTypeCommission = mysql_fetch_assoc($rsShowTypeCommission);
$totalRows_rsShowTypeCommission = mysql_num_rows($rsShowTypeCommission);

$colname_rsShowNature = "-1";
if (isset($row_DetailRS1['nature_id'])) {
  $colname_rsShowNature = $row_DetailRS1['nature_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowNature = sprintf("SELECT nature_id, lib_nature FROM natures WHERE nature_id = %s", GetSQLValueString($colname_rsShowNature, "int"));
$rsShowNature = mysql_query($query_rsShowNature, $MyFileConnect) or die(mysql_error());
$row_rsShowNature = mysql_fetch_assoc($rsShowNature);
$totalRows_rsShowNature = mysql_num_rows($rsShowNature);

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
    <th width="185" align="left" scope="col"><a href="afficher_commissions.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Afficher les commissions</th>
  </tr>
  <tr>
    <th align="left" scope="col"><a href="affecter_membres.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter des membres à une commission</th>
  </tr>
</table>
<p>&nbsp;</p>
<table border="0" align="left">
  <tr>
    <th align="right" scope="row"><table border="1" align="left" class="std">
      <tr>
        <th align="right">ID</th>
        <td align="left"><?php echo $row_DetailRS1['commission_id']; ?></td>
      </tr>
      <tr>
        <th align="right">LOCALITE</th>
        <td align="left"><?php echo $row_rsShowLocalite['localite_lib']; ?></td>
      </tr>
      <tr>
        <th align="right">TYPE COMMISSION</th>
        <td align="left"><?php echo $row_rsShowTypeCommission['type_commission_lib']; ?></td>
      </tr>
      <tr>
        <th align="right">NATURE</th>
        <td align="left"><?php echo $row_rsShowNature['lib_nature']; ?></td>
      </tr>
    </table></th>
  </tr>
  <tr>
    <th scope="row">&nbsp;</th>
  </tr>
  <tr>
    <th align="left" scope="row"> <?php if ($totalRows_rsShowMembres > 0) { // Show if recordset not empty ?>
  <table border="1" align="center" class="std">
    <tr>
      <th width="196">FONCTION</th>
      <th width="222">NOM ET PRENOM</th>
      </tr>
    <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><?php 
		$colname_rsShowFonction = "-1";
		if (isset($row_rsShowMembres['fonctions_fonction_id'])) {
		  $colname_rsShowFonction = $row_rsShowMembres['fonctions_fonction_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowFonction = sprintf("SELECT fonction_id, fonction_lib FROM fonctions WHERE fonction_id = %s", GetSQLValueString($colname_rsShowFonction, "int"));
		$rsShowFonction = mysql_query($query_rsShowFonction, $MyFileConnect) or die(mysql_error());
		$row_rsShowFonction = mysql_fetch_assoc($rsShowFonction);
		$totalRows_rsShowFonction = mysql_num_rows($rsShowFonction);
	  
	  echo $row_rsShowFonction['fonction_lib']; ?>
        &nbsp; </td>
      <td><?php 
	  
		$colname_rsShowPersonnes = "-1";
		if (isset($row_rsShowMembres['personnes_personne_id'])) {
		  $colname_rsShowPersonnes = $row_rsShowMembres['personnes_personne_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowPersonnes = sprintf("SELECT personne_id, personne_nom, personne_prenom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsShowPersonnes, "int"));
		$rsShowPersonnes = mysql_query($query_rsShowPersonnes, $MyFileConnect) or die(mysql_error());
		$row_rsShowPersonnes = mysql_fetch_assoc($rsShowPersonnes);
		$totalRows_rsShowPersonnes = mysql_num_rows($rsShowPersonnes);
	  
	  echo $row_rsShowPersonnes['personne_nom'] . " " . $row_rsShowPersonnes['personne_prenom']; ?>
        &nbsp; </td>
      </tr>
    <?php } while ($row_rsShowMembres = mysql_fetch_assoc($rsShowMembres)); ?>
  </table>
  <?php } // Show if recordset not empty ?></th>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);

mysql_free_result($rsShowMembres);

mysql_free_result($rsShowFonction);

mysql_free_result($rsShowPersonnes);

mysql_free_result($rsShowNature);

mysql_free_result($rsShowTypeCommission);

mysql_free_result($rsShowLocalite);
?>