<?php require_once('Connections/MyFileConnect.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO mois (lib_mois) VALUES (%s)",
                       GetSQLValueString($_POST['lib_mois'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "show_sessions.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$maxRows_rsMembres = 10;
$pageNum_rsMembres = 0;
if (isset($_GET['pageNum_rsMembres'])) {
  $pageNum_rsMembres = $_GET['pageNum_rsMembres'];
}
$startRow_rsMembres = $pageNum_rsMembres * $maxRows_rsMembres;

$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id  FROM commissions, membres, fonctions, personnes  WHERE membres.commissions_commission_id = commissions.commission_id   AND fonctions_fonction_id = fonctions.fonction_id   AND personnes_personne_id = personne_id  AND membres.fonctions_fonction_id BETWEEN 1 AND 3 AND personnes.display = 1  AND commissions_commission_id = %s AND membres.display = 1 ORDER BY fonctions_fonction_id ", GetSQLValueString($colname_rsMembres, "int"));
$query_limit_rsMembres = sprintf("%s LIMIT %d, %d", $query_rsMembres, $startRow_rsMembres, $maxRows_rsMembres);
$rsMembres = mysql_query($query_limit_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);

if (isset($_GET['totalRows_rsMembres'])) {
  $totalRows_rsMembres = $_GET['totalRows_rsMembres'];
} else {
  $all_rsMembres = mysql_query($query_rsMembres);
  $totalRows_rsMembres = mysql_num_rows($all_rsMembres);
}
$totalPages_rsMembres = ceil($totalRows_rsMembres/$maxRows_rsMembres)-1;

$colname_rsRepresentant = "-1";
if (isset($_GET['comID'])) {
  $colname_rsRepresentant = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRepresentant = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id, code_structure FROM commissions, membres, fonctions, personnes, structures  WHERE membres.commissions_commission_id = commissions.commission_id   AND fonctions_fonction_id = fonctions.fonction_id   AND personnes_personne_id = personne_id  AND personnes.structure_id = structures.structure_id AND membres.fonctions_fonction_id <> 1 AND membres.fonctions_fonction_id <> 2 AND membres.fonctions_fonction_id <> 3 AND personnes.display = 1  AND membres.display = 1 AND commissions_commission_id = %s ORDER BY fonctions_fonction_id ", GetSQLValueString($colname_rsRepresentant, "int"));
$rsRepresentant = mysql_query($query_rsRepresentant, $MyFileConnect) or die(mysql_error());
$row_rsRepresentant = mysql_fetch_assoc($rsRepresentant);
$totalRows_rsRepresentant = mysql_num_rows($rsRepresentant);

$colname_rsJourMois = "-1";
if (isset($_GET['moisID'])) {
  $colname_rsJourMois = $_GET['moisID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois ORDER BY mois_id ASC";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$queryString_rsMembres = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsMembres") == false && 
        stristr($param, "totalRows_rsMembres") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsMembres = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsMembres = sprintf("&totalRows_rsMembres=%d%s", $totalRows_rsMembres, $queryString_rsMembres);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <h1>&nbsp;</h1>
<table border="0">
  <tr>
    <td><table width="50%" align="left" class="std2">
      <tr valign="baseline">
        <th nowrap="nowrap" align="right">Mois:</th>
        <td><select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
          <option value="" <?php if (!(strcmp("", $_GET['moisID']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
          <?php
do {  
?>
          <option value="sample16.php?comID=<?php echo $_GET['comID'] ?>&amp;menuID=<?php echo $_GET['menuID'] ?>&amp;moisID=<?php echo $row_rsMois['mois_id']?>&amp;valid=<?php echo $_GET['valid'] ?>"<?php if (!(strcmp($row_rsMois['mois_id'], $_GET['moisID']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois['lib_mois'])?></option>
          <?php
} while ($row_rsMois = mysql_fetch_assoc($rsMois));
  $rows = mysql_num_rows($rsMois);
  if($rows > 0) {
      mysql_data_seek($rsMois, 0);
	  $row_rsMois = mysql_fetch_assoc($rsMois);
  }
?>
        </select>
          <?php
             /** Display error messages if the "password" field is empty */
       if ($moisIsEmpty) { ?>
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" /><blink>Select month, please</blink></div>
          <?php  } ?></td>
        <td>Annee :</td>
        <td><input name="annee2" type="hidden" size="15" value="<?php if ( isset($_POST['annee'])) { echo $_POST['annee'];} ?>" />
          <select name="annee2">
            <option value="" <?php if (!(strcmp("", $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>>Select:::</option>
            <?php 
do {  
?>
            <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($_GET['moisID'], $row_rsAnnee['lib_annee']))) {echo "SELECTED";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
            <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
?>
          </select>
          <?php
             /** Display error messages if the "password" field is empty */
       if ($anneeIsEmpty) { ?>
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Enter the YEAR, please</div>
          <?php  } ?>
&nbsp;</td>
      </tr>
    </table>
      <table width="75%" border="1" align="left" class="std">
      <tr valign="baseline">
        <td colspan="4" nowrap="nowrap">Selectionner  une commission
          <?php           
	$showGoTo = "search_commissions2.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
          <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
          <strong>
            <?php if (isset($_GET['comID'])) { ?>
            <?php echo $row_rsCommissions['commission_lib']; ?>
            <?php } ?>
            </strong>
          <input name="commission_id2" type="hidden" id="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" />
          <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
          <?php }
            ?>
          <input type="hidden" name="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" /></td>
      </tr>
      <tr>
        <th>ID</th>
        <th>Type</th>
        <th>Nature</th>
        <th>Localite</th>
      </tr>
      <tr>
        <td nowrap="nowrap"><img src="images/img/b_views.png" alt="" width="16" height="16" /></td>
        <td nowrap="nowrap"><?php echo $row_rsCommissions['type_commission_lib']; ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsCommissions['lib_nature']; ?>&nbsp;</td>
        <td nowrap="nowrap"><?php echo $row_rsCommissions['localite_lib']; ?>&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <td colspan="4" align="right" nowrap="nowrap">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><h1>Dossiers</h1>
      <table border="1" align="center" class="std">
        <tr>
          <th colspan="3">Jours</th>
          <?php $counter = 0; do { $counter++; ?>
          <th><?php echo $counter ?>&nbsp;</th>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <tr>
          <td colspan="3">Nombre de dossiers</td>
          <?php 
			$counter=0; do  { $counter++; ?>
          <td><select name="<?php echo "nombre_dossier" . $counter ?>2">
            <option value=""></option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
          </select></td>
          <?php }while ($counter < $row_rsJourMois['nbre_jour']);
	?>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><h1>Session Commissions</h1>
      <table border="1" align="center">
        <tr>
          <th>ID</th>
          <th>Noms et Prenoms</th>
          <th>Fonctions</th>
          <?php $counter = 0; do { $counter++; ?>
          <th><?php echo $counter ?>&nbsp;</th>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <?php do { ?>
        <tr>
          <td><a href="ToDelete.php?recordID=<?php echo $row_rsMembres['commission_id']; ?>" onclick="<?php popup($showGoToPersonne, "700", "300"); ?>"> <?php echo $counter; ?></a>&nbsp;</td>
          <td><?php echo $row_rsMembres['personne_nom'] . " " . $row_rsMembres['personne_prenom']; ?>&nbsp; </td>
          <td><?php echo $row_rsMembres['fonction_lib']; ?>&nbsp; </td>
          <?php $counter = 0; do { $counter++; ?>
          <td><input type="checkbox" name="<?php echo "jour" . $compter . "[]" ?>" id="<?php echo "jour". $compter . $value ?>" value="<?php echo $value ?>"  
				<?php echo ((in_array($value, $liste1))? "checked='checked'":"") ?> /></td>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
      </table>
      <table border="0">
        <tr>
          <td height="35"><?php if ($pageNum_rsMembres > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, 0, $queryString_rsMembres); ?>">Premier</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsMembres > 0) { // Show if not first page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, max(0, $pageNum_rsMembres - 1), $queryString_rsMembres); ?>">Précédent</a>
            <?php } // Show if not first page ?></td>
          <td><?php if ($pageNum_rsMembres < $totalPages_rsMembres) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, min($totalPages_rsMembres, $pageNum_rsMembres + 1), $queryString_rsMembres); ?>">Suivant</a>
            <?php } // Show if not last page ?></td>
          <td><?php if ($pageNum_rsMembres < $totalPages_rsMembres) { // Show if not last page ?>
            <a href="<?php printf("%s?pageNum_rsMembres=%d%s", $currentPage, $totalPages_rsMembres, $queryString_rsMembres); ?>">Dernier</a>
            <?php } // Show if not last page ?></td>
        </tr>
      </table>
      <p>Enregistrements <?php echo ($startRow_rsMembres + 1) ?> à <?php echo min($startRow_rsMembres + $maxRows_rsMembres, $totalRows_rsMembres) ?> sur <?php echo $totalRows_rsMembres ?> </p></td>
  </tr>
  <tr>
    <td><h1>Session Representant Maitre d'Ouvrage</h1>
      <table border="1" align="center">
        <tr>
          <td colspan="2"><?php           
			$showGoTo = "sample14.php";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
            <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">Ajouter un representant...<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <?php $counter = 0; do { $counter++; ?>
          <td><strong>&nbsp;</strong></td>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <tr>
          <td><strong>ID</strong></td>
          <td><strong>Noms et Prenoms</strong></td>
          <td><strong>Fonctions</strong></td>
          <td><strong>Structures</strong></td>
          <?php $counter = 0; do { $counter++; ?>
          <td><strong><?php echo $counter ?>&nbsp;</strong></td>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <?php do { ?>
        <tr>
          <td><a href="ToDelete.php?recordID=<?php echo $row_rsRepresentant['commission_id']; ?>"> <?php echo $counter; ?></a>&nbsp; </td>
          <td><?php echo $row_rsRepresentant['personne_nom'] . " " . $row_rsRepresentant['personne_prenom']; ?>&nbsp; </td>
          <td><?php echo $row_rsRepresentant['fonction_lib']; ?>&nbsp; </td>
          <td><?php echo $row_rsRepresentant['code_structure']; ?>&nbsp; </td>
          <?php $counter = 0; do { $counter++; ?>
          <td><input type="checkbox" name="<?php echo "day". $compter . $value ?>" id="<?php echo "day". $compter . $value ?>2" value="<?php echo $value ?>"  
				<?php echo ((in_array($value, $liste1))? "checked='checked'":"") ?> /></td>
          <?php } while($counter < $row_rsJourMois['nbre_jour']); ?>
        </tr>
        <?php } while ($row_rsRepresentant = mysql_fetch_assoc($rsRepresentant)); ?>
      </table>
      <br />
      <?php echo $totalRows_rsRepresentant ?> Enregistrements Total</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
  <input type="submit" value="Ins&eacute;rer un enregistrement" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsMembres);

mysql_free_result($rsRepresentant);

mysql_free_result($rsJourMois);

mysql_free_result($rsMois);

mysql_free_result($rsAnnee);

mysql_free_result($rsCommissions);
?>
