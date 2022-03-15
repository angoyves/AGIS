<?php require_once('Connections/MyFileConnect.php'); ?>
<?php require_once('includes/MyFonction.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
if (isset($_POST['personne_id1']) && isset($_POST['fonction_id1'])) {
 $insertSQL1 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id1'], "int"),
                       GetSQLValueString($_POST['personne_id1'], "int"),
					   GetSQLValueString("un", "text"),
                       GetSQLValueString(1, "int"));  }

 if (isset($_POST['personne_id2']) && isset($_POST['fonction_id2'])) {
 $insertSQL2 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id2'], "int"),
                       GetSQLValueString($_POST['personne_id2'], "int"),
					   GetSQLValueString("deux", "text"),
                       GetSQLValueString(2, "int")); }

if (isset($_POST['personne_id3']) && isset($_POST['fonction_id3']))	{				   
  $insertSQL3 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id3'], "int"),
                       GetSQLValueString($_POST['personne_id3'], "int"),
					   GetSQLValueString("trois", "text"),
                       GetSQLValueString(3, "int")); }

if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{				   
  $insertSQL4 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id4'], "int"),
                       GetSQLValueString($_POST['personne_id4'], "int"),
					   GetSQLValueString("quatre", "text"),
                       GetSQLValueString(4, "int")); }

if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{				   
  $insertSQL5 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id5'], "int"),
                       GetSQLValueString($_POST['personne_id5'], "int"),
					   GetSQLValueString("cinq", "text"),
                       GetSQLValueString(5, "int")); }

if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{			   
 $insertSQL6 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id6'], "int"),
                       GetSQLValueString($_POST['personne_id6'], "int"),
					   GetSQLValueString("six", "text"),
                       GetSQLValueString(6, "int")); }

if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{			   
 $insertSQL7 = sprintf("INSERT INTO membres (commissions_commission_id, fonctions_fonction_id, personnes_personne_id, checboxName, position) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['commission_id'], "int"),
                       GetSQLValueString($_POST['fonction_id7'], "int"),
                       GetSQLValueString($_POST['personne_id7'], "int"),
					   GetSQLValueString("sept", "text"),
                       GetSQLValueString(7, "int")); } 
					   
 $updateSQL = sprintf("UPDATE commissions SET membre_insert=%s WHERE commission_id=%s",
                       GetSQLValueString($_POST['membre_insert'], "text"),
                       GetSQLValueString($_POST['commission_id'], "int"));
					   
 $updateSQL1 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id1'], "int"));
					   
 $updateSQL2 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id2'], "int"));
					   
 $updateSQL3 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id3'], "int"));
					   
if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{					   
 $updateSQL4 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id4'], "int"));
}
if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{					   
 $updateSQL5 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id5'], "int"));
}
if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{
 $updateSQL6 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id6'], "int"));
}
if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{
 $updateSQL7 = sprintf("UPDATE personnes SET add_commission='2' WHERE personne_id=%s",
                       GetSQLValueString($_POST['personne_id7'], "int"));	
}

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
if (isset($_POST['personne_id1']) && isset($_POST['fonction_id1']))	{
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id2']) && isset($_POST['fonction_id2']))	{	  
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id3']) && isset($_POST['fonction_id3']))	{	  
  $Result3 = mysql_query($insertSQL3, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{	  
  $Result4 = mysql_query($insertSQL4, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{	  
  $Result5 = mysql_query($insertSQL5, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{	  
  $Result6 = mysql_query($insertSQL6, $MyFileConnect) or die(mysql_error()); }
  if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{	
  $Result7 = mysql_query($insertSQL7, $MyFileConnect) or die(mysql_error()); }

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
if (isset($_POST['personne_id1']) && isset($_POST['fonction_id1']))	{	
  $Results1 = mysql_query($updateSQL1, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id2']) && isset($_POST['fonction_id2']))	{	
  $Results2 = mysql_query($updateSQL2, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id3']) && isset($_POST['fonction_id3']))	{	
  $Results3 = mysql_query($updateSQL3, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id4']) && isset($_POST['fonction_id4']))	{	
  $Results4 = mysql_query($updateSQL4, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id5']) && isset($_POST['fonction_id5']))	{	
  $Results5 = mysql_query($updateSQL5, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id6']) && isset($_POST['fonction_id6']))	{	
  $Results6 = mysql_query($updateSQL6, $MyFileConnect) or die(mysql_error()); }
if (isset($_POST['personne_id7']) && isset($_POST['fonction_id7']))	{	
  $Results7 = mysql_query($updateSQL7, $MyFileConnect) or die(mysql_error()); }
					   


  $insertGoTo = "new_membres.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}