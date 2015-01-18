<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$contents=$_POST['filters'];

$filename = 'filter.txt';
$handle = fopen($filename, 'w');
fwrite($handle, $contents);
fclose($handle);
	
header("Location: emailuser.php");
exit();
?>