<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
//$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];
$customerid=$_SESSION[secdata][customers][edit.php][pid];
$array=retedittable("pack",0);

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
$sql="UPDATE packages SET $sqlins WHERE idpackages='$customerid'";
gosql($sql);
}




header("Location: pedit.php");

//disparray($_SESSION,SESSIONEND);



//phpinfo();
