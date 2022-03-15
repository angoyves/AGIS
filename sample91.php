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
$query_rsMois1 = "SELECT * FROM mois";
$rsMois1 = mysql_query($query_rsMois1, $MyFileConnect) or die(mysql_error());
$row_rsMois1 = mysql_fetch_assoc($rsMois1);
$totalRows_rsMois1 = mysql_num_rows($rsMois1);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMois2 = "SELECT * FROM mois";
$rsMois2 = mysql_query($query_rsMois2, $MyFileConnect) or die(mysql_error());
$row_rsMois2 = mysql_fetch_assoc($rsMois2);
$totalRows_rsMois2 = mysql_num_rows($rsMois2);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAnnee = "SELECT * FROM annee ORDER BY lib_annee ASC";
$rsAnnee = mysql_query($query_rsAnnee, $MyFileConnect) or die(mysql_error());
$row_rsAnnee = mysql_fetch_assoc($rsAnnee);
$totalRows_rsAnnee = mysql_num_rows($rsAnnee);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/file.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="sample92.php" method="POST">
<p>&nbsp;</p>
<table border="1" align="center" class="std2">
  <tr>
    <th align="right" scope="row">Exercice:</th>
    <td><select name="txtYear" id="txtYear">
      <option value=""<?php if (!(strcmp("", $_POST['txtType']))) {echo "selected=\"selected\"";} ?>>Select:::</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rsAnnee['lib_annee']?>" <?php if (!(strcmp($row_rsAnnee['lib_annee'], $_POST['txtYear']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsAnnee['lib_annee']?></option>
      <?php
} while ($row_rsAnnee = mysql_fetch_assoc($rsAnnee));
  $rows = mysql_num_rows($rsAnnee);
  if($rows > 0) {
      mysql_data_seek($rsAnnee, 0);
	  $row_rsAnnee = mysql_fetch_assoc($rsAnnee);
  }
?>
      </select>      <input type="hidden" name="comID" id="comID" value="<?php echo $_GET['comID'] ?>"/></td>
    <td><input type="submit" name="button" id="button" value="Rechercher" /></td>
    </tr>
  <tr>
    <td colspan="2" align="center" scope="row">Commissions</td>
    <td align="center" scope="row">Sous Commission</td>
    </tr>
  <tr>
    <th align="right" scope="row">Janvier</th>
    <td><input type="checkbox" name="checkbox1" id="checkbox1" value="01"/></td>
    <td><input type="checkbox" name="checkbox14" id="checkbox14" value="01"/></td>
    </tr>
  <tr>
    <th align="right" scope="row">Fevrier</th>
    <td><input type="checkbox" name="checkbox2" id="checkbox2" value="02"/></td>
    <td><input type="checkbox" name="checkbox15" id="checkbox15" value="02"/></td>
    </tr>
  <tr>
    <th align="right" scope="row">Mars</th>
    <td><input type="checkbox" name="checkbox3" id="checkbox3" value="03"/></td>
    <td><input type="checkbox" name="checkbox16" id="checkbox16" value="03"/></td>
    </tr>
  <tr>
    <th align="right" scope="row">Avril</th>
    <td><input type="checkbox" name="checkbox4" id="checkbox4" value="04"/></td>
    <td><input type="checkbox" name="checkbox17" id="checkbox17" value="04"/></td>
    </tr>
  <tr>
    <th align="right" scope="row">Mai</th>
    <td><input type="checkbox" name="checkbox5" id="checkbox5" value="05"/></td>
    <td><input type="checkbox" name="checkbox18" id="checkbox18" value="05"/></td>
    </tr>
  <tr>
    <th align="right" scope="row">Juin</th>
    <td><input type="checkbox" name="checkbox6" id="checkbox6" value="06" /></td>
    <td><input type="checkbox" name="checkbox19" id="checkbox19" value="06" /></td>
    </tr>
  <tr>
    <th align="right" scope="row">Juillet</th>
    <td><input type="checkbox" name="checkbox7" id="checkbox7" value="07" /></td>
    <td><input type="checkbox" name="checkbox20" id="checkbox20" value="07" /></td>
    </tr>
  <tr>
    <th align="right" scope="row">Aout </th>
    <td><input type="checkbox" name="checkbox8" id="checkbox8" value="08" /></td>
    <td><input type="checkbox" name="checkbox21" id="checkbox21" value="08" /></td>
    </tr>
  <tr>
    <th align="right" scope="row">Septembre</th>
    <td><input type="checkbox" name="checkbox9" id="checkbox9" value="09" /></td>
    <td><input type="checkbox" name="checkbox22" id="checkbox22" value="09" /></td>
    </tr>
  <tr>
    <th align="right" scope="row">Octobre</th>
    <td><input type="checkbox" name="checkbox10" id="checkbox10" value="10" /></td>
    <td><input type="checkbox" name="checkbox23" id="checkbox23" value="10" /></td>
    </tr>
  <tr>
    <th align="right" scope="row">Novembre</th>
    <td><input type="checkbox" name="checkbox11" id="checkbox11" value="11" /></td>
    <td><input type="checkbox" name="checkbox24" id="checkbox24" value="11" /></td>
    </tr>
  <tr>
    <th align="right" scope="row">Decembre</th>
    <td><input type="checkbox" name="checkbox12" id="checkbox12" value="12" /></td>
    <td><input type="checkbox" name="checkbox25" id="checkbox25" value="12" /></td>
    </tr>
</table>
<label for="checkbox"></label>
<p>&nbsp;</p>
</form>
</body>
</html>
<?php
mysql_free_result($rsMois1);

mysql_free_result($rsMois2);

mysql_free_result($rsAnnee);
?>
