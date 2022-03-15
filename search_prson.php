<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php
	  
		$commissionIsEmpty = false;
		$personneIsUnique = true;
		$personneIsEmpty1 = false;
		$fonctionIsEmpty2 = false;
		$fonctionIsEmpty3 = false;
		$fonctionIsEmpty4 = false;
		$fonctionIsEmpty5 = false;
		$fonctionIsEmpty6 = false;
		$fonctionIsEmpty7 = false;	
		$fonctionIsEmpty = false;
		$sessionIsUnique = true;
		$date = date('Y-m-d H:i:s');
	  ?>
<?php

$maxRows_rsPersonnes = 100;
$pageNum_rsPersonnes = 0;
if (isset($_GET['pageNum_rsPersonnes'])) {
  $pageNum_rsPersonnes = $_GET['pageNum_rsPersonnes'];
}
$startRow_rsPersonnes = $pageNum_rsPersonnes * $maxRows_rsPersonnes;

$txtCom = trim($_REQUEST['comID']);
$txtSearch = strtoupper(trim(substr($_REQUEST['pID'],0,6)));
$txtMod = $_REQUEST['modID'];

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = sprintf("SELECT personnes.personne_id, agence_cle, banque_code, agence_code, numero_compte, cle, personnes.personne_matricule, personne_nom, personne_prenom, add_commission, display FROM personnes, rib WHERE personnes.personne_id = rib.personne_id AND personne_nom LIKE %s AND display = '1' ORDER BY personne_nom ASC", GetSQLValueString("%" . $txtSearch . "%", "text"));
$query_limit_rsPersonnes = sprintf("%s LIMIT %d, %d", $query_rsPersonnes, $startRow_rsPersonnes, $maxRows_rsPersonnes);
$rsPersonnes = mysql_query($query_limit_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);

if (isset($_GET['totalRows_rsPersonnes'])) {
  $totalRows_rsPersonnes = $_GET['totalRows_rsPersonnes'];
} else {
  $all_rsPersonnes = mysql_query($query_rsPersonnes);
  $totalRows_rsPersonnes = mysql_num_rows($all_rsPersonnes);
}
$totalPages_rsPersonnes = ceil($totalRows_rsPersonnes/$maxRows_rsPersonnes)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnesAdd = "SELECT personnes.personne_id, personnes.personne_matricule, personne_nom, personne_prenom, add_commission, display, numero_compte, cle FROM personnes, rib WHERE rib.personne_id = personnes.personne_id AND add_commission = '1' AND display = '1' ORDER BY personne_nom ASC";
$rsPersonnesAdd = mysql_query($query_rsPersonnesAdd, $MyFileConnect) or die(mysql_error());
$row_rsPersonnesAdd = mysql_fetch_assoc($rsPersonnesAdd);
$totalRows_rsPersonnesAdd = mysql_num_rows($rsPersonnesAdd);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelFonction = "SELECT fonction_id, fonction_lib FROM fonctions WHERE groupe_fonction_id = '3' AND display = '1' ORDER BY fonction_id ASC";
$rsSelFonction = mysql_query($query_rsSelFonction, $MyFileConnect) or die(mysql_error());
$row_rsSelFonction = mysql_fetch_assoc($rsSelFonction);
$totalRows_rsSelFonction = mysql_num_rows($rsSelFonction);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = sprintf("SELECT commission_id, natures.lib_nature, type_commission_lib, localite_lib, commission_lib FROM natures, type_commissions, localites, commissions WHERE commissions.nature_id = natures.nature_id AND commissions.type_commission_id = type_commissions.type_commission_id AND commissions.localite_id = localites.localite_id AND commissions.commission_id = %s", GetSQLValueString($txtCom, "int"));
$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
$row_rsCommission = mysql_fetch_assoc($rsCommission);
$totalRows_rsCommission = mysql_num_rows($rsCommission);

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
$query_rsSelStructure = "SELECT structure_id, structure_lib, code_structure FROM structures WHERE display = '1' AND type_structure_id = '4'";
$rsSelStructure = mysql_query($query_rsSelStructure, $MyFileConnect) or die(mysql_error());
$row_rsSelStructure = mysql_fetch_assoc($rsSelStructure);
$totalRows_rsSelStructure = mysql_num_rows($rsSelStructure);

$queryString_rsPersonnes = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsPersonnes") == false && 
        stristr($param, "totalRows_rsPersonnes") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsPersonnes = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsPersonnes = sprintf("&totalRows_rsPersonnes=%d%s", $totalRows_rsPersonnes, $queryString_rsPersonnes); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body onunload="window.opener.location.reload();">
