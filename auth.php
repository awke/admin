<?php

$HEADER=0;
$PRELOGIN=1;

include('functions.inc');

sqlconn();

$sql="SELECT idauthusers,description as authuserdescription from authusers WHERE username='$_POST[uname]' AND passwd='$_POST[passwd]'";

$result=gosql($sql,0);


$num_rows = mysql_num_rows($result);

if($num_rows==0)
{
//no match
header("Location: login.php");
}

if($num_rows==1)
{
//matched

$row=mysql_fetch_assoc($result);
$_SESSION[idauthusers]=$row[idauthusers];
$_SESSION[authuserdescription]=$row[authuserdescription];

header("Location: index.php");
}

