<?php
$time=time();
$saveloc="vgs";
exec('vgs  --nosuffix --units b ',$array);
//$array=`df -h`;


$count=count($array);

$done=0;
$pos=1;

while($pos<$count)
{
$line=preg_split("/[\s,]+/",$array[$pos]);

$cnt=count($line);



$loc="/root/mon/data";

#$loc=$loc . "/vgs";
$v[0]="vgs-$line[1]";

$max="RRA:MAX:0.5:1:600 RRA:MAX:0.5:6:700 RRA:MAX:0.5:24:775 RRA:MAX:0.5:288:797";

$min="RRA:MIN:0.5:1:600 RRA:MIN:0.5:6:700 RRA:MIN:0.5:24:775 RRA:MIN:0.5:288:797";

$last="RRA:LAST:0.5:1:600 RRA:LAST:0.5:6:700 RRA:LAST:0.5:24:775 RRA:LAST:0.5:288:797";

$average="RRA:AVERAGE:0.5:1:600";// RRA:AVERAGE:0.5:6:700 RRA:AVERAGE:0.5:24:775 RRA:AVERAGE:0.5:288:797";

$extras=" RRA:AVERAGE:0.5:1:800";
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


if(!file_exists("$loc/$saveloc/$v[0].rrd"))
{
 $tt=$time-1;
$func="rrdtool create \"$loc/$saveloc/$v[0].rrd\" --step 300 --start $tt  \
 DS:pvs:GAUGE:900:0:U  \
DS:lvs:GAUGE:900:0:U DS:size:GAUGE:900:0:U \
 DS:free:GAUGE:900:0:U  DS:size-chng:DERIVE:900:U:U DS:free-chng:DERIVE:900:U:U DS:lvs-chng:DERIVE:900:U:U";

$func.=$extras;
unset($ret);
//print $func . "\n";
$ret=system("$func");
//print $ret . "\n";

//print "$func\n";
}
$v[1]=$line[2];
$v[2]=$line[3];
$v[3]=$line[6];
$v[4]=$line[7];

$func="rrdtool update $loc/$saveloc/$v[0].rrd $time:$v[1]:$v[2]:$v[3]:$v[4]:$v[3]:$v[4]:$v[2]";
unset($ret);
$ret=system("$func");

//print $func . "\n";

//print_r($line);
//print "\n";


$pos++;
}