<h1>Recherche de : <?php echo $_REQUEST['pID']; ?></h1>
<table width="60%" border="1" align="center" class="std">
  <tr>
    <td colspan="6"><form id="form1" name="form1" method="post" action="">
      Rechercher :
      <input name="pID" type="text" id="pID" value="<?php echo $txtSearch; ?>" />
      <input type="submit" name="button" id="button" value="Rechercher" />
      <input type="submit" name="close" id="close" value="Fermer" onclick="window.opener.location.reload(); 
		self.close();" />
    </form></td>
  </tr>
  <?php if (!$sessionIsUnique) { ?>
  <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" /> This Session already exists. Please check the spelling and try again</div>
  <?php   } ?>
  <?php if ($totalRows_rsPersonnes > 0) { // Show if recordset not empty ?>
  <tr>
    <th height="25" nowrap="nowrap">ID</th>
    <th nowrap="nowrap">Numero compte</th>
    <th nowrap="nowrap">Nom et Prenom</th>
    <th nowrap="nowrap">Ajouter le RIB OV</th>
    <th nowrap="nowrap">Membre...</th>
    <th nowrap="nowrap">Comission</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
  <?php $etatMembre = MinmapDB::getInstance()->verify_membres_by_person_id($row_rsPersonnes['personne_id'])?>
  <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
    <td nowrap="nowrap"><a href="#"> <?php echo $row_rsPersonnes['personne_id']; ?></a></td>
    <td nowrap="nowrap">
	<?php echo $rib = $row_rsPersonnes['banque_code']."-".$row_rsPersonnes['agence_code']."-".$row_rsPersonnes['numero_compte']."-".$row_rsPersonnes['cle']; ?>&nbsp; </td>
    <td nowrap="nowrap"><?php if (isset($txtSearch) && $txtSearch != "") {echo str_ireplace($txtSearch,'<span style="background-color:yellow">'. strtolower($txtSearch) .'</span>',ucfirst(strtolower(htmlentities($row_rsPersonnes['personne_nom'] . " " . $row_rsPersonnes['personne_prenom']))));
          } else { echo ucfirst(strtolower(htmlentities($row_rsPersonnes['personne_nom'] . " " . $row_rsPersonnes['personne_prenom'])));} ?>&nbsp; </td>
    <td align="center" nowrap="nowrap"><?php if (isset($row_rsPersonnes['add_commission'])) { ?>
      <!--<a href="activ_rep.php?comID=<?php //echo $txtCom; ?>&perID=<?php //echo $row_rsPersonnes['personne_id']; ?>&action=active"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>-->
      <a href="redirect_prso.php?comID=<?php echo $txtCom; ?>&perID=<?php echo $row_rsPersonnes['personne_id']; ?>&action=active&lID=<?php echo $_GET[lID] ?>&pID=<?php echo $_REQUEST['pID'] ?>&rID=<?php echo $_REQUEST['rID'] ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php }  ?>
      &nbsp; </td>
    <td align="center" nowrap="nowrap">
	<?php if ($etatMembre) { ?>
      <img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/>
      <?php } else { ?>
	  <img src="images/img/s_cancel.png" alt="" width="16" height="16" align="absmiddle"/>
    <?php } ?>
    </td>
    <td align="left" nowrap="nowrap">
	<?php 
		$commissionID = MinmapDB::getInstance()->get_commission_id_by_person_id($row_rsPersonnes['personne_id']); 
		echo MinmapDB::getInstance()->get_commission_lib_by_commission_id($commissionID);
		//echo  MinmapDB::getInstance()->get_commission_lib_by_commission_id($commissionID);
	?>&nbsp;</td>
  </tr>
  <?php } while ($row_rsPersonnes = mysql_fetch_assoc($rsPersonnes)); ?>
  <?php } else {// Show if recordset not empty ?>
  <tr>
    <td colspan="6"><table width="100%" border="1" align="left">
      <tr>
        <td>Aucun resultat trouvé pour la recherche sur :<strong><?php echo $txtSearch ?></strong></td>
      </tr>
      <tr>
      <?php } ?>
      <?php if(isset($_POST['button']) && (isset($txtMod) && $txtMod==1)) {?>
        <td colspan="4"><a href="membre_ins.php?txtSearch=<?php echo $txtSearch?>&lID=<?php echo $_GET['lID'] ?>&menuID=2&amp;action=new&amp;page=new_representant&amp;comID=<?php echo $txtCom; ?>&modID=<?php echo $txtMod; ?>&menuID=<?php echo $_GET['menuID']; ?>"></BR>Ajouter une nouvelle personne<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
      <?php } else { ?>
      <td colspan="4"><a href="sample14.php?txtSearch=<?php echo $txtSearch?>&lID=<?php echo $_GET['lID'] ?>&menuID=2&amp;action=new&amp;page=new_representant&amp;comID=<?php echo $txtCom; ?>&modID=<?php echo $txtMod; ?>&menuID=<?php echo $_GET['menuID']; ?>"></BR>Ajouter une nouvelle personne<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
      <?php } ?>
      </tr>
    </table></td>
  </tr>
</table>
<br />
<table border="0">
  <tr>
    <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, 0, $queryString_rsPersonnes); ?>">First</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPersonnes > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, max(0, $pageNum_rsPersonnes - 1), $queryString_rsPersonnes); ?>">Previous</a>
      <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, min($totalPages_rsPersonnes, $pageNum_rsPersonnes + 1), $queryString_rsPersonnes); ?>">Next</a>
      <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_rsPersonnes < $totalPages_rsPersonnes) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rsPersonnes=%d%s", $currentPage, $totalPages_rsPersonnes, $queryString_rsPersonnes); ?>">Last</a>
      <?php } // Show if not last page ?></td>
  </tr>
</table>
</body>
</html>