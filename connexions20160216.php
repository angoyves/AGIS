<?php 
	require_once('Connections/MyFileConnect.php'); 
	require_once('includes/db.php');
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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=md5($_POST['userpassword']);
  $MM_fldUserAuthorization = "user_groupe_id";
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "denied.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  	
  $LoginRS__query=sprintf("SELECT user_login, user_password, user_groupe_id FROM users WHERE user_login=%s AND user_password=%s AND display=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"), GetSQLValueString(1, "int")); 
   
  $LoginRS = mysql_query($LoginRS__query, $MyFileConnect) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'user_groupe_id');
    
    //declare two session variables and assign them
	$_SESSION['MM_UserID'] = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user']));
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
		$date = date('Y-m-d H:i:s');
	  	$updateSQL = sprintf("UPDATE users SET date_last_login=%s WHERE user_login=%s",
                       GetSQLValueString($date, "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));

	  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	  	$Result = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
	
	
	
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link href="css/form.css" rel="stylesheet" type="text/css" />
<link href="css/structure.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container">
  <h1><a id="logo" href="">Application de Gestion des Indemnites de Session (AGIS)</a></h1>
  <form class="wufoo" action="<?php echo $loginFormAction; ?>" method="POST" name="form1" id="form1">
    <ul>
      <li class="section">
        <h3></h3>
      </li>
      <li>
        <label class="desc">Compte utilisateur</label>
        <span>
          <input class="field text" name="user" size="32" value="<?php echo $_POST['user']; ?>"/>
        </span></li>
      <li>
        <label class="desc">Mot de passe</label>
        <span>
          <input name="userpassword" type="password" class="field text" size="32"/>
          <br/>
          <div class="error">
            <?php
                    if ($_SERVER['REQUEST_METHOD'] == "POST") {
                        if (!$logonSuccess)
                            echo "Invalid name and/or password";
                    }
                    ?>
          </div>
        </span></li>
      <li class="buttons">
        <input id="saveForm" class="btTxt" type="submit" value="Connexion" />
      </li>
      <li class="section">
        <h3>Pas encore enregistré?</h3>
        <div>Créer votre compte en cliquant sur le lien suivant...<a href="new_user.php">Créer son compte</a></div>
      </li>
    </ul>
  </form>
</div>
</body>
</html>