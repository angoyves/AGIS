<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); 
	  
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
		$numberInsertIsLow = false;	
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

    $sessionID = MinmapDB::getInstance()->get_session_id_by_value($_POST['annee'], $_GET['moisID'], $_GET['comID']);
    if ($sessionID) {
        $sessionIsUnique = false;
    }

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if (!$commissionIsEmpty && !$fonctionIsEmpty && !$numberInsertIsLow && $sessionIsUnique){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
if (isset($_POST['personne_id1']) && isset($_POST['fonction_id1'])) {
 $insertSQL1 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id1'], "int"),
                       GetSQLValueString($_POST['personne_id1'], "int"),
					   GetSQLValueString($_POST['montant_id1'], "text"),
					   GetSQLValueString("un", "text"),
                       GetSQLValueString(1, "int"));  }

 if (isset($_POST['personne_id2']) && isset($_POST['fonction_id2'])) {
 $insertSQL2 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id2'], "int"),
                       GetSQLValueString($_POST['personne_id2'], "int"),
					   GetSQLValueString($_POST['montant_id2'], "text"),
					   GetSQLValueString("deux", "text"),
                       GetSQLValueString(2, "int")); }

if (isset($_POST['personne_id3']) && isset($_POST['fonction_id3']))	{				   
  $insertSQL3 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id3'], "int"),
                       GetSQLValueString($_POST['personne_id3'], "int"),
					   GetSQLValueString($_POST['montant_id3'], "text"),
					   GetSQLValueString("trois", "text"),
                       GetSQLValueString(3, "int")); }

if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{				   
  $insertSQL4 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id4'], "int"),
                       GetSQLValueString($_POST['personne_id4'], "int"),
					   GetSQLValueString($_POST['montant_id4'], "text"),
					   GetSQLValueString("quatre", "text"),
                       GetSQLValueString(4, "int")); }

if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{				   
  $insertSQL5 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id5'], "int"),
                       GetSQLValueString($_POST['personne_id5'], "int"),
					   GetSQLValueString($_POST['montant_id5'], "text"),
					   GetSQLValueString("cinq", "text"),
                       GetSQLValueString(5, "int")); }

if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{			   
 $insertSQL6 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id6'], "int"),
                       GetSQLValueString($_POST['personne_id6'], "int"),
					   GetSQLValueString($_POST['montant_id6'], "text"),
					   GetSQLValueString("six", "text"),
                       GetSQLValueString(6, "int")); }

