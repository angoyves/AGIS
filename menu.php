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
?>
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

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowMenu = "SELECT menu_id, menu_lib FROM menus WHERE display = '1'";
$rsShowMenu = mysql_query($query_rsShowMenu, $MyFileConnect) or die(mysql_error());
$row_rsShowMenu = mysql_fetch_assoc($rsShowMenu);
$totalRows_rsShowMenu = mysql_num_rows($rsShowMenu);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER MINMAP</title>
	<link href="css/file.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/menu.css">
	<script type="text/javascript" src="js/menu.js"></script>
</head>

<body>
<table border="0" align="left">
  <?php do { ?>
    <tr>
      <td colspan="2"><h1><?php echo $row_rsShowMenu['menu_lib']; ?>
        <?php 
		
		$colname_rsSousMenu = "-1";
if (isset($row_rsShowMenu['menu_id'])) {
  $colname_rsSousMenu = $row_rsShowMenu['menu_id'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT sous_menu_id, sous_menu_lib, sous_menu_lien FROM sous_menus WHERE menu_id = %s ", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);
		
		?>
      </h1></td>
    </tr>
    <tr>
      <td><?php do { ?>
        <ul>
          <li><a href="<?php echo $row_rsSousMenu['sous_menu_lien']; ?>"><?php echo $row_rsSousMenu['sous_menu_lib']; ?></a></li>
        </ul>
      <?php } while ($row_rsSousMenu = mysql_fetch_assoc($rsSousMenu)); ?></td>
    </tr>
    <?php } while ($row_rsShowMenu = mysql_fetch_assoc($rsShowMenu)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<div id="MenuCours">
  <ul>
    <li class="PageCours"><img src="./Les meilleurs cours et tutoriels CSS_files/kitmoins.gif" style="cursor: pointer;" /> <a href="" style="background: rgb(255, 255, 255);">Introduction aux CSS</a>
      <ul class="ListeCategorieCours" style="display: block;">
        <li class="ThemeCours"> <a href="#2">Initiation au CSS 2</a></li>
        <li class="ThemeCours"> <a href="#3">Pr&eacute;sentation du CSS 3</a></li>
      </ul>
    </li>
    <li class="PageCours"><img src="./Les meilleurs cours et tutoriels CSS_files/kitmoins.gif" style="cursor: pointer;" /> <a href="http://css.developpez.com/cours/?page=pratique-css" style="background: rgb(255, 255, 255);">Mise en pratique du CSS</a>
      <ul class="ListeCategorieCours" style="display: block;">
        <li class="ThemeCours"> <a href="debutant">D&eacute;butants</a></li>
        <li class="ThemeCours"> <a href="design">Les mises en page</a></li>
        <li class="ThemeCours"> <a href="navigation">Menus et barres de navigation</a></li>
        <li class="ThemeCours"> <a href="astuce">Les meilleures astuces</a></li>
        <li class="ThemeCours"> <a href="personnalisation">Personnalisation d'&eacute;l&eacute;ments (X)HTML</a></li>
      </ul>
    </li>
    <li class="PageCours"><img src="./Les meilleurs cours et tutoriels CSS_files/kitmoins.gif" style="cursor: pointer;" /> <a href="" style="background: rgb(255, 255, 255);">G&eacute;n&eacute;ralit&eacute;s / Divers</a>
      <ul class="ListeCategorieCours" style="display: block;">
        <li class="ThemeCours"> <a href="#generalite">G&eacute;n&eacute;ralit&eacute;s</a></li>
        <li class="ThemeCours"> <a href="#divers">Divers</a></li>
      </ul>
    </li>
  </ul>
</div>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsSousMenu);

mysql_free_result($rsShowMenu);

mysql_free_result($rsSousMenu);
?>
