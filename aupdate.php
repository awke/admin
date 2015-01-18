<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
//$customerid=$_SESSION[secdata][customers][index.php][dpid][$_GET[id]];
$customerid=$_SESSION[secdata][customers][edit.php][pid];
$array=retedittable("std",0);

//disparray($array,rebuiltform);

if($array[0]==1)
{
$first=0;
$sqlins="";
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$first=1;
	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="UPDATE accounts SET $sqlins WHERE idaccounts='$customerid'";
gosql($sql,0);
}
$_SESSION[error][]="YOU MAY NEED TO MANUALLY email users if you have changed invoice paid to inform the old user that it hasnt been paid.";

clredittable("std",0);
header("Location: aindex.php");

//disparray($_SESSION,SESSIONEND);



//phpinfo();
