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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsFichierExpert = 200;
$pageNum_rsFichierExpert = 0;
if (isset($_GET['pageNum_rsFichierExpert'])) {
  $pageNum_rsFichierExpert = $_GET['pageNum_rsFichierExpert'];
}
$startRow_rsFichierExpert = $pageNum_rsFichierExpert * $maxRows_rsFichierExpert;

$colname_rsFichierExpert = "-1";
if ((isset($_GET['txtSearch'])) && (isset($_GET['txtChamp']))) {
  $colname1_rsFichierExpert = $_GET['txtChamp'];
  $colname2_rsFichierExpert = $_GET['txtSearch'];

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFichierExpert = sprintf("SELECT * FROM personnes, rib, domaine_expert, localite_expert WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = domaine_expert.personne_id AND personnes.personne_id = localite_expert.personne_id AND personnes.type_personne_id = 3 AND personnes.$colname1_rsFichierExpert LIKE  %s ORDER BY personne_nom ASC", GetSQLValueString("%" . $colname2_rsFichierExpert . "%", "text"));
$query_limit_rsFichierExpert = sprintf("%s LIMIT %d, %d", $query_rsFichierExpert, $startRow_rsFichierExpert, $maxRows_rsFichierExpert);
$rsFichierExpert = mysql_query($query_limit_rsFichierExpert, $MyFileConnect) or die(mysql_error());
$row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert);
} else {
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFichierExpert = "SELECT * FROM personnes, rib, domaine_expert, localite_expert WHERE personnes.personne_id = rib.personne_id AND personnes.personne_id = domaine_expert.personne_id AND personnes.personne_id = localite_expert.personne_id AND personnes.type_personne_id = 3 ORDER BY personne_nom ASC";
$query_limit_rsFichierExpert = sprintf("%s LIMIT %d, %d", $query_rsFichierExpert, $startRow_rsFichierExpert, $maxRows_rsFichierExpert);
$rsFichierExpert = mysql_query($query_limit_rsFichierExpert, $MyFileConnect) or die(mysql_error());
$row_rsFichierExpert = mysql_fetch_assoc($rsFichierExpert);
}

if (isset($_GET['totalRows_rsFichierExpert'])) {
  $totalRows_rsFichierExpert = $_GET['totalRows_rsFichierExpert'];
} else {
  $all_rsFichierExpert = mysql_query($query_rsFichierExpert);
  $totalRows_rsFichierExpert = mysql_num_rows($all_rsFichierExpert);
}
$totalPages_rsFichierExpert = ceil($totalRows_rsFichierExpert/$maxRows_rsFichierExpert)-1;

$queryString_rsFichierExpert = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsFichierExpert") == false && 
        stristr($param, "totalRows_rsFichierExpert") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsFichierExpert = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsFichierExpert = sprintf("&totalRows_rsFichierExpert=%d%s", $totalRows_rsFichierExpert, $queryString_rsFichierExpert);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FICHIER DES EXPERTS MINMAP</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="60%" border="0" align="center">
  <tr>
    <td width="157">&nbsp;</td>
    <td width="869" height="46" align="center" valign="bottom">&nbsp;</td>
    <td width="376"><a href="#" onclick="window.print()" title="Imprimer cette page">Imprimer<img src="images/img/b_print.png" alt="print" width="16" height="16" hspace="2" vspace="2" border="0" align="absmiddle" /></a>&nbsp;</td>
  </tr>
  <tr>
    <td><table>
      <tr>
        <th align="center" nowrap="nowrap">REPUBLIQUE  DU CAMEROUN</BR>
          Paix - Travail - Patrie</BR>
          ------*------</BR>
          PRESIDENCE  DE LA REPUBLIQUE</BR>
          ------*------</BR>
          MINISTERE  DES MARCHES PUBLICS</BR></th>
      </tr>
    </table></td>
    <td width="869" height="48" align="center" valign="bottom">&nbsp;</td>
    <td width="376"><table>
      <tr>
        <th align="center" nowrap="nowrap">REPUBLIC OF CAMEROON</BR>
          Peace - Work - Fatherland</BR>
          ------*------</BR>
          PRESIDENCY  OF THE REPUBLIC</BR>
          ------*------</BR>
          MINISTRY  OF PUBLIC CONTRACTS</th>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td width="157">&nbsp;</td>
    <td align="center" valign="top"><strong class="welcome">ETAT DES EXPERTS</strong></td>
    <td width="376">&nbsp;</td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
    <td align="center"><span class="welcome"><strong>DU MINISTERE 