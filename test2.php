<table border="1" align="center">
<?php
print "<tr>";
$counter=0; do  { $counter++;
    print "<td>$counter</td>";
}while ($counter<30);
 print "</tr><tr>";
$counter=0; do  { $counter++;
print "<td><input name=\"jour\" type=\"checkbox\" id=\"jour32\" value=\"$counter\" /></td>";
}while ($counter<30);
print"</tr>";
?>
</table>