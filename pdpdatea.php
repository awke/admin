<?php
$HEADER=0;
include('functions.inc');
sqlconn();

disparray($_SESSION,session);
disparray($_POST,POST);
//$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];
$customerid=$_SESSION[secdata][customers][edit.php][custid];
$customerid=$_SESSION[secdata][customers][edit.php][pdid];
$array=retedittable("packdata",0);

//disparray($array,rebuiltform);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins."packages_idpackages='$customerid', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="INSERT INTO custemail SET $sqlins";
gosql($sql,1);
}



header("Location: pindex.php");

//disparray($_SESSION,SESSIONEND);



//phpinfo();
