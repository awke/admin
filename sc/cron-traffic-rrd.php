#!/usr/bin/php
<?php
$MIN=10;
$MINPERCENT=20;
$PERCENT=91;
require_once('/Supported/phpcode/email.php');
require_once('/Supported/phpcode/files.php');
require_once('/Supported/phpcode/logging.php');
require_once('/Supported/phpcode/sql.php');
$LOGGER_ID='Traffic to RRD';
$dbhost = "localhost";
$dbname = "adminInfo";
$dbuser = "awkeadminsoft";
$dbpasswd = "y3ipRLp";
 $reload=0;
 sqlconn();
 logsys("Started");

 $last="RRA:LAST:0.5:1:800 RRA:LAST:0.5:6:800 RRA:LAST:0.5:24:800 RRA:LAST:0.5:288:800";


$extras=" RRA:AVERAGE:0.5:1:800 \
RRA:AVERAGE:0.5:6:800 \
RRA:AVERAGE:0.5:24:800 \
RRA:AVERAGE:0.5:288:800 \
RRA:MIN:0.5:1:800 \
RRA:MIN:0.5:6:800 \
RRA:MIN:0.5:24:800 \
RRA:MIN:0.5:288:800 \
RRA:MAX:0.5:1:800 \
RRA:MAX:0.5:6:800 \
RRA:MAX:0.5:24:800 \
RRA:MAX:0.5:288:800 $last";

$time=time();

 
 $sql="SELECT * from domains where deactivated='0'";
 
 $result=gosql($sql,0);
$date=date("Y-m-00"); 
 while($row=mysql_fetch_assoc($result))
 {
sleep(5);
 $sql="SELECT * from traffic where d_id='$row[D_ID]' AND dat='$date' ORDER BY `type`";
 
 $res=gosql($sql,0);
 
 while($row2=mysql_fetch_assoc($res))
 {
sleep(0.5);
//print "$row[domainn] $row2[type] $row2[dat] $row2[bytes] $row2[inb] $row2[outb]\n"; 

$path="/Supported/stats/traffic/$row[domainn]";
$filen="$row2[type].rrd"; 
 
if(!file_exists("$path/$filen"))
{
@mkdir($path,0755,TRUE);

 $tt=$time-1;
$func="rrdtool create \"$path/$filen\" --step 300 --start $tt  \
 DS:inb:GAUGE:900:0:U  \
DS:outb:GAUGE:900:0:U DS:inbs:DERIVE:900:0:U DS:outbs:DERIVE:900:0:U";

$func.=$extras;
unset($ret);
//print $func . "\n";
$ret=system("$func");
//print $ret . "\n";

//print "$func\n";
} 
@mkdir("/Supported/temp/trafficrrd",0755,TRUE);
$temp="/Supported/temp/trafficrrd/$row[domainn]-$row2[type]-$date.tmp";
$t=@file($temp);
$inb=$row2[inb];
$outb=$row2[outb];
if($inb<0)
$inb=0;
if($outb<0)
$outb=0;

$inbc=$inb-$t[0];
$outbc=$outb-$t[1];

if($inbc<0)
$inbc=0;
if($outbc<0)
$outbc=0;


$func="rrdtool update \"$path/$filen\" $time:$inbc:$outbc:$inb:$outb";
unset($ret);
$ret=system("$func");
//print "$func\n";
file_put_contents($temp,"$inb\n$outb");
 
 }
 
 }
 logsys("FINISHED");
