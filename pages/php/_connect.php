<?php
session_start();

$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_pass = '';
$mysql_db = 'stdbOrig';
$conn = mysql_connect($mysql_host, $mysql_user, $mysql_pass);
if(!$conn || !mysql_select_db($mysql_db)) {
  die(mysql_error());
}
mysql_set_charset('utf8', $conn);
?>
