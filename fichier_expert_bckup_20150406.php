<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php');?>
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

$maxRows_rsFichierExpert = 20;
$pageNum_rsFichierExpert = 0;
if (isset($_GET['pageNum_rsFichierExpert'])) {
  $pageNum_rsFichierExpert = $_GET['pageNum_rsFichierExpert'];
}
$startRow_rsFichierExpert = $pageNum_rsFichierExpert * $maxRows_rsFichierExpert;

$colname_rsFichierExpert = "-1";
if ((isset($_GET['txtSearch'])) && (isset($_GET['txtChamp']))) {
  $colname1_rsFichierExpert = $_GET['txtChamp'];
  $colname2_rsFichierExpert = $_GET['txtSearch'];

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFichierExpert = sprintf("SELECT * FROM personnes, rib, domaine_expert, localite_expert WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = domaine_expert.personne_id AND personnes.personne_id = localite_expert.personne_id AND personnes.type_personne_id = 3 AND personnes.$colname1_rsFichierExpert LIKE  %s ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname2_rsFichierExpert . "%", "text"));
$query_limit_rsFichierExpert = sprintf("%s LIMIT %d, %d", $query_rsFichierExpert, $startRow_rsFichierExpert, $maxRows_rsFichierExpert);
$rsFichierExpert = mysql_query($query_limit_rsFichierExpert, $MyFileConnect) or die(mysql_error());
$row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert);
} else {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFichierExpert = "SELECT * FROM personnes, rib, domaine_expert, localite_expert WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = domaine_expert.personne_id AND personnes.personne_id = localite_expert.personne_id AND personnes.type_personne_id = 3 ORDER BY personne_nom ASC";
$query_limit_rsFichierExpert = sprintf("%s LIMIT %d, %d", $query_rsFichierExpert, $startRow_rsFichierExpert, $maxRows_rsFichierExpert);
$rsFichierExpert = mysql_query($query_limit_rsFichierExpert, $MyFileConnect) or die(mysql_error());
$row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert);
}

if (isset($_GET['totalRows_rsFichierExpert'])) {
  $totalRows_rsFichierExpert = $_GET['totalRows_rsFichierExpert'];
} else {
  $all_rsFichierExpert = mysql_query($query_rsFichierExpert);
  $totalRows_rsFichierExpert = mysql_num_rows($all_rsFichierExpert);
}
$totalPages_rsFichierExpert = ceil($totalRows_rsFichierExpert/$maxRows_rsFichierExpert)-1;

$queryString_rsFichierExpert = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsFichierExpert") == false && 
        stristr($param, "totalRows_rsFichierExpert") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsFichierExpert = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsFichierExpert = sprintf("&totalRows_rsFichierExpert=%d%s", $totalRows_rsFichierExpert, $queryString_rsFichierExpert);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form id="form1" name="form1" method="get" action="">
