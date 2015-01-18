<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$passedid=$_GET[id];
$ftp_id=$_SESSION[secstore][editdns][$passedid];
unset($_SESSION[secstore][editdns]);

$sql="DELETE FROM dnsrecords WHERE id='$ftp_id'";
gosql($sql);

sqlconn();
if (mysql_num_rows($result) == 0)
{
$_SESSION[secstore][editdns][deleted]=1;
}


header("Location: dns.php");
?>




