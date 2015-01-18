<?php
ob_start();
$NOBOOKING=0;
$PRELOGIN=1;
$HEADER=0;
include('functions.inc');
sqlconn();

if(!isset($_SESSION[INFO][D_ID]))
{
exit("NOT LOGGED IN");
}
$domain=$_SESSION[INFO][D_ID];
$sql="SELECT * from domains WHERE D_ID='$domain'";

$res=gosql($sql,0);
$row=mysql_fetch_assoc($res);

$start=$_GET[start];
$end=$_GET[end];
if(!isset($_GET[width]))
{
$width=500;
}
else
{
$width=$_GET[width];
}
$height=$_GET[height];
if(isset($_GET[nolegend]))
{
$nolegend="NoLegend=1";
}

$directory=$row[directory];



header('Content-Type: image/png');
virtual("/cgi-bin/drraw/drraw.cgi?Mode=show;Template=t1230072957.26814;Base=%2F%2FSupported%2Fcollectd%2F%2Fdeltaserver.awke.co.uk%2Fdf%2Fdf-Supported-domains-$directory.rrd;Start=$start;End=$end;Width=$width;Height=$height;$nolegend");



