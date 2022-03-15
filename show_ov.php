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
if (isset($_REQUEST['txt_search']))
$_SESSION['select_value_ov'] = $_REQUEST['txt_search'];

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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_REQUEST['txt_search'])) {
$colname_rsOV_IDNULL = $_REQUEST['txt_search'];
$query_rsOV_IDNULL = sprintf("SELECT * FROM appurements WHERE commission_id IS NULL
							  AND motif LIKE %s GROUP BY motif", GetSQLValueString("%" . $colname_rsOV_IDNULL . "%", "text"));
} else {
$query_rsOV_IDNULL = "SELECT * FROM appurements WHERE commission_id IS NULL GROUP BY motif";
}
$rsOV_IDNULL = mysql_query($query_rsOV_IDNULL, $MyFileConnect) or die(mysql_error());
$row_rsOV_IDNULL = mysql_fetch_assoc($rsOV_IDNULL);
$totalRows_rsOV_IDNULL = mysql_num_rows($rsOV_IDNULL);

	$showUpdTo = "upd_ov.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$showUpdTo .= (strpos($showUpdTo, '?')) ? "&" : "?";
		$showUpdTo .= $_SERVER['QUERY_STRING'];
	  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>

<body>
<table border="1" align="center">
  <tr>
    <td colspan="3">Rechercher : 
      <form id="form2" name="form2" method="post" action="">
        <input type="text" name="txt_search" id="txt_search" value="<?php echo (isset($_GET['txt_search'])?$_GET['txt_search']:"indemnites"); ?>"/>
        <input type="submit" name="Btn_Search" id="Btn_Search" value="Envoyer" />
    </form>
      <form id="form3" name="form3" method="post" action="show_ov.php">
        <input type="submit" name="button2" id="button2" value="Reinitialiser" />
      </form>
      <?php if (isset($_POST['txt_search'])) { echo 'Resultat de la recherche pour : <strong>' . $_POST['txt_search'] . '</strong>'; } ?></td>
  </tr>
  <tr>
    <td>ID</td>
    <td><strong>MOTIF</strong></td>
    <td><strong>COM ID </strong></td>
  </tr>
  <form id="form1" name="form1" method="post" action="<?php echo $showUpdTo ?>">
  <?php do { ?>
    <tr>
      <td><a href="show_detailsov.php?recordID=<?php echo $row_rsOV_IDNULL['motif']; ?>"><?php echo $row_rsOV_IDNULL['appurement_id']; ?></a></td>
      <?php           
	  $showGoTo = "show_ov.php?txt_search=".strtoupper(trim(substr($row_rsOV_IDNULL['motif'],-25,-1)));
	  /*if (isset($_SERVER['QUERY_STRING'])) {
		$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
		$showGoTo .= $_SERVER['QUERY_STRING'];
	  }*/
	?>
      <td><a href="<?php echo $showGoTo ?>"><?php echo $row_rsOV_IDNULL['motif'];?>&nbsp;</a></td>
      <td align="center">&nbsp;</td>
      </tr>
  <?php } while ($row_rsOV_IDNULL = mysql_fetch_assoc($rsOV_IDNULL)); ?>
    <tr>
      <td><input name="textfield" type="text" id="textfield" size="5" value="<?php echo (isset($_GET['comID'])?$_GET['comID']:""); ?>" />
      <input type="hidden" name="MM_update" value="form1" /></td>
      <td>
	<?php           
	$colname_rstxtSerach = "-1";
	if (isset($_POST['txt_search'])) {
	$colname_rstxtSerach = $_POST['txt_search'];
	} else { 
	$colname_rstxtSerach = $_GET['txt_search'];
	}
	$showGoTo = "search_commissions_id.php?txt_search=".$colname_rstxtSerach;
	 /* if (isset($_SERVER['QUERY_STRING'])) {
		$showGoTo .= (strpos($showGoTo, '?')) ? "&" : "?";
		$showGoTo .= $_SERVER['QUERY_STRING'];
	  }*/
	?>
        <a href="#" onclick="<?php popup($showGoTo, "610", "300"); ?>">Selectionner une commission...<img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
      <td align="center"><input type="submit" name="button" id="button" value="Envoyer" /></td>
    </tr>
    </form>
</table>
<br />
<?php echo $totalRows_rsOV_IDNULL ?> Enregistrements Total
</body>
</html>
<?php
mysql_free_result($rsOV_IDNULL);
?>
