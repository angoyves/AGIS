<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('Connections/MyFileConnect.php'); 
	  require_once('includes/MyFonction.php'); ?>
<?php 
$anneeIsEmpty = false;
$moisIsEmpty = false;
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

if ($_POST['txt_Year']=="" ){
        $anneeIsEmpty = true;
    }
if ($_POST['txt_Month']==""){
    	$moisIsEmpty = true;
    }
	
$colname_rsCommissions = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommissions = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommissions = sprintf("SELECT commission_id, commission_lib, lib_nature, type_commission_lib, localite_lib FROM commissions, natures, type_commissions, localites WHERE commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id   AND commission_id = %s ORDER BY localite_lib ASC ", GetSQLValueString($colname_rsCommissions, "int"));
$rsCommissions = mysql_query($query_rsCommissions, $MyFileConnect) or die(mysql_error());
$row_rsCommissions = mysql_fetch_assoc($rsCommissions);
$totalRows_rsCommissions = mysql_num_rows($rsCommissions);

$colname_rsMembres = "-1";
if (isset($_GET['comID'])) {
  $colname_rsMembres = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMembres = sprintf("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, position, fonctions_fonction_id FROM commissions, membres, fonctions, personnes WHERE membres.commissions_commission_id = commissions.commission_id  AND fonctions_fonction_id = fonctions.fonction_id  AND personnes_personne_id = personne_id  AND commissions_commission_id = %s", GetSQLValueString($colname_rsMembres, "int"));
$rsMembres = mysql_query($query_rsMembres, $MyFileConnect) or die(mysql_error());
$row_rsMembres = mysql_fetch_assoc($rsMembres);
$totalRows_rsMembres = mysql_num_rows($rsMembres);

$colname_rsJourMois = "-1";
if (isset($_POST['txt_Month'])) {
  $colname_rsJourMois = $_POST['txt_Month'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsJourMois = sprintf("SELECT * FROM mois WHERE mois_id = %s", GetSQLValueString($colname_rsJourMois, "text"));
$rsJourMois = mysql_query($query_rsJourMois, $MyFileConnect) or die(mysql_error());
$row_rsJourMois = mysql_fetch_assoc($rsJourMois);
$totalRows_rsJourMois = mysql_num_rows($rsJourMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois = "SELECT * FROM mois ORDER BY mois_id ASC";
$rsMois = mysql_query($query_rsMois, $MyFileConnect) or die(mysql_error());
$row_rsMois = mysql_fetch_assoc($rsMois);
$totalRows_rsMois = mysql_num_rows($rsMois);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);



$totalRows_rsMenu = mysql_num_rows($rsMenu);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelYear = "SELECT * FROM annee";
$rsSelYear = mysql_query($query_rsSelYear, $MyFileConnect) or die(mysql_error());
$row_rsSelYear = mysql_fetch_assoc($rsSelYear);
$totalRows_rsSelYear = mysql_num_rows($rsSelYear);

$colname_rsSessions = "-1";
if (isset($_POST['txt_Com'])) {
  $colname_rsSessions = $_POST['txt_Com'];
}
$colname_rsSessions1 = "-1";
if (isset($_POST['txt_Month'])) {
  $colname_rsSessions1 = $_POST['txt_Month'];
}
$colname_rsSessions2 = "-1";
if (isset($_POST['txt_Year'])) {
  $colname_rsSessions2 = $_POST['txt_Year'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom,  membres_commissions_commission_id,  membres_fonctions_fonction_id, fonction_lib,  lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, annee, montant,(nombre_jour * montant) as total FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres WHERE sessions.membres_personnes_personne_id = personnes.personne_id  AND sessions.membres_commissions_commission_id = commissions.commission_id  AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id AND sessions.membres_personnes_personne_id = membres.personnes_personne_id  AND commissions.nature_id = natures.nature_id   AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id AND sessions.membres_fonctions_fonction_id <> 4 AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
$rsSessions = mysql_query($query_rsSessions, $MyFileConnect) or die(mysql_error());
$row_rsSessions = mysql_fetch_assoc($rsSessions);
$totalRows_rsSessions = mysql_num_rows($rsSessions);

$colonne_rsSessions_sCom = "-1";
if (isset($_POST['txt_Com'])) {
  $colonne_rsSessions_sCom = $_POST['txt_Com'];
}
$colonne2_rsSessions_sCom = "-1";
if (isset($_POST['txt_Year'])) {
  $colonne2_rsSessions_sCom = $_POST['txt_Year'];
}
$colonne1_rsSessions_sCom = "-1";
if (isset($_POST['txt_Month'])) {
  $colonne1_rsSessions_sCom = $_POST['txt_Month'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_sCom = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom,  membres_commissions_commission_id,  membres_fonctions_fonction_id, fonction_lib,  lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, annee, montant,(nombre_jour * montant) as total FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres WHERE sessions.membres_personnes_personne_id = personnes.personne_id AND sessions.membres_commissions_commission_id = commissions.commission_id AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id  AND sessions.membres_personnes_personne_id = membres.personnes_personne_id   AND commissions.nature_id = natures.nature_id    AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id  AND sessions.membres_fonctions_fonction_id <> 4  AND commission_parent = %s  AND mois = %s AND annee = %s", GetSQLValueString($colonne_rsSessions_sCom, "int"),GetSQLValueString($colonne1_rsSessions_sCom, "int"),GetSQLValueString($colonne2_rsSessions_sCom, "int"));
$rsSessions_sCom = mysql_query($query_rsSessions_sCom, $MyFileConnect) or die(mysql_error());
$row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom);
$totalRows_rsSessions_sCom = mysql_num_rows($rsSessions_sCom);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSessions_rep = sprintf("SELECT membres_personnes_personne_id, personne_nom, personne_prenom,  membres_commissions_commission_id,  membres_fonctions_fonction_id, fonction_lib,  lib_nature, type_commission_lib, localite_lib, nombre_jour, jour, mois, annee, montant,(nombre_jour * montant) as total FROM sessions, personnes, commissions, fonctions, natures, type_commissions, localites, membres WHERE sessions.membres_personnes_personne_id = personnes.personne_id  AND sessions.membres_commissions_commission_id = commissions.commission_id  AND sessions.membres_fonctions_fonction_id = fonctions.fonction_id AND sessions.membres_personnes_personne_id = membres.personnes_personne_id  AND commissions.nature_id = natures.nature_id   AND commissions.type_commission_id = type_commissions.type_commission_id   AND commissions.localite_id = localites.localite_id AND sessions.membres_fonctions_fonction_id = 4 AND membres_commissions_commission_id = %s AND mois = %s AND annee = %s", GetSQLValueString($colname_rsSessions, "int"),GetSQLValueString($colname_rsSessions1, "text"),GetSQLValueString($colname_rsSessions2, "text"));
$rsSessions_rep = mysql_query($query_rsSessions_rep, $MyFileConnect) or die(mysql_error());
$row_rsSessions_rep = mysql_fetch_assoc(rsSessions_rep);
$totalRows_rsSessions_rep = mysql_num_rows($rsSessions_rep);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="">
  <table width="100%" border="0" align="center">
    <tr>
      <td>
      <table width="75%" border="1" align="left" class="std">
        <tr valign="baseline">
          <td colspan="4" nowrap="nowrap">Selectionner  une commission
            <?php           
	$showGoTo = "search_commissions4.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoTo .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoTo .= $_SERVER['QUERY_STRING'];
  }
?>
            <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
            <strong>
              <?php if (isset($_GET['comID'])) { ?>
                <?php echo $row_rsCommissions['type_commission_lib']; ?> de <?php echo $row_rsCommissions['lib_nature']; ?> du (de)<?php echo $row_rsCommissions['localite_lib']; ?>
                <?php } ?>
              </strong>
            <input name="txt_Com" type="hidden" id="commission_id" value="<?php echo $row_rsCommissions['commission_id']; ?>" />
            <?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
            <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
            <?php }
            ?>
            <input type="hidden" name="txt_Com" value="<?php echo $row_rsCommissions['commission_id']; ?>" size="32" /></td>
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
          <td colspan="4" align="right" nowrap="nowrap"><input type="hidden" name="MM_insert" value="form1" /></td>
        </tr>
      </table>
      </td>
    </tr>
    <tr>
        <td>
    <?php if ($totalRows_rsCommissions > 0) { // Show if recordset not empty ?>
    <table align="left" class="std">
      <tr valign="baseline">
        <th nowrap="nowrap" align="right">Mois:</th>
        <td><select name="txt_Month" id="txt_Month">
          <option value="" <?php if (!(strcmp("", $_POST['txt_Month']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
          <?php do {  ?>
          <option value="<?php echo $row_rsMois['mois_id']?>"<?php if (!(strcmp($row_rsMois['mois_id'], $_POST['txt_Month']))) {echo "selected=\"selected\"";} ?>>
		  <?php echo ucfirst($row_rsMois['lib_mois']) ?></option>
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
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Select month, please</div>
          <?php  } ?>
          </td>      
      </tr>
      
      <tr valign="baseline">
        <th nowrap="nowrap" align="right">Année : </th>
        <td><select name="txt_Year" id="txt_Year">
          <option value="" <?php if (!(strcmp("", $_POST['txt_Year']))) {echo "selected=\"selected\"";} ?>>:: Select...</option>
          <?php
do {  
?>
          <option value="<?php echo $row_rsSelYear['lib_annee']?>"<?php if (!(strcmp($row_rsSelYear['lib_annee'], $_POST['txt_Year']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsSelYear['lib_annee']?></option>
          <?php
} while ($row_rsSelYear = mysql_fetch_assoc($rsSelYear));
  $rows = mysql_num_rows($rsSelYear);
  if($rows > 0) {
      mysql_data_seek($rsSelYear, 0);
	  $row_rsSelYear = mysql_fetch_assoc($rsSelYear);
  }
?>
        </select>
          <?php
             /** Display error messages if the "password" field is empty */
       if ($anneeIsEmpty) { ?>
          <div class="control"><img src="images/img/s_attention.png" alt="" width="16" height="16" align="absmiddle" />Enter the YEAR, please</div>
          <?php  } ?></td>
      </tr>
    </table>
    <?php } // Show if recordset not empty ?>
    	</td>
    </tr>
    <tr>
      <td><input type="submit" value="Rechercher..." /></td>
    </tr>
  </table>
</form>

<h1>Membres Commission</h1>
<table>
	<tr>
    	<td>
		<?php $link = "print_sessions.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID']. "&menuID=" . $_GET['menuID'] ?>
      	<?php $link2 = "upd_sessions.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=" . $_GET['menuID']?>
        <?php $link8 = "del_membre.php"?><?php $link9 = "add_membre.php"?>
    	<a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Version imprimable</a>&nbsp;&nbsp;<a href="<?php echo $link2 ?>"><img src="images/img/b_edit.png" width="16" height="16" /> Mettre à jour</a>&nbsp;&nbsp;<a href="<?php echo $link9 ?>?fID=<?php echo $row_rsSessions['membres_fonctions_fonction_id']; ?>&comID=<?php echo $row_rsSessions['membres_commissions_commission_id']; ?>&pID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&aID=<?php echo $_POST['txt_Year'] ?>&mID=<?php echo$_POST['txt_Month'] ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter un membre</a></BR>
    	</td>
	</tr>
</table>
      <?php if ($totalRows_rsSessions > 0) { // Show if recordset not empty ?>
  <table border="1" align="center" class="std">
    <tr>
      <th>&nbsp;</th>
      <th>&nbsp;</th>
      <th>Nom et Prenom</th>
      <th>Fonction</th>
      <?php $counter=0; do  { $counter++; ?>
      <th><?php echo $counter ?></th>
      <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
      <th>Total</th>
      <th>Taux</th>
      <th>Montant</th>
    </tr>
    <?php $counter=0; do  { $counter++; ?>
    <tr>
      <td nowrap="nowrap"><a href="<?php echo $link2 ?>"><img src="images/img/b_edit.png" width="16" height="16" /></a><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&map=personnes"></a></td>
      <td nowrap="nowrap"><a href="<?php echo $link8 ?>?fID=<?php echo $row_rsSessions['membres_fonctions_fonction_id']; ?>&comID=<?php echo $row_rsSessions['membres_commissions_commission_id']; ?>&pID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&aID=<?php echo $_POST['txt_Year'] ?>&mID=<?php echo$_POST['txt_Month'] ?>"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&map=personnes"></a></td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['personne_nom'] . " " . ucfirst(strtolower($row_rsSessions['personne_prenom'])); ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions['fonction_lib']; ?>&nbsp; </td>
      <?php $counter=0; do  { $counter++; ?>
      <td nowrap="nowrap">
        <?php 
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}
			?>
      </td>
      <?php }while ($counter < $row_rsJourMois['nbre_jour']); ?>
      <td align="right" nowrap="nowrap"><?php echo $row_rsSessions['nombre_jour']; ?></td>
      <td nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><strong><?php echo number_format($row_rsSessions['total'],0,' ',' '); ?></strong></td>
    </tr>
    <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
	<br />
    <?php echo $totalRows_rsSessions ?> Enregistrements Total 
    <h1>Sous COmmission Dépendante</h1>
<table>
	<tr>
    	<td>
		<?php $link = "print_sessions.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID']. "&menuID=" . $_GET['menuID'] ?>
      	<?php $link4 = "upd_sessions.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=" . $_GET['menuID']?>
    	<a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Version imprimable</a> <a href="<?php echo $link2 ?>"><img src="images/img/b_edit.png" width="16" height="16" /> Mettre à jour</a>&nbsp;<a href="<?php echo $link4 ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une sous commission</a>
    	</td>
	</tr>
</table>

<?php if ($totalRows_rsSessions_sCom > 0) { // Show if recordset not empty ?>

  <table border="1" align="center" class="std">
    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Prenom</th>
      <th>Fonction</th>
      <?php $counter=0; do  { $counter++; ?>
      <th><?php echo $counter ?></th>
      <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
      <th>Total</th>
      <th>Taux</th>
      <th>Montant</th>
    </tr>
    <?php $counter=0; do  { $counter++; ?>
    <tr>
      <td nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions_sCom['membres_personnes_personne_id']; ?>&map=personnes"> <?php echo $row_rsSessions_sCom['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td nowrap="nowrap"><?php echo $row_rsSessions_sCom['personne_nom']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions_sCom['personne_prenom']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions_sCom['fonction_lib']; ?>&nbsp; </td>
      <?php $counter=0; do  { $counter++; ?>
      <td nowrap="nowrap"><?php 
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions_sCom['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}
			?></td>
      <?php }while ($counter < $row_rsJourMois['nbre_jour']); ?>
      <td align="right" nowrap="nowrap"><?php echo $row_rsSessions_sCom['nombre_jour']; ?></td>
      <td nowrap="nowrap"><?php echo number_format($row_rsSessions_sCom['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><strong><?php echo number_format($row_rsSessions_sCom['total'],0,' ',' '); ?></strong></td>
    </tr>
    <?php } while ($row_rsSessions_sCom = mysql_fetch_assoc($rsSessions_sCom)); ?>
  </table><br />
	<?php echo $totalRows_rsSessions_sCom ?> Enregistrements Total 
  <?php } // Show if recordset not empty ?>

<h1>Representants Maitres d'Ouvrages à la commission</h1>
<table>
	<tr>
    	<td>
		<?php $link = "print_sessions.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID']. "&menuID=" . $_GET['menuID'] ?>
      	<?php $link6 = "new_representant.php?aID=" . $_POST['txt_Year']. "&mID=" . $_POST['txt_Month'] . "&comID=" . $_GET['comID'] . "&menuID=6" ?>
    	<a href="#" onclick="<?php popup($link, "1200", "700") ?>"><img src="images/img/b_print.png" width="16" height="16" />Version imprimable</a>&nbsp; <a href="<?php echo $link2 ?>"><img src="images/img/b_edit.png" width="16" height="16" /> Mettre à jour</a><a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;&amp;mapid=personne_id&amp;action=add_com&amp;page=rep"></a>&nbsp;<a href="<?php echo $link6 ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter un représentant</a></td>
	</tr>
</table>
<?php if ($totalRows_rsSessions_rep > 0) { // Show if recordset not empty ?>
<table border="1" align="center" class="std">
    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Prenom</th>
      <th>Fonction</th>
      <?php $counter=0; do  { $counter++; ?>
      <th><?php echo $counter ?></th>
      <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
      <th>Total</th>
      <th>Taux</th>
      <th>Montant</th>
    </tr>
    <?php $counter=0; do  { $counter++; ?>
    <tr>
      <td nowrap="nowrap"><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions_rep['membres_personnes_personne_id']; ?>&map=personnes"> <?php echo $row_rsSessions_rep['membres_personnes_personne_id']; ?>&nbsp; </a></td>
      <td nowrap="nowrap"><?php echo $row_rsSessions_rep['personne_nom']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions_rep['personne_prenom']; ?>&nbsp; </td>
      <td nowrap="nowrap"><?php echo $row_rsSessions_rep['fonction_lib']; ?>&nbsp; </td>
      <?php $counter=0; do  { $counter++; ?>
      <td nowrap="nowrap">
        <?php 
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions_rep['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}
			?>
      </td>
      <?php }while ($counter < $row_rsJourMois['nbre_jour']); ?>
      <td align="right" nowrap="nowrap"><?php echo $row_rsSessions_rep['nombre_jour']; ?></td>
      <td nowrap="nowrap"><?php echo number_format($row_rsSessions_rep['montant'],0,' ',' '); ?></td>
      <td align="right" nowrap="nowrap"><strong><?php echo number_format($row_rsSessions_rep['total'],0,' ',' '); ?></strong></td>
    </tr>
    <?php } while ($row_rsSessions_rep = mysql_fetch_assoc($rsSessions_rep)); ?>
  </table>
  <?php } // Show if recordset not empty ?>
</body>
</html>
<?php
mysql_free_result($rsCommissions);

mysql_free_result($rsMembres);

mysql_free_result($rsJourMois);

mysql_free_result($rsMois);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);

mysql_free_result($rsSelYear);

mysql_free_result($rsSessions);

mysql_free_result($rsSessions_sCom);

mysql_free_result($rsSessions_rep);
?>