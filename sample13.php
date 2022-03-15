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
$date = date('Y-m-d H:i:s');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

 echo "membres =".$_POST['membres_personnes_personne_id']."commissions = " . $_POST['membres_commissions_commission_id'] . "commission =".$_POST['membres_fonctions_fonction_id']."dossiers =".$_POST['dossiers_dossier_id']."nombre dossier =".$_POST['nombre_dossier']."Nombre jour =".$_POST['Nombre_jour']."jour =".$_POST['jour']."mois =".$_POST['mois']."annee =".$_POST['annee2']."Date =".$date."dateUpdate =".$_POST['dateUpdate']."display =".$_POST['display'];

/*
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sessions (membres_personnes_personne_id, membres_commissions_commission_id, membres_fonctions_fonction_id, dossiers_dossier_id, nombre_dossier, Nombre_jour, jour, mois, annee, dateCreation, dateUpdate, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['membres_personnes_personne_id'], "int"),
                       GetSQLValueString($_POST['membres_commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['membres_fonctions_fonction_id'], "int"),
                       GetSQLValueString($_POST['dossiers_dossier_id'], "int"),
                       GetSQLValueString($_POST['nombre_dossier'], "int"),
                       GetSQLValueString($_POST['Nombre_jour'], "int"),
                       GetSQLValueString($_POST['jour'], "text"),
                       GetSQLValueString($_POST['mois'], "text"),
                       GetSQLValueString($_POST['annee'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "sample13.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
*/
$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois ORDER BY mois_id ASC";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id 
FROM commissions, membres, fonctions, personnes 
WHERE membres.commissions_commission_id = commissions.commission_id  
AND fonctions_fonction_id = fonctions.fonction_id  
AND personnes_personne_id = personne_id 
AND membres.fonctions_fonction_id BETWEEN 1 AND 3
AND personnes.display = 1 
AND commissions_commission_id = %s
AND membres.display = 1
ORDER BY fonctions_fonction_id", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsJourMois = "-1";
if (isset($_GET['moisID'])) {
  $colname_rsJourMois = $_GET['moisID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

$colname_rsRepresentants = "-1";
if (isset($_GET['comID'])) {
  $colname_rsRepresentants = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsRepresentants = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id, code_structure
FROM commissions, membres, fonctions, personnes, structures 
WHERE membres.commissions_commission_id = commissions.commission_id  
AND fonctions_fonction_id = fonctions.fonction_id  
AND personnes_personne_id = personne_id 
AND personnes.structure_id = structures.structure_id
AND membres.fonctions_fonction_id <> 1
AND membres.fonctions_fonction_id <> 2
AND membres.fonctions_fonction_id <> 3
AND personnes.display = 1 
AND membres.display = 1
AND commissions_commission_id = %s 
ORDER BY fonctions_fonction_id", GetSQLValueString($colname_rsRepresentants, "int"));
$rsMembres = mysql_query($query_rsRepresentants, $MyFileConnect) or die(mysql_error());
$row_rsRepresentants = mysql_fetch_assoc($rsMembres);
$totalRows_rsRepresentants = mysql_num_rows($rsMembres);
?>
<?php 
switch ($_GET['moisID']){
case 01 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 02 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');break;
case 03 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 04 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 05 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 06 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 07 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 8 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 9 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 10 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 11 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 12 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
}
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
            <option value="<?php echo "sample13.php?comID=".$_GET['comID']."&amp;menuID=".$_GET['menuID']."&moisID=".$row_rsMois['mois_id']."&aID=".$_GET['aID']."&valid=".$_GET['valid'] ?>"<?php if (!(strcmp($row_rsMois['mois_id'], $_GET['moisID']))) {echo "selected=\"selected\"";} ?>><?php echo ucfirst($row_rsMois['lib_mois'])?></option>
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
          <th>Annee :</th>
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
      </table></td>
    </tr>
    <tr>
      <td><table width="75%" border="1" align="left" class="std">
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
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>
<h1>Dossiers</h1>
<table border="1" align="center" class="std">
  <tr>
    <th colspan="3">Jours</th>
    <?php 
			$counter=0; do  { $counter++; ?>
    <th><?php echo $counter ?></th>
    <?php }while ($counter< $row_rsJourMois['nbre_jour']);
	?>
  </tr>
  <tr>
    <td colspan="3">Nombre de dossiers</td>
    <?php 
			$counter=0; do  { $counter++; ?>
    <td><select name="<?php echo "nombre_dossier" . $counter ?>2">
      <option value="">.</option>
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
    <?php }while ($counter< $row_rsJourMois['nbre_jour']);
	?>
  </tr>
</table>
<h1>Membres</h1>
<table border="1" align="center" class="std">
          <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Fonction</th>
            <?php $counter=0; do  { $counter++; ?>
            <th><?php echo $counter ?></th>
            <?php }while ($counter< $row_rsJourMois['nbre_jour']);?>
          </tr>
          <?php $compter=0; do  { $compter++; ?>
<tr>
            <td nowrap="nowrap"><a href="detail_personnes.php?recordID=<?php echo $row_rsMembres['commission_id']; ?>"> <?php echo $compter; ?></a></td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsMembres['personne_nom'] . " " . ucfirst($row_rsMembres['personne_prenom'])); ?>
              <input type="hidden" name="<?php echo "personne_id".$compter; ?>" value="<?php echo $row_rsMembres['personne_id']; ?>" size="32" />
              <a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
              <input type="hidden" name="personne_id" id="personne_id" value="2129"/>
              </a>&nbsp; </td>
            <td nowrap="nowrap"><?php echo strtoupper(htmlentities($row_rsMembres['fonction_lib'])); ?>&nbsp;
              <input type="hidden" name="<?php echo "fonction_id".$compter; ?>" value="<?php echo $row_rsMembres['fonctions_fonction_id']; ?>" size="32" />
              &nbsp;<a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
              <input type="hidden" name="fonction_id" id="fonction_id" value="41"/>
              </a></td>
            <?php 
			// debut
			 foreach($list as $value){ ?>
				<td nowrap="nowrap">
				<?php
				switch($compter){
				case $compter :
				$jour = $_POST['jour'.$compter];
				$liste = $_POST['jour'.$compter];
				$count = count($_POST['jour'.$compter]);
				?>
				<input type="checkbox" name=<?php echo "jour" . $compter . "[]" ?> id=<?php echo "jour". $compter . $value ?> value="<?php echo $value ?>"  
				<?php echo ((in_array($value, $liste1))? "checked='checked'":"") ?> >
				<?php break; } ?>
				</td>
				<?php } //fin ?>
        </tr>
          <?php } while ($row_rsMembres = mysql_fetch_assoc($rsMembres)); ?>
        </table>
<h1>Representant Maitre d'Ouvrage</h1>
      <table>
        <tr valign="baseline">
          <th nowrap="nowrap" align="right">&nbsp;</th>
          <?php           
			$showGoTo = "sample14.php";
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
          <th></th>
        </tr>
        <tr valign="baseline">
          <td align="right" nowrap="nowrap">&nbsp;</td>
          <td align="right" nowrap="nowrap"><a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">Ajouter un representant...<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>&nbsp;</td>
        </tr>
        <tr valign="baseline">
          <td colspan="2" align="right" nowrap="nowrap">&nbsp;</td>
        </tr>
      </table>
        <table border="1" align="center" class="std">
          <tr>
            <th>ID</th>
            <th>Nom representant</th>
            <th>Maitre d'ouvrage</th>
          <?php 
			$counter=0; do  { $counter++; ?>
            <th><?php echo $counter ?></th>
          <?php }while ($counter< $row_rsJourMois['nbre_jour']); ?>
          </tr>
          <?php $compter=0; do  { $compter++; ?>
          <tr>
            <td nowrap="nowrap"><a href="detail_personnes.php?recordID=<?php echo $row_rsRepresentants['commission_id']; ?>"> <?php echo $compter; ?></a></td>
            <td nowrap="nowrap"><?php echo strtoupper($row_rsRepresentants['personne_nom'] . " " . ucfirst($row_rsRepresentants['personne_prenom'])); ?>
              <input type="hidden" name="<?php echo "rep_personne_id".$compter; ?>" value="<?php echo $row_rsRepresentants['personne_id']; ?>" size="32" />
              <a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
                <input type="hidden" name="personne_id" id="personne_id" value="2129"/>
              </a> </td>
            <td nowrap="nowrap"><?php echo strtoupper(htmlentities($row_rsRepresentants['code_structure'])); ?>&nbsp;
              <input type="hidden" name="<?php echo "fonction_id".$compter; ?>" value="<?php echo $row_rsRepresentants['fonctions_fonction_id']; ?>" size="32" />
              &nbsp;<a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>">
                <input type="hidden" name="fonction_id" id="fonction_id" value="41"/>
              </a></td>
            <?php 
			$counter=0; do  { $counter++; ?>
            <td nowrap="nowrap">
            <input type="checkbox" name="<?php echo "day" . $compter . "[]" ?>" id="<?php echo "day". $compter . $value ?>" value="<?php echo $value ?>"  
				<?php echo ((in_array($value, $liste1))? "checked='checked'":"") ?> /></td>
            <?php }while ($counter< $row_rsJourMois['nbre_jour']);
	?>
          </tr>
          <?php } while ($row_rsRepresentants = mysql_fetch_assoc(rsRepresentants)); ?>
      </table>
      <input type="submit" value="Insérer un enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="membres_personnes_personne_id" value="" />
  <input type="hidden" name="membres_commissions_commission_id" value="" />
  <input type="hidden" name="membres_fonctions_fonction_id" value="" />
  <input type="hidden" name="dossiers_dossier_id" value="1" />
  <input type="hidden" name="nombre_dossier" value="1" />
  <input type="hidden" name="Nombre_jour" value="1" />
  <input type="hidden" name="jour" value="" />
  <input type="hidden" name="mois" value="" />
  <input type="hidden" name="annee" value="" />
  <input type="hidden" name="dateCreation" value="" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsCommissions);

mysql_free_result($rsAnnee);

mysql_free_result($rsMois);

mysql_free_result($rsJourMois);

mysql_free_result($rsMembres);
?>
