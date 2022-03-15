<script type="text/javascript">
<!--
//init variable JS globale, elle conserve sa valeur entre chaque appel de la fonction
//c a d après le premier appel elle va passer à 1 etc...
i=0;
///
function create_champ(){
	//on increment la variable globale i, numero de produit
	i++;	
	// en JS on peut atteindre les elements de la page HTML par document.getElementById
	// creation de l'objet tableau avec lequeml on va travailler
    var obj_tableau=document.getElementById("tableau_produits"); 
	//lecture des lignes du tableau actuel, JS le met dans un array
	var arrayLignes = obj_tableau.rows;
	//pour avoir le nombre de lignes, avec  .length on a la taille de l'array
	var nbr_de_lignes=arrayLignes.length;
	//on insere une nouvelle ligne de tableau juste avant celle contenant le bouton
	var nouvelleLigne = obj_tableau.insertRow(nbr_de_lignes-1);
	//ensuite on remplit chacune des cellules <td></td> du tableau avec les input
	var colonne1=nouvelleLigne.insertCell(0);
	colonne1.innerHTML="Produit "+i;
	var colonne2=nouvelleLigne.insertCell(1);
	colonne2.innerHTML='<input type="text" name="lib_produit['+i+']" value="" />';
	var colonne3=nouvelleLigne.insertCell(2);
	colonne3.innerHTML='<input type="text" name="qte_produit['+i+']" value="" />';
	var colonne4=nouvelleLigne.insertCell(3);
	colonne4.innerHTML='<input type="text" name="prix_produit['+i+']" value="" />';
	//tu peux rajouter des colonnes, modifier le name, mais garde sa forme name="lib_produit['+i+']"
	//ainsi tu pourra facilement récuper par $_POST['lib_produit'] qui est un array, donc boucler pour insertiondans BDD
}
-->
</script>
</head>
<body onload="javascript:create_champ();">
<!-- au chargement de la page on ajoute la premiere ligne produit -->
<form name="form_ajaout_cde" method="post" action="" >
<table width="800" id="tableau_produits" >
    <tr>
        <th>Produit</th>
        <th>Libelle</th>
        <th>Quantité</th>
        <th>Prix</th>
        
    </tr>
    <tr>
        <td colspan="4">
			<input name="button" type="button" class="input2" onClick="javascript:create_champ()" value="Ajouter une ligne produit">
		</td>
    </tr>
</table>

</form>
</body>
</html>