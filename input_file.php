<html>
    <head>
        <script>
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
        inputFile.onchange = addInputFile;
        label.appendChild(labelText);
        cellule1.appendChild(label);
        cellule2.appendChild(inputFile);
        ligne.appendChild(cellule1);
        ligne.appendChild(cellule2);
        tbody.appendChild(ligne);
        return true;
        }  
    }
    </script>
    </head>
    <body>
    <table class="detail" id="inputsTable" border="0"><tbody>
        <tr>
        <td class=label><label for=fichier>Fichier à charger</label></td>
        <td><input id=fichier onchange=addInputFile() size=20 type=file name=fichier /></td>
        </tr>
    </tbody></table
    </body>
</html>