<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
//$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];
$customerid=$_SESSION[secdata][customers][edit.php][custid];
$array=retedittable("email",0);

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
		$sqlins=$sqlins."customers_idcustomers='$customerid', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="INSERT INTO custemail SET $sqlins";
gosql($sql,0);
}



header("Location: cindex.php");

//disparray($_SESSION,SESSIONEND);



//phpinfo();
