<?php require_once('Connections/MyFileConnect.php'); ?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$date = date('Y-m-d H:i:s');
// On verifie que le formulaire a ete envoyé
//On verifie si le mot de passe et celui de la verification sont identiques
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1") && ($_POST['user_password'] == $_POST['user_password2']) && (isset($_POST['user_login']) && $_POST['user_login'] != "") && (isset($_POST['user_password']) && $_POST['user_password'] != "") && (isset($_POST['structure_id']) && $_POST['structure_id'] != "")) {

	//On verifie si le mot de passe a 6 caracteres ou plus
	if(strlen($_POST['user_password'])>=6)
		{
			//On verifie si lemail est valide
			if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['user_email']))
			{
			//On verifie sil ny a pas deja un utilisateur inscrit avec le pseudo choisis
				$dn = mysql_num_rows(mysql_query('SELECT user_id FROM  users WHERE user_login="'. $_POST['user_login'] .'"'));
				if($dn==0)
				{
				//On verifie sil ny a pas deja un utilisateur inscrit avec le pseudo choisis
					$dn2 = mysql_num_rows(mysql_query('SELECT email FROM  users WHERE email="'. $_POST['email'] .'"'));
					if($dn2==0)
					{
					$insertSQL = sprintf("INSERT INTO users (user_id, user_name, user_lastname, user_login, user_password, user_groupe_id, structure_id, display, dateCreation, user_question, user_answer) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
									   GetSQLValueString($_POST['user_id'], "int"),
									   GetSQLValueString($_POST['user_name'], "text"),
									   GetSQLValueString($_POST['user_lastname'], "text"),
									   GetSQLValueString($_POST['user_login'], "text"),
									   GetSQLValueString(md5($_POST['user_password']), "text"),
									   GetSQLValueString($_POST['user_groupe_id'], "int"),
									   GetSQLValueString($_POST['structure_id'], "int"),
									   GetSQLValueString($_POST['display'], "text"),
									   GetSQLValueString($date, "date"),
									   GetSQLValueString($_POST['user_question'], "text"),
									   GetSQLValueString($_POST['user_answer'], "text"));
				
				  mysql_select_db($database_MyFileConnect, $MyFileConnect);
				  if (mysql_query($insertSQL, $MyFileConnect)){
					  $user_id = MinmapDB::getInstance()->get_user_id_by_name($_POST['user_login']);
					  $insertSQL1 = sprintf("INSERT INTO commentaires (destinataire_id, objet, expediteur_id, `comment`, notation, dateCreation) VALUES (%s, %s, %s, %s, %s, %s)",
										   GetSQLValueString(12, "int"),
										   GetSQLValueString("Demande de validation de compte", "text"),
										   GetSQLValueString($user_id, "int"),
										   GetSQLValueString("Demande validataion de compte", "text"),
										   GetSQLValueString(0, "int"),
										   GetSQLValueString($date, "date"));
					
					  mysql_select_db($database_MyFileConnect, $MyFileConnect);
					  $Result2 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
					  $form = false;
?>
<div class="message">Vous avez bien été inscrit. Vous pouvez dorénavant vous connecter.<br />
<a href="connexions.php">Se connecter</a></div>
<?php	
					} 
					else 
					{
						//Sinon on dit quil y a eu une erreur
						$form = true;
						$message = 'Une erreur est survenue lors de l\'inscription.';
					}
				}
				else
				{
					//Sinon, on dit que le pseudo voulu est deja pris
					$form = true;
					$message = 'Cet email est dejà utilisé par un autre utilisateur.';
				}
				}
				else
				{
					//Sinon, on dit que le pseudo voulu est deja pris
					$form = true;
					$message = 'Un autre utilisateur utilise déjà le nom d\'utilisateur que vous désirez utiliser.';
				}
			}
			else
			{
				//Sinon, on dit que lemail nest pas valide
				$form = true;
				$message = 'L\'email que vous avez entr&eacute; n\'est pas valide.';
			}
			
		}
		else
		{
			//Sinon, on dit que le mot de passe nest pas assez long
			$form = true;
			$message = 'Le mot de passe que vous avez entr&eacute; contien moins de 6 caract&egrave;res.';
		}

  /*$insertGoTo = "msg.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];*/
  }
  else
  {
	$form = true;
  }
  
 /* header(sprintf("Location: %s", $insertGoTo));
}*/

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructures = "SELECT structure_id, structure_lib FROM structures WHERE type_structure_id = 1 ORDER BY structure_lib ASC";
$rsStructures = mysql_query($query_rsStructures, $MyFileConnect) or die(mysql_error());
$row_rsStructures = mysql_fetch_assoc($rsStructures);
$totalRows_rsStructures = mysql_num_rows($rsStructures);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
<link rel="stylesheet" href="css/structure.css" type="text/css" />
<link rel="stylesheet" href="css/form.css" type="text/css" />
<link rel="stylesheet" href="css/theme.css" type="text/css" />

