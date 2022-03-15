<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<script type="text/javascript">
    function addInputFile(){
        var inputs = document.getElementsByTagName('input');
        var nbInputFile = 0;
        var nbFile = 0;
        // boucle de vérification du nombre d'input type file total
            // et du nombre d'input type file renseignés
        for (i=0;i!=inputs.length;i++) {
        input = inputs[i];
        if (input.type == 'file') {
            nbInputFile++;
            if (input.value.length > 0) {
            nbFile++;
            }
        }
        }
        //Si tous les inputs type file sont renseignés, on en ajoute un
        if (nbInputFile == nbFile) {
        tbody = document.getElementById("inputsTable").firstChild;
        ligne = document.createElement('tr');
        cellule1 = document.createElement('td');
        cellule1.setAttribute('class','label');
        label = document.createElement('label');
        label.setAttribute('for','fichier'+nbInputFile);
        labelText = document.createTextNode('');
        labelText.data = 'Fichier à charger';
        cellule2 = document.createElement('td');
        inputFile = document.createElement('input');
        inputFile.setAttribute('type','file');
        inputFile.setAttribute('id','fichier'+nbInputFile);
        inputFile.setAttribute('size','20');
        inputFile.setAttribute('name','fichier'+nbInputFile);
        //inputFile.onchange = addInputFile;
        label.appendChild(labelText);
        cellule1.appendChild(label);
        cellule2.appendChild(inputFile);
        ligne.appendChild(cellule1);
        ligne.appendChild(cellule2);
        tbody.appendChild(ligne);
        return true;
        }  
    }
function checkCheckBox(ele) {
 
    if (ele.checked == false ) {
        var div = document.getElementById("ajout");
        div.removeChild(div.firstChild);
    return false;
    }
    else {
        var inputFiles = document.createElement("input");
		var input = document.createElement("input");
        inputFiles.setAttribute('type','file');
        inputFiles.setAttribute('id','fichier');
        inputFiles.setAttribute('size','20');
        inputFiles.setAttribute('name','fichier');
        document.getElementById("ajout").appendChild(inputFiles);
		document.getElementById("ajout").appendChild(input);

    }
    return true;
     
}
</script>
<input type="checkbox" value="0" name="oui" onclick="checkCheckBox(this)" > Coché ce que vous voulez
<div id="ajout"></div>
</body>
</html>