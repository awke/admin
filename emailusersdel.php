<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$passedid=$_GET[id];
$email_id=$_SESSION[secstore][editemail][$passedid];
unset($_SESSION[secstore][editemail]);

$sql="DELETE FROM emailusers WHERE EU_ID='$email_id'";
gosql($sql);

sqlconn();
$sql="SELECT email FROM emailusers WHERE domain='$_INFO[D_ID]'";
$result=gosql($sql,0);
if (mysql_num_rows($result) == 0)
{
$_SESSION[secstore][editemail][deleted]=1;
}


header("Location: emailusers.php");
?>




