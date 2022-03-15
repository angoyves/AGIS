<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php');

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sous_menus (sous_menu_id, sous_menu_lib, sous_menu_description, sous_menu_lien, menu_id, display, dateCreation, dateUpdate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['sous_menu_id'], "int"),
                       GetSQLValueString($_POST['sous_menu_lib'], "text"),
                       GetSQLValueString($_POST['sous_menu_description'], "text"),
					   GetSQLValueString($_POST['sous_menu_lien'], "text"),
                       GetSQLValueString($_POST['menu_id'], "int"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "afficher_sous_menu.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT menu_id, menu_lib FROM menus WHERE display = '1'";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center" class="std">
    <tr valign="baseline">
      <th nowrap="nowrap" align="right">Libellé Sous Menu :</th>
      <td><input type="text" name="sous_menu_lib" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th nowrap="nowrap" align="right" valign="top">Description :</th>
      <td><textarea name="sous_menu_description" cols="50" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <th align="right" nowrap="nowrap">Lien : </th>
      <td><input type="text" name="sous_menu_lien" value="" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <th align="right" nowrap="nowrap">Menu :</th>
      <td><select name="menu_id">
        <option value="">:: Selectionner</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsMenu['menu_id']?>"><?php echo $row_rsMenu['menu_lib']?></option>
        <?php
} while ($row_rsMenu = mysql_fetch_assoc($rsMenu));
  $rows = mysql_num_rows($rsMenu);
  if($rows > 0) {
      mysql_data_seek($rsMenu, 0);
	  $row_rsMenu = mysql_fetch_assoc($rsMenu);
  }
?>
      </select>
      <a href="#" onclick="<?php popup("create_menu.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Enregistrer" /></td>
    </tr>
  </table>
  <input type="hidden" name="sous_menu_id" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="dateCreation" value="" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsMenu);
?>
