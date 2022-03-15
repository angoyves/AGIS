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

$date = date('Y-m-d H:i:s');
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	
    $content_dir = 'upload/'; // dossier où sera déplacé le fichier
	$taille_maxi = 5000000;
	$taille = filesize($_FILES['avatar']['tmp_name']);
    $tmp_file = $_FILES['fichier']['tmp_name'];
    $name_file = $_FILES['fichier']['name'];
	$type_file = $_FILES['fichier']['type'];

    if( !is_uploaded_file($tmp_file) )
    {
        exit("Le fichier est introuvable");
    }
	
	if($taille>$taille_maxi)
	{
		 $erreur = 'Le fichier est trop gros...';
	}
	
    // on vérifie maintenant l'extension
    if( !strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'bmp') && !strstr($type_file, 'gif') && !strstr($type_file, 'pdf'))
    {
        exit("Vous devez uploader un fichier de type png, gif, jpg, jpeg ou pdf...");
    }

    // on copie le fichier dans le dossier de destination
	
	if( preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name_file) )
	{
		exit("Nom de fichier non valide");
	}
	else if( !move_uploaded_file($tmp_file, $content_dir . $name_file) )
	{
		exit("Impossible de copier le fichier dans $content_dir");
	} else {

    echo "Le fichier a bien été uploadé";
	
    $insertSQL = sprintf("INSERT INTO dossiers (dossier_id, commission_id, dossier_ref, dossiers_jour, dossier_observ, dossier_nom, dateCreation, display) VALUES (%s, %s, %s, %s, %s, %s,  %s, %s)",
                       GetSQLValueString($_POST['dossier_id'], "int"),
					   GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['dossier_ref'], "text"),
                       GetSQLValueString($_GET['day'], "text"),
                       GetSQLValueString($_POST['dossier_observ'], "text"),
					   GetSQLValueString($name_file, "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());

  $insertGoTo = "dossiers.php";
 /* if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));*/
  
    echo "<script type='".text/javascript."'>
  		window.opener.location.reload(); 
		self.close(); 
		</script>";
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1" enctype="multipart/form-data">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Reference dossier:</td>
      <td><textarea name="dossier_ref" cols="50" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Télécharger Fichier:</td>
      <td>&nbsp;&nbsp;
      <input type="file" name="fichier" size="30" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right" valign="top">Observations dossiers:</td>
      <td><textarea name="dossier_observ" cols="50" rows="5"></textarea></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Ins&eacute;rer un dossier" /></td>
    </tr>
  </table>
  <input type="hidden" name="dossier_id" value="" />
  <input type="hidden" name="commission_id" value="<?php echo $_GET['comID'] ?>" />
  <input type="hidden" name="dossiers_jour" value="" />
  <input type="hidden" name="dateCreation" value="" />
  <input type="hidden" name="display" value="1" />
  <input type="hidden" name="MAX_FILE_SIZE" value="100000">
  <input type="hidden" name="MM_insert" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>