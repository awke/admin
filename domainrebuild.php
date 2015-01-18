<?php

$HEADER=0;
include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$command=$_GET[rebuild];
$date=date('YmdHis');

switch ($command) 
	{
	case 0:
		$sql="INSERT INTO superusercommands SET command='0', parameters='0', lastupdated='$date'";
		gosql($sql,0);
		break;
	case 1:
		$sql="INSERT INTO superusercommands SET command='1', parameters='0', lastupdated='$date'";
		gosql($sql,0);
		break;
	case 2:
		$sql="INSERT INTO superusercommands SET command='2', parameters='0', lastupdated='$date'";
		gosql($sql,0);
		break;
	case 3:
		$sql="INSERT INTO superusercommands SET command='3', parameters='0', lastupdated='$date'";
		gosql($sql,0);
		break;
	case 4:
		$sql="INSERT INTO superusercommands SET command='4', parameters='0', lastupdated='$date'";
		gosql($sql,0);
		break;
	case 5:
		$sql="INSERT INTO superusercommands SET command='5', parameters='0', lastupdated='$date'";
		gosql($sql,0);
		break;
	}
header("Location: domain.php");
exit();

?>
