<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<script type="text/javascript">
var inputs = 0;
function addContact(){
	var table = document.getElementById('contacts');

	var tr    = document.createElement('TR');
	var td1   = document.createElement('TD');
	var td2   = document.createElement('TD');
	var td3   = document.createElement('TD');
	var td4   = document.createElement('TD');
	var inp1  = document.createElement('INPUT');
	var inp2  = document.createElement('INPUT');
	var inp3  = document.createElement('INPUT');

	if(inputs>0){
		var img     = document.createElement('IMG');
		img.setAttribute('src', 'delete.gif');
		img.onclick = function(){
			removeContact(tr);
		}
		td1.appendChild(img);
	}

	inp1.setAttribute("Name", "ref" +inputs);
	inp2.setAttribute("Name", "fichier"+inputs);
	inp2.setAttribute("type", "file");
	inp3.setAttribute("Name", "observ" +inputs);
	table.appendChild(tr);
	tr.appendChild(td1);
	tr.appendChild(td2);
	tr.appendChild(td3);
	tr.appendChild(td4);
	td2.appendChild(inp1);
	td3.appendChild(inp2);
	td4.appendChild(inp3);

	inputs++;
}
function removeContact(tr){
	tr.parentNode.removeChild(tr);
}

</script>
<form name="form1" method="post" action="">
<table>
   <tbody id="contacts">
      <tr>
         <td colspan="4"><a href="javascript:addContact();">Ajouter dossier</a></td>
      </tr>
      <tr align="left">
         <th>&nbsp;</th>
         <th>Reference</th>
         <th>Fichier</th>
         <th>Observation</th>
      </tr>
   </tbody>
</table>
<blockquote>
  <p>
    <input name="btn_valid" type="submit" value="Envoyer" />
  </p>
</blockquote>
</form>
</body>
</html>