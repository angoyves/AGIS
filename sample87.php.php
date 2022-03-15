
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE sessions SET etat_appur=%s, dateUpdate=%s, user_id=%s 
					   WHERE mois = %s
					   
					   AND membres_commissions_commission_id=%s
					   AND membres_personnes_personne_id=%s",
                       
                       GetSQLValueString($_POST['etat_appur'], "text"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['user_id'], "int"),
					   GetSQLValueString($_POST['mois'], "text"),
					   GetSQLValueString($_POST['anne'], "text"),
                       GetSQLValueString($_POST['commission'], "int"),
					   GetSQLValueString($_POST['personne'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "sample84.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

?>
