<?php
$HEADER=0;
include('functions.inc');

sqlconn();

//disparray($_SESSION,_SESSION);
//disparray($_POST,_GET);
//disparray($_INFO,_INFO);
$D_ID=$_SESSION[INFO][D_ID];

$sql="SELECT domainn FROM domains WHERE D_ID='$D_ID'";
$results=gosql($sql,0);
$row=mysql_fetch_assoc($results);

$website="/Supported/webalizer/http/$_SESSION[webhostname].$row[domainn]";

if(!isset($_SERVER['PATH_INFO']))
{
$_SERVER['PATH_INFO']="/index.html";
}
if(strcmp($_SERVER['PATH_INFO'],"/")==0)
{
$_SERVER['PATH_INFO']="/index.html";
}

$base=$website; #'/websites/intranet-base';
$path_info=$_SERVER['PATH_INFO'];
$file=$base . $path_info;

$temp=pathinfo($_SERVER['PATH_INFO']);
if(strcmp($temp['extension'],"jpg")==0)
{
header("Content-Type: image/jpeg");
}
elseif(strcmp($temp['extension'],"gif")==0)
{
header("Content-Type: image/gif");
}
elseif(strcmp($temp['extension'],"png")==0)
{
header("Content-Type: image/jpeg");
}
elseif(strcmp($temp['extension'],"html")==0)
{
header("Content-Type: text/html");
}
elseif(strcmp($temp['extension'],"htm")==0)
{
header("Content-Type: text/html");
}
else
{
header("Content-Type: application/octet-stream");
}

//disparray($temp,pathinfo);

//disparray($file,file);
//disparray($_SERVER,server);

readfile($file);

//phpinfo();
?>
