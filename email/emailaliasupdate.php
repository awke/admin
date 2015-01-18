<?php
$HEADER=0;
include('functions.inc');
sqlconn();
$aliasid=$_SESSION[secstore][alias];
unset($_SESSION[secstore][editalias]); 
$array=retedittable("add",0);
if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

if (strcmp($array[1][alias],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter alias";
}
if (ereg("[[:space:]]", $array[1][alias]))
{ 
$error=1;
$_SESSION[error][]="Please correct alias";
}

if (strcmp($array[1][aliased],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter aliased";
}
if (ereg("[[:space:]]", $array[1][aliased]))
{ 
$error=1;
$_SESSION[error][]="Please correct aliased";
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
$sql="UPDATE aliases SET $sqlins WHERE A_ID='$aliasid'";
gosql($sql,0);
clredittable("add",0);
header("Location: emailuser.php");

}
else
{//error=1 therefor error
$_SESSION[secstore][editalias][aliaserrid]=$aliasid;
header("Location: emailaliasedit.php");
exit();
}

}
?>
