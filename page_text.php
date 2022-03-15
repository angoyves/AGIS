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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE membres SET commissions_commission_id=%s, fonctions_fonction_id=%s, display=%s WHERE personnes_personne_id=%s",
                       GetSQLValueString($_POST['commissions_commission_id'], "int"),
                       GetSQLValueString($_POST['fonctions_fonction_id'], "int"),
                       GetSQLValueString($_POST['display'], "text"),
                       GetSQLValueString($_POST['personnes_personne_id'], "int"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());

  $updateGoTo = "todele.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_Membres = "SELECT personnes_personne_id FROM membres";
$Membres = mysql_query($query_Membres, $MyFileConnect) or die(mysql_error());
$row_Membres = mysql_fetch_assoc($Membres);
$totalRows_Membres = mysql_num_rows($Membres);
 $tab = array(1,2,3);
echo implode('**',$tab);
$count =0; do { 
//if (isset($_POST['select_id'.$count])){
//echo 'select_id'.$count.'</BR>';
//foreach($HTTP_POST_VARS['select_id'] as $value){
//$select_id .= $value."**";
$select_id = $_POST['select_id'];
if (isset($select_id[$count]) && $select_id[$count] != ""){
echo $select_id[$count]."</BR>";
$value .= $select_id[$count]."*";
$counter++;
}
//echo $select_id;
//}
$count++; } while($count<31);
echo $value.'</BR>';
echo $counter.'</BR>';
echo $values = explode('*',$value).'</BR>';
echo array_values($values);
echo 'values = '.implode('**',$values).'</BR>';

?>
<html> 
<head> 
<script language="JavaScript">

function Agree(Terms) { 
	checkobj = Terms; 
	if ((document.donnee.agree.checked == true) || (document.donnee.accept.checked == true))
		{ document.donnee.btn_valid.disabled = false; 
	} 
	else { document.donnee.btn_valid.disabled = true; 
	} 
} 

function go_go() 
{ 
window.open('http://www.paypal.com/cgi-bin/cmd?paye_ton_inscription_ici','_Blank','') 
} 
</script>

</head> 
<body>
<p>&nbsp;</p>
<!--<form name="donnee" action="Agree" method="post">--> 
<form name="donnee" action="" method="post">
  <p>
    <input name="agree" type="checkbox" onClick="Agree(this)"> 
    J'accepte...<br>
<input name="accept" type="checkbox" onClick="Agree(this)"> 
I agree...</p>
  <table border="1">
  	<tr>
    	<td>Libelle</td>
        <?php $var = 0; do { $var++; ?>
        <td>
        <select name="select_id[]" id="select">
        <option value=""></option>
        <?php foreach($_POST['select_id'] as $value){ ?>
        <?php $values = 0; do { $values++;  ?>
        <option value="<?php echo $values ?>"  <?php echo (($value == $values)? "selected='selected'":"") ?> ><?php echo $values ?></option>
        <?php } while ($values < 9) ?>
		<?php }// end if ?> 
        </select>
        </td>
		<?php } while($var <31) ?>
    </tr>
  </table>
  <p>
    <!--<input name="btn_valid" type="submit" value="Inscription" onclick="go_go()" disabled="true">-->
     <input name="btn_valid" type="submit" value="Inscription">
  </p>
  </p>
</form> 
</body> 
</html>
<?php
mysql_free_result($Membres);
?>
