<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$array=retedittable("add",0);

//print_r($array);
//exit();

if($array[0]==1)
{

if (strcmp($array[1][name],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter Webhost name";
}
if (ereg("[[:space:]]", $array[1][name]))
{ 
$error=1;
$_SESSION[error][]="Please correct Webhost name";
}


$name= $array[1][name];
$sql="SELECT * FROM webhosts WHERE D_ID='$_INFO[D_ID]' AND name='$name'";
$result=gosql($sql,0);
$numrows = mysql_num_rows($result);

if ($numrows > 0 ) {
$error=1;
$_SESSION[error][]="Webhost (subdomain) name already exists, please enter a different one";
}

/*
$error=1;
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins." D_ID='$_INFO[D_ID]', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}

$_SESSION[error][]="FINISHED";
$_SESSION[error][]=$sqlins;
$_SESSION[error][]="<PRE>";
$_SESSION[error][]=print_r($array,TRUE);
$_SESSION[error][]="</PRE>";
*/

if($error==0)
{
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins." D_ID='$_INFO[D_ID]', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}

$sql="INSERT INTO webhosts SET $sqlins";
gosql($sql,0);
$sql="INSERT INTO superusercommands SET command='1', parameters='$_INFO[D_ID]'";
gosql($sql,0);


clredittable("add",0);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");

}
else
{//error=1 therefor error


header("Location: webhostadd.php");
exit();
}

}
?>
