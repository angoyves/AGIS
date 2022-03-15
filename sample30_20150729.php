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

$colname_rsPersonnes = "-1";
if (isset($_POST['txt_search'])) {
  $colname_rsPersonnes = $_POST['txt_search'];
}
$colname2_rsPersonnes = "-1";
if (isset($_POST['txt_search'])) {
  $colname2_rsPersonnes = $_POST['txt_search'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsPersonnes = sprintf("SELECT * FROM personnes WHERE personne_nom LIKE %s OR personne_prenom LIKE %s AND display = 1 ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname_rsPersonnes . "%", "text"),GetSQLValueString("%" . $colname2_rsPersonnes . "%", "text"));
$rsPersonnes = mysql_query($query_rsPersonnes, $MyFileConnect) or die(mysql_error());
$row_rsPersonnes = mysql_fetch_assoc($rsPersonnes);
$totalRows_rsPersonnes = mysql_num_rows($rsPersonnes);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
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
          <a href="sample33.php?comID=<?php echo $_GET['comID']; ?>&pID=<?php echo $row_rsPersonnes['personne_id']; ?>&uID=<?php echo $_GET['uID'] ?>&map=personnes&amp;mapid=personne_id&amp;action=add_com&page=addMbr3&fID=<?php echo $_GET['comID']; ?>&pID=<?php echo $_GET['pID=']; ?>&aID=<?php echo $_GET['aID']; ?>&mID=<?php echo $_GET['mID']; ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
          <?php } else {?>
          <a href="sample33.php?recordID=<?php echo $row_rsPersonnes['personne_id']; ?>&amp;map=personnes&amp;mapid=personne_id&amp;action=add_com&page=addMbr3&fID=<?php echo $_GET['comID']; ?>&pID=<?php echo $_GET['pID=']; ?>&aID=<?php echo $_GET['aID']; ?>&mID=<?php echo $_GET['mID']; ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
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
        <td>Aucun resultat trouvé pour la recherche sur :<strong><?php echo $_POST['txt_search'] ?></strong></td>
      </tr>
      <tr>
        <td>
        <?php           
			$showGoTo = "sample21.php?txt=".$_POST['txt_search'];
			if (isset($_SERVER['QUERY_STRING'])) {
				$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
				$showGoTo .= $_SERVER['QUERY_STRING'];
			}
	 	  ?>
          <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">Enregistrer  une personne au fichier...<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>
          </td>
      </tr>
    </table></td>
  </tr>
<?php } ?>
</table>
</body>
</html>
<?php
mysql_free_result($rsPersonnes);
?>
