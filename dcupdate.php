<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
//$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];
$emailid=$_SESSION[secdata][customers][edit.php][did];
$array=retedittable("email",0);

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
$sql="UPDATE custdom SET $sqlins WHERE idcustdom='$emailid'";
gosql($sql,0);
}




header("Location: cindex.php");

//disparray($_SESSION,SESSIONEND);



//phpinfo();
