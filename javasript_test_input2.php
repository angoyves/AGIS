<html> 
<head> 
<script type="text/javascript">
function Ajouter(){ 
var newRow = document.getElementById('matable').insertRow(-1); 
var newCell = newRow.insertCell(0); 
newCell.innerHTML = "<?php 
OpenStreamDB(); 
$sql = "SELECT * FROM produit'"; 
$res = mysql_query($sql); 
Disconnect(); 
echo "<select name='produit'>"; 
echo "<OPTION value='0'>Choisir un produit</OPTION>"; 
while($j = mysql_fetch_object($res)){ 
echo "<OPTION value='$j->id_bonus'>$j->desc_bonus</OPTION>"; 
} 
echo "</select>"; 
?>"; 
newCell = newRow.insertCell(1); 
newCell.innerHTML = "Prix <input type='text' name='prix' size='5'>"; 
}; 
</script>
</head> 
<body> 
<table id='matable'> 
</table> 
</body> 
</html> 