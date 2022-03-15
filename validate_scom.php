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
	
  if (!isset($_SESSION)) {
	  session_start();
	} 
	
  if (isset($_GET['mID']))
  //$mois = GetSQLValueString($_GET['mID'], "text");
  $mois = $_GET['mID'];
  
  if (isset($_GET['aID']))
  //$annee = GetSQLValueString($_GET['aID'], "text");
  $annee = $_GET['aID'];
  
  if (isset($_GET['comID']))
  $commission_id = GetSQLValueString($_GET['comID'], "int");
  //$commission_id = $_GET['comID'];
  
  if (isset($_SESSION['MM_UserID']))
  $UserId = GetSQLValueString($_SESSION['MM_UserID'], "int");
  
  if (isset($_GET['value']))
  //$value = GetSQLValueString($_GET['value'], "text");
  $value = $_GET['value'];
  
  if (isset($_GET['champ']))
  //$value = GetSQLValueString($_GET['value'], "text");
  $champ = $_GET['champ'];
  
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
     MinmapDB::getInstance()->validate_sessions_all($value, $date, $mois, $annee, $row_rsCommission['commission_id'], $UserId, $champ);
   } while ($row_rsCommission = mysql_fetch_assoc($rsCommission));

   echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
?>
