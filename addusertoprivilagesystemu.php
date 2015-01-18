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

if (strcmp($array[1][username],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter a username";
}
if (ereg("[[:space:]]", $array[1][username]))
{ 
$error=1;
$_SESSION[error][]="Please correct username";
}
$username = $array[1][username];
$sql = "SELECT username FROM authusers WHERE username='$username'";
$results = mysql_query($sql);
$num_rows = mysql_num_rows($results);
if ($num_rows != 0)
	{
	$error=1;
	$_SESSION[error][]="Username is not available, please try another";
	}

if (strcmp($array[1][description],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter description";
}
	
if (strcmp($array[1][passwd],"")==0)
{
$array[1][passwd]=passgen();
$_SESSION[privsyspass] = $array[1][passwd];
}

if (ereg("[[:space:]]", $array[1][passwd]))
{ 
$error=1;
$_SESSION[error][]="Please correct password";
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

$sql="INSERT INTO authusers SET $sqlins";
gosql($sql,0);
clredittable("add",0);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");
}
else
{//error=1 therefor error

header("Location: adduserstoprivilagesystem.php");
exit();
}

}
?>