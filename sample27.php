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

	if (isset($_POST['fonction_id1']) && $_POST['fonction_id1']==""){	
		$fonctionIsEmpty = true;		
	}
	if (isset($_POST['fonction_id2']) && $_POST['fonction_id2']==""){	
		$fonctionIsEmpty = true;		
	}
	if (isset($_POST['fonction_id3']) && $_POST['fonction_id3']==""){	
		$fonctionIsEmpty = true;		
	}
	if (isset($_POST['fonction_id4']) && $_POST['fonction_id4']==""){	
		$fonctionIsEmpty = true;		
	}
	if (isset($_POST['fonction_id5']) && $_POST['fonction_id5']==""){	
		$fonctionIsEmpty = true;	
	}
	if (isset($_POST['fonction_id6']) && $_POST['fonction_id6']==""){	
		$fonctionIsEmpty = true;	
	}
	if (isset($_POST['fonction_id7']) && $_POST['fonction_id7']==""){	
		$fonctionIsEmpty = true;	
	}
	if ($_POST['commission_id']==""){	
		$commissionIsEmpty = true;
	}
	if ($_POST['numberInsert'] < 4){	
		$numberInsertIsLow = true;
	}

$typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($_POST['commission_id']);
	
 if (!function_exists("GetMontantValue")) {
function GetMontantValue($theCommission_type_id)  
{

  switch ($theCommission_type_id) {
    case 1:
		  $montant = 100000;  
      break;    
    case 3:
		  $montant = 75000;  
      break;   
    case 4:
		  $montant = 75000;  
      break;
    default:
		  $montant = 75000;  
      break;
  }
  return $montant;
}
}


$maxRows_rsPersonnes = 100;
$pageNum_rsPersonnes = 0;
if (isset($_GET['pageNum_rsPersonnes'])) {
  $pageNum_rsPersonnes = $_GET['pageNum_rsPersonnes'];
}
$startRow_rsPersonnes = $pageNum_rsPersonnes * $maxRows_rsPersonnes;

$colname_rsCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommission = $_GET['comID'];
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$colname_rsPersonnes = "-1";
if (isset($_POST['txt_search'])) {
$colname_rsPersonnes = $_POST['txt_search'];

$query_rsPersonnes = sprintf("SELECT personnes.personne_id, numero_compte, cle, personnes.personne_matricule, personne_nom, personne_prenom, add_commission, display
							 FROM personnes, rib WHERE personnes.personne_id = rib.personne_id AND personne_nom LIKE %s AND display = '1' AND add_commission = '0' ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname_rsPersonnes . "%", "text"));
} else {
$query_rsPersonnes = sprintf("SELECT personnes.personne_id, numero_compte, cle, personnes.personne_matricule, personne_nom, personne_prenom, add_commission, personnes.display 
FROM personnes, membres, rib 
WHERE personnes.personne_id = personnes_personne_id
AND personnes.personne_id = rib.personne_id
AND personnes.display = '1'
AND commissions_commission_id = %s
AND membres.display = '0'
ORDER BY membres.dateUpdate, personne_nom ASC", GetSQLValueString($colname_rsCommission, "int"));	
	}
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

$colname_rsCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = sprintf("SELECT commission_id, natures.lib_nature, type_commission_lib, localite_lib, commission_lib FROM natures, type_commissions, localites, commissions WHERE commissions.nature_id = natures.nature_id AND commissions.type_commission_id = type_commissions.type_commission_id AND commissions.localite_id = localites.localite_id AND commissions.commission_id = %s", GetSQLValueString($colname_rsCommission, "int"));
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
<h1>Ajouter des representants de Maitres d'Ouvrage à une commission</h1>
<table width="60%" border="1" align="center" class="std">
  <tr>
    <td colspan="4"><form id="form1" name="form1" method="post" action="">
      Rechercher :
      <input type="text" name="txt_search" id="txt_search" />
      <input type="submit" name="button" id="button" value="Rechercher" />
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
    <th nowrap="nowrap">Ajouter</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
  <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
    <td nowrap="nowrap"><a href="#"> <img src="images/img/b_views.png" width="16" height="16" /></a></td>
    <td nowrap="nowrap">
	<?php echo $row_rsPersonnes['numero_compte']."-".$row_rsPersonnes['cle']; ?>&nbsp; </td>
    <td nowrap="nowrap"><?php if (isset($_POST['txt_search']) && $_POST['txt_search'] != "") {echo str_ireplace($_POST['txt_search'],'<span style="background-color:yellow">'. strtolower($_POST['txt_search']) .'</span>',ucfirst(strtolower(htmlentities($row_rsPersonnes['personne_nom'] . " " . $row_rsPersonnes['personne_prenom']))));
          } else { echo ucfirst(strtolower(htmlentities($row_rsPersonnes['personne_nom'] . " " . $row_rsPersonnes['personne_prenom'])));} ?>&nbsp; </td>
    <td align="center" nowrap="nowrap"><?php if (isset($row_rsPersonnes['add_commission'])) { ?>
      <!--<a href="activ_rep.php?comID=<?php //echo $_GET['comID']; ?>&perID=<?php //echo $row_rsPersonnes['personne_id']; ?>&action=active"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>-->
      <a href="redirection2.php?comID=<?php echo $_GET['comID']; ?>&perID=<?php echo $row_rsPersonnes['personne_id']; ?>&lID=<?php echo $_GET['lID']; ?>&action=active"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php }  ?>
      &nbsp; </td>
  </tr>
  <?php } while ($row_rsPersonnes = mysql_fetch_assoc($rsPersonnes)); ?>
  <?php } else {// Show if recordset not empty ?>
  <tr>
    <td colspan="4"><table width="100%" border="1" align="left">
      <tr>
        <td>Aucun resultat trouvé pour la recherche sur :<strong><?php echo $_POST['txt_search'] ?></strong></td>
      </tr>
      <tr>
      <?php } ?>
      <?php if(isset($_POST['button'])) {?>
        <td colspan="4"><a href="sample14.php?txtSearch=<?php echo $_POST['txt_search']?>&menuID=2&amp;action=new&amp;page=new_representant&amp;comID=<?php echo $_GET['comID']; ?>&amp;menuID=<?php echo $_GET['menuID']; ?>&lID=<?php echo $_GET['lID']; ?>"></BR>Ajouter une nouvelle personne<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
      </tr>
    </table></td>
  </tr>
  <?php } ?>
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