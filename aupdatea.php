<?php
$HEADER=0;
include('functions.inc');
require_once("invoices.php");
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
//$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];
$customerid=$_SESSION[secdata][customers][edit.php][pid];
$array=retedittable("std",0);

//disparray($array,rebuiltform);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
foreach($array[1] as $key => $value)
{
if($key=="invoice")
{
if($value!="")
{
if($value!=0)
{
//$sql1="UPDATE invoices set paid='1' WHERE idinvoices='$value'";
//gosql($sql1);

$invoicec=new invoices();

$invoicec->paid($value);


}
}
}
	if($first==0)
	{
		//$sqlins=$sqlins." domains_D_ID='$customerid', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="INSERT INTO accounts SET $sqlins";
gosql($sql,0);
}


clredittable("std",0);

$array=retedittable("xfr",0);

//disparray($array,rebuiltform);

if($array[0]==1)
{
//"0"=>"From Current to Reserve","1"=>"From Reserve to Current"
//print_r($array[1]);

$dat=$array[1][dat];
$amount=$array[1][amount];
$description=$array[1][Description];
$direction=$array[1][Direction];
if($direction==0)
{
$amount=$amount*-1;

$sql="INSERT into accounts SET dat='$dat',descrip='$description',amount='$amount',account='0'";

gosql($sql);

$amount=$amount*-1;

$sql="INSERT into accounts SET dat='$dat',descrip='$description',amount='$amount',account='1'";

}
elseif($direction==1)
{
$amount=$amount*-1;

$sql="INSERT into accounts SET dat='$dat',descrip='$description',amount='$amount',account='1'";

gosql($sql);

$amount=$amount*-1;

$sql="INSERT into accounts SET dat='$dat',descrip='$description',amount='$amount',account='0'";

}

}

clredittable("xfr",0);

header("Location: aindex.php");

//disparray($_SESSION,SESSIONEND);



//phpinfo();
