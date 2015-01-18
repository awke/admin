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

if (strcmp($array[1][alias],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter an alias";
}
if (ereg("[[:space:]]", $array[1][alias]))
{ 
$error=1;
$_SESSION[error][]="Please correct alias";
}

if (strcmp($array[1][aliased],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter email user";
}
if (ereg("[[:space:]]", $array[1][aliased]))
{ 
$error=1;
$_SESSION[error][]="Please correct email user";
}

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

$sql="INSERT INTO aliases SET $sqlins";
gosql($sql,0);
clredittable("add",0);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");

}
else
{//error=1 therefor error

header("Location: emailaliasadd.php");
exit();
}

}
?>
