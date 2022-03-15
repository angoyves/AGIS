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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsAffichePerson = 10;
$pageNum_rsAffichePerson = 0;
if (isset($_GET['pageNum_rsAffichePerson'])) {
  $pageNum_rsAffichePerson = $_GET['pageNum_rsAffichePerson'];
}
$startRow_rsAffichePerson = $pageNum_rsAffichePerson * $maxRows_rsAffichePerson;

$colname_rsPersonne = "nom";
if (isset($_POST['TxtChamp'])) {
  $colname_rsPersonne = $_POST['TxtChamp'];
}
$text_rsPersonne = "m";
mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_POST['TxtSearch']))
{
$text_rsPersonne = $_POST['TxtSearch'];	
$query_rsAffichePerson = sprintf("SELECT * FROM personnes WHERE $colname_rsPersonne LIKE %s AND display = '1' ORDER BY personne_nom ASC", GetSQLValueString("%" . $text_rsPersonne . "%", "text"));
} else {
$query_rsAffichePerson = "SELECT * FROM personnes WHERE display = '1' ORDER BY date_creation DESC";
}
$query_limit_rsAffichePerson = sprintf("%s LIMIT %d, %d", $query_rsAffichePerson, $startRow_rsAffichePerson, $maxRows_rsAffichePerson);
$rsAffichePerson = mysql_query($query_limit_rsAffichePerson, $MyFileConnect) or die(mysql_error());
$row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson);
if (isset($_GET['totalRows_rsAffichePerson'])) {
  $totalRows_rsAffichePerson = $_GET['totalRows_rsAffichePerson'];
} else {
  $all_rsAffichePerson = mysql_query($query_rsAffichePerson);
  $totalRows_rsAffichePerson = mysql_num_rows($all_rsAffichePerson);
}
$totalPages_rsAffichePerson = ceil($totalRows_rsAffichePerson/$maxRows_rsAffichePerson)-1;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowPersonInactiv = "SELECT * FROM personnes WHERE display = '0' ORDER BY dateUpdate DESC";
$rsShowPersonInactiv = mysql_query($query_rsShowPersonInactiv, $MyFileConnect) or die(mysql_error());
$row_rsShowPersonInactiv = mysql_fetch_assoc($rsShowPersonInactiv);
$totalRows_rsShowPersonInactiv = mysql_num_rows($rsShowPersonInactiv);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

$queryString_rsAffichePerson = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAffichePerson") == false && 
        stristr($param, "totalRows_rsAffichePerson") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAffichePerson = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAffichePerson = sprintf("&totalRows_rsAffichePerson=%d%s", $totalRows_rsAffichePerson, $queryString_rsAffichePerson);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="fr"><!-- InstanceBegin template="/Templates/FilesTemplates.dwt.php" codeOutsideHTMLIsLocked="false" -->
<?php

//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

$MM_authorizedUsers = "0,1,2,3,4,5,6,7";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid 