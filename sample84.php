<?php 
	require_once('Connections/MyFileConnect.php'); 
	require_once('includes/db.php');
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

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

$maxRows_rsSessions = 20;
$pageNum_rsSessions = 0;
if (isset($_GET['pageNum_rsSessions'])) {
  $pageNum_rsSessions = $_GET['pageNum_rsSessions'];
}
$startRow_rsSessions = $pageNum_rsSessions * $maxRows_rsSessions;

$colname_rsSessions = "-1";
if (isset($_GET['persID'])) {
  $colname_rsSessions = $_GET['persID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT personnes.personne_nom, personnes.personne_prenom, sessions.*, membres.montant, (nombre_jour * montant) as total 
							FROM sessions, personnes, membres
							WHERE sessions.membres_personnes_personne_id = personnes.personne_id
							AND sessions.membres_personnes_personne_id = membres.personnes_personne_id 
							AND membres_personnes_personne_id = %s ORDER BY mois, annee ASC", GetSQLValueString($colname_rsSessions, "int"));
$query_limit_rsSessions = sprintf("%s LIMIT %d, %d", $query_rsSessions, $startRow_rsSessions, $maxRows_rsSessions);
$rsSessions = mysql_query($query_limit_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);

if (isset($_GET['totalRows_rsSessions'])) {
  $totalRows_rsSessions = $_GET['totalRows_rsSessions'];
} else {
  $all_rsSessions = mysql_query($query_rsSessions);
  $totalRows_rsSessions = mysql_num_rows($all_rsSessions);
}
$totalPages_rsSessions = ceil($totalRows_rsSessions/$maxRows_rsSessions)-1;

$queryString_rsSessions = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsSessions") == false && 
        stristr($param, "totalRows_rsSessions") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsSessions = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsSessions = sprintf("&totalRows_rsSessions=%d%s", $totalRows_rsSessions, $queryString_rsSessions);
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
    <th>ID</th>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Commission</th>
    <th>Fonction</th>
    <th>Nombre de sessions</th>
    <th>Montant</th>
    <th>Total</th>
    <th>Retenu (<?php echo $_SESSION['MM_Retenu']*100 ."%"; ?>)</th>
    <th>Net à percevoir</th>
    <th>Etat appurement</th>
    <th>Jour</th>
    <th>Mois</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
  <?php $somme=0; $sumRetenu = 0; $netPercevoir=0; $sumNetPercu=0; do { ?>
    <tr>
      <td><?php echo $personne_id = $row_rsSessions['membres_personnes_personne_id']; ?>&nbsp; </td>
      <td nowrap="nowrap"><a href="sample85.php?recordID=<?php echo $row_rsSessions['personne_nom']; ?>"> <?php echo strtoupper($row_rsSessions['personne_nom']); ?>&nbsp;</a></td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['personne_prenom']; ?>&nbsp; </td>
      <td><?php echo MinmapDB::getInstance()->get_commission_lib_by_commission_id($commission_id = $row_rsSessions['membres_commissions_commission_id']); ?>&nbsp;</td>
      <td><?php echo MinmapDB::getInstance()->get_fonction_lib_by_id($row_rsSessions['membres_fonctions_fonction_id']); ?>&nbsp;</td>
      <td align="center"><?php echo $row_rsSessions['Nombre_jour']; ?>&nbsp; </td>
      <td align="right"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?>&nbsp;</td>
      <td align="right"><?php echo number_format($row_rsSessions['total'],0,' ',' '); ?>&nbsp;</td>
      <td align="right"><?php echo number_format($retenu = $row_rsSessions['total']*$_SESSION['MM_Retenu'],0,' ',' '); $sumRetenu = $sumRetenu + $row_rsSessions['total']*$_SESSION['MM_Retenu']?></td>
      <td align="right"><?php echo number_format($netPercevoir = $row_rsSessions['total']-$retenu,0,' ',' '); ?>&nbsp;</td>
      <td align="right">
	  <?php if (isset($row_rsSessions['etat_appur']) && $row_rsSessions['etat_appur'] == 1) { ?>
      Appuré
      <?php } else { ?>
      <strong>Non appuré</strong>
      <?php } ?>
      &nbsp;</td>
      <td><?php $somme=$somme+$row_rsSessions['total']; ?> 
        
      <?php  
	  /*$count = count(explode("**", $row_rsSessions['jour'])); 
	  $liste_jour = explode("**", $row_rsSessions['jour']);
	  $counter=0; do  { 

					 print $liste_jour[$counter]." ".MinmapDB::getInstance()->get_mois_lib($row_rsSessions['mois'])." ".$row_rsSessions['annee']."</BR>";
	 $counter++; }while ($counter < $count);*/
	?>
      </td>
      <td><?php echo strtoupper(MinmapDB::getInstance()->get_mois_lib($row_rsSessions['mois']))." ". $annee = $row_rsSessions['annee']; ?>&nbsp;</td>
      <td><form id="form1" name="form1" method="post" action="sample86.php?persID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>">
      <?php if (isset($row_rsSessions['etat_appur']) && $row_rsSessions['etat_appur']==1) { ?>
		  <input type="submit" name="button" id="button" value="Appurer" disabled="disabled"/>
      <?php } else { ?>
      	  <input type="submit" name="button" id="button" value="Appurer" onClick="return confirm('Etes vous sur de vouloir appurer ?')"; />
          <input type="hidden" name="personne" id="personne" value="<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>" />
          <input type="hidden" name="appur" id="appur" value="1"/>
          <input type="hidden" name="mois" id="mois" value="<?php echo $row_rsSessions['mois']; ?>"/>
          <input type="hidden" name="annee" id="annee" value="<?php echo $row_rsSessions['annee']; ?>"/>
          <input type="hidden" name="commission" id="commission" value="<?php echo $row_rsSessions['membres_commissions_commission_id']; ?>"/>
          <input type="hidden" name="url" id="url" value="sample84.php?persID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>"/>
          <input type="hidden" name="MM_update" value="form1" />
          <?php } ?>
      </form></td>
      <td><form id="form2" name="form2" method="post" action="sample86.php?persID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>">
        <input type="submit" name="button2" id="button2" value="Annuler" onclick="return confirm('Etes vous sur de vouloir appurer ?')"; />
        <input type="hidden" name="personne2" id="personne2" value="<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>" />
        <input type="hidden" name="appur2" id="appur2" value="0"/>
        <input type="hidden" name="mois2" id="mois2" value="<?php echo $row_rsSessions['mois']; ?>"/>
        <input type="hidden" name="annee2" id="annee2" value="<?php echo $row_rsSessions['annee']; ?>"/>
        <input type="hidden" name="commission2" id="commission2" value="<?php echo $row_rsSessions['membres_commissions_commission_id']; ?>"/>
        <input type="hidden" name="url2" id="url2" value="sample84.php?persID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>"/>
        <input type="hidden" name="MM_update2" value="form2" />
      </form></td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
    <tr>
      <td>&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td nowrap="nowrap">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="center">&nbsp;</td>
      <th align="right">Total : </th>
      <td align="right"><?php echo number_format($somme,0,' ',' '); ?>&nbsp;</td>
      <td align="right"><?php echo $sumRetenu; ?>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">Montant Appuré = <?php echo MinmapDB::getInstance()->get_session_appurement($annee, $commission_id, $personne_id); ?></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsSessions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, 0, $queryString_rsSessions); ?>">Premier</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessions > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, max(0, $pageNum_rsSessions - 1), $queryString_rsSessions); ?>">Précédent</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsSessions < $totalPages_rsSessions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, min($totalPages_rsSessions, $pageNum_rsSessions + 1), $queryString_rsSessions); ?>">Suivant</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsSessions < $totalPages_rsSessions) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsSessions=%d%s", $currentPage, $totalPages_rsSessions, $queryString_rsSessions); ?>">Dernier</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
Enregistrements <?php echo ($startRow_rsSessions + 1) ?> à <?php echo min($startRow_rsSessions + $maxRows_rsSessions, $totalRows_rsSessions) ?> sur <?php echo $totalRows_rsSessions ?>
</body>
</html>
<?php
mysql_free_result($rsSessions);
?>
