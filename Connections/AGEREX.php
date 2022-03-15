<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_AGEREX = "localhost";
$database_AGEREX = "experts_db";
$username_AGEREX = "root";
$password_AGEREX = "";
$AGEREX = mysql_pconnect($hostname_AGEREX, $username_AGEREX, $password_AGEREX) or trigger_error(mysql_error(),E_USER_ERROR); 
?>