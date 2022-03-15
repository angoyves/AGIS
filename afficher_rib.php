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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsPersonnes = 10;
$pageNum_rsPersonnes = 0;
if (isset($_GET['pageNum_rsPersonnes'])) {
  $pageNum_rsPersonnes = $_GET['pageNum_rsPersonnes'];
}
$startRow_rsPersonnes = $pageNum_rsPersonnes * $maxRows_rsPersonnes;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = "SELECT personnes.personne_id,personnes.personne_matricule,personnes.personne_nom,personnes.personne_grade,personnes.sous_groupe_id,personnes.structure_id,personnes.type_personne_id, rib.personne_id,rib.banque_code,rib.agence_code,rib.numero_compte, rib.cle FROM rib, personnes WHERE personnes.personne_id = rib.personne_id";
$query_limit_rsPersonnes = sprintf("%s LIMIT %d, %d", $query_rsPersonnes, $startRow_rsPersonnes, $maxRows_rsPersonnes);
$rsPersonnes = mysql_query($query_limit_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);

if (isset($_GET['totalRows_rsPersonnes'])) {
  $totalRows_rsPersonnes = $_GET['totalRows_rsPersonnes'];
} else {
  $all_rsPersonnes = mysql_query($query_rsPersonnes);
  $totalRows_rsPersonnes = mysql_num_rows($all_rsPersonnes);
}
$totalPages_rsPersonnes = ceil($totalRows_rsPersonnes/$maxRows_rsPersonnes)-1;

$queryString_rsPersonnes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPersonnes") == false && 
        stristr($param, "totalRows_rsPersonnes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPersonnes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPersonnes = sprintf("&totalRows_rsPersonnes=%d%s", $totalRows_rsPersonnes, $queryString_rsPersonnes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIIER MINMAP...</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div class="std">
    	<ul>
          <p>Informations de Base</p>
          <p><a href="new_personnes.php"><img src="images/img/b_snewtbl.png" width="16" height="16" align="absmiddle"/> Ajouter une personne</a></p>
          <p><a href="new_commissions.php"><img src="images/img/b_snewtbl.png" width="16" height="16" align="absmiddle"/> Ajouter une Commission</a> </p>
          <p><a href="create_dossiers.php"><img src="images/img/b_snewtbl.png" width="16" height="16" align="absmiddle"/> Ajouter un dossier</a></p>
          <p><a href="create_fonctions.php"><img src="images/img/b_snewtbl.png" width="16" height="16" align="absmiddle"/> Ajouter une fonction</a></p>
          <p><a href="affecter_membres.php"><img src="images/img/b_snewtbl.png" width="16" height="16" align="absmiddle"/> Ajouter un membre</a></p>
          <p><a href="validate_sessions.php"><img src="images/img/b_snewtbl.png" width="16" height="16" align="absmiddle"/> Ajouter un dossier</a></p>
          <p><a href="#" onclick=""><img src="images/img/b_print.png" alt="" width="16" height="16" align="absmiddle"/> Imprimer la page</a></p>
    	</ul>
	</div>
<p></p>
<p>&nbsp;</p>
<table border="1" align="center" class="std">
  <tr>
    <th>Numero</th>
    <th>Matricule</th>
    <th>Nom et prenom</th>
    <th>Code Banque</th>
    <th>Libelle Banque </th>
    <th>Code Agence</th>
    <th>Libelle Agence</th>
    <th>Numero de compte</th>
    <th>Cle</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="detail_person.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>"> <?php echo $row_rsPersonnes['personne_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsPersonnes['personne_matricule']; ?>&nbsp; </td>
      <td><?php echo htmlentities($row_rsPersonnes['personne_nom']); ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['banque_code']; ?>&nbsp; </td>
      <td><?php
		$colname_rsLibBanque = "-1";
		if (isset($row_rsPersonnes['banque_code'])) {
		  $colname_rsLibBanque = $row_rsPersonnes['banque_code'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsLibBanque = sprintf("SELECT banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsLibBanque, "int"));
		$rsLibBanque = mysql_query($query_rsLibBanque, $MyFileConnect) or die(mysql_error());
		$row_rsLibBanque = mysql_fetch_assoc($rsLibBanque);
		$totalRows_rsLibBanque = mysql_num_rows($rsLibBanque);

	  ?>
	  <?php echo $row_rsLibBanque['banque_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['agence_code']; ?>&nbsp; </td>
      <td>
	  <?php 
		$colname_rsLibAgence = "-1";
		if (isset($row_rsPersonnes['agence_code'])) {
		  $colname_rsLibAgence = $row_rsPersonnes['agence_code'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsLibAgence = sprintf("SELECT agence_lib FROM agences WHERE agence_code = %s", GetSQLValueString($colname_rsLibAgence, "int"));
		$rsLibAgence = mysql_query($query_rsLibAgence, $MyFileConnect) or die(mysql_error());
		$row_rsLibAgence = mysql_fetch_assoc($rsLibAgence);
		$totalRows_rsLibAgence = mysql_num_rows($rsLibAgence);
	  ?>
	  <?php echo $row_rsLibAgence['agence_lib']; ?></td>
      <td><?php echo $row_rsPersonnes['numero_compte']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['cle']; ?>&nbsp; </td>
      <td>
        <?php
		$colname_rsLibStructures = "-1";
		if (isset($row_rsPersonnes['structure_id'])) {
		  $colname_rsLibStructures = $row_rsPersonnes['structure_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsLibStructures = sprintf("SELECT structure_lib FROM structures WHERE structure_id = %s", GetSQLValueString($colname_rsLibStructures, "int"));
		$rsLibStructures = mysql_query($query_rsLibStructures, $MyFileConnect) or die(mysql_error());
		$row_rsLibStructures = mysql_fetch_assoc($rsLibStructures);
		$totalRows_rsLibStructures = mysql_num_rows($rsLibStructures);
	  ?>
      <?php echo $row_rsLibStructures['structure_lib']; ?></td>
      <td>
	  <?php 
		$colname_rsLibGroupe = "-1";
		if (isset($row_rsPersonnes['groupe_id'])) {
		  $colname_rsLibGroupe = $row_rsPersonnes['groupe_id'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsLibGroupe = sprintf("SELECT fonction_lib FROM fonctions WHERE fonction_id = %s", GetSQLValueString($colname_rsLibGroupe, "int"));
		$rsLibGroupe = mysql_query($query_rsLibGroupe, $MyFileConnect) or die(mysql_error());
		$row_rsLibGroupe = mysql_fetch_assoc($rsLibGroupe);
		$totalRows_rsLibGroupe = mysql_num_rows($rsLibGroupe);
	  ?>
	  <?php echo htmlentities($row_rsLibGroupe['fonction_lib']); ?></td>
    </tr>
    <?php } while ($row_rsPersonnes = mysql_fetch_assoc($rsPersonnes)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, 0, $queryString_rsPersonnes); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, max(0, $pageNum_rsPersonnes - 1), $queryString_rsPersonnes); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, min($totalPages_rsPersonnes, $pageNum_rsPersonnes + 1), $queryString_rsPersonnes); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, $totalPages_rsPersonnes, $queryString_rsPersonnes); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsPersonnes + 1) ?> à <?php echo min($startRow_rsPersonnes + $maxRows_rsPersonnes, $totalRows_rsPersonnes) ?> sur <?php echo $totalRows_rsPersonnes ?>
</body>
</html>
<?php
mysql_free_result($rsPersonnes);

mysql_free_result($rsLibBanque);

mysql_free_result($rsLibAgence);

mysql_free_result($rsLibStructures);

mysql_free_result($rsLibGroupe);
?>
