<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
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
$query_rsCommissions = "SELECT * FROM commissions WHERE commission_parent = 49";
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);



$colname_rsSousCommissions = "49";
if (isset($row_rsCommissions['commission_id'])) {
  $colname_rsSousCommissions = $row_rsCommissions['commission_id'];
}
$colname1_rsSousCommissions = "1";
if (isset($_GET['m1ID'])) {
  $colname1_rsSousCommissions = $_GET['m1ID'];
}
$colname2_rsSousCommissions = "12";
if (isset($_GET['m2ID'])) {
  $colname2_rsSousCommissions = $_GET['m2ID'];
}
$colname3_rsSousCommissions = "2014";
if (isset($_GET['aID'])) {
  $colname3_rsSousCommissions = $_GET['aID'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<p><?php echo $row_rsCommissions['commission_id']; ?>
</p>
<p>&nbsp;
<?php $nbre_sous_commission = MinmapDB::getInstance()->get_nombre_sous_commissions($_GET['comID']); ?>
<table border="1" align="center">
    <?php $count=0; do { $count++; ?>
    <tr>
      <td><?php 
	  
			mysql_select_db($database_MyFileConnect, $MyFileConnect);
			$query_rsSousCommissions = sprintf("SELECT commission_id, commission_lib, personnes.personne_id, personne_nom, fonction_lib, nombre_jour, jour, mois, annee, montant, (nombre_jour * montant) as total, montant_cumul, nombre_offre, dossier_ref
FROM commissions, membres, personnes, fonctions, sessions, dossiers
WHERE membres.commissions_commission_id = commissions.commission_id 
AND membres.personnes_personne_id = personnes.personne_id 
AND membres.fonctions_fonction_id = fonctions.fonction_id 
AND sessions.membres_commissions_commission_id = membres.commissions_commission_id 
AND sessions.membres_personnes_personne_id = membres.personnes_personne_id 
AND sessions.dossiers_dossier_id = dossiers.dossier_id
AND personnes.display = '1' 
AND sessions.membres_fonctions_fonction_id <> 4  
AND commission_id = %s 
AND mois between 01 AND 12 
AND annee = 2014  
ORDER BY commissions.commission_id, fonctions.fonction_id", GetSQLValueString($row_rsCommissions['commission_id'], "int"));
			$rsSousCommissions = mysql_query($query_rsSousCommissions, $MyFileConnect) or die(mysql_error());
			$row_rsSousCommissions = mysql_fetch_assoc($rsSousCommissions);
			$totalRows_rsSousCommissions = mysql_num_rows($rsSousCommissions);
	  
	  
	   ?>
        <strong><?php echo htmlentities($row_rsSousCommissions['commission_lib']); ?></strong>&nbsp;
            <table border="1" align="center">
              <tr>
                <td><strong>N° et objet du DAO</strong></td>
                <td><strong>Noms et Prenoms</strong></td>
                <td><strong>Fonctions</strong></td>
                <td><strong>Montant Projet</strong></td>
                <td><strong>Mois</strong></td>
                <td><strong>Annee</strong></td>
                <td><strong>Montant</strong></td>
                <td><strong>Total</strong></td>
              </tr>
              <?php $compt=0; do { $compt++; ?>
                <tr>
                  <td ><?php echo $row_rsSousCommissions['dossier_ref']; ?>&nbsp;</td>
                  <td><?php echo $row_rsSousCommissions['personne_nom']; ?>&nbsp; </td>
                  <td><?php echo $row_rsSousCommissions['fonction_lib']; ?>&nbsp; </td>
                  <td><?php echo $row_rsSousCommissions['montant_cumul']; ?>&nbsp; </td>
                  <td><?php echo $row_rsSousCommissions['nombre_offre']; ?>&nbsp; </td>
                  <td><?php echo $row_rsSousCommissions['annee']; ?>&nbsp; </td>
                  <td><?php echo $row_rsSousCommissions['montant']; ?>&nbsp; </td>
                  <td><?php echo $row_rsSousCommissions['total']; ?>&nbsp; </td>
                </tr>
                <?php } while ($row_rsSousCommissions = mysql_fetch_assoc($rsSousCommissions)); ?>
            </table>
      </td>
    </tr>
    <?php } while ($row_rsCommissions = mysql_fetch_assoc($rsCommissions)); ?>
</table>
  
  <p>	<br />
  <?php echo $totalRows_rsSousCommissions ?> Enregistrements Total
</p>
</p>
  </p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsCommissions);

mysql_free_result($rsSousCommissions);
?>
