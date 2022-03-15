<html>
<head>
<script type="text/javascript">
<!--
function redirection()
{
	if (confirm("Bienvenue visiteur !\n[Ok] = Redirection vers google\n[Annuler] = Redirection vers lycos")) 
	{
		document.location.href='http://www.google.fr';
	}
	else
	{
		document.location.href='http://www.lycos.fr';
	}
	
}

// -->
</script>
</head>
<body>
<form>
<input type="button" value="Test redirection" onclick="redirection()">
</form>
</body>
</html>