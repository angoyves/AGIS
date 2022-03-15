<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
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

$colname_rs_appurements = "-1";
if (isset($_GET['perID'])) {
  $colname_rs_appurements = $_GET['perID'];
}
$colname_rs_appurements1 = "-1";
if (isset($_GET['comID'])) {
  $colname_rs_appurements1 = $_GET['comID'];
}
$colname_rs_appurements2 = "-1";
if (isset($_GET['ovID'])) {
  $colname_rs_appurements2 = $_GET['ovID'];
}

if ((isset($colname_rs_appurements) && $colname_rs_appurements != "-1") && (isset($colname_rs_appurements1) && $colname_rs_appurements1 != "-1")) {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rs_appurements = sprintf("SELECT * FROM appurements WHERE commission_id = %s AND personne_id = %s ORDER BY dateCreation DESC", GetSQLValueString($colname_rs_appurements1, "int"), GetSQLValueString($colname_rs_appurements, "int"));
$rs_appurements = mysql_query($query_rs_appurements, $MyFileConnect) or die(mysql_error());
$row_rs_appurements = mysql_fetch_assoc($rs_appurements);
$totalRows_rs_appurements = mysql_num_rows($rs_appurements);

} else if (isset($colname_rs_appurements2) && $colname_rs_appurements2 != "-1"){
	
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rs_appurements = sprintf("SELECT appurements.*, membres.montant FROM appurements, membres WHERE appurements.commission_id = membres.commissions_commission_id
AND appurements.personne_id = membres.personnes_personne_id AND  ref_dossier = %s ORDER BY dateCreation DESC", GetSQLValueString($colname_rs_appurements2, "text"));
$rs_appurements = mysql_query($query_rs_appurements, $MyFileConnect) or die(mysql_error());
$row_rs_appurements = mysql_fetch_assoc($rs_appurements);
$totalRows_rs_appurements = mysql_num_rows($rs_appurements);
	
} else {
	
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rs_appurements = sprintf("SELECT appurements.*, membres.montant FROM appurements, membres WHERE appurements.commission_id = membres.commissions_commission_id
AND appurements.personne_id = membres.personnes_personne_id AND commission_id = %s ORDER BY dateCreation DESC", GetSQLValueString($colname_rs_appurements1, "int"));
$rs_appurements = mysql_query($query_rs_appurements, $MyFileConnect) or die(mysql_error());
$row_rs_appurements = mysql_fetch_assoc($rs_appurements);
$totalRows_rs_appurements = mysql_num_rows($rs_appurements);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<p><?php echo MinmapDB::getInstance()->get_commission_lib_by_commission_id($row_rs_appurements['commission_id']); ?></p>
<p><?php           
			$showGoTo = "search_pers.php?acID=find";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
            <a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>">Rechercher une personne...<img src="images/img/b_views.png" alt="" width="16" height="16" align="absmiddle" /></a>&nbsp;<a href="#">Choisir Commission</a><a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>"><img src="images/img/b_views.png" alt="" width="16" height="16" align="absmiddle" /></a> 
            
 		  <?php           
			if (isset($_GET['ovID']) && $_GET['ovID'] != ""){ ?>
          <?php 
				$showGoTo = "search_pers.php?acID=add";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?><a href="#" onclick="<?php popup($showGoTo, "710", "450"); ?>">Ajouter une personne a la liste</a></p>
          <?php } ?>
<table border="1" align="center" class="std">
  <tr>
    <th>OV</th>
    <th>Personne</th>
    <th>Reference Dossier</th>
    <th>Periode </th>
    <th colspan="1">Exercice</th>
    <th>Montant</th>
    <th>&nbsp;</th>
  </tr>
  <?php $counter=0; $Montant_global =0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="sample125_details.php?recordID=<?php echo $row_rs_appurements['num_virement']; ?>"><?php echo $row_rs_appurements['num_virement']; ?></a><a href="sample125_details.php?recordID=<?php echo $row_rs_appurements['num_virement']; ?>">&nbsp; </a></td>
      <td><?php echo MinmapDB::getInstance()->get_personne_name_by_person_id($row_rs_appurements['personne_id']); ?>&nbsp; </td>
      <td><?php echo MinmapDB::getInstance()->get_fonction_lib_by_fonction_id($row_rs_appurements['fonction_id']); ?>&nbsp; <a href="sample125.php?ovID=<?php echo $row_rs_appurements['ref_dossier']; ?>"><?php echo $row_rs_appurements['ref_dossier']; ?></a></td>
      <td align="center"><?php echo "De ". $row_rs_appurements['periode_debut'] ." à ". $row_rs_appurements['periode_fin']; ?></td>
      <td align="center"><?php echo $row_rs_appurements['annee']; ?></td>
      <td align="right">&nbsp;
      <?php $Somme = $row_rs_appurements['nombre_jour']*$row_rs_appurements['montant']; $Montant_global=$Montant_global+$Somme; echo number_format(($Somme),0,' ',' '); ?></td>
      <td align="right">&nbsp;</td>
    </tr>
    <?php 
	$Dossier = $row_rs_appurements['ref_dossier'];
	$Ordre_virement = $row_rs_appurements['num_virement'] + 1;
	$Periode = "De ". $row_rs_appurements['periode_debut'] ." à ". $row_rs_appurements['periode_fin'];
	$Exercice = $row_rs_appurements['annee'];
	
	} while ($row_rs_appurements = mysql_fetch_assoc($rs_appurements)); ?>
    <?php if (isset($_GET['acID']) && $_GET['acID']=="add"){ ?>
	<form action="#" method="post">
	<tr bgcolor="#B7B7B7">
      <td><input name="" type="text" value="<?php echo $Ordre_virement; ?>" /></td>
      <td><?php echo strtoupper(MinmapDB::getInstance()->get_personne_name_by_person_id($_GET['perID'])); ?>
      <input type="hidden" name="hiddenField" id="hiddenField" /></td>
      <td><?php echo $Dossier ?>&nbsp;
      <input type="hidden" name="hiddenField2" id="hiddenField2" /></td>
      <td align="center"><?php echo $Periode ?>
        <input type="hidden" name="hiddenField3" id="hiddenField3" />
      <input type="hidden" name="hiddenField4" id="hiddenField4" /></td>
      <td align="center"><input type="hidden" name="hiddenField5" id="hiddenField5" />
      <?php echo $Exercice; ?></td>
      <td align="right"><input type="text" name="textfield" id="textfield" /></td>
      <td align="right"><label>
        <input type="submit" name="button" id="button" value="Appurer" />
      </label></td>
    </tr>
    </form>
    <?php } ?>
   <tr>
    <td align="right"><strong>TOTAL</strong></td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right">&nbsp;</td>
    <td align="right"><strong><?php echo number_format(($Montant_global),0,' ',' '); ?></strong></td>
    <td align="right">&nbsp;</td>
  </tr>
</table>
<br />
<?php echo $totalRows_rs_appurements ?> Enregistrements Total
 <a href="sample110.php"><br />
 Retour
 </a>
</body>
</html>
<?php
mysql_free_result($rs_appurements);
?>
