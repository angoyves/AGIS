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

if (!isset($_SESSION)) {
  session_start();
} 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$typeCommission_id = MinmapDB::getInstance()->get_type_id_by_commission_id($_GET['comID']);
$verifyMembre = (MinmapDB::getInstance()->verify_commissions_representant($_GET['comID'], $_GET['perID']));
$date = date('Y-m-d H:i:s');

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  if ($verifyMembre){
  MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 0, $date);
  MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 2, $date);
  MinmapDB::getInstance()->update_membre($_GET['comID'],$_POST['fonctions_fonction_id'],$_GET['perID'], GetMontantValue($typeCommission_id, $_POST['fonctions_fonction_id']), $date, $_SESSION['MM_UserID']);
  
  } else {

  MinmapDB::getInstance()->create_membre($_GET['comID'],$_POST['fonctions_fonction_id'],$_GET['perID'], GetMontantValue($typeCommission_id, $_POST['fonctions_fonction_id']), $date, $_SESSION['MM_UserID']);
  
  /*MinmapDB::getInstance()->create_membre(
					   GetSQLValueString($_POST['commissions_commission_id'], "int"),
					   GetSQLValueString($_POST['fonctions_fonction_id'], "int"),
					   GetSQLValueString($_POST['personnes_personne_id'], "int"),
                       GetMontantValue($typeCommission_id, GetSQLValueString($_POST['fonctions_fonction_id'], "int")),
                       GetSQLValueString("un", "text"),
					   GetSQLValueString(1, "text"),
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_SESSION['MM_UserID'], "int"));*/
  MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 0, $date);
  MinmapDB::getInstance()->activ_or_desactiv_person_add_commission_by_pers_id($_GET['perID'], 2, $date);
  }
  


  /*$insertGoTo = "sample23.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/
 echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonctions = "SELECT fonction_id, fonction_lib FROM fonctions WHERE groupe_fonction_id = 3 ORDER BY fonction_lib ASC";
$rsFonctions = mysql_query($query_rsFonctions, $MyFileConnect) or die(mysql_error());
$row_rsFonctions = mysql_fetch_assoc($rsFonctions);
$totalRows_rsFonctions = mysql_num_rows($rsFonctions);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Commissions:</td>
      <td><?php echo MinmapDB::getInstance()->get_commission_lib_by_commission_id($_GET['comID']); ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Personnes:</td>
      <td><?php echo MinmapDB::getInstance()->get_personne_name_by_person_id($_GET['perID']); ?>&nbsp;</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Fonctions:</td>
      <td><select name="fonctions_fonction_id">
        <option value="" >Select</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsFonctions['fonction_id']?>"><?php echo $row_rsFonctions['fonction_lib']?></option>
        <?php
} while ($row_rsFonctions = mysql_fetch_assoc($rsFonctions));
  $rows = mysql_num_rows($rsFonctions);
  if($rows > 0) {
      mysql_data_seek($rsFonctions, 0);
	  $row_rsFonctions = mysql_fetch_assoc($rsFonctions);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Ins&eacute;rer un enregistrement" /></td>
    </tr>
  </table>
  <input type="hidden" name="commissions_commission_id" value="<?php echo $_GET['comID']; ?>" />
  <input type="hidden" name="personnes_personne_id" value="<?php echo $_GET['perID']; ?>" />
<input type="hidden" name="montant" value="" />
  <input type="hidden" name="checboxName" value="" />
  <input type="hidden" name="position" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="dateCreation" value="" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="user_id" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsFonctions);
?>
