<?php
$HEADER=0;
include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$domainid=$_SESSION[secstore][domains][domainid];
unset($_SESSION[secstore][domains]); 

$array=retedittable("add",0);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

$array[1][lastupdated]=date('YmdHis');

if (strcmp($array[1][password1],"")==0)
{
$array[1][password1]=passgen();
}
if (strcmp($array[1][password2],"")==0)
{
$array[1][password2]=passgen();
}

if($error==0)
{
foreach($array[1] as $key => $value)
{
if ($key != "customerid")
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
}
$sql="UPDATE domains SET $sqlins WHERE D_ID='$domainid'";
gosql($sql,0);
clredittable("add",0);

$sql="INSERT INTO superusercommands SET command='0', parameters='$domainid', lastupdated='$date'";
gosql($sql,0);

$customerid=$array[1][customerid];
if ($customerid != "0")
	{
	$sql="Update custdom SET customers_idcustomers='$customerid' WHERE domains_D_ID='$domainid'";
	gosql($sql,0);
	}
header("Location: domain.php");
}
else
{//error=1 therefor error

header("Location: domainedit.php");
$_SESSION[secstore][editdomain][domainerrid]=$domainid;

exit();
}

}
?>
