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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsAffichePerson = 10;
$pageNum_rsAffichePerson = 0;
if (isset($_GET['pageNum_rsAffichePerson'])) {
  $pageNum_rsAffichePerson = $_GET['pageNum_rsAffichePerson'];
}
$startRow_rsAffichePerson = $pageNum_rsAffichePerson * $maxRows_rsAffichePerson;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAffichePerson = "SELECT * FROM personnes ORDER BY date_creation DESC";
$query_limit_rsAffichePerson = sprintf("%s LIMIT %d, %d", $query_rsAffichePerson, $startRow_rsAffichePerson, $maxRows_rsAffichePerson);
$rsAffichePerson = mysql_query($query_limit_rsAffichePerson, $MyFileConnect) or die(mysql_error());
$row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson);

if (isset($_GET['totalRows_rsAffichePerson'])) {
  $totalRows_rsAffichePerson = $_GET['totalRows_rsAffichePerson'];
} else {
  $all_rsAffichePerson = mysql_query($query_rsAffichePerson);
  $totalRows_rsAffichePerson = mysql_num_rows($all_rsAffichePerson);
}
$totalPages_rsAffichePerson = ceil($totalRows_rsAffichePerson/$maxRows_rsAffichePerson)-1;

$queryString_rsAffichePerson = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAffichePerson") == false && 
        stristr($param, "totalRows_rsAffichePerson") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAffichePerson = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAffichePerson = sprintf("&totalRows_rsAffichePerson=%d%s", $totalRows_rsAffichePerson, $queryString_rsAffichePerson);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIIER MINMAP...</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0">
  <tr>
    <th align="left"><a href="#"><img src="images/img/b_home.png" alt="" width="16" height="16" align="absmiddle"/></a>Retour ?? l'accueil</th>
  </tr>
  <tr>
    <th width="185" align="left" scope="col"><a href="new_personnes.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une personne</a></th>
  </tr>
<!--  <tr>
    <th align="left" scope="col"><a href="create_membres.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter des membres ?? une commission</th>
  </tr>-->
