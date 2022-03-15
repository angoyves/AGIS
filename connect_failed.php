<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>::AGIS::</title>
</head>

<body>
<p><img src="/images/img/s_cancel.png" width="16" height="16" />
<?php 
//$fichierlog = file_get_contents('C:/wamp/logs/mysql.log'); 
echo $fichierlog;

$file = file("C:/wamp/logs/mysql.log");
for ($i = count($file)-30; $i < count($file); $i++) {
  echo $file[$i] . "\n";
} 
?>

</body>
</html>