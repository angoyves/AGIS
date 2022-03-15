<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$sql = 'SELECT COUNT(dossier_id) AS nb_titres FROM dossiers GROUP BY commission_id ORDER BY dossier_jour DESC';
$query = mysql_query($sql);
  
$sql2 = 'SELECT dossier_id, dossier_ref FROM dossiers ORDER BY dossier_jour DESC';
$query2 = mysql_query($sql2);
    
echo '<table>';
    
$j = false;
$y = '';
    
while ($resultat = mysql_fetch_assoc($query2))
{
    $dossier_ref = $resultat['dossier_ref'];
    $dossier_id = $resultat['dossier_id'];
 
    if($j == true)
        $coloration = ' class = "coloration"';
    else
        $coloration = '';
  
    if($y != $annee)
    {
        $data = mysql_fetch_assoc($query);
        $nb_titres = $data['nb_titres'];
   
        echo '<tr'.$coloration.'><td rowspan = "'.$nb_titres.'">'.$dossier_ref.'</td>';
    }
    else
    {
        $j = !$j;
         
        if($j == true)
            $coloration2 = ' class = "coloration"';
        else
            $coloration2 = '';
             
       echo '<tr'.$coloration2.'>';
    }
  
       echo '<td><a href = "dossier/index.php?titre='.$id.'">'.$titre.'</td></tr>';
    
       $j = !$j;
       $y = $annee;
}
    
echo '</table>';
?>