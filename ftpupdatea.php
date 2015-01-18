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
$error=0;

if (strcmp($array[1][username],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter username";
}
if (ereg("[[:space:]]", $array[1][username]))
{ 
$error=1;
$_SESSION[error][]="Please correct username";
}

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
$username= $array[1][username];
$sql="SELECT * FROM ftpusers WHERE domain='$_INFO[D_ID]' AND username='$username'";
$result=gosql($sql,0);
$numrows = mysql_num_rows($result);

if ($numrows > 0 ) {
$error=1;
$_SESSION[error][]="Username already exists, please enter a different one";
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

$sql="INSERT INTO ftpusers SET $sqlins, uid=(SELECT userid FROM domains WHERE D_ID='$_INFO[D_ID]'), gid=(SELECT userid FROM domains WHERE D_ID='$_INFO[D_ID]')";
gosql($sql,0);
clredittable("add",0);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");

}
else
{//error=1 therefor error

header("Location: ftpusersadd.php");
exit();
}

}
?>