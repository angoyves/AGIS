<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('includes/MyFonction.php'); ?>
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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1'";
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
<table border="0">
  <tr>
    <th scope="col"><a href="create_privileges.php"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>Ajouter privilège</th>
  </tr>
  <tr>
    <th><a href="accueil.php"><img src="images/img/b_home.png" alt="" width="16" height="16" align="absmiddle"/></a>Retour à l'accueil</th>
  </tr>
</table>
<p><a href="create_menu.php"></a></p>
<table border="1" align="center" class="std">
  <tr>
    <th>menu_id</th>
    <th>menu_lib</th>
    <th>menu_description</th>
    <th>dateCreation</th>
    <th>dateUpdate</th>
    <th>display</th>
  </tr>
  <?php $counter=0; do  { $counter++; ?>
    <tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
      <td><a href="detail_menu.php?recordID=<?php echo $row_rsMenu['menu_id']; ?>"> <?php echo $row_rsMenu['menu_id']; ?>&nbsp; </a></td>
      <td><?php echo $row_rsMenu['menu_lib']; ?>&nbsp; </td>
      <td><?php echo $row_rsMenu['menu_description']; ?>&nbsp; </td>
      <td><?php echo $row_rsMenu['dateCreation']; ?>&nbsp; </td>
      <td><?php echo $row_rsMenu['dateUpdate']; ?>&nbsp; </td>
      <td><?php echo $row_rsMenu['display']; ?>&nbsp; </td>
    </tr>
    <?php } while ($row_rsMenu = mysql_fetch_assoc($rsMenu)); ?>
</table>
<br />
<?php echo $totalRows_rsMenu ?> Records Total
</body>
</html>
<?php
mysql_free_result($rsMenu);
?>
