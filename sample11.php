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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois1 = "SELECT * FROM mois";
$rsMois1 = mysql_query($query_rsMois1, $MyFileConnect) or die(mysql_error());
$row_rsMois1 = mysql_fetch_assoc($rsMois1);
$totalRows_rsMois1 = mysql_num_rows($rsMois1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois2 = "SELECT * FROM mois";
$rsMois2 = mysql_query($query_rsMois2, $MyFileConnect) or die(mysql_error());
$row_rsMois2 = mysql_fetch_assoc($rsMois2);
$totalRows_rsMois2 = mysql_num_rows($rsMois2);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_id, type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);

$colname4_rsSessionsCommite = "-1";
if (isset($_POST['txtType'])) {
  $colname4_rsSessionsCommite = $_POST['txtType'];
}
$colname2_rsSessionsCommite = "-1";
if (isset($_POST['txtMonth2'])) {
  $colname2_rsSessionsCommite = $_POST['txtMonth2'];
}
$colname3_rsSessionsCommite = "-1";
if (isset($_POST['txtYear'])) {
  $colname3_rsSessionsCommite = $_POST['txtYear'];
}
$colname1_rsSessionsCommite = "-1";
if (isset($_POST['txtMonth1'])) {
  $colname1_rsSessionsCommite = $_POST['txtMonth1'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT distinct(membres_commissions_commission_id), commission_lib, localite_lib, taux
FROM commissions, sessions, localites, type_commissions WHERE membres_commissions_commission_id = commission_id AND commissions.localite_id = localites.localite_id AND commissions.type_commission_id = type_commissions.type_commission_id AND mois between %s AND %s AND annee = %s AND commissions.type_commission_id = %s", GetSQLValueString($colname1_rsSessionsCommite, "text"),GetSQLValueString($colname2_rsSessionsCommite, "text"),GetSQLValueString($colname3_rsSessionsCommite, "text"),GetSQLValueString($colname4_rsSessionsCommite, "text"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form action="" method="POST">
<p>&nbsp;</p>
<table border="1" class="std2">
<tr>
    <th align="right" scope="row">Exercice:</th>
    <td><select name="txtYear" id="txtYear">
      <option value=""<?php if (!(strcmp("", $_POST['txtType']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
      <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
  $rows = mysql_num_rows($rsAnnee);
  if($rows > 0) {
      mysql_data_seek($rsAnnee, 0);
	  $row_rsAnnee = mysql_fetch_assoc($rsAnnee);
  }
?>
    </select></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th align="right" scope="row">Periode allant de :</th>
    <td><select name="txtMonth1" id="txtMonth1">
        <option value="" <?php if (!(strcmp("", $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsMois1['mois_id']?>" <?php if (!(strcmp($row_rsMois1['mois_id'], $_POST['txtMonth1']))) {echo "selected=\"selected\"";} ?>><?php echo Ucfirst($row_rsMois1['lib_mois']);?></option>
      <?php
} while ($row_rsMois1 = mysql_fetch_assoc($rsMois1));
  $rows = mysql_num_rows($rsMois1);
  if($rows > 0) {
      mysql_data_seek($rsMois1, 0);
	  $row_rsMois1 = mysql_fetch_assoc($rsMois1);
  }
?>
    </select></td>
    <th>à</th>
    <td><select name="txtMonth2" id="txtMonth2">
       <option value="" <?php if (!(strcmp("", $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsMois2['mois_id']?>" <?php if (!(strcmp($row_rsMois2['mois_id'], $_POST['txtMonth2']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois2['lib_mois']); ?></option>
      <?php
} while ($row_rsMois2 = mysql_fetch_assoc($rsMois2));
  $rows = mysql_num_rows($rsMois2);
  if($rows > 0) {
      mysql_data_seek($rsMois2, 0);
	  $row_rsMois2 = mysql_fetch_assoc($rsMois2);
  }
?>
    </select></td>
    <td>&nbsp;</td>
    </tr>
  <tr>
    <th align="right" scope="row">Type de commission:</th>
    <td colspan="3"><select name="txtType" id="txtType">
      <option value="" <?php if (!(strcmp("", $_POST['txtType']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsTypeCommission['type_commission_id']?>"<?php if (!(strcmp($row_rsTypeCommission['type_commission_id'], $_POST['txtType']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsTypeCommission['type_commission_lib']?></option>
      <?php
} while ($row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission));
  $rows = mysql_num_rows($rsTypeCommission);
  if($rows > 0) {
      mysql_data_seek($rsTypeCommission, 0);
	  $row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
  }
?>
      </select></td>
    <td><input type="submit" name="button" id="button" value="Rechercher" /></td>
    </tr>
</table>

</form>
</BR>
<table border="1" align="center">
  <tr>
    <td>ID</td>
    <td>Libelle</td>
    <td>Localite</td>
    <td><?php echo ucfirst($month = MinmapDB::getInstance()->get_month_by_month_id($counter));?>&nbsp;</td>
    <td>Taux</td>
    <td>Montant</td>
  </tr>
    <?php $counter = 0; do { $counter++; ?>
    <tr>
      <td nowrap="nowrap"><?php echo $row_rsSessions['membres_commissions_commission_id']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['commission_lib']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['localite_lib']; ?>&nbsp; </td>
      <td nowrap="nowrap">
      <?php 
		$colname_rsCountIndemnit2 = "-1";
		if (isset($row_rsSessionsCommite['membres_commissions_commission_id'])) {
		  $colname_rsCountIndemnity = $row_rsSessionsCommite['membres_commissions_commission_id'];
		}
		$colname_rsCountIndemnity1 = "-1";
		if (isset($counter)) {
		  $colname_rsCountIndemnity1 = $counter;
		}
		$colname_rsCountIndemnity2 = "-1";
		if (isset($_POST['txtYear'])) {
		  $colname_rsCountIndemnity2 = $_POST['txtYear'];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsCountIndemnity = sprintf("SELECT sum(nombre_jour) as number_day FROM sessions WHERE membres_personnes_personne_id = %s
		AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s",  GetSQLValueString(2129, "int"), GetSQLValueString($colname_rsCountIndemnity, "int"),GetSQLValueString($colname_rsCountIndemnity1, "text"),GetSQLValueString($colname_rsCountIndemnity2, "text"));
		$rsCountIndemnity = mysql_query($query_rsCountIndemnity, $MyFileConnect) or die(mysql_error());
		$row_rsCountIndemnity = mysql_fetch_assoc($rsCountIndemnity);
		$totalRows_rsCountIndemnity = mysql_num_rows($rsCountIndemnity);
		
		$montant_session = $row_rsCountIndemnity['number_day'] * $row_rsSessionsCommite['taux'];
		$total_montant = $total_montant + $montant_session;
		echo number_format($montant_session,0,' ',' ');
		
		?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo number_format($row_rsSessions['taux'],0,' ',' '); ?>&nbsp;</td>
      <td nowrap="nowrap"><?php echo number_format($total_montant,0,' ',' '); $somme = $somme + $total_montant; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
</table>
<br />
<?php echo $totalRows_rsSessions ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($rsAnnee);

mysql_free_result($rsTypeCommission);

mysql_free_result($rsSessions);
?>
