<?php
$HEADER=0;
include('functions.inc');
sqlconn();
$D_ID=$_SESSION[INFO][D_ID];
$array=retedittable("add",0);

$idauthusers = $array[1][idauthusers];

foreach($array[1] as $key => $value)
{
$key = strval($key);
if ($key == "0")
	{
	if ($value == "1")
		{
		$sql="DELETE FROM userpriv WHERE idauthusers='$idauthusers' AND D_ID='$D_ID'";
		gosql($sql,0);
		}
	}
if ($key != "idauthusers")
	{
	if ($value == "1")
		{
		$sql="INSERT INTO userpriv SET idauthusers='$idauthusers', D_ID='$D_ID', privdev_idprivdev='$key'";
		gosql($sql,0);
		}
	}
}
clredittable("add",0);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");

?>