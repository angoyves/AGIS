<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="1" align="center" class="std">
  <tr>
    <?php if (isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup']==1 || $_SESSION['MM_UserGroup']==5 || $_SESSION['MM_UserGroup']==6) && $etat_validate!=2) { ?>
    <th nowrap="nowrap">U</th>
    <?php } else { ?>
    <th nowrap="nowrap">N&deg;</th>
    <?php } ?>
    <?php /*$user_group = MinmapDB::getInstance()->get_user_groupe_by_name($_SESSION['MM_Username']);*/
	if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == 1){ ?>
    <th nowrap="nowrap">&nbsp;</th>
    <?php } ?>
    <th nowrap="nowrap">Nom et Prenom</th>
    <th nowrap="nowrap">Fonction</th>
    <?php $counter=0; do  { $counter++; ?>
    <th nowrap="nowrap"><?php echo $counter ?>&nbsp;</th>
    <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
    <th nowrap="nowrap">Total</th>
    <th nowrap="nowrap">Taux</th>
    <th nowrap="nowrap">Montant</th>
  </tr>
  <tr>
    <?php /*$user_group = MinmapDB::getInstance()->get_user_groupe_by_name($_SESSION['MM_Username']);*/
	if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == 1){ ?>
    <td colspan="4" align="center"><strong>Nombre de dossiers trait&eacute;s</strong></td>
    <?php } else { ?>
    <td colspan="3" align="center"><strong>Nombre de dossiers trait&eacute;s</strong></td>
    <?php } ?>
    <?php  
	  $count = count(explode("**", $row_rsDossiers_traites['nombre_dossier'])); 
	  $liste_nbre = explode("**", $row_rsDossiers_traites['nombre_dossier']);
	  $counter=0; $compt=0; $som_value = 0; do  { $counter++; ?>
    <td><strong>
      <?php 
					//$i =0; do { 
					$countDoc = 0; 
					foreach(explode("**", $row_rsDossiers_traites['jour']) as $val){
						$countDoc++; 
						 $val == $counter ? (print $liste_nbre[$compt]) : ""; 
						 $val == $counter ? ($som_value = $som_value + $liste_nbre[$compt]) : "";
						 
						 //$i++;
					 }
					//}while ($i<$count)
			?>
      &nbsp;</strong></td>
    <?php $compt++; }while ($counter < $row_rsJourMois['nbre_jour']);?>
    <td align="right"><strong><?php echo $som_value //$row_rsSessions['nombre_dossier']; ?></strong></td>
    <td><?php echo array_sum($row_rsDossiers_traites['nombre_dossier']) ?>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php $counter=0; $compter=0; $somme1 = 0; $somme2 = 0; do  { $counter++; $compter++; ?>
  <tr>
    <td nowrap="nowrap"><?php if (isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup']==1 || $_SESSION['MM_UserGroup']==5 || $_SESSION['MM_UserGroup']==6) && $etat_validate!=2) { ?>
      <a href="<?php echo $link20 . "&persID=" . $row_rsSessions['personne_id']."&valid=mb" ?>"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php } else {?>
      <?php echo $compter ?>
      <?php } ?>
      <?php           
	$showGoToPersonnes1 = "upd_rib.php?recordID=". $row_rsSessions['personne_id'];
	?></td>
    <?php $user_group = MinmapDB::getInstance()->get_user_groupe_by_name($_SESSION['MM_Username']);
		if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == 1 && $etat_validate!=2){ ?>
    <td nowrap="nowrap"><a href="<?php echo $link5 . $row_rsSessions['personne_id']; ?>" onclick="return confirm('Etes vous sur de vouloir supprimer 																																																				cette sessions ?');"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a><a href="maj_personnes.php?recordID=<?php echo $row_rsSessions['membres_personnes_personne_id']; ?>&amp;map=personnes"></a></td>
    <?php } ?>
    <td><a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>"><?php echo strtoupper($row_rsSessions['personne_nom'] . " " . ucfirst(strtolower($row_rsSessions['personne_prenom']))); ?>
      <?php //echo $user_id = (MinmapDB::getInstance()->get_user_id_by_name($_SESSION['MM_Username']));
		  $user_rib = (MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessions['personne_id']));
		  if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){ ?>
      <img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/>
      <?php } ?>
    </a>&nbsp; </td>
    <td nowrap="nowrap"><?php echo strtoupper($row_rsSessions['fonction_lib']); ?>&nbsp; </td>
    <?php $counter=0; do  { $counter++; ?>
    <td nowrap="nowrap"><a href="#">
      <?php 
					$countDoc = 0;
					foreach(explode("**", $row_rsSessions['jour']) as $val){
						$countDoc++; 
						$val == $counter ? (print "1") : $countDoc--; 
						
					}
			?>
    </a></td>
    <?php }while ($counter < $row_rsJourMois['nbre_jour']); ?>
    <td align="right" nowrap="nowrap"><?php echo $row_rsSessions['nombre_jour']; ?></td>
    <td align="right" nowrap="nowrap"><?php echo number_format($row_rsSessions['montant'],0,' ',' '); ?></td>
    <td align="right" nowrap="nowrap"><strong><?php echo number_format($row_rsSessions['total'],0,' ',' '); ?>
      <?php $somme2 = $somme1; $somme1 = $somme1 + $row_rsSessions['total'];  ?>
      F CFA </strong></td>
  </tr>
  <?php } while ($row_rsSessions = mysql_fetch_assoc($rsSessions)); ?>
  <tr>
    <?php /*$user_group = MinmapDB::getInstance()->get_user_groupe_by_name($_SESSION['MM_Username']);*/
	if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == 1){ ?>
    <td height="29" colspan="<?php echo $row_rsJourMois['nbre_jour']+5 ?>">&nbsp;</td>
    <?php } else { ?>
    <td height="29" colspan="<?php echo $row_rsJourMois['nbre_jour']+4 ?>">&nbsp;</td>
    <?php } ?>
    <?php $counter=0; do  { $counter++; ?>
    <?php }while ($counter < $row_rsJourMois['nbre_jour']);?>
    <th align="center" nowrap="nowrap"><strong>Sous total</strong></th>
    <th nowrap="nowrap"><strong>
      <?php $somme1 = $somme1 - $row_rsCountIndemnity['total']; echo number_format($somme2,0,' ',' '); ?>
      F CFA</strong></th>
  </tr>
