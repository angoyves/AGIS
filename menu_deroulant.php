<html> 
<title>liste déroulante dynamique</title> 
<head> 
<script language="Javascript" type="text/javascript" > 
function choix(formulaire) 
{ 
var j; 
var i = formulaire.boite1.selectedIndex; 
if (i == 0) 
for(j = 1; j <3; j++) 
formulaire.boite2.options[j].text=""; 


else{ 
switch (i){ 
case 1 : var text = new Array( "Marseille","PSG","Monaco"); 
break; 
case 2 : var text = new Array("Toulouse","Agen","Paris"); 
break; 

case 3 : var text = new Array("Dijon","Pau","Gravelines"); 
break; 
} 

for(j = 0; j<3; j++) 
formulaire.boite2.options[j+1].text=text[j];	
} 
formulaire.boite2.selectedIndex=0; 
} 
</script> 
</head> 
<body> 
<form name="formulaire"> 
<select name="boite1" onChange="choix(this.form)"> 
<option selected>...........Choisissez une rubrique...........</option> 
<option>foot</option> 
<option>rugby</option> 
<option>basket</option> 

</select> 

<select name="boite2"> 
<option selected>...........Choisissez une rubrique...........</option> 
<option value="index.php"></option> 
<option></option> 
<option></option> 
</select> 
</form> 
</body> 
</html>