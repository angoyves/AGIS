<?php require_once('Connections/MyFileConnect.php');
	  require_once('includes/db.php');
	  require_once('includes/MyFonction.php'); ?>
<?php
/*if (!function_exists("GetSQLValueString")) {
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
$personne_nom = $_POST['personne_nom'];
$structure_id = GetSQLValueString($_POST['structure_id'], "int");

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
$insertSQL = sprintf("INSERT INTO personnes (personne_id, personne_matricule, personne_nom, personne_prenom, personne_grade, personne_telephone, structure_id, sous_groupe_id, fonction_id, domaine_id, user_id, personne_date_nais, type_personne_id, localite_id, add_commission, date_creation, dateUpdate, display) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['personne_id'], "int"),
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString($_POST['personne_nom'], "text"),
                       GetSQLValueString($_POST['personne_prenom'], "text"),
                       GetSQLValueString($_POST['personne_grade'], "text"),
                       GetSQLValueString($_POST['personne_telephone'], "text"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['sous_groupe_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($_POST['domaine_id'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['personne_date_nais'], "date"),
                       GetSQLValueString($_POST['type_personne_id'], "int"),
                       GetSQLValueString($_POST['localite_id'], "text"),
                       GetSQLValueString($_POST['add_commission'], "text"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['dateUpdate'], "date"),
                       GetSQLValueString($_POST['display'], "text"));

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$Result = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
  
$personne = MinmapDB::getInstance()->get_personne_id_by_name_and_structure($personne_nom, $structure_id);

$insertSQL1 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, montant, checboxName, `position`, display, dateCreation) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_GET['comID'], "int"),
                       GetSQLValueString($_POST['fonctions_fonction_id'], "int"),
                       GetSQLValueString($personne, "int"),
                       GetSQLValueString($_POST['montant'], "text"),
                       GetSQLValueString($_POST['checboxName'], "text"),
                       GetSQLValueString($_POST['position'], "text"),
                       GetSQLValueString(1, "text"),
                       GetSQLValueString($date, "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
} */

/*
$date = date('Y-m-d H:i:s');

echo "ID" . $_POST['personne_id'] . " Matricule : " . $_POST['personne_matricule'] . " " . $_POST['personne_nom'] . " " . $_POST['personne_prenom'] . " " . $_POST['personne_grade'] . " " . $_POST['personne_telephone'] . " " . $_POST['structure_id'] . " " . $_POST['sous_groupe_id'] . " " . $_POST['fonction_id'] . " " . $_POST['domaine_id'] . " " . $_POST['user_id'] . " " . $_POST['personne_date_nais'] . " " . $_POST['type_personne_id'] . " " . $_POST['lieu'] . " " . $_POST['add_commission'] . " Date :" . $date  . " " . $_POST['dateUpdate'] . " " . $_POST['display'];*/

  $insertGoTo = "sample16.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  //header(sprintf("Location: %s", $insertGoTo));
  //exit();
?>