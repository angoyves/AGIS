<?php
require_once("db.php");
$logonSuccess = false;

// verify user's credentials
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $logonSuccess = (MinmapDB::getInstance()->verify_user_credentials($_POST['user'], $_POST['userpassword']));
    if ($logonSuccess == true) {
        session_start();
        /*$_SESSION['MM_Username'] = $_POST['user'];
        header('Location: index.php');*/
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
        exit;
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Rib Application</title>
        <link href="wishes/wishlist.css" type="text/css" rel="stylesheet" media="all" />
    <style type="text/css">
<!--
body {
	background-color: #FFF;
}
h1 {
	font-size: 12px;
}
-->
</style></head>
    <body>

    <div id="content">
        <div class="logo"><br/>
        </div>
        <div class="logon">
          <input type="submit" name="myWishList" value="Se connecter... &gt;&gt;" onclick="javascript:showHideLogonForm()"/>
            <form name="logon" action="test_connexion.php" method="POST"
                  style="visibility:<?php if ($logonSuccess)
    echo "hidden"; else
    echo "visible"; ?>">
                compte:
                  <input type="text" name="user"/>

                Mot de passe:
                <input type="password" name="userpassword"/><br/>
                
                <div class="error">
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == "POST") {
                        if (!$logonSuccess)
                            echo "Invalid name and/or password";
                    }
                    ?>
                </div>
              <input type="submit" value="Afficher mon compte..."/>
          </form>
      </div>
        <div class="showWishList">
            <input type="submit" name="showWishList" value="Rechercher dans la base de données &gt;&gt;" onclick="javascript:showHideShowWishListForm()"/>

            <form name="wishList" action="wishes/file.php" method="GET" style="visibility:hidden">
                <input type="text" name="value"/>
                <input type="submit" value="Go" />
          </form>
      </div>
  <div class="createWishList">
            Pas encore enregistrer?! <a href="wishes/createNewWisher.php">Créer votre compte maintenant</a>
      </div>
    </div>
        <script type="text/javascript">
            function showHideLogonForm() {
                if (document.all.logon.style.visibility == "visible"){
                    document.all.logon.style.visibility = "hidden";
                    document.all.myWishList.value = "<< Mes informations";
                }
                else {
                    document.all.logon.style.visibility = "visible";
                    document.all.myWishList.value = "My Wish List >>";
                }
            }

            function showHideShowWishListForm() {
                if (document.all.wishList.style.visibility == "visible") {
                    document.all.wishList.style.visibility = "hidden";
                    document.all.showWishList.value = "Afficher les information sur >>";
                }
                else {
                    document.all.wishList.style.visibility = "visible";
                    document.all.showWishList.value = "<< Afficher les information sur...";
                }
            }
        </script>
    </body>
</html>