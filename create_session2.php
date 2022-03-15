<?php require_once('Connections/MyFileConnect.php'); 

$commissionIsEmpty = false;
$personneIsEmpty = false;
$fonctionIsEmpty = false;
$jourIsEmpty = false;
$moisIsEmpty = false;
$anneeIsEmpty = false;
$dossierIsEmpty = false;

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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	if (array_key_exists("back3", $_POST)) {
  $insertSQL = sprintf("INSERT INTO dossiers (dossier_id, dossier_ref, dossier_observ) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['dossier_id'], "int"),
                       GetSQLValueString($_POST['dossier_ref'], "text"),
                       GetSQLValueString($_POST['dossier_observ'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "detail.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
if ($_SERVER['REQUEST_METHOD'] == "POST"){
	if (array_key_exists("back5", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: create_commission.php');
        exit;
    }
}
if ($_SERVER['REQUEST_METHOD'] == "POST"){
	if (array_key_exists("back2", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: create_session2.php?action=insert');
        exit;
    }
}
    if ($_POST['membres_commissions_commission_id1']=="" || $_POST['membres_commissions_commission_id2']=="" || $_POST['membres_commissions_commission_id3']=="" || $_POST['membres_commissions_commission_id4']=="" || $_POST['membres_commissions_commission_id5']=="" ){
        $commissionIsEmpty = true;
    }
   // if ($_POST['personne_matricule']==""){
	if ($_POST['membres_personnes_personne_id1']=="" || $_POST['membres_personnes_personne_id2']=="" || $_POST['membres_personnes_personne_id3']=="" || $_POST['membres_personnes_personne_id4']=="" || $_POST['membres_personnes_personne_id5']==""){
        $personneIsEmpty = true;
    }
    if ($_POST['un']=="" || $_POST['deux']=="" || $_POST['trois']=="" || $_POST['quatre']=="" || $_POST['cinq']==""){
    	$jourIsEmpty = true;
    }
	
    if ($_POST['mois']==""){
    	$moisIsEmpty = true;
    }
	
    if ($_POST['annee']==""){
        $anneeIsEmpty = true;
    }
    if ($_POST['dossiers_dossier_id']==""){
        $dossierIsEmpty = true;
    }
if (!$commissionIsEmpty && !$personneIsEmpty && !$fonctionIsEmpty && !$moisIsEmpty && !$anneeIsEmpty && !$dossierIsEmpty){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {

$variable1 = implode( "**", $_POST['un'] ); 
$variable2 = implode( "**", $_POST['deux'] ); 
$variable3 = implode( "**", $_POST['trois'] ); 
$variable4 = implode( "**", $_POST['quatre'] );
$variable5 = implode( "**", $_POST['cinq'] );
	
  $insertSQL1 = sprintf("INSERT INTO sessions (membres_personnes_personne_id, membres_commissions_commission_id, membres_fonctions_fonction_id, dossiers_dossier_id, jour, mois, annee) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['membres_personnes_personne_id1'], "int"),
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id1'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($variable1, "text"),
					   GetSQLValueString($_POST['mois'], "text"),
					   GetSQLValueString($_POST['annee'], "text"));
					   
  $insertSQL2 = sprintf("INSERT INTO sessions (membres_personnes_personne_id, membres_commissions_commission_id, membres_fonctions_fonction_id, dossiers_dossier_id, jour, mois, annee) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['membres_personnes_personne_id2'], "int"),
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id2'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($variable2, "text"),
					   GetSQLValueString($_POST['mois'], "text"),
					   GetSQLValueString($_POST['annee'], "text"));
					   
  $insertSQL3 = sprintf("INSERT INTO sessions (membres_personnes_personne_id, membres_commissions_commission_id, membres_fonctions_fonction_id, dossiers_dossier_id, jour, mois, annee) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['membres_personnes_personne_id3'], "int"),
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id3'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($variable3, "text"),
					   GetSQLValueString($_POST['mois'], "text"),
					   GetSQLValueString($_POST['annee'], "text"));
					   
  $insertSQL4 = sprintf("INSERT INTO sessions (membres_personnes_personne_id, membres_commissions_commission_id, membres_fonctions_fonction_id, dossiers_dossier_id, jour, mois, annee) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['membres_personnes_personne_id4'], "int"),
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id4'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($variable4, "text"),
					   GetSQLValueString($_POST['mois'], "text"),
					   GetSQLValueString($_POST['annee'], "text"));
					   
  $insertSQL5 = sprintf("INSERT INTO sessions (membres_personnes_personne_id, membres_commissions_commission_id, membres_fonctions_fonction_id, dossiers_dossier_id, jour, mois, annee) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['membres_personnes_personne_id5'], "int"),
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id5'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($variable5, "text"),
					   GetSQLValueString($_POST['mois'], "text"),
					   GetSQLValueString($_POST['annee'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());
  $Result3 = mysql_query($insertSQL3, $MyFileConnect) or die(mysql_error());
  $Result4 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error());
  $Result5 = mysql_query($insertSQL5, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "insert.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsShowMember = 10;
$pageNum_rsShowMember = 0;
if (isset($_GET['pageNum_rsShowMember'])) {
  $pageNum_rsShowMember = $_GET['pageNum_rsShowMember'];
}
$startRow_rsShowMember = $pageNum_rsShowMember * $maxRows_rsShowMember;

$colname_rsShowMember = "-1";
if (isset($_GET['recordID'])) {
  $colname_rsShowMember = $_GET['recordID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowMember = sprintf("SELECT * FROM membres WHERE commissions_commission_id = %s", GetSQLValueString($colname_rsShowMember, "int"));
$query_limit_rsShowMember = sprintf("%s LIMIT %d, %d", $query_rsShowMember, $startRow_rsShowMember, $maxRows_rsShowMember);
$rsShowMember = mysql_query($query_limit_rsShowMember, $MyFileConnect) or die(mysql_error());
$row_rsShowMember = mysql_fetch_assoc($rsShowMember);

if (isset($_GET['totalRows_rsShowMember'])) {
  $totalRows_rsShowMember = $_GET['totalRows_rsShowMember'];
} else {
  $all_rsShowMember = mysql_query($query_rsShowMember);
  $totalRows_rsShowMember = mysql_num_rows($all_rsShowMember);
}
$totalPages_rsShowMember = ceil($totalRows_rsShowMember/$maxRows_rsShowMember)-1;

$queryString_rsShowMember = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsShowMember") == false && 
        stristr($param, "totalRows_rsShowMember") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsShowMember = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsShowMember = sprintf("&totalRows_rsShowMember=%d%s", $totalRows_rsShowMember, $queryString_rsShowMember);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelCommission = "SELECT * FROM commissions WHERE membre_insert = '1'";
$rsSelCommission = mysql_query($query_rsSelCommission, $MyFileConnect) or die(mysql_error());
$row_rsSelCommission = mysql_fetch_assoc($rsSelCommission);
$totalRows_rsSelCommission = mysql_num_rows($rsSelCommission);
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
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="" method="post" name="form1" id="form1">
<table>
  <tr>
    <th valign="top">Commission :</th>
    <td><select name="jumpMenu" size="15" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
      <?php
do {  
?>
      <?php 
$colname_rsLocaliteUrl = $row_rsSelCommission['localite_id'];
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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelDossier = "SELECT * FROM dossiers";
$rsSelDossier = mysql_query($query_rsSelDossier, $MyFileConnect) or die(mysql_error());
$row_rsSelDossier = mysql_fetch_assoc($rsSelDossier);
$totalRows_rsSelDossier = mysql_num_rows($rsSelDossier);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

?>
      <option value="create_session2.php?recordID=<?php echo $row_rsSelCommission['commission_id']?>"><?php echo htmlentities($row_rsTypeCommission['type_commission_lib']) . " de " . htmlentities($row_rsNature['lib_nature']) . " de(du) " . htmlentities($row_rsLocaliteUrl['localite_lib'])?></option>
      <?php
} while ($row_rsSelCommission = mysql_fetch_assoc($rsSelCommission));
  $rows = mysql_num_rows($rsSelCommission);
  if($rows > 0) {
      mysql_data_seek($rsSelCommission, 0);
	  $row_rsSelCommission = mysql_fetch_assoc($rsSelCommission);
  }
?>
    </select>
      <input type="submit" name="back5" value="Ajouter un dossier..."/></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th>TITRE</th>
    <td><span class="welcome"><?php echo htmlentities($row_rsTypeCommission['type_commission_lib']) . " de " . htmlentities($row_rsNature['lib_nature']) . " de(du) " . htmlentities($row_rsLocaliteUrl['localite_lib'])?></span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <th valign="top">Référence Dossiers :</th>
    <td><select name="dossiers_dossier_id" size="10" multiple="multiple" id="dossiers_dossier_id">
      <?php
do {  
?>
      <option value="<?php echo $row_rsSelDossier['dossier_id']?>" <?php if (!(strcmp($row_rsSelDossier['dossier_id'], htmlentities($_POST['dossiers_dossier_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsSelDossier['dossier_ref'])?></option>
      <?php
} while ($row_rsSelDossier = mysql_fetch_assoc($rsSelDossier));
  $rows = mysql_num_rows($rsSelDossier);
  if($rows > 0) {
      mysql_data_seek($rsSelDossier, 0);
	  $row_rsSelDossier = mysql_fetch_assoc($rsSelDossier);
  }
?>
    </select>
      <input type="submit" name="back2" value="Ajouter un dossier..."/>
      <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($dossierIsEmpty) {
                echo ('<div class="error">Selectionner le(s) dossiers examiné(s), sinon saisissez sa referérénce a partir du bouton AJOUTER DOSSIER ci-dessus...SVP</div>');
            }
            ?></td>
    <td><?php if (isset($_GET['action'])) { // Show if recordset not empty ?>
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Reference dossier :</td>
      <td><input type="text" name="dossier_ref" value="" size="32" /></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Observations :</td>
      <td><textarea name="dossier_observ" cols="50" rows="5"></textarea></td>
      </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right"><input type="hidden" name="dossier_id" value="" />        <input type="hidden" name="MM_insert2" value="form1" /></td>
      <td><input type="submit" value="Inserer dossier" /></td>
      </tr>
  </table>
  <?php } // Show if recordset not empty ?></td>
  </tr>
  <tr>
    <td colspan="3"><table border="0" class="std">
      <tr>
        <th>Exercice :</th>
        <td><select name="annee" id="annee">
          <option value="">Choisir une annee...</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], htmlentities($_POST['annee'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
          <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
  $rows = mysql_num_rows($rsAnnee);
  if($rows > 0) {
      mysql_data_seek($rsAnnee, 0);
	  $row_rsAnnee = mysql_fetch_assoc($rsAnnee);
  }
?>
        </select>
          <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($anneeIsEmpty) {
                echo ('<div class="error">Entrez l\'annee, SVP!</div>');
            }
            ?></td>
        <th>Mois : </th>
        <td><select name="mois" id="mois">
          <option value="">Choisir un mois...</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsMois['mois_id']?>" <?php if (!(strcmp($row_rsMois['mois_id'], htmlentities($_POST['mois'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsMois['lib_mois']?></option>
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
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($moisIsEmpty) {
                echo ('<div class="error">Enter le MOIS de la session, please!</div>');
            }
            ?></td>
        </tr>
    </table></td>
    </tr>
</table>
<table width="100%" align="center" class="std">
  <tr>
    <th>N°</th>
    <th>Membres</th>
    <th>Fonction</th>
	<?php 
	$counter=0; do  { $counter++; ?>
    <th><?php echo $counter ?></th>
	<?php }while ($counter<30);
	?>
  </tr>
  <?php do { ?>
  <tr>
    <td><a href="detail_test3.php?recordID=<?php echo $row_rsShowMember['commissions_commission_id']; ?>">
      <input type="hidden" name="membres_commissions_commission_id" value="<?php echo $_GET['recordID']; ?>" />
    </a></td>
    <td><p>
      <?php 
$colname_rsSelPersonne = $row_rsShowMember['personnes_personne_id'];
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelPersonne = sprintf("SELECT personne_nom, personne_prenom FROM personnes WHERE personne_id = %s", GetSQLValueString($colname_rsSelPersonne, "int"));
$rsSelPersonne = mysql_query($query_rsSelPersonne, $MyFileConnect) or die(mysql_error());
$row_rsSelPersonne = mysql_fetch_assoc($rsSelPersonne);
$totalRows_rsSelPersonne = mysql_num_rows($rsSelPersonne);

echo htmlentities($row_rsSelPersonne['personne_nom']) . " " . htmlentities($row_rsSelPersonne['personne_prenom']); 
 ?>
      <input type="hidden" name="<?php echo "membres_personnes_personne_id" . $row_rsShowMember['position'] ?>" value="<?php echo $row_rsShowMember['personnes_personne_id']; ?>" />
    </td>
    <td><?php 
$colname_rsSelFonction = "-1";
if (isset($row_rsShowMember['fonctions_fonction_id'])) {
  $colname_rsSelFonction = $row_rsShowMember['fonctions_fonction_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelFonction = sprintf("SELECT fonction_lib FROM fonctions WHERE fonction_id = %s", GetSQLValueString($colname_rsSelFonction, "int"));
$rsSelFonction = mysql_query($query_rsSelFonction, $MyFileConnect) or die(mysql_error());
$row_rsSelFonction = mysql_fetch_assoc($rsSelFonction);
$totalRows_rsSelFonction = mysql_num_rows($rsSelFonction);
		echo htmlentities($row_rsSelFonction['fonction_lib']); ?>
      <input type="hidden" name="<?php echo "membres_fonctions_fonction_id" . $row_rsShowMember['position']; ?>" value="<?php echo $row_rsShowMember['fonctions_fonction_id']; ?>" /></td>
<?php $counter=0; do  { $counter++; ?>
	<td>
    <input name="<?php echo $row_rsShowMember['checboxName'] . "[]"; ?>" type="checkbox" id="<?php echo $row_rsShowMember['checboxName'].$counter; ?>" value="<?php echo $counter ?>" />
    </td>
<?php	}while ($counter<30);
?>
  </tr>
  <?php } while ($row_rsShowMember = mysql_fetch_assoc($rsShowMember)); ?>
</table>
<?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($jourIsEmpty) {
                echo ('<div class="error">(*) Entrez les dates de session des membres , SVP!</div>');
            }
            ?>
<input type="hidden" name="MM_insert" value="form1" />
<input type="submit" value="Insert record" />
</form>
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsShowMember > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, 0, $queryString_rsShowMember); ?>">First</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowMember > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, max(0, $pageNum_rsShowMember - 1), $queryString_rsShowMember); ?>">Previous</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsShowMember < $totalPages_rsShowMember) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, min($totalPages_rsShowMember, $pageNum_rsShowMember + 1), $queryString_rsShowMember); ?>">Next</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsShowMember < $totalPages_rsShowMember) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsShowMember=%d%s", $currentPage, $totalPages_rsShowMember, $queryString_rsShowMember); ?>">Last</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<p>Records <?php echo ($startRow_rsShowMember + 1) ?> to <?php echo min($startRow_rsShowMember + $maxRows_rsShowMember, $totalRows_rsShowMember) ?> of <?php echo $totalRows_rsShowMember ?>
</body>
</html>
<?php
mysql_free_result($rsShowMember);

mysql_free_result($rsMois);

mysql_free_result($rsAnnee);
?>
