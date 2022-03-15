<?php
require_once("includes/db_1.php");
$logonSuccess = false;

// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (MinmapDB::getInstance()->verify_wisher_credentials($_POST['user'], md5($_POST['userpassword'])));
    if ($logonSuccess == true) {
        session_start();
        $_SESSION['user'] = $_POST['user'];
		$_SESSION['user_id'] = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user']));
		    
		//$loginStrGroup  = mysql_result($LoginRS,0,'user_groupe_id');
    
    	//declare two session variables and assign them
		$_SESSION['MM_Username'] = $_POST['user'];
		$_SESSION['MM_UserGroup'] = 1;
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />

<link rel="canonical" href="#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AGIS :: Application de Gestion des Indemnites de Session</title>
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
        </span> </li>
      <li>
        <label class="desc">Mot de passe</label>
        <span>
          <input name="userpassword" type="password" class="field text" size="32"/><br/>
                
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