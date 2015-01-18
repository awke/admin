<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$passedid=$_GET[id];
$alias_id=$_SESSION[secstore][editalias][$passedid];
unset($_SESSION[secstore][editalias]);

$sql="DELETE FROM aliases WHERE A_ID='$alias_id'";
gosql($sql);

sqlconn();
$sql="SELECT alias FROM aliases WHERE domain='$_INFO[D_ID]'";
$result=gosql($sql,0);
if (mysql_num_rows($result) == 0)
{
$_SESSION[secstore][editalias][deleted]=1;
}

header("Location: emailalias.php");
?>




