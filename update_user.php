<?php require_once('Connections/MyFileConnect.php'); ?>
<?php
// *** Validate request to login to this site.
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE users SET user_question=%s, user_answer=%s WHERE user_id=%s",
                       GetSQLValueString($_POST['user_question'], "text"),
                       GetSQLValueString($_POST['user_answer'], "text"),
                       GetSQLValueString($_SESSION['MM_UserID'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "sample70.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_Recordset1 = "-1";
if (isset($_SESSION['MM_UserID'])) {
  $colname_Recordset1 = $_SESSION['MM_UserID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_Recordset1 = sprintf("SELECT * FROM users WHERE user_id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $MyFileConnect) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
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
        <td width="339" scope="col"><h1><a id="logo" href=""></h1></td>
      </tr>
    </table>
    <div id="ps-img">
      <p class="ps_loginmessagelarge">ATTENTION!!! Votre session est active...!</p>
      <p>Pour plus de sécurité sur ce site, nous bloquons l'acces à une seule connexion active pour un compte.</p>
      <p>En remplissant cette question secrete vous pourrez  fermer votre session laissée active ou ouverte et vous connecter sans l'assistance de l'administreur.</p>
    </div>
  </ul>
  <form class="wufoo" action="<?php echo $editFormAction ?>" method="post" name="form1" id="form1">
    <ul>
      <li class="section">
        <h3></h3>
      </li>
      <li>
        <label class="desc">Question secrete</label>
        <span>
        <select class="field text" name="user_question" id="select">
        <option>[Select...]</option>
            <option value="Le prenom de votre compagne">Le prenom de votre compagne</option>
            <option value="Le prenom de votre premie	r enfant">Le prenom de votre premier enfant</option>
            <option value="Le prenom de votre grande mere">Le prenom de votre grande mere</option>
            <option value="Votre mot de passe yahoo.mail">Votre mot de passe yahoo.mail</option>
            <option value="Le code de votre smart phone">Le code de votre smart phone</option>
          </select>
        </span></li>
      <li>
        <label class="desc">Reponse secrete</label>
        <span><span class="section">
        <input class="field text" type="password" name="user_answer" value="" size="32" />
        </span><br/>
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
        <input class="ps-button" type="submit" value="Enregistrer" />
      </span></li>
      <li class="section">
        <h3>
          <input type="hidden" name="user_id" value="<?php echo $row_Recordset1['user_id']; ?>" />
          <input type="hidden" name="MM_update" value="form1" />
        </h3>
      </li>
    </ul>
  </form>
</div>
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>