</table>
<p>&nbsp;</p>
<table border="1" align="center" class="std">
  <tr>
    <th>ID</th>
    <?php if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == 1 && $etat_validate!=2){ ?>
    <th>&nbsp;</th>
    <?php } ?>
    <th>Nom </th>
    <th>Structure</th>
    <?php $count=0; do { $count++ ?>
    <th><?php echo $count ?>&nbsp;</th>
    <?php } while($count<$row_rsJourMois['nbre_jour']) ?>
    <th>Total</th>
    <th>Taux</th>
    <th>Montant</th>
  </tr>
  <?php $compter=0; $sommes=0; do { $compter++ ?>
  <?php           
	$showGoToPersonnes3 = "upd_rib.php?recordID=". $row_rsSessionRepresentant['personne_id'];
	?>
  <tr>
    <td><?php if (isset($_SESSION['MM_UserGroup']) && ($_SESSION['MM_UserGroup']==1 || $_SESSION['MM_UserGroup']==5 || $_SESSION['MM_UserGroup']==6) && $etat_validate!=2) { ?>
      <a href="<?php echo $link20 . "&persID=" . $row_rsSessionRepresentant['personne_id'] . "&valid=mo" ?>"><img src="images/img/b_edit.png" alt="" width="16" height="16" align="absmiddle"/></a>
      <?php } else {?>
      <?php echo $compter ?>
      <?php } ?></td>
    <?php if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == 1 && $etat_validate!=2){ ?>
    <td><a href="<?php echo $link5 . $row_rsSessionRepresentant['personne_id']; ?>" onclick="return confirm('Etes vous sur de vouloir supprimer 																																																				cette sessions ?');"><img src="images/img/b_drop.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    <?php } ?>
    <td><a href="#" onclick="<?php popup($showGoToPersonnes3, "700", "400"); ?>"><?php echo htmlentities(strtoupper($row_rsSessionRepresentant['personne_nom'])); ?></a>
      <?php //echo $user_id = (MinmapDB::getInstance()->get_user_id_by_name($_SESSION['MM_Username']));
		  $user_rib = (MinmapDB::getInstance()->get_user_rib_by_id($row_rsSessionRepresentant['personne_id']));
		  if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){ ?>
      <img src="images/img/s_okay.png" alt="" width="16" height="16" align="absmiddle"/>
      <?php } ?>
      &nbsp; </td>
    <td><a href="#" onclick="<?php popup($GoToChangeStructure, "700", "400"); ?>"><?php echo strtoupper($row_rsSessionRepresentant['code_structure']); ?>&nbsp;</a></td>
    <?php $count=0; $sommer=0; do { $count++ ?>
    <td><?php 
					$countDoc = 0;
					foreach(explode("**", $row_rsSessionRepresentant['jour']) as $val){ $countDoc++; $val == $count ? (print "1") : $countDoc--; }
			?>
      &nbsp;</td>
    <?php } while($count<$row_rsJourMois['nbre_jour']) ?>
    <td><?php echo $row_rsSessionRepresentant['nombre_jour']; ?>&nbsp; </td>
    <td align="right"><?php echo number_format(($row_rsSessionRepresentant['montant']),0,' ',' '); ?>&nbsp; </td>
    <td align="right"><?php $sommes = $sommes + $row_rsSessionRepresentant['total']; echo number_format($row_rsSessionRepresentant['total'],0,' ',' '); ?>
      F CFA
      &nbsp; </td>
  </tr>
  <?php } while ($row_rsSessionRepresentant = mysql_fetch_assoc($rsSessionRepresentant)); ?>
  <tr>
    <td colspan="<?php echo $row_rsJourMois['nbre_jour']+4 ?>" nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <?php $count=0; do  { $count++; ?>
    <?php }while ($count < 12); ?>
    <th align="right" nowrap="nowrap"><strong>Sous Total</strong></th>
    <th align="right" nowrap="nowrap"><strong>
      <?php  echo number_format($sommes,0,' ',' '); ?>
      F CFA </strong></th>
  </tr>
</table>
</body>
</html>