</head>

<body>
<h1>&nbsp;</h1>
<?php
if($form)
{
	//On affiche un message sil y a lieu
	if(isset($message))
	{
		echo '<div class="message">'.$message.'</div></BR>';
	}
	//On affiche le formulaire
?>
<div id="container">
  <h1><a id="logo" href="">formulaire d'enregistrement:</a></h1>

<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nom / <em>Last name</em>(*):</td>
      <td><input type="text" name="user_name" value="<?php echo $_POST['user_name']; ?>" size="32" /></BR>
	  <?php 
	  	if (isset($_POST['user_name']) && $_POST['user_name'] == ""){
			echo 'Saisir votre nom SVP!!!';
		}
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Prenom / <em>First Name</em>:</td>
      <td><input type="text" name="user_lastname" value="<?php echo $_POST['user_lastname']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Nom utilisateur / <em>Login</em> (*):</td>
      <td><input type="text" name="user_login" value="<?php echo $_POST['user_login']; ?>" size="32" /></BR>
	  <?php 
	  	if (isset($_POST['user_login']) && $_POST['user_login'] == ""){
			echo 'Champ Login obligatoire SVP!!!';
		}
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Mot de Passe / <em>Password</em> (*):</td>
      <td><input type="password" name="user_password" value="" size="32" /> 
        (au moins 6 caracteres)</BR>
	  <?php 
	  	if (isset($_POST['user_password']) && $_POST['user_password'] == ""){
			echo 'Champ Mot de passe obligatoire SVP!!!';
		}
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Confirmer le mot  de Passe / <em>Confirm password</em>:</td>
      <td><input type="password" name="user_password2" value="" size="32" /></BR>
	  <?php 
	  	if (isset($_POST['user_password']) && $_POST['user_password'] != $_POST['user_password2']){
			echo 'Vos mots de passes ne coincident pas, bien vouloir les entrer à nouveau';
		}
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Email (*)</td>
      <td><input type="text" name="user_email" value="<?php echo $_POST['user_email']; ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Structures/Structure (*):</td>
      <td><select name="structure_id">
        <option value=""  <?php if (!(strcmp("", $_POST['structure_id']))) {echo "selected=\"selected\"";} ?>>[ Select... ]</option>
        <?php
do {  
?>
        <option value="<?php echo $row_rsStructures['structure_id']?>"<?php if (!(strcmp($row_rsStructures['structure_id'], $_POST['structure_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsStructures['structure_lib']?></option>
        <?php
} while ($row_rsStructures = mysql_fetch_assoc($rsStructures));
  $rows = mysql_num_rows($rsStructures);
  if($rows > 0) {
      mysql_data_seek($rsStructures, 0);
	  $row_rsStructures = mysql_fetch_assoc($rsStructures);
  }
?>
      </select>
        <?php 
	  	if (isset($_POST['structure_id']) && $_POST['structure_id'] == ""){
			echo 'Selection structure obligatoire SVP!!!';
		}
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Question Secrete / Secrete question (*):</td>
      <td><select name="user_question" id="select">
        <option>[Select...]</option>
        <option value="Le prenom de votre compagne">Le prenom de votre compagne</option>
        <option value="Le prenom de votre premie	r enfant">Le prenom de votre premier enfant</option>
        <option value="Le prenom de votre grande mere">Le prenom de votre grande mere</option>
        <option value="Votre mot de passe yahoo.mail">Votre mot de passe yahoo.mail</option>
        <option value="Le code de votre smart phone">Le code de votre smart phone</option>
      </select>        
        <?php 
	  	if (isset($_POST['structure_id']) && $_POST['structure_id'] == ""){
			echo 'Selection structure obligatoire SVP!!!';
		}
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Reponse / answer<em></em> (*):</td>
      <td><input type="password" name="user_answer" value="<?php echo $_POST['user_answer']; ?>" size="32" /></BR>
	  <?php 
	  	if (isset($_POST['user_login']) && $_POST['user_login'] == ""){
			echo 'Champ Login obligatoire SVP!!!';
		}
	  ?></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Enregistrer" />
        <a href="connexions.php">Revenir à l'interface de connexion</a></td>
    </tr>
  </table>
  <input type="hidden" name="user_id" value="" />
  <input type="hidden" name="user_groupe_id" value="2" />
  <input type="hidden" name="display" value="0" />
  <input type="hidden" name="dateCreation" value="" />
  <input type="hidden" name="dateUpdate" value="" />
  <input type="hidden" name="MM_insert" value="form1" />
</form>

</div>
<?php
}
?>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($rsStructures);
?>
