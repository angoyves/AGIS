<?php require_once('Connections/MyFileConnect.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}

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
$date = date('Y-m-d H:i:s');

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO commentaires (comment_id, destinataire_id, objet, expediteur_id, `comment`, notation, dateCreation) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['comment_id'], "int"),
                       GetSQLValueString($_POST['destinataire_id'], "int"),
                       GetSQLValueString($_POST['objet'], "text"),
                       GetSQLValueString($_SESSION['MM_UserID'], "int"),
                       GetSQLValueString($_POST['comment'], "text"),
                       GetSQLValueString($_POST['notation'], "int"),
					   GetSQLValueString($date, "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

/*  $insertGoTo = "index.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo)); */
  
   echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUsers = "SELECT user_id, user_name, user_lastname FROM users WHERE display = '1'";
$rsUsers = mysql_query($query_rsUsers, $MyFileConnect) or die(mysql_error());
$row_rsUsers = mysql_fetch_assoc($rsUsers);
$totalRows_rsUsers = mysql_num_rows($rsUsers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/v2.css" rel="stylesheet" type="text/css" />
<link href="css/form.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">A :</td>
      <td align="left"><select name="destinataire_id">
        <option value="" >[ Selectionner le destinataire...]</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsUsers['user_id']?>" <?php if (!(strcmp($row_rsUsers['user_id'], htmlentities($_GET['expID'], ENT_COMPAT, 'utf-8')))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper($row_rsUsers['user_name'])?></option>
        <?php
} while ($row_rsUsers = mysql_fetch_assoc($rsUsers));
  $rows = mysql_num_rows($rsUsers);
  if($rows > 0) {
      mysql_data_seek($rsUsers, 0);
	  $row_rsUsers = mysql_fetch_assoc($rsUsers);
  }
?>
      </select></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Objet:</td>
      <td align="left"><textarea name="objet" cols="50" rows="2"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Comment:</td>
      <td align="left"><textarea name="comment" cols="50" rows="10"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td align="left"><input type="submit" value="Envoyer" />
        <input type="hidden" name="comment_id" value="" />
        <input type="hidden" name="expediteur_id" value="" />
        <input type="hidden" name="notation" value="0" />
      <input type="hidden" name="MM_insert" value="form1" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsUsers);
?>
