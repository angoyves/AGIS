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

$colname_rsCommission = "-1";
if (isset($_GET['comID'])) {
  $colname_rsCommission = $_GET['comID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsCommission = sprintf("SELECT commission_id FROM commissions WHERE commission_parent = %s", GetSQLValueString($colname_rsCommission, "int"));
$rsCommission = mysql_query($query_rsCommission, $MyFileConnect) or die(mysql_error());
$row_rsCommission = mysql_fetch_assoc($rsCommission);
$totalRows_rsCommission = mysql_num_rows($rsCommission);

do { 
     Minmap::getInstance()->validate_sessions($value, $duedate, $mois, $annee, $row_rsCommission['commission_id'], $UserId, $champ);
   } while ($row_rsCommission = mysql_fetch_assoc($rsCommission));

mysql_free_result($rsCommission);
?>
