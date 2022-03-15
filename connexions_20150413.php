<?php 
	$hostname_MyFileConnect = "localhost";
	$database_MyFileConnect = "fichier_db8";
	$username_MyFileConnect = "root";
	$password_MyFileConnect = "";
	$MM_redirectLoginEchec = "error_data.php";
	$MyFileConnect = mysql_pconnect($hostname_MyFileConnect, $username_MyFileConnect, $password_MyFileConnect) or header("Location: " . $MM_redirectLoginEchec ); 

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

//$loginFormAction = $_SERVER['PHP_SELF'];
$loginFormAction = "connexions.php";
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=md5($_POST['userpassword']);
  $MM_fldUserAuthorization = "user_groupe_id";
  $MM_redirectLoginSuccess = "sample70.php";
  $MM_redirectLoginFailed = "connexions.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  	
  $LoginRS__query=sprintf("SELECT user_login, user_password, user_groupe_id FROM users WHERE user_login=%s AND user_password=%s AND display=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text"), GetSQLValueString(1, "int")); 
   
  $LoginRS = mysql_query($LoginRS__query, $MyFileConnect) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'user_groupe_id');
    
    //declare four session variables and assign them
	$_SESSION['MM_UserID'] = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user']));
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;
	$_SESSION['MM_UserGroupName'] = (MinmapDB::getInstance()->get_user_groupe($loginStrGroup));
	$_SESSION['MM_NomPrenom'] = (MinmapDB::getInstance()->get_user_name($_SESSION['MM_UserID']));

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
		$date = date('Y-m-d H:i:s');
	  	$updateSQL = sprintf("UPDATE users SET date_last_login=%s WHERE user_login=%s",
                       GetSQLValueString($date, "date"),
					   GetSQLValueString($_SESSION['MM_Username'], "text"));

	  	mysql_select_db($database_MyFileConnect, $MyFileConnect);
	  	$Result = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
	
	    MinmapDB::getInstance()->update_users_connected('1', $_SESSION['MM_UserID']);
	
	
    header("Location: " . $MM_redirectLoginSuccess );
  }  else {
	$insertSQL = sprintf("INSERT INTO listing_action (UserId, PageLink, ModificationType, DateUpdate) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['user'], "int"),
                       GetSQLValueString('connexions.php', "text"),
                       GetSQLValueString('Echec de connexion a AGIS - '.$_POST['user'].' et mot de passe '.$_POST['userpassword'], "text"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result3 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
    //header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
<link href="css/form.css" rel="stylesheet" type="text/css" />
<link href="css/structure.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/signin.css" type="text/css">
</head>

<body>
<div id="container">
  
  <ul>
    <table border="0" align="center">
      <tr>
        <td width="213" align="left" scope="col">
          <img src="images/img/logo_minmap.gif" alt="Connexion à AGIS" width="200" height="223" class="ps-staticimg" title="Connexion à AGIS" />
        </td>
        <td width="339" scope="col"><h1><a id="logo" href="">Application de Gestion des Indemnites de Session (AGIS)</h1></td>
      </tr>
    </table>
  </ul>
  <form class="wufoo" action="connexions.php" method="POST" name="form1" id="form1">
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
                        if (!$loginFoundUser)
                            echo "Login Invalide et/ou Mot de Passe";
							
                    }
            ?>
          </div>
        </span></li>
      <li class="ps_loginmessagelarge"><span class="ps_box-button">
        <input type="submit" name="Submit" title="Connexion" class="ps-button" value="Connexion" onclick="return onFormSubmit(document.login);" tabindex="3" />
      </span></li>
      <li class="section">
        <h3>Pas encore enregistré?</h3>
        <div>Créer votre compte en cliquant sur le lien suivant...<a href="new_user.php">Créer son compte</a></div>
      </li>
    </ul>
  </form>
</div>
</body>
</html>