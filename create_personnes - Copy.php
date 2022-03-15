<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php');

$personneNomIsUnique = true;
$personneNomIsEmpty = false;
$numeroCompteIsUnique = true;
$numeroCompteIsEmpty = false;
$personneMatriculeIsUnique = true;
$personneMatriculeIsEmpty = false;
$cleIsEmpty = false;
$groupeIsEmpty = false;
$structureIsEmpty = false;
$fonctionIsEmpty = false;
$agenceIsEmpty = false;
$date = date('Y-m-d H:i:s');

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

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	
	//$matricule = $_POST['personne_mat1'] . " " . $_POST['personne_mat2'] . "-" . $_POST['personne_mat3'];
    
	if (array_key_exists("back", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: index.php');
        exit;
    }
	if (array_key_exists("back2", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: verify.php?nameID=variableURL');
        exit;
    }
	if (array_key_exists("back3", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: create_personnes.php');
        exit;
    }
	if (array_key_exists("back4", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: agence.php');
        exit;
    }
	if (array_key_exists("back5", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
		  $goTo = "index.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$goTo .= (strpos($goTo, '?')) ? "&" : "?";
			$goTo .= $_SERVER['QUERY_STRING'];
		  }
		  header(sprintf("Location: %s", $updateGoTo));
    }
    /** Check whether the user has filled in the wisher's name in the text field "user" */
    if ($_POST['personne_nom']== ""){
        $personneNomIsEmpty = true;
    }
   // if ($_POST['personne_matricule']==""){
	if ($_POST['personne_matricule']=="" ){
        $personneMatriculeIsEmpty = true;
    }
	
    if ($_POST['numero_compte']==""){
    	$numeroCompteIsEmpty = true;
    }
	
    if ($_POST['cle']==""){
        $cleIsEmpty = true;
    }
	if ($_POST['sous_groupe_id']==""){	
		$groupeIsEmpty = true;
	}
	if ($_POST['agence_code']==""){	
		$agenceIsEmpty = true;
	}
	if ($_POST['structure_id']==""){	
		$structureIsEmpty = true;
	}
	if ($_POST['fonction_id']==""){	
		$fonctionIsEmpty = true;
	}

    /** Create database connection */

   // $personneID = MinmapDB::getInstance()->get_value_id_by_value(personne_id, personnes, personne_nom, $_POST['personne_nom']);
    $personneID = MinmapDB::getInstance()->get_wisher_id_by_name($_POST['personne_nom'], $_POST['personne_prenom']);
    if ($personneID) {
        $personneNomIsUnique = false;
    }

   // $matriculeID = MinmapDB::getInstance()->get_value_id_by_value(personne_id, personnes, personne_matricule, $_POST['personne_matricule']);
    $matriculeID = MinmapDB::getInstance()->get_personne_id_by_matricule($_POST['personne_matricule']);
    if ($matriculeID && ($_GET[personneID]==1 || $_GET[personneID]==2)) {
        $personneMatriculeIsUnique = false;
    }

    $compteID = MinmapDB::getInstance()->get_value_id_by_value('personne_id', 'personnes', 'numero_compte', $_POST['numero_compte']);
  // $compteID = MinmapDB::getInstance()->get_wisher_id_by_name($_POST['numero_compte']);
    if ($compteID) {
        $numeroCompteIsUnique = false;
    }
	
if (!$personneNomIsEmpty && $personneNomIsUnique && !$personneMatriculeIsEmpty && $personneMatriculeIsUnique && !$numeroCompteIsEmpty && $numeroCompteIsUnique && !$cleIsEmpty && !$groupeIsEmpty && !$agenceIsEmpty && !$structureIsEmpty && !$fonctionIsEmpty){
	
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL1 = sprintf("INSERT INTO personnes (personne_matricule, personne_nom, personne_prenom, personne_grade, domaine_id, personne_telephone, structure_id, sous_groupe_id, fonction_id, type_personne_id, date_creation, display) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                      /* GetSQLValueString($_POST['personne_id'], "int"),*/
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString($_POST['personne_nom'], "text"),
                       GetSQLValueString($_POST['personne_prenom'], "text"),
                       GetSQLValueString($_POST['personne_grade'], "text"),
					   GetSQLValueString($_POST['domaine_id'], "int"),
                       GetSQLValueString($_POST['personne_telephone'], "text"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['sous_groupe_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($_POST['type_personne_id'], "int"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
  $Personne_id = MinmapDB::getInstance()->get_value_id_by_value('personne_id', 'personnes', 'personne_nom', $_POST['personne_nom']); 
  $agence_cle = $_POST['banque_code'] . "" . $_POST['agence_code'];
  $insertSQL2 = sprintf("INSERT INTO rib (personne_id, personne_matricule, agence_cle, banque_code, agence_code, numero_compte, cle, date_creation) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($Personne_id, "int"),
					   GetSQLValueString($_POST['personne_matricule'], "text"),
					   GetSQLValueString($agence_cle, "text"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['numero_compte'], "text"),
                       GetSQLValueString($_POST['cle'], "text"),
                       GetSQLValueString($date, "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "afficher_personnes.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
}

$colname_rsStructures = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsStructures = $_GET['personneID'];
  $colname_rsFonctionID = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructures = sprintf("SELECT structure_id, structure_lib FROM structures WHERE type_structure_id = %s AND display = '1'", GetSQLValueString($colname_rsStructures, "int"));
$rsStructures = mysql_query($query_rsStructures, $MyFileConnect) or die(mysql_error());
$row_rsStructures = mysql_fetch_assoc($rsStructures);
$totalRows_rsStructures = mysql_num_rows($rsStructures);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGroupe = "SELECT groupes.groupe_id,groupes.groupe_lib,sous_groupes.sous_groupe_id,sous_groupes.sous_groupe_lib FROM groupes, sous_groupes WHERE sous_groupes.groupe_id = groupes.groupe_id AND  sous_groupes.display = '1'";
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = sprintf("SELECT fonction_id, fonction_lib FROM fonctions WHERE groupe_fonction_id = %s AND display = '1'",GetSQLValueString($colname_rsFonctionID, "int"));
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypePersonne = "SELECT type_personne_id, type_personne_lib FROM type_personnes WHERE display = '1'";
$rsTypePersonne = mysql_query($query_rsTypePersonne, $MyFileConnect) or die(mysql_error());
$row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
$totalRows_rsTypePersonne = mysql_num_rows($rsTypePersonne);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanques = "SELECT banque_code, banque_lib FROM banques WHERE display = '1'";
$rsBanques = mysql_query($query_rsBanques, $MyFileConnect) or die(mysql_error());
$row_rsBanques = mysql_fetch_assoc($rsBanques);
$totalRows_rsBanques = mysql_num_rows($rsBanques);



$colname_rsBanqueq = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsBanqueq = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanqueq = sprintf("SELECT banque_code, banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsBanqueq, "text"));
$rsBanqueq = mysql_query($query_rsBanqueq, $MyFileConnect) or die(mysql_error());
$row_rsBanqueq = mysql_fetch_assoc($rsBanqueq);
$totalRows_rsBanqueq = mysql_num_rows($rsBanqueq);


$colname_rsSelectTypePersonne = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsSelectTypePersonne = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelectTypePersonne = sprintf("SELECT type_personne_id, type_personne_lib FROM type_personnes WHERE type_personne_id = %s", GetSQLValueString($colname_rsSelectTypePersonne, "int"));
$rsSelectTypePersonne = mysql_query($query_rsSelectTypePersonne, $MyFileConnect) or die(mysql_error());
$row_rsSelectTypePersonne = mysql_fetch_assoc($rsSelectTypePersonne);
$totalRows_rsSelectTypePersonne = mysql_num_rows($rsSelectTypePersonne);

$colname_rsSousGrouepByUrl = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsSousGrouepByUrl = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGrouepByUrl = sprintf("SELECT groupes.groupe_id,groupes.groupe_lib,sous_groupes.sous_groupe_id,sous_groupes.sous_groupe_lib FROM groupes, sous_groupes WHERE sous_groupes.groupe_id = groupes.groupe_id AND  sous_groupes.display = '1' AND  groupe_id = %s", GetSQLValueString($colname_rsSousGrouepByUrl, "int"));
$rsSousGrouepByUrl = mysql_query($query_rsSousGrouepByUrl, $MyFileConnect) or die(mysql_error());
$row_rsSousGrouepByUrl = mysql_fetch_assoc($rsSousGrouepByUrl);
$totalRows_rsSousGrouepByUrl = mysql_num_rows($rsSousGrouepByUrl);

$colname_rsSelBanqueUrl = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsSelBanqueUrl = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelBanqueUrl = sprintf("SELECT banque_code, banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsSelBanqueUrl, "text"));
$rsSelBanqueUrl = mysql_query($query_rsSelBanqueUrl, $MyFileConnect) or die(mysql_error());
$row_rsSelBanqueUrl = mysql_fetch_assoc($rsSelBanqueUrl);
$totalRows_rsSelBanqueUrl = mysql_num_rows($rsSelBanqueUrl);

$colname_rsGroupe = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsGroupe = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupe = sprintf("SELECT sous_groupe_id, sous_groupe_lib, groupe_id FROM sous_groupes WHERE groupe_id = %s", GetSQLValueString($colname_rsGroupe, "int"));
$rsGroupe = mysql_query($query_rsGroupe, $MyFileConnect) or die(mysql_error());
$row_rsGroupe = mysql_fetch_assoc($rsGroupe);
$totalRows_rsGroupe = mysql_num_rows($rsGroupe);

$colname_rsAgences = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsAgences = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAgences = sprintf("SELECT agence_code, agence_lib FROM agences WHERE banque_code = %s AND display = '1'", GetSQLValueString($colname_rsAgences, "text"));
$rsAgences = mysql_query($query_rsAgences, $MyFileConnect) or die(mysql_error());
$row_rsAgences = mysql_fetch_assoc($rsAgences);
$totalRows_rsAgences = mysql_num_rows($rsAgences);

$colname_rsDomaines = "-1";
if (isset($_GET['domID'])) {
  $colname_rsDomaines = $_GET['domID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsDomaines = sprintf("SELECT domaine_id, domaine_lib FROM domaines_activites WHERE domaine_id = %s", GetSQLValueString($colname_rsDomaines, "int"));
$rsDomaines = mysql_query($query_rsDomaines, $MyFileConnect) or die(mysql_error());
$row_rsDomaines = mysql_fetch_assoc($rsDomaines);
$totalRows_rsDomaines = mysql_num_rows($rsDomaines);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: FICHIIER MINMAP...</title>
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table border="0">
  <tr>
    <th width="185" align="left"><a href="afficher_personnes.php"><img src="images/img/bd_browse.png" alt="" width="16" height="16" align="absmiddle"/></a>Afficher le fichier</th>
  </tr>
<!--  <tr>
    <th align="left" scope="col"><a href="create_membres.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter des membres à une commission</th>
  </tr>-->
</table>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
<h1>Creation d'une personne</h1>
  <table align="center" class="std">
    <?php if ($totalRows_rsAgences == 0) { // Show if recordset empty ?>
      <tr valign="baseline">
        <th align="right" valign="top" nowrap="nowrap">Banque  <span class="error">*</span> :</th>
        <td colspan="3"><select name="jumpMenu" size="15" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
          <?php do {  ?>
          <option value="new_personnes.php?banqueID=<?php echo $row_rsBanques['banque_code']?>"><?php echo $row_rsBanques['banque_code'] . " : " . $row_rsBanques['banque_lib'] ?></option>
          <?php
} while ($row_rsBanques = mysql_fetch_assoc($rsBanques));
  $rows = mysql_num_rows($rsBanques);
  if($rows > 0) {
      mysql_data_seek($rsBanques, 0);
	  $row_rsBanques = mysql_fetch_assoc($rsBanques);
  }
?>
        </select></td>
      </tr>
      <?php } // Show if recordset empty ?>
      <?php if ($totalRows_rsSelBanqueUrl > 0) { // Show if recordset not empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">BANQUE <span class="error">*</span> :</th>
    <td colspan="3" valign="middle"><input type="hidden" name="banque_code" value="<?php echo $_GET[banqueID]; ?>" size="32" />
      <span class="welcome"><strong><?php echo $row_rsSelBanqueUrl['banque_code'] . " : " . $row_rsSelBanqueUrl['banque_lib']; ?></strong></span><a href="#" onclick="<?php popup("popup_banques.php", "610", "500"); ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
  </tr>
  <?php } // Show if recordset not empty ?>
<tr valign="baseline">
      <td align="right" nowrap="nowrap">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <?php if ( isset($_GET['banqueID'])) { // Show if recordset empty ?>
  <tr valign="baseline">
    <th align="right" valign="middle" nowrap="nowrap">TYPE PERSONNE <span class="error">*</span> :</th>
    <td colspan="3" valign="middle">
      <?php if (empty($_GET['personneID'])) { // Show if recordset empty ?>
      <select name="jumpMenu2" id="jumpMenu2" onchange="MM_jumpMenu('parent',this,0)">
        <option value="">[Select type personne...]</option>
        <?php do {  ?>
        <option value="new_personnes.php?banqueID=<?php echo $_GET['banqueID'] ?>&amp;personneID=<?php echo $row_rsTypePersonne['type_personne_id']?>" <?php if (!(strcmp($row_rsTypePersonne['type_personne_id'], htmlentities($_POST['type_personne_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsTypePersonne['type_personne_lib']); ?></option>
        <?php
} while ($row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne));
  $rows = mysql_num_rows($rsTypePersonne);
  if($rows > 0) {
      mysql_data_seek($rsTypePersonne, 0);
	  $row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
  }
?>
        </select>
      <?php } // Show if recordset empty ?>
      <?php if (isset($_GET[personneID])) { // Show if recordset not empty ?>
      <span class="welcome"><strong><?php echo htmlentities($row_rsSelectTypePersonne['type_personne_id'] . " : " . $row_rsSelectTypePersonne['type_personne_lib']) ?></strong></span>
<input type="hidden" name="type_personne_id" value="<?php echo $_GET[personneID]; ?>" size="32" />
<?php } // Show if recordset not empty ?></td>
  </tr>
      <?php } // Show if recordset empty ?>
  <?php if (isset($_GET['personneID'])) { // Show if recordset empty ?>
  	<?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
  <tr valign="baseline">
    <th nowrap="nowrap" align="right">MATRICULE <span class="error"><strong>*</strong></span> :</th>
    <td colspan="3"><input name="personne_matricule" type="text" value="<?php if ( isset($_POST['personne_matricule'])) { echo $_POST['personne_matricule'];} ?>" size="12" maxlength="8" />      
	(ex :XXXXXX-X)
	<?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($personneMatriculeIsEmpty) { ?>
        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the MATRICULE, please!</div>
            <?php }
            if (!$personneMatriculeIsUnique) { ?>
        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This MATRICULE already exists. Please check the spelling and try again</div>
         <?php   } ?>
	  </td>
  </tr>
  <?php } else { ?>
	<input name="personne_matricule" type="hidden" value="XXXXXX-X" size="12" maxlength="9" />
  <?php } ?>
<tr valign="baseline">
    <th nowrap="nowrap" align="right">NOM <span class="error">*</span> :</th>
        <td><input type="text" name="personne_nom" value="<?php if ( isset($_POST['personne_nom'])) { echo $_POST['personne_nom'];} ?>" size="32"/><br/>
        <?php
            /** Display error messages if "user" field is empty or there is already a user with that name*/
            if ($personneNomIsEmpty) { ?>
                <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the NAME, please!</div>
           <?php  }
            if (!$personneNomIsUnique) { ?>
                <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />The person already exists. Please check the spelling and try again</div>
				<!--<input type="submit" name="back2" value="Verifier"/>-->
        <?php    }
            ?>
        </td>
        <th align="right">PRENOM :</th>
      <td><input type="text" name="personne_prenom" value="<?php if ( isset($_POST['personne_prenom'])) { echo $_POST['personne_prenom'];} ?>" size="32" /></td>
    </tr>

      <tr valign="baseline">
  <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
        <input name="domaine_id" type="hidden" value="1" />
        <th nowrap="nowrap" align="right">GRADE :</th>
        <td><input type="text" name="personne_grade" value="<?php if ( isset($_POST['personne_grade'])) { echo $_POST['personne_grade'];} ?>" size="32" /></td>
  <?php } else { ?>
		<input name="personne_grade" type="hidden" value="Certifié" />
        <th align="right" nowrap="nowrap">DOMAINE D'ACTIVITE :</th>
        <td><input type="hidden" name="domaine_id" value="<?php if ( isset($_GET['domID'])) { echo $_GET['domID'];} else {echo "1";} ?>" size="32" />
          <input name="domaine_id2" type="text" value="<?php echo $row_rsDomaines['domaine_lib']; ?>" size="32" readonly="readonly" />
        <a href="#" onclick="<?php popup("popup_domaines.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
<?php           
	$showGoTo = "afficher_domaines.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
        
        <a href="#" onclick="<?php popup($showGoTo, "610", "500"); ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
  <?php }// Show if recordset empty ?>
        <th>TELEPHONE :</th>
        <td><input type="text" name="personne_telephone" value="<?php if ( isset($_POST['personne_telephone'])) { echo $_POST['personne_telephone'];} ?>" size="32" /></td>
      </tr>
  <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
      <tr valign="baseline">
        <th nowrap="nowrap" align="right">STRUCTURE <span class="error">*</span> :</th>
        <td colspan="3"><select name="structure_id">
            <option value="">:: Selectionner </option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsStructures['structure_id']?>" <?php if (!(strcmp($row_rsStructures['structure_id'], htmlentities($_POST['structure_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsStructures['structure_lib']?></option>
            <?php
} while ($row_rsStructures = mysql_fetch_assoc($rsStructures));
  $rows = mysql_num_rows($rsStructures);
  if($rows > 0) {
      mysql_data_seek($rsStructures, 0);
	  $row_rsStructures = mysql_fetch_assoc($rsStructures);
  }
?>
        </select>
          <a href="#" onclick="<?php popup("create_structure.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($structureIsEmpty) { ?>
                <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the STRUCTURE, please</div>
        <?php  } ?>
        </td>
      </tr>
  <?php } else { ?>
		<input name="structure_id" type="hidden" value="342" />
  <?php }// Show if recordset empty ?>
<?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
      <tr valign="baseline">
        <th align="right" nowrap="nowrap">GROUPE <span class="error">*</span> :</th>
        <td colspan="3"><select name="sous_groupe_id" id="groupe_id">
          <option selected="selected" value="">:: Selectionner</option>
       <?php
do {  
?>
       <option value="<?php echo $row_rsGroupe['sous_groupe_id']?>" <?php if (!(strcmp($row_rsGroupe['sous_groupe_id'], htmlentities($_POST['sous_groupe_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsGroupe['sous_groupe_lib'])?></option>
       <?php
} while ($row_rsGroupe = mysql_fetch_assoc($rsGroupe));
  $rows = mysql_num_rows($rsGroupe);
  if($rows > 0) {
      mysql_data_seek($rsGroupe, 0);
	  $row_rsGroupe = mysql_fetch_assoc($rsGroupe);
  }
?>
   </select>
          <a href="#" onclick="<?php popup("popup_groupes.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($groupeIsEmpty) { ?>
                <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the GROUPE, please</div>
        <?php  } ?>
        </td>
      </tr>
  <?php } else { ?>
	<input name="sous_groupe_id" type="hidden" value="33"/>
  <?php } ?>
    
<?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
      <tr valign="baseline">
        <th nowrap="nowrap" align="right">FONCTION <span class="error">*</span> :</th>
        <td colspan="3"><select name="fonction_id">
            <option value="" >::Selectionner</option>
            <?php
do {  
?>
            <option value="<?php echo $row_rsFonction['fonction_id']?>" <?php if (!(strcmp($row_rsFonction['fonction_id'], htmlentities($_POST['fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsFonction['fonction_lib'])?></option>
            <?php
} while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
  $rows = mysql_num_rows($rsFonction);
  if($rows > 0) {
      mysql_data_seek($rsFonction, 0);
	  $row_rsFonction = mysql_fetch_assoc($rsFonction);
  }
?>
        </select>
          <a href="#" onclick="<?php popup("create_fonction.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
        <?php
             /** Display error messages if the "password" field is empty */
            if ($fonctionIsEmpty) { ?>
                <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the FONCTION, please</div>
           <?php }
            ?></td>
      </tr>
  <?php } else { ?>
		<input name="fonction_id" type="hidden" value="35"/>
  <?php } ?>

  <tr valign="baseline">
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr valign="baseline">
        <th align="right" valign="top" nowrap="nowrap">AGENCE <span class="error">*</span> :</th>
        <td colspan="3" align="left" valign="top"><select name="agence_code" size="10" id="select">
            <?php
do {  
?>
            <option value="<?php echo $row_rsAgences['agence_code']?>" <?php if (!(strcmp($row_rsAgences['agence_code'], htmlentities($_POST['agence_code'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsAgences['agence_code'] . " : " . $row_rsAgences['agence_lib']?></option>
            <?php
} while ($row_rsAgences = mysql_fetch_assoc($rsAgences));
  $rows = mysql_num_rows($rsAgences);
  if($rows > 0) {
      mysql_data_seek($rsAgences, 0);
	  $row_rsAgences = mysql_fetch_assoc($rsAgences);
  }
?>
            </select>
          <a href="#" onclick="<?php popup("create_agence.php", "810", "700"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
          <?php
             /** Display error messages if the "password" field is empty */
            if ($agenceIsEmpty) { ?>
                <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selected AGENCE, please</div>
          <?php  }
            ?></td>
      </tr>
      <tr valign="baseline">
        <th align="right" nowrap="nowrap">N° COMPTE <span class="error">*</span> :</th>
        <td><input name="numero_compte" type="text" value="<?php if ( isset($_POST['numero_compte'])) { echo $_POST['numero_compte'];} ?>" size="32" maxlength="11" />
        <?php
             /** Display error messages if the "password" field is empty */
            if ($numeroCompteIsEmpty) { ?>
                <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the NUMERO COMPTE, please</div>
       <?php     }
            if (!$numeroCompteIsUnique) { ?>
                <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This NUMERO COMPTE already exists. Please check the spelling and try again</div>
         <?php   }
            ?>(11 chiffres)</td>
        <th align="right">CLE  <span class="error">*</span> :</th>
        <td><input name="cle" type="text" value="<?php if ( isset($_POST['cle'])) { echo $_POST['cle'];} ?>" size="10" maxlength="2" />
          <?php
             /** Display error messages if the "password" field is empty */
            if ($cleIsEmpty) { ?>
                <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the CLE, please</div>
          <?php  }
            ?>
        (2 chiffres)</td>
      </tr>
      <tr valign="baseline">
        <td align="right" nowrap="nowrap">&nbsp;</td>
        <td colspan="3"><input type="submit" value="Ins&eacute;rer un enregistrement"/>
        <input type="reset" name="back5" value="Reinitialiser"/></td>
      </tr>
        <?php } // Show if recordset empty ?>
  </table>
  <input type="hidden" name="date_creation" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
  <input type="hidden" name="display" value="1" size="32" />
  <input type="hidden" name="personne_id" value="" size="32" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsStructures);

mysql_free_result($rsSousGroupe);

mysql_free_result($rsFonction);

mysql_free_result($rsTypePersonne);

mysql_free_result($rsBanques);

mysql_free_result($rsAgences);

mysql_free_result($rsDomaines);

mysql_free_result($rsBanqueq);

mysql_free_result($rsSelectTypePersonne);

mysql_free_result($rsSousGrouepByUrl);

mysql_free_result($rsSelBanqueUrl);

mysql_free_result($rsGroupe);
?>
