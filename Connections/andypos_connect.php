<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_andypos_connect = "localhost";
$database_andypos_connect = "andypos";
$username_andypos_connect = "root";
$password_andypos_connect = "12345678";
$andypos_connect = mysql_pconnect($hostname_andypos_connect, $username_andypos_connect, $password_andypos_connect) or trigger_error(mysql_error(),E_USER_ERROR); 
?>