<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$passedid=$_GET[id];
$ftp_id=$_SESSION[secstore][editftp][$passedid];
unset($_SESSION[secstore][editftp]);

$sql="DELETE FROM ftpusers WHERE FTPU_ID='$ftp_id'";
gosql($sql);

sqlconn();
$sql="SELECT username FROM ftpusers WHERE domain='$_INFO[D_ID]'";
$result=gosql($sql,0);
if (mysql_num_rows($result) == 0)
{
$_SESSION[secstore][editftp][deleted]=1;
}


header("Location: ftpusers.php");
?>




