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
$date = date('Y-m-d H:i:s');
$editFormAction = "appurement.php";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_rsSessionsPersonnes = "-1";
if (isset($_GET['aID'])) {
  $colname_rsSessionsPersonnes = $_GET['aID'];
}
$colname3_rsSessionsPersonnes = "-1";
if (isset($_GET['perID'])) {
  $colname3_rsSessionsPersonnes = $_GET['perID'];
}
$colname1_rsSessionsPersonnes = "1";
if (isset($_GET['m1ID'])) {
  $colname1_rsSessionsPersonnes = $_GET['m1ID'];
}
$colname2_rsSessionsPersonnes = "12";
if (isset($_GET['m2ID'])) {
  $colname2_rsSessionsPersonnes = $_GET['m2ID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessionsPersonnes = sprintf("SELECT membres_commissions_commission_id, personne_id, personne_nom, membres_fonctions_fonction_id, montant, nombre_jour, mois, (nombre_jour * montant) as montant_total FROM sessions, membres, personnes WHERE sessions.membres_personnes_personne_id = personnes.personne_id AND sessions.membres_fonctions_fonction_id = membres.fonctions_fonction_id  AND mois between %s AND %s  AND annee = %s AND personnes.personne_id = %s ORDER BY annee, mois", GetSQLValueString($colname1_rsSessionsPersonnes, "int"),GetSQLValueString($colname2_rsSessionsPersonnes, "int"),GetSQLValueString($colname_rsSessionsPersonnes, "text"),GetSQLValueString($colname3_rsSessionsPersonnes, "int"));
$rsSessionsPersonnes = mysql_query($query_rsSessionsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsSessionsPersonnes = mysql_fetch_assoc($rsSessionsPersonnes);
$totalRows_rsSessionsPersonnes = mysql_num_rows($rsSessionsPersonnes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td>Commission</td>
    <td>ID</td>
    <td>Nom et Prenom</td>
    <td>Fonction</td>
    <td>Taux</td>
    <td>Nombre de sessions</td>
    <td>Mois</td>
    <td>Montant</td>
    <td>&nbsp;</td>
  </tr>
  <?php $count=0; do { $count++ ?>
  <form name="form<?php echo $count ?>" action="<?php echo $editFormAction; ?>" method="post" >
    <tr>
      <td><a href="detail_session.php?recordID=<?php echo $row_rsSessionsPersonnes['membres_commissions_commission_id']; ?>"> <?php echo $count; ?> 
        <input type="hidden" name="commission_id" id="commission_id" value="<?php echo $row_rsSessionsPersonnes['membres_commissions_commission_id']; ?>" />
      </a></td>
      <td><a href="detail_session.php?perID=<?php echo $row_rsSessionsPersonnes['membres_personnes_personne_id']; ?>"><?php echo $row_rsSessionsPersonnes['personne_id']; ?></a>&nbsp; <input type="hidden" name="personne_id" id="personne_id" value="<?php echo $row_rsSessionsPersonnes['personne_id']; ?>" /></td>
      <td><?php echo $row_rsSessionsPersonnes['personne_nom']; ?>&nbsp; </td>
      <td><?php echo $row_rsSessionsPersonnes['membres_fonctions_fonction_id']; ?>&nbsp; <input type="hidden" name="fonction_id" id="fonction_id" value="<?php echo $row_rsSessionsPersonnes['membres_fonctions_fonction_id']; ?>" /></td>
      <td><?php echo $row_rsSessionsPersonnes['montant']; ?>&nbsp; <input type="hidden" name="taux" id="taux" value="<?php echo $row_rsSessionsPersonnes['montant']; ?>" /></td>
      <td><?php echo $row_rsSessionsPersonnes['nombre_jour']; ?>&nbsp; <input type="hidden" name="nombre_jour" id="nombre_jour" value="<?php echo $row_rsSessionsPersonnes['nombre_jour']; ?>" /></td>
      <td><?php echo $row_rsSessionsPersonnes['mois']; ?>&nbsp; <input type="hidden" name="mois" id="mois" value="<?php echo $row_rsSessionsPersonnes['mois']; ?>" />
      <input type="hidden" name="annee" id="<?php echo $_POST['aID'] ?>" /></td>
      <td><?php echo $row_rsSessionsPersonnes['montant_total']; ?>&nbsp; <input type="hidden" name="montant" id="montant" value="<?php echo $row_rsSessionsPersonnes['montant_total']; ?>" /></td>
      <td><input type="submit" name="button" id="button" value="Appurer..." />
          <input type="hidden" name="display" value="1" />
          <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
    </form>
    <?php } while ($row_rsSessionsPersonnes = mysql_fetch_assoc($rsSessionsPersonnes)); ?>
</table>
<br />
<?php echo $totalRows_rsSessionsPersonnes ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsSessionsPersonnes);
?>
