<?php
$HEADER=0;
include('functions.inc');
sqlconn();
$emailid = $_SESSION[emailid];

$array=retedittable("add",0);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

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

if($error==0)
{
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins;
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="UPDATE emailusers SET $sqlins WHERE EU_ID='$emailid'";
$_SESSION[password] = $array[1][p1clear];
gosql($sql,0);

clredittable("add",0);
header("Location: emailuser.php");
}
else
{//error=1 therefor error
header("Location: emailedit.php");
exit();
}

}
?>