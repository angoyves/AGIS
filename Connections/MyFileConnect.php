<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$insertGoTo = "connect_failed.php";
  
$hostname_MyFileConnect = "localhost";
//$database_MyFileConnect = "not_file";
$database_MyFileConnect = "fichier_db8";
$username_MyFileConnect = "root";
$password_MyFileConnect = "";
$MyFileConnect = mysql_pconnect($hostname_MyFileConnect, $username_MyFileConnect, $password_MyFileConnect) or trigger_error(mysql_error(),E_USER_ERROR); 
//header(sprintf("Location: %s", $insertGoTo));

if (!isset($_SESSION)) {
  session_start();
}
?>