<?php
require_once("db.php");
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

// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  	$loginUsername=$_POST['user'];
  	$password=$_POST['userpassword'];
  	$MM_fldUserAuthorization = "";
  	$MM_redirectLoginSuccess = "index.php";
  	$MM_redirectLoginFailed = "error.php";
  	$MM_redirecttoReferrer = true;
  	$logonSuccess = false;
  	$loginFormAction = $_SERVER['PHP_SELF'];
	
    $logonSuccess = (MinmapDB::getInstance()->verify_wisher_credentials($loginUsername, $password));
    if ($logonSuccess == true) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>
Fichier MINMAP
</title>


<!-- Meta Tags -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- JavaScript -->
<script type="text/javascript" src="scripts/wufoo.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />

<link rel="canonical" href="#">
</head>

<body id="public">

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
        </span> 
    </li>
     <li>          
	  <label class="desc">Mot de passe</label>
	  <span>
	    <input name="userpassword" type="password" class="field text" size="32"/>
	    </span></li>
     <span class="error">
	<?php
                    if ($_SERVER['REQUEST_METHOD'] == "POST") {
                        if (!$logonSuccess)
						echo ('<li><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />Nom et/ou mot de passe invalid</li>');
                    }
                    ?>
	</span>
	<li class="buttons">
		<input id="saveForm" class="btTxt" type="submit" value="Connexion" />
	</li>
    <li class="section">
		<h3>Pas encore enregistré?</h3>
		<div>Créer votre compte en cliquant sur le lien suivant...<a href="new_user.php">Créer son compte</a></div>
	</li>
	</ul>

</form>

</div><!--container-->


</body>

</html>