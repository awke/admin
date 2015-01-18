<?php
$HEADER=0;
include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$domainid=$_SESSION[secstore][domains][domainid];
unset($_SESSION[secstore][domains]);  
$password1=passgen();
$password2=passgen();
$date=date('YmdHis');

$sql="SELECT description, userid, username FROM domains WHERE D_ID='$domainid'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
$description="***DISABLED*** " . "userid was " . $row[userid] . " username was " .$row[username] . " description was " . $row[description];

$sql="UPDATE domains SET deactivated='1', username='', userid='65535', description='$description', password1='$password1', password2='$password2', nodns='1', nocreateconfigs='1', nowwwconfig='1', noftpconfig='1', nomaillistconfig='1', nosqlconfig='1', lastupdated='$date' WHERE D_ID='$domainid'";
gosql($sql,0);
$sql="UPDATE dnsrecords SET nocreate='1' WHERE domain_id='$domainid'";
gosql($sql,0);
$sql="UPDATE ftpusers SET deactivated='1' WHERE domain='$domainid'";
gosql($sql,0);
$sql="UPDATE emailusers SET deactivated='1' WHERE domain='$domainid'";
gosql($sql,0);
$sql="UPDATE aliases SET disabled='1' WHERE domain='$domainid'";
gosql($sql,0);
$sql="DELETE FROM userpriv WHERE D_ID='$domainid'";
gosql($sql,0);
$sql="DELETE FROM ftpgroup WHERE domain='$domainid'";
gosql($sql,0);

$sql="INSERT INTO superusercommands SET command='0', parameters='$domainid', lastupdated='$date'";
gosql($sql,0);



header("Location: domain.php");
?>


