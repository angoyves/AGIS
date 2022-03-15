<?php require_once('Connections/MyFileConnect.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO sous_menus (sous_menu_id, sous_menu_lib, sous_menu_description, sous_menu_lien, image, `position`, `action`, menu_id, dateCreation, dateUpdate, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['sous_menu_id'], "int"),
                       GetSQLValueString($_POST['sous_menu_lib'], "text"),
                       GetSQLValueString($_POST['sous_menu_description'], "text"),
                       GetSQLValueString($_POST['sous_menu_lien'], "text"),
                       GetSQLValueString($_POST['image'], "text"),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString($_POST['action'], "text"),
                       GetSQLValueString($_POST['menu_id'], "int"),
                       GetSQLValueString($_POST['dateCreation'], "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "sousmenu.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '2' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

?>

<form method="post" name="form1" action="<?php echo $editFormAction; ?>">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">Sous_menu_lib:</td>
      <td><input type="text" name="sous_menu_lib" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Sous_menu_description:</td>
      <td><textarea name="sous_menu_description" id="sous_menu_description" cols="45" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Sous_menu_lien:</td>
      <td><input type="text" name="sous_menu_lien" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Image:</td>
      <td><input type="text" name="image" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Position:</td>
      <td><input type="text" name="position" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Action:</td>
      <td><input type="text" name="action" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Menu_id:</td>
      <td><select name="menu_id">
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
      </select></td>
    
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insérer un enregistrement"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
  <input type="hidden" name="display" value="1" size="32">
</form>
<p>&nbsp;</p>
<?php mysql_free_result($rsMenu); ?>