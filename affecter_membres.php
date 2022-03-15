<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('includes/MyFonction.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
if (isset($_POST['personne_id1']) && isset($_POST['fonction_id1'])) {
 $insertSQL1 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id1'], "int"),
                       GetSQLValueString($_POST['personne_id1'], "int"),
					   GetSQLValueString("un", "text"),
                       GetSQLValueString(1, "int"));  }

if (isset($_POST['personne_id2']) && isset($_POST['fonction_id2'])) {
 $insertSQL2 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id2'], "int"),
                       GetSQLValueString($_POST['personne_id2'], "int"),
					   GetSQLValueString("deux", "text"),
                       GetSQLValueString(2, "int")); }

if (isset($_POST['personne_id3']) && isset($_POST['fonction_id3']))	{				   
  $insertSQL3 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id3'], "int"),
                       GetSQLValueString($_POST['personne_id3'], "int"),
					   GetSQLValueString("trois", "text"),
                       GetSQLValueString(3, "int")); }

if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{				   
  $insertSQL4 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id4'], "int"),
                       GetSQLValueString($_POST['personne_id4'], "int"),
					   GetSQLValueString("quatre", "text"),
                       GetSQLValueString(4, "int")); }

if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{				   
  $insertSQL5 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id5'], "int"),
                       GetSQLValueString($_POST['personne_id5'], "int"),
					   GetSQLValueString("cinq", "text"),
                       GetSQLValueString(5, "int")); }

if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{			   
 $insertSQL6 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id6'], "int"),
                       GetSQLValueString($_POST['personne_id6'], "int"),
					   GetSQLValueString("cinq", "text"),
                       GetSQLValueString(5, "int")); }

