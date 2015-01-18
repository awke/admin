<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$array=retedittable("add",0);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

if (strcmp($array[1][email],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter email";
}
if (ereg("[[:space:]]", $array[1][email]))
{ 
$error=1;
$_SESSION[error][]="Please correct email";
}

if (strcmp($array[1][p1clear],"")==0)
{
$array[1][p1clear]=passgen();
}
if (ereg("[[:space:]]", $array[1][p1clear]))
{ 
$error=1;
$_SESSION[error][]="Please correct password 1";
}

if (strcmp($array[1][p2clear],"")==0)
{
$array[1][p2clear]=passgen();
}
if (ereg("[[:space:]]", $array[1][p2clear]))
{ 
$error=1;
$_SESSION[error][]="Please correct password 2";
}

$array[1][maildir] = $array[1][email];

if($error==0)
{
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins." domain='$_INFO[D_ID]', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}

$sql="INSERT INTO emailusers SET $sqlins";
gosql($sql,0);
clredittable("add",0);
confirm_mail($array[1][email],$_INFO[D_ID]);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");
}
else
{//error=1 therefor error

header("Location: emailusersadd.php");
exit();
}

}

function confirm_mail($user, $domain)
{
$sql="SELECT domainn FROM domains WHERE D_ID='$domain'";
$result=gosql($sql,0);
$row = mysql_fetch_assoc($result);
$to="$user"."@"."$row[domainn]";
$headers="From: \"AWKE Admin\" <admin@awke.co.uk>\r\n";
$subject='Welcome to AWKE';

$body=file_get_contents("newusersemail.txt");

$from="admin@awke.co.uk";
sock_mail($auth,$to, $subject, $body, $headers, $from);
}
?>
