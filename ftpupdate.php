<?php
$HEADER=0;
include('functions.inc');
sqlconn();
$ftpid=$_SESSION[secstore][editftp][$id];
unset($_SESSION[secstore][editftp]); 
$array=retedittable("add",0);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

if (strcmp($array[1][password],"")==0)
{
$array[1][password]=passgen();
}
if (ereg("[[:space:]]", $array[1][password]))
{ 
$error=1;
$_SESSION[error][]="Please correct password";
}

if (ereg("[[:space:]]", $array[1][homedir]))
{ 
$error=1;
$_SESSION[error][]="Please correct homedir";
}

if($error==0)
{
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins." domain='$_INFO[D_ID]', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="UPDATE ftpusers SET $sqlins WHERE FTPU_ID='$ftpid'";
gosql($sql,0);
clredittable("add",0);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");

}
else
{//error=1 therefor error
$_SESSION[secstore][editftp][ftperrid]=$ftpid;
header("Location: ftpusersedit.php");
exit();
}

}
?>