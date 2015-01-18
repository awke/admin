<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
//$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];
$customerid=$_SESSION[secdata][customers][edit.php][custid];
$array=retedittable("cust",0);

//disparray($array,rebuiltform);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";

if ($_SESSION[fromdomainadd] == 1)
{

$tel=$array[1][tel];
$email=$array[1][email];
unset($array[1][tel]);
unset($array[1][email]);

}


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
$sql="INSERT INTO customers SET $sqlins";
gosql($sql,0);

if ($_SESSION[fromdomainadd] == 1)
{
$id=mysql_insert_id();
if(strlen($tel)>5)
{
$sql="INSERT into adminInfo.custtel set idcustomers='$id',tel='$tel',pri='1'";
gosql($sql);
}
if(strlen($email)>5)
{
$sql="INSERT into adminInfo.custemail set customers_idcustomers='$id',email='$email',pri='1'";
gosql($sql);

}


}


}


if ($_SESSION[fromdomainadd] == 1)
	{
	unset($_SESSION[fromdomainadd]);
	header("Location: domainadd.php");
	}
else
	{
	header("Location: cindex.php");
	}

//disparray($_SESSION,SESSIONEND);



//phpinfo();
