<?php

if (isset($_FILES['csv_file']))
{
  $handle = fopen($_FILES['tmp_name'], 'r');
  $data = array(); // le tableau qui va contenir nos données
  $keys = explode(';', fgets($handle));
  while ($line = fgets($handle))
  {
     $data[] = array_combine($keys, explode(';', $line));
  }
 
  fclose($handle);
  var_dump($data);
}

?>