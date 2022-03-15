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
  $insertSQL = sprintf("INSERT INTO agences (agence_id, banque_code, agence_code, agence_lib, agence_cle, date_creation, display) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['agence_id'], "int"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['agence_lib'], "text"),
                       GetSQLValueString($_POST['agence_cle'], "text"),
                       GetSQLValueString($_POST['date_creation'], "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "popup.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


?>

<html>
<title>Fenetre Popup Fille</title>
<script type="text/javascript">

// fonction qui raffraichit la fenêtre mère et ferme la popup (activé au submit)
function refreshParent() {
  window.opener.location.href =window.opener.location.href;

  if (window.opener.progressWindow)

 {
    window.opener.progressWindow.close()
  }
window.close();
}

</script>
<h1><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/>Ajouter une ville</h1>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>" onSubmit="refreshParent()>
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">Banque_code:</td>
      <td><input type="text" name="banque_code" value="<?php echo $_GET['banqueID'] ?>" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Agence_code:</td>
      <td><input type="text" name="agence_code" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Agence_lib:</td>
      <td><input type="text" name="agence_lib" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Insert record"></td>
    </tr>
  </table>
  <input type="hidden" name="agence_id" value="">
  <input type="hidden" name="agence_cle" value="">
  <input type="hidden" name="date_creation" value="">
  <input type="hidden" name="display" value="">
  <input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
</html>