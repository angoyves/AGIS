<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>APPLICATION DE GESTION DES INDEMNITES DE SESSION</title>
</head>

<body>
<form name="form1" action="" id="form1" method="post">
<table>
    <tr>
    	<td></td>
        <?php $count=0; do { $count++ ?>
    	<td>
          <select name="select_id[]" >
            <option value="0"></option>
            <?php $compter=0; do { $compter++; 
					foreach($_POST['select_id'] as $value){ $compteur++; ?>
            <option value="<?php $compteur == $count ? (print $value) : $compter; } ?>" 
			<?php /*foreach($_POST['select_id'] as $value){
					$compteur++;
					if (!(strcmp($compteur, $count))) {echo "SELECTED";}; 
				  }*/
		    ?>><?php echo $compter ?></option>
            <?php } while ($compter<10); ?>
          </select>
        </td>
        <?php } while ($count<10); ?>
    </tr>
</table>
<input type="submit" value="Envoyer" />
</form>
<?php
echo implode('**',$_POST['select_id']).'</BR>';
foreach($_POST['select_id'] as $values){
echo $values.'</BR>';
}
?>
</body>
</html>