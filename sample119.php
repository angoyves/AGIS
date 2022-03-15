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

$maxRows_Recordset1 = 100;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
  $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

$colname_Recordset1 = "al";
if (isset($_POST['txtSearch'])) {
  $colname_Recordset1 = $_POST['txtSearch'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_Recordset1 = sprintf("SELECT distinct(membres_personnes_personne_id), personne_nom, personne_prenom, fonction_id FROM personnes, sessions WHERE sessions.membres_personnes_personne_id=personnes.personne_id 
AND personne_nom LIKE %s ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname_Recordset1 . "%", "text"));
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysql_query($query_limit_Recordset1, $MyFileConnect) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);

if (isset($_GET['totalRows_Recordset1'])) {
  $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
  $all_Recordset1 = mysql_query($query_Recordset1);
  $totalRows_Recordset1 = mysql_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1/$maxRows_Recordset1)-1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_Recordset1") == false && 
        stristr($param, "totalRows_Recordset1") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="sample119.php">
  <table border="0" class="std">
    <tr>
      <td><strong>Rechercher Par :</strong></td>
      <td><select name="txtChamp" id="txtChamp">
        <option value="personne_nom" <?php if (!(strcmp("personne_nom", $colname_rsSelect1))) {echo "selected=\"selected\"";} ?>>Nom</option>
        <option value="personne_id" <?php if (!(strcmp("personne_id", $colname_rsSelect1))) {echo "selected=\"selected\"";} ?>>ID</option>
        <option value="personne_prenom" <?php if (!(strcmp("personne_prenom", $colname_rsSelect1))) {echo "selected=\"selected\"";} ?>>Prenom</option>
      </select></td>
      <td><input name="txtSearch" type="text" id="txtSearch" value="<?php echo htmlentities($colname_rsSelect2, ENT_COMPAT, 'utf-8'); ?>" />
      <input name="menuID" type="hidden" id="menuID" value="<?php echo $_GET['menuID']; ?>" />
      <input name="action" type="hidden" id="action" value="<?php echo $_GET['action']; ?>" /></td>
      <td><input name="BtnOk" type="submit" class="button" id="BtnOk" value="Envoyer" /></td>
    </tr>
    <tr>
      <td><?php echo $maxRows_rsAffichePerson; ?>&nbsp;<strong>Commission :</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="reset" name="button" id="button" value="Réinitialiser" />
      </label></td>
    </tr>
    <tr>
      <td colspan="4" align="right"><input type="hidden" name="MM_insert" value="form1" />
        Nombre d'enregistrements
      <select name="nbr_reg">
        <option value="10" <?php if (!(strcmp("10", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>10</option>
        <option value="30" <?php if (!(strcmp("30", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>30</option>
        <option value="60" <?php if (!(strcmp("60", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>60</option>
        <option value="100" <?php if (!(strcmp("100", $_GET['nbr_reg']))) {echo "selected=\"selected\"";} ?>>100</option>
      </select>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4"><?php if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {?>
        Enregistrements trouvés pour votre recherche &quot; <strong> <?php echo $colname_rsSelect2; ?></strong> &quot; dans la colonne <strong> <?php echo $colname_rsSelect1; ?></strong>
        <?php } ?>
        <a href="#" onclick="">
        
        </a></td>
    </tr>
  </table>
</form>
<table border="1" align="center">
  <tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Fonction</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="sample120.php?recordID=<?php echo $row_Recordset1['membres_personnes_personne_id']; ?>"> <?php echo $row_Recordset1['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_Recordset1['personne_nom']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo $row_Recordset1['fonction_id']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_Recordset1 = mysql_fetch_assoc($Recordset1)); ?>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Records <?php echo ($startRow_Recordset1 + 1) ?> to <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> of <?php echo $totalRows_Recordset1 ?>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
