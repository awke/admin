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

if ($array[1][passwd] == "")
	{
	$array[1][passwd] = passgen();
	}

	foreach($array[1] as $key => $value)
	{
	$sqlins .= "$key='$value',";
	}
$sqlins = trim($sqlins, ",");

$sql="INSERT INTO authusers SET $sqlins";
gosql($sql,0);
clredittable("add",0);
header("Location: userprivileges.php");
}
?>
