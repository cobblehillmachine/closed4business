<?php
$host = 'localhost'; // MYSQL database host adress
$db = 'closed_wp'; // MYSQL database name
$user = 'closed_wp'; // Mysql Datbase user
$pass = '?2W57wtpi8IV'; // Mysql Datbase password
 
// Connect to the database
$link = mysql_connect($host, $user, $pass);
mysql_select_db($db);

include 'exportmailinglistcsv.php';
$table = "cfb_newsletter"; // this is the tablename that you want to export to csv from mysql.
exportMysqlToCsv($table);
?>