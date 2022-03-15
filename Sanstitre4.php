<?php virtual('/AGIS/Connections/MyFileConnect.php'); ?>
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

$colYear_rsAppurements = "$_GET['aID']";
if (isset($_POST['Year'])) {
  $colYear_rsAppurements = $_POST['Year'];
}
$colCommission_rsAppurements = "$_GET['comID']";
if (isset($_GET['comID'])) {
  $colCommission_rsAppurements = $_GET['comID'];
}
$colMonth1_rsAppurements = "$_GET['mID1']";
if (isset($_POST['Month1'])) {
  $colMonth1_rsAppurements = $_POST['Month1'];
}
$colMonth2_rsAppurements = "$_GET['mID2']";
if (isset($_POST['Month2'])) {
  $colMonth2_rsAppurements = $_POST['Month2'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAppurements = sprintf("SELECT concat(rib.agence_cle, rib.numero_compte, rib.cle) as num_cpte, appurements.rib_beneficiaire, personnes.personne_nom, appurements.nom_beneficiaire, membres.montant, sum(sessions.nombre_jour) as nbre_jour, sum(membres.montant*sessions.nombre_jour) as somme, appurements.montant, (sum(membres.montant*sessions.nombre_jour)- appurements.montant) as reste_a_payer FROM personnes, rib, membres, sessions, appurements WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = membres.personnes_personne_id AND personnes.personne_id = sessions.membres_personnes_personne_id AND concat(rib.agence_cle, rib.numero_compte, rib.cle) = appurements.rib_beneficiaire AND sessions.mois between %s AND %s AND sessions.annee = %s AND sessions.membres_commissions_commission_id = %s GROUP BY personnes.personne_id", GetSQLValueString($colMonth1_rsAppurements, "text"),GetSQLValueString($colMonth2_rsAppurements, "text"),GetSQLValueString($colYear_rsAppurements, "text"),GetSQLValueString($colCommission_rsAppurements, "int"));
$rsAppurements = mysql_query($query_rsAppurements, $MyFileConnect) or die(mysql_error());
$row_rsAppurements = mysql_fetch_assoc($rsAppurements);
$totalRows_rsAppurements = mysql_num_rows($rsAppurements);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <th>RIB</th>
    <th>RIB B</th>
    <th>NOM</th>
    <th>NOM B</th>
    <th>MONTANT</th>
    <th>NOMBRE DE JOUR</th>
    <th>SOMME</th>
    <th>MONTANT</th>
    <th>RESTE A PAYER</th>
  </tr>
  <?php do { ?>
    <tr>
      <td><a href="detail_appurement.php?recordID=<?php echo $row_rsAppurements['num_cpte']; ?>"> <?php echo $row_rsAppurements['num_cpte']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsAppurements['rib_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['personne_nom']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['nom_beneficiaire']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['montant']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['nobre_jour']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['somme']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['montant']; ?>&nbsp; </td>
      <td><?php echo $row_rsAppurements['reste_a_payer']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsAppurements = mysql_fetch_assoc($rsAppurements)); ?>
</table>
<br />
<?php echo $totalRows_rsAppurements ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsAppurements);
?>
