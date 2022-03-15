<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form method="post" action="" >
    <div id="champs" >
        <input type="text" name="titre[]" />
        <input type="file" name="contenu[]" />
        <input type="text" name="description[]" />
        </BR>
    </div>
  <script type="text/javascript" >
        var div = document.getElementById('champs');
        function addInput(nam){
            var input = document.createElement("input");
            input.name = name;
            div.appendChild(input);
        }
        function addField() {
            addInput("titre[]");
            addInput("contenu[]");
            addInput("description[]");
            div.appendChild(document.createElement("br"));
        }
    </script>
    <button type="button" onclick="addField()" >+</button>
    <input type="submit" />
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
    <script type="text/javascript">
      window.onload = function(){
        document.getElementById("gAttachBI").innerHTML='';
        var newElem = document.createElement('input');
        newElem.setAttribute("id", "attach");
        newElem.setAttribute("type", "file");
        document.getElementById("gAttachBI").appendChild(newElem);
      }
      function Validate(){
        if(document.msgform.attach.value.length == 0){
          alert('You need to select a file to upload');
          return false;
        }
        return true;
      }
    </script>
    <form name="msgform" onsubmit="return Validate()">
      <div id="gAttachBI">
      </div>
      <input type="submit" name="btnSubmit" value="upload">
    </form>
</body>
</html>