</table>
<p>&nbsp;</p>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <th>MATRICULE</th>
    <th>NOM</th>
    <th>PRENOM</th>
    <th>GRADE</th>
    <th>TELEPHONE</th>
    <th>STRUCTURE</th>
    <th>GROUPE</th>
    <th>FONCTION</th>
    <th>TYPE PERSONNE</th>
    <th>Actif</th>
    <th>Edit</th>
    <th>Del</th>
    <th>Show</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="detail_person.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>">  RIB&nbsp; </a></td>
      <td><?php echo $row_rsAffichePerson['personne_matricule']; ?>&nbsp; </td>
      <td><?php echo htmlentities($row_rsAffichePerson['personne_nom']); ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo htmlentities($row_rsAffichePerson['personne_grade']); ?>&nbsp; </td>
      <td><?php echo $row_rsAffichePerson['personne_telephone']; ?>&nbsp; </td>
      <td><?php 
		$colname_rsShowStructure = "-1";
		if (isset($row_rsAffichePerson['structure_id'])) {
		  $colname_rsShowStructure = $row_rsAffichePerson['structure_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowStructure = sprintf("SELECT structure_lib FROM structures WHERE structure_id = %s", GetSQLValueString($colname_rsShowStructure, "int"));
		$rsShowStructure = mysql_query($query_rsShowStructure, $MyFileConnect) or die(mysql_error());
		$row_rsShowStructure = mysql_fetch_assoc($rsShowStructure);
		$totalRows_rsShowStructure = mysql_num_rows($rsShowStructure);
	  
	  echo $row_rsShowStructure['structure_lib']; ?>&nbsp; </td>
      <td><?php 
		$colname_rsShowSousGroupe = "-1";
		if (isset($row_rsAffichePerson['sous_groupe_id'])) {
		  $colname_rsShowSousGroupe = $row_rsAffichePerson['sous_groupe_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowSousGroupe = sprintf("SELECT sous_groupe_lib FROM sous_groupes WHERE sous_groupe_id = %s", GetSQLValueString($colname_rsShowSousGroupe, "int"));
		$rsShowSousGroupe = mysql_query($query_rsShowSousGroupe, $MyFileConnect) or die(mysql_error());
		$row_rsShowSousGroupe = mysql_fetch_assoc($rsShowSousGroupe);
		$totalRows_rsShowSousGroupe = mysql_num_rows($rsShowSousGroupe);

	  
	  echo $row_rsShowSousGroupe['sous_goupe_lib']; ?>&nbsp; </td>
      <td><?php 
		$colname_rsShowFonction = "-1";
		if (isset($row_rsAffichePerson['fonction_id'])) {
		  $colname_rsShowFonction = $row_rsAffichePerson['fonction_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowFonction = sprintf("SELECT fonction_lib FROM fonctions WHERE fonction_id = %s", GetSQLValueString($colname_rsShowFonction, "int"));
		$rsShowFonction = mysql_query($query_rsShowFonction, $MyFileConnect) or die(mysql_error());
		$row_rsShowFonction = mysql_fetch_assoc($rsShowFonction);
		$totalRows_rsShowFonction = mysql_num_rows($rsShowFonction);
	  
	  echo $row_rsShowFonction['fonction_lib']; ?>&nbsp; </td>
      <td><?php 
		$colname_rsShowTypePersonne = "-1";
		if (isset($row_rsAffichePerson['type_personne_id'])) {
		  $colname_rsShowTypePersonne = $row_rsAffichePerson['type_personne_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsShowTypePersonne = sprintf("SELECT type_personne_lib FROM type_personnes WHERE type_personne_id = %s", GetSQLValueString($colname_rsShowTypePersonne, "int"));
		$rsShowTypePersonne = mysql_query($query_rsShowTypePersonne, $MyFileConnect) or die(mysql_error());
		$row_rsShowTypePersonne = mysql_fetch_assoc($rsShowTypePersonne);
		$totalRows_rsShowTypePersonne = mysql_num_rows($rsShowTypePersonne);
	  echo htmlentities($row_rsShowTypePersonne['type_personne_lib']); ?>&nbsp; </td>
      <td><?php if (isset($row_rsAffichePerson['display']) && $row_rsAffichePerson['display'] == '1') { ?>
        <a href="change_etat.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes&&mapid=personne_id&&action=desactive"><img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } else { ?>
        <a href="change_etat.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes&&mapid=personne_id&&action=active"><img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/></a>
        <?php } ?></td>
      <td><a href="edit_personnes.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td><a href="delete.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
      <td><a href="detail_personnes.php"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <?php } while ($row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsAffichePerson > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, 0, $queryString_rsAffichePerson); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAffichePerson > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, max(0, $pageNum_rsAffichePerson - 1), $queryString_rsAffichePerson); ?>">Pr??c??dent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsAffichePerson < $totalPages_rsAffichePerson) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, min($totalPages_rsAffichePerson, $pageNum_rsAffichePerson + 1), $queryString_rsAffichePerson); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsAffichePerson < $totalPages_rsAffichePerson) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, $totalPages_rsAffichePerson, $queryString_rsAffichePerson); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<p>Enregistrements <?php echo ($startRow_rsAffichePerson + 1) ?> ?? <?php echo min($startRow_rsAffichePerson + $maxRows_rsAffichePerson, $totalRows_rsAffichePerson) ?> sur <?php echo $totalRows_rsAffichePerson ?>
</p>
<p>&nbsp;</p>
<fieldset>
  <legend>Op??ration sur le fichier  </legend>
  <table border="0">
    <tr>
      <td><a href="#" target="print_view"><img src="http://localhost/phpmyadmin/themes/pmahomme/images/img/b_print.png" title="Version imprimable" alt="Version imprimable" width="16" height="16" />??Version imprimable</a></td>
      <td><a href="#" target="print_view"><img src="http://localhost/phpmyadmin/themes/pmahomme/images/img/b_print.png" title="Version imprimable (avec textes complets)" alt="Version imprimable (avec textes complets)" width="16" height="16" />??Version imprimable </a><a href="#"><img src="http://localhost/phpmyadmin/themes/pmahomme/images/img/b_tblexport.png" title="Exporter" alt="Exporter" width="16" height="16" />??Exporter </a><a href="http://localhost/phpmyadmin/sql.php?db=fichier_db85&amp;table=personnes&amp;printview=1&amp;sql_query=SELECT+%2A+FROM+%60personnes%60&amp;display_text=F&amp;token=62ade055dc15cdff8c9aedaf7fb5a081" target="print_view">textes complets)</a></td>
      <td><a href="#"><img src="http://localhost/phpmyadmin/themes/pmahomme/images/img/b_chart.png" title="Afficher le graphique" alt="Afficher le graphique" width="16" height="16" />??Afficher le graphique</a></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</fieldset>
</body>
</html>
<?php
mysql_free_result($rsAffichePerson);

mysql_free_result($rsShowStructure);

mysql_free_result($rsShowSousGroupe);

mysql_free_result($rsShowFonction);

mysql_free_result($rsShowTypePersonne);
?>