<table border="0" class="std">
  <tr>
    <td><strong>Rechercher par :</strong></td>
    <td><select name="txtChamp" id="txtChamp">
      <option value="personne_nom" <?php if (!(strcmp("personne_nom", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Nom</option>
      <option value="personne_prenom" <?php if (!(strcmp("personne_prenom", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Prenom</option>
      <option value="personne_telephone" <?php if (!(strcmp("personne_telephone", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>N° Téléphone</option>
      <option value="personne_specialisation" <?php if (!(strcmp("personne_specialisation", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Specialisation</option>
      <option value="personne_qualification" <?php if (!(strcmp("personne_qualification", $_GET['txtChamp']))) {echo "selected=\"selected\"";} ?>>Qualification</option>
    </select></td>
    <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo $_GET['txtSearch']; ?>" />
      <input name="menuID" type="hidden" id="menuID" value="<?php echo $_GET['menuID']; ?>" />
      <input name="action" type="hidden" id="action" value="<?php echo $_GET['action']; ?>" /></td>
    <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
  </tr>
  <tr>
    <td colspan="3"><?php if (isset($_GET['txtSearch'])) { ?>
      Enregistrement trouvé pour votre recherche &quot; <strong> <?php echo $_GET['txtSearch']; ?></strong> &quot; dans <strong> <?php echo $_GET['txtChamp']; ?></strong></BR>
      <?php $link = "print_expert.php?txtChamp=" . $_GET['txtChamp']. "&txtSearch=" . $_GET['txtSearch']; ?>
    	 <a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Imprimer le resultat</a>
      <?php } ?></td>
    <td> </td>
  </tr>
</table>
<h1>Detail</h1>
</form>
<table border="1" align="center">
  <tr>
    <th nowrap="nowrap">N°</th>
    <th nowrap="nowrap">Rib</th>
    <th nowrap="nowrap">Nom et Prenom</th>
    <th nowrap="nowrap">Telephone</th>
    <th nowrap="nowrap">Specialisation</th>
    <th nowrap="nowrap">Qualification</th>
    <th nowrap="nowrap">Domaines</th>
    <th nowrap="nowrap">Localités</th>
  </tr>
  <?php $counter = 0; do { $counter++; ?>
    <tr>
    <?php           
	$showGoToPersonne = "show_rib.php?recordID=". $row_rsFichierExpert['personne_id'];  
	$showGoToPersonnes = "detail_personnes.php?menuID=". $_GET['menuID']."&action=".$_GET['action']."&recordID=".$row_rsAffichePerson['personne_id'];
	?>
      <td><a href="" > <?php echo $counter; ?></a></td>
      <td><a href="" onclick="<?php popup($showGoToPersonne, "700", "300"); ?>">RIB</a></td>
      <td><?php echo $row_rsFichierExpert['personne_nom'] . " " . $row_rsFichierExpert['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo $row_rsFichierExpert['personne_telephone']; ?>&nbsp; </td>
      <td><?php echo $row_rsFichierExpert['personne_specialisation']; ?>&nbsp; </td>
      <td><?php echo $row_rsFichierExpert['personne_qualification']; ?>&nbsp; </td>
      <td><a href="" onclick="<?php popup($showGoToPersonne2, "700", "300"); ?>">Domaine</a></td>
      <td><a href="" onclick="<?php popup($showGoToPersonne3, "700", "300"); ?>">localites</a></td>
    </tr>
    <?php } while ($row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert)); ?>
</table>
<br />
<?php if (isset($_POST['txtChamp'])) { ?>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", 0, $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Premier</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", max(0, $pageNum_rsFichierExpert - 1), $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Précédent</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", min($totalPages_rsFichierExpert, $pageNum_rsFichierExpert + 1), $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Suivant</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("fichier_expert.php?pageNum_rsFichierExpert=%d%s&txtChamp=%s&txtSearch=%s", $totalPages_rsFichierExpert, $queryString_rsFichierExpert, $_GET['txtChamp'], $_GET['txtSearch']); ?>">Dernier</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>

<?php } else { ?>

<table border="0">
  <tr>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, 0, $queryString_rsFichierExpert); ?>">Premier</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, max(0, $pageNum_rsFichierExpert - 1), $queryString_rsFichierExpert); ?>">Précédent</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, min($totalPages_rsFichierExpert, $pageNum_rsFichierExpert + 1), $queryString_rsFichierExpert); ?>">Suivant</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsFichierExpert < $totalPages_rsFichierExpert) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsFichierExpert=%d%s", $currentPage, $totalPages_rsFichierExpert, $queryString_rsFichierExpert); ?>">Dernier</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
<?php } // End condition isset $_POST['txtChamp'] ?>
Enregistrements <?php echo ($startRow_rsFichierExpert + 1) ?> à <?php echo min($startRow_rsFichierExpert + $maxRows_rsFichierExpert, $totalRows_rsFichierExpert) ?> sur <?php echo $totalRows_rsFichierExpert ?>
</body>
</html>
<?php
mysql_free_result($rsFichierExpert);
?>