if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{			   
 $insertSQL7 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id7'], "int"),
                       GetSQLValueString($_POST['personne_id7'], "int"),
					   GetSQLValueString("cinq", "text"),
                       GetSQLValueString(5, "int")); }
					   
 $updateSQL = sprintf("UPDATE commissions SET membre_insert=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($_POST['commissions_commission_id'], "int"));

  $Result = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
					   
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());
  $Result3 = mysql_query($insertSQL3, $MyFileConnect) or die(mysql_error());
  $Result4 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error());
  $Result5 = mysql_query($insertSQL5, $MyFileConnect) or die(mysql_error());
  $Result6 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error());
  $Result7 = mysql_query($insertSQL5, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "create_membres.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = "SELECT * FROM commissions WHERE membre_insert = '0'";
$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
$row_rsCommission = mysql_fetch_assoc($rsCommission);
$totalRows_rsCommission = mysql_num_rows($rsCommission);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = "SELECT fonction_id, fonction_lib FROM fonctions WHERE groupe_fonction_id = 1 AND display = '1'";
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonne = "SELECT personne_id, personne_nom, personne_prenom FROM personnes WHERE display = '1'";
$rsPersonne = mysql_query($query_rsPersonne, $MyFileConnect) or die(mysql_error());
$row_rsPersonne = mysql_fetch_assoc($rsPersonne);
$totalRows_rsPersonne = mysql_num_rows($rsPersonne);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
<script type="text/javascript">
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
</script>
</head>

<body>
<table width="100%" border="0" class="std">
  <tr>
    <th width="185" align="left"><a href="#"><img src="images/img/b_home.png" alt="" width="16" height="16" align="absmiddle"/></a>Retour à l'accueil</th>
    <th width="185" align="left"><a href="show_personnes.php"><img src="images/img/bd_browse.png" alt="" width="16" height="16" align="absmiddle"/></a>Fichier du personnel<a href="new_personnes.php"></a></th>
    <th width="185" align="left"><a href="show_personnes.php"><img src="images/img/bd_browse.png" alt="" width="16" height="16" align="absmiddle"/></a>Les  commissions</th>
    <th width="185" align="left"><a href="show_personnes.php"><img src="images/img/bd_browse.png" alt="" width="16" height="16" align="absmiddle"/></a>Les données de base du système</th>
    <th width="185" align="left">&nbsp;</th>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
    <td align="left"><a href="new_personnes.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une personne</a></td>
    <td align="left"><p><a href="new_commissions.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/> Créer une commission</a></p>
      <p><a href="affecter_membres.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter des membres à une commision</a></p>
<p><a href="validate_sessions.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/> Ajouter une session</a></p></td>
    <td align="left"><a href="create_fonctions.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a> <a href="#">Créer une fonction</a></td>
    <td align="left">&nbsp;</td>
  </tr>
  <!--  <tr>
    <th align="left" scope="col"><a href="create_membres.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter des membres à une commission</th>
  </tr>-->
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std">
    <tr valign="baseline">
      <th align="right" valign="top" nowrap="nowrap">Commission :</th>
      <td colspan="3"><select name="commissions_commission_id" size="10">
        <?php
do {  
?>
 <?php 
$colname_rsLocaliteUrl = $row_rsCommission['localite_id'];
if (isset($_SERVER['localite_id'])) {
  $colname_rsLocaliteUrl = $_SERVER['localite_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsLocaliteUrl = sprintf("SELECT localite_lib FROM localites WHERE localite_id = %s", GetSQLValueString($colname_rsLocaliteUrl, "int"));
$rsLocaliteUrl = mysql_query($query_rsLocaliteUrl, $MyFileConnect) or die(mysql_error());
$row_rsLocaliteUrl = mysql_fetch_assoc($rsLocaliteUrl);
$totalRows_rsLocaliteUrl = mysql_num_rows($rsLocaliteUrl);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsNature = "SELECT lib_nature FROM natures WHERE display = '1'";
$rsNature = mysql_query($query_rsNature, $MyFileConnect) or die(mysql_error());
$row_rsNature = mysql_fetch_assoc($rsNature);
$totalRows_rsNature = mysql_num_rows($rsNature);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypeCommission = "SELECT type_commission_lib FROM type_commissions WHERE display = '1'";
$rsTypeCommission = mysql_query($query_rsTypeCommission, $MyFileConnect) or die(mysql_error());
$row_rsTypeCommission = mysql_fetch_assoc($rsTypeCommission);
$totalRows_rsTypeCommission = mysql_num_rows($rsTypeCommission);
?>
        <option value="<?php echo $row_rsCommission['commission_id']?>"
<?php if (!(strcmp($row_rsCommission['commission_id'], htmlentities($_POST['commissions_commission_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsTypeCommission['type_commission_lib']) . " de " . htmlentities($row_rsNature['lib_nature']) . " de(du) " . htmlentities($row_rsLocaliteUrl['localite_lib'])?></option>
        <?php
} while ($row_rsCommission = mysql_fetch_assoc($rsCommission));
  $rows = mysql_num_rows($rsCommission);
  if($rows > 0) {
      mysql_data_seek($rsCommission, 0);
	  $row_rsCommission = mysql_fetch_assoc($rsCommission);
  }
?>
      </select><?php echo $_POST['commissions_commission_id'] ?></td>
    </tr>
    <?php if ($totalRows_rsCommission > 0) { // Show if recordset not empty ?>
  <tr valign="baseline">
    <td colspan="4" nowrap="nowrap">Nombre de membres : 
      <select name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
        <option>:: Select</option>
        <option value="affecter_membres.php?value=4>">4</option>
        <option value="affecter_membres.php?value=5">5</option>
        <option value="affecter_membres.php?value=6">6</option>
        <option value="affecter_membres.php?value=7">7</option>
      </select>
      <?php echo $_POST['jumpMenu']; ?></td>
  </tr>
      <?php if (isset($_GET['value'])) { // Show if recordset not empty ?>
      <tr valign="baseline">
        <th nowrap="nowrap" align="right">&nbsp;</th>
        <th>Fonction :</th>
        <th>Personnes désignées:</th>
        <th>Montant</th>
      </tr>
      <?php $counter=0; do  { $counter++; ?>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td><select name="<?php echo "fonction_id" . $counter  ?>">
          <option value=""><?php echo ":: Fonction" . $counter  ?></option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsFonction['fonction_id']?>"<?php if (!(strcmp($row_rsFonction['fonction_id'], htmlentities($_POST['fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>>
		  <?php echo htmlentities($row_rsFonction['fonction_lib'])?></option>
          <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
        </select></td>
        <td><input type="hidden" name="txttest" id="textfield" />
          <?php 
		$colname_rsSelectPersonne = "-1";
		$MyUrl = "recordID" . $counter;
		$Personne_nom = "id" . $counter;  // doit correspondre à une valeur idx ou x estune valeur incrémentale
		if (isset($_GET[$Personne_nom])) { // on controle l'URL
		  $colname_rsSelectPersonne = $_GET[$Personne_nom];
		}
		mysql_select_db($database_MyFileConnect, $MyFileConnect);
		$query_rsSelectPersonne = sprintf("SELECT personne_id, personne_nom, personne_prenom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsSelectPersonne, "int"));
		$rsSelectPersonne = mysql_query($query_rsSelectPersonne, $MyFileConnect) or die(mysql_error());
		$row_rsSelectPersonne = mysql_fetch_assoc($rsSelectPersonne);
		$totalRows_rsSelectPersonne = mysql_num_rows($rsSelectPersonne);
		?>
          <input type="hidden" name="<?php echo "personne_id" . $counter  ?>" id="<?php echo "personne_id" . $counter  ?>" value="<?php echo $row_rsSelectPersonne['personne_id']; ?>"/>
          
          <?php           
	$showGoToPersonne = "personnes" . $counter . ".php?link" . $counter . "=id" . $counter;
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoToPersonne .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoToPersonne .= $_SERVER['QUERY_STRING'];
  }
?>        
         <?php echo $row_rsSelectPersonne['personne_nom'] . "  " . $row_rsSelectPersonne['personne_prenom']?>
<a href="#" onclick="<?php popup($showGoToPersonne, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>
          
          
          
        </td>
        <td><input type="text" name="<?php echo "montant" . $counter  ?>" id="<?php echo "montant" . $counter  ?>" value="<?php echo "montant" . $counter  ?>"/></td>
      </tr>
      <?php } while ($counter < $_GET['value']); ?>
      <tr valign="baseline">
        <td nowrap="nowrap" align="right">&nbsp;</td>
        <td colspan="3"><input type="submit" value="Insert record" /></td>
      </tr>
      <?php } // Show if recordset not empty ?>
    <?php } // Show if recordset not empty ?>
  </table>
  <input type="hidden" name="membre_insert" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsCommission);

mysql_free_result($rsFonction);

mysql_free_result($rsPersonne);

mysql_free_result($rsSelectPersonne);
?>
