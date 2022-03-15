<?php require_once('Connections/MyFileConnect.php'); ?><?php
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
$query_DetailRS1 = sprintf("SELECT personnes.personne_id, personnes.personne_matricule, personne_nom, personne_prenom, personne_grade, personne_telephone, personnes.structure_id, structure_lib, personnes.sous_groupe_id, sous_groupe_lib, personnes.fonction_id, fonction_lib, personnes.domaine_id, domaine_lib, personne_date_nais, personnes.type_personne_id, type_personne_lib, add_commission, banque_code, agence_code, numero_compte, cle 
FROM personnes, rib, structures, sous_groupes, fonctions, domaines_activites, type_personnes
WHERE personnes.personne_id = rib.personne_id
AND personnes.structure_id = structures.structure_id
AND personnes.fonction_id = fonctions.fonction_id
AND personnes.sous_groupe_id = sous_groupes.sous_groupe_id
AND personnes.domaine_id = domaines_activites.domaine_id
AND personnes.type_personne_id = type_personnes.type_personne_id
AND personnes.personne_id = %s", GetSQLValueString($colname_DetailRS1, "int"));
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>

<table width="75%" border="1" align="center" class="std2">
  <tr>
    <td colspan="4" align="right"><h1>Information sur la personne</h1></td>
  </tr>
  <tr>
    <th align="right">ID: </th>
    <td colspan="3"><?php echo $row_DetailRS1['personne_id']; ?></td>
  </tr>
  <tr>
    <th align="right">Matricule:</th>
    <td colspan="3"><?php if (isset($row_DetailRS1['personne_matricule']) && $row_DetailRS1['personne_matricule'] != 'XXXXXX-X') {echo $row_DetailRS1['personne_matricule']; } else { echo 'Expert'; 	}?></td>
  </tr>
  <tr>
    <th align="right">Nom et prenon </th>
    <td colspan="3"><?php echo $row_DetailRS1['personne_nom'] . " " . $row_DetailRS1['personne_prenom']; ?></td>
  </tr>
  <tr>
    <th align="right">Telephone:</th>
    <td colspan="3"><?php echo $row_DetailRS1['personne_nom'] . " " . $row_DetailRS1['personne_telephone']; ?></td>
  </tr>
  <tr>
    <th align="right">Grade:</th>
    <td><?php echo $row_DetailRS1['personne_grade']; ?></td>
    <th align="right">&nbsp;</th>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th align="right">Structure:</th>
    <td><?php echo $row_DetailRS1['structure_lib']; ?></td>
    <th align="right">Groupe:</th>
    <td><?php echo $row_DetailRS1['sous_goupe_lib']; ?></td>
  </tr>
  <tr>
    <th align="right">Fonction:</th>
    <td><?php echo $row_DetailRS1['fonction_lib']; ?></td>
    <th align="right">Domaine:</th>
    <td><?php echo $row_DetailRS1['domaine_lib']; ?></td>
  </tr>
  <tr>
    <th align="right">Type personne:</th>
    <td><?php echo $row_DetailRS1['type_personne_lib']; ?></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="4" align="right"><h1>Releve d'identite bancaire</h1></td>
  </tr>
  <tr>
    <th align="right">Banque: </th>
    <td><?php echo $row_DetailRS1['banque_code']; ?></td>
    <th align="right">Agence:</th>
    <td><?php echo $row_DetailRS1['agence_cle']; ?></td>
  </tr>
  <tr>
    <th align="right">Numero de compte :</th>
    <td><?php echo $row_DetailRS1['numero_compte']; ?></td>
    <th align="right">Clé:</th>
    <td><?php echo $row_DetailRS1['cle']; ?></td>
  </tr>
</table>
</body>
</html><?php
mysql_free_result($DetailRS1);
?>