if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{			   
 $insertSQL7 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, position) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id7'], "int"),
                       GetSQLValueString($_POST['personne_id7'], "int"),
					   GetSQLValueString($_POST['montant_id7'], "text"),
					   GetSQLValueString("sept", "text"),
                       GetSQLValueString(7, "int")); } 
					   
 $updateSQL = sprintf("UPDATE commissions SET membre_insert=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($_POST['commission_id'], "int"));
					   
 $updateSQL1 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id1'], "int"));
					   
 $updateSQL2 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id2'], "int"));
					   
 $updateSQL3 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id3'], "int"));
					   
if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{					   
 $updateSQL4 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id4'], "int"));
}
if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{					   
 $updateSQL5 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id5'], "int"));
}
if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{
 $updateSQL6 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id6'], "int"));
}
if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{
 $updateSQL7 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id7'], "int"));	
}

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_POST['personne_id1']) && isset($_POST['fonction_id1']))	{
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id2']) && isset($_POST['fonction_id2']))	{	  
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id3']) && isset($_POST['fonction_id3']))	{	  
  $Result3 = mysql_query($insertSQL3, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{	  
  $Result4 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{	  
  $Result5 = mysql_query($insertSQL5, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{	  
  $Result6 = mysql_query($insertSQL6, $MyFileConnect) or die(mysql_error()); }
  if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{	
  $Result7 = mysql_query($insertSQL7, $MyFileConnect) or die(mysql_error()); }

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
if (isset($_POST['personne_id1']) && isset($_POST['fonction_id1']))	{	
  $Results1 = mysql_query($updateSQL1, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id2']) && isset($_POST['fonction_id2']))	{	
  $Results2 = mysql_query($updateSQL2, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id3']) && isset($_POST['fonction_id3']))	{	
  $Results3 = mysql_query($updateSQL3, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{	
  $Results4 = mysql_query($updateSQL4, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{	
  $Results5 = mysql_query($updateSQL5, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{	
  $Results6 = mysql_query($updateSQL6, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{	
  $Results7 = mysql_query($updateSQL7, $MyFileConnect) or die(mysql_error()); }
					   


  $insertGoTo = "new_membres.php";
/*  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }*/
  header(sprintf("Location: %s", $insertGoTo));
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsPersonnes = 10;
$pageNum_rsPersonnes = 0;
if (isset($_GET['pageNum_rsPersonnes'])) {
  $pageNum_rsPersonnes = $_GET['pageNum_rsPersonnes'];
}
$startRow_rsPersonnes = $pageNum_rsPersonnes * $maxRows_rsPersonnes;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$colname_rsPersonnes = "-1";
if (isset($_POST['txt_search'])) {
$colname_rsPersonnes = $_POST['txt_search'];
$query_rsPersonnes = sprintf("SELECT personne_id, personne_matricule, personne_nom, personne_prenom, add_commission, display FROM personnes WHERE personne_nom LIKE %s AND display = '1' AND add_commission = '0' ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname_rsPersonnes . "%", "text"));
} else {
$query_rsPersonnes = sprintf("SELECT personne_id, personne_matricule, personne_nom, personne_prenom, add_commission, display FROM personnes WHERE display = '1' AND add_commission = '0' ORDER BY personne_nom ASC");	
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
</head>

<body>
<h1>Creation d'une commission</h1>
<table width="60%" border="1" align="center" class="std">
  <tr>
    <td colspan="5">
      <form id="form1" name="form1" method="post" action="">
      Rechercher :<input type="text" name="txt_search" id="txt_search" />
      <input type="submit" name="button" id="button" value="Submit" />
    </form></td>
  </tr>
  <?php if (!$sessionIsUnique) { ?>
        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />
        This Session already exists. Please check the spelling and try again</div>
  <?php   } ?>
  <?php if ($totalRows_rsPersonnes > 0) { // Show if recordset not empty ?>
  <tr>
    <th>ID</th>
    <th>Matricule</th>
    <th>Nom</th>
    <th>personne_prenom</th>
    <th>Add</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="#"> <img src="images/img/b_views.png" width="16" height="16" /></a></td>
      <td><?php echo $row_rsPersonnes['personne_matricule']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['personne_nom']; ?>&nbsp; </td>
      <td><?php echo $row_rsPersonnes['personne_prenom']; ?>&nbsp; </td>
      <td align="center"><?php if (isset($row_rsPersonnes['add_commission'])) { ?>
          <?php if (isset ($_GET['comID'])) { ?>
          <a href="change_etat_index.php?comID=<?php echo $_GET['comID']; ?>&&recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&&map=personnes&amp;&amp;mapid=personne_id&amp;&amp;action=add_com"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
          <?php } else {?>
          <a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;&amp;map=personnes&amp;&amp;mapid=personne_id&amp;&amp;action=add_com"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
          <?php } ?>
        <?php }  ?>
  &nbsp;
    </td>
  
  </tr>
<?php } while ($row_rsPersonnes = mysql_fetch_assoc($rsPersonnes)); ?>
<?php } else {// Show if recordset not empty ?>
  <tr>
    <td colspan="5"><table width="100%" border="1" align="left">
      <tr>
        <td>Aucun resultat trouv?? pour la recherche sur :<strong><?php echo $_POST['txt_search'] ?></strong></td>
      </tr>
      <tr>
        <td>Ajouter une personne
          <a href="new_personnes.php?menuID=2&amp;action=new"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
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
<p>Records <?php echo ($startRow_rsPersonnes + 1) ?> to <?php echo min($startRow_rsPersonnes + $maxRows_rsPersonnes, $totalRows_rsPersonnes) ?> of <?php echo $totalRows_rsPersonnes ?>
</p>
<table width="60%" border="1" align="center" class="std">
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <tr>
    <td colspan="9">Ajouter une commission
      <?php           
	$showGoToPersonne = "search_commissions.php";
/*  if (isset($_SERVER['QUERY_STRING'])) {
    $showGoToPersonne .= (strpos($showGoToPersonne, '?')) ? "&" : "?";
    $showGoToPersonne .= $_SERVER['QUERY_STRING'];
  }*/
?>
      <a href="#" onclick="<?php popup($showGoToPersonne, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a><br/>
      <strong>
      <?php if (isset($_GET['comID'])) { ?>
        <?php echo $row_rsCommission['type_commission_lib']; ?> de <?php echo $row_rsCommission['lib_nature']; ?> du (de)<?php echo $row_rsCommission['localite_lib']; ?>
        <?php } ?>
      </strong>
<input name="commission_id" type="hidden" id="commission_id" value="<?php echo $row_rsCommission['commission_id']; ?>" />
<?php
             /** Display error messages if the "commission" field is empty */
            if ($commissionIsEmpty || !isset($_GET['comID'])) { ?>
<div class="control"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />Choose commission, please</div>
<?php }
            ?>
&nbsp;
<?php
             /** Display error messages if the "password" field is empty */
            if ($fonctionIsEmpty) { ?>
<div class="control"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />FONCTION (S) MANQUANTE (S)...Selectionnez une fonction pour tous les Personnes, SVP!</div>
<?php }
            ?>
<?php
             /** Display error messages if the "password" field is empty */
            if ($numberInsertIsLow) { ?>
<div class="control"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />Le nombre de membre pour la cr??ation d'une commission doit etre sup??rieur ou ??gale ?? 4...Ajoutez des membres SVP!</div>
<?php }
            ?></td>
    </tr>
  <tr>
    <th>N??</th>
    <th>RIB</th>
    <th>Nom</th>
    <th>Prenom</th>
    <th>Fonction</th>
    <th>Montant</th>
    <th><input type="submit" name="button2" id="button2" value="Submit" /></th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
  <?php if ($totalRows_rsPersonnesAdd > 0) { // Show if recordset not empty ?>
  <tr align="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
    <td><a href="to_delete2.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>"> <?php echo $counter; ?>
      <input type="hidden" name="<?php echo "personne_id" . $counter ?>" id="<?php echo "personne_id" . $counter ?>" value="<?php echo $row_rsPersonnesAdd['personne_id']; ?>"/>
    </a></td>
    <td><?php echo $row_rsPersonnesAdd['numero_compte'] . "-" . $row_rsPersonnesAdd['cle']; ?>&nbsp; </td>
    <td><?php echo $row_rsPersonnesAdd['personne_nom']; ?>&nbsp; </td>
    <td><?php echo $row_rsPersonnesAdd['personne_prenom']; ?>&nbsp; </td>
    <td> 
      <select name="<?php echo "fonction_id" . $counter ?>" id="<?php echo "fonction_id" . $counter ?>">
        <option value="">:: Select</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsSelFonction['fonction_id']?>" <?php if (!(strcmp($row_rsSelFonction['fonction_id'], htmlentities($_POST["fonction_id" . $counter], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsSelFonction['fonction_lib']); ?></option>
        <?php
} while ($row_rsSelFonction = mysql_fetch_assoc($rsSelFonction));
  $rows = mysql_num_rows($rsSelFonction);
  if($rows > 0) {
      mysql_data_seek($rsSelFonction, 0);
	  $row_rsSelFonction = mysql_fetch_assoc($rsSelFonction);
  }
?>
      </select>      &nbsp;</td>
    <td align="right"><input type="text" name="<?php echo "montant_id" . $counter ?>" id="<?php echo "personne_id" . $counter ?>" value="100000" align="right"/>
    &nbsp;</td>
    <td>
      <?php if (isset ($_GET['comID'])) { ?> 
      <a href="change_etat_index.php?comID=<?php echo $_GET['comID'];?>&&recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>&amp;&amp;map=personnes&amp;&amp;mapid=personne_id&amp;&amp;action=move_com"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle" /></a>
      <?php } else { ?>
      <a href="change_etat_index.php?recordID=<?php echo $row_rsPersonnesAdd['personne_id']; ?>&amp;&amp;map=personnes&amp;&amp;mapid=personne_id&amp;&amp;action=move_com"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle" /></a>   
      <?php } ?>
  </tr>
  <?php } // Show if recordset not empty ?>
<?php } while ($row_rsPersonnesAdd = mysql_fetch_assoc($rsPersonnesAdd)); ?>
  <input type="hidden" name="numberInsert" value="<?php echo $totalRows_rsPersonnesAdd ?>" />
  <input type="hidden" name="membre_insert" value="1" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
</table>
<br />
<?php echo $totalRows_rsPersonnesAdd ?> Records Total
</body>
</html>
<?php
mysql_free_result($rsPersonnes);

mysql_free_result($rsPersonnesAdd);

mysql_free_result($rsSelFonction);

mysql_free_result($rsCommission);

mysql_free_result($rsMenu);

mysql_free_result($rsSousMenu);
?>