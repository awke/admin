<?php
global $loc;
$loc="/root/mon/data";

/*
type is type of graph
time is timescale
src is src file if approp
escapeshellarg($_GET[src])

*/

switch($_GET[type])
{
case "disk_p":
disk_p();
break;
case "disk_a":
disk_a();
break;
case "disk_ap":
disk_ap();
break;
case "cpu":
cpu();
break;
case "cpu_i":
cpu_i();
break;
case "vgs_n":
vgs_n();
break;
case "vgs_s":
vgs_s();
break;
case "bind":
bind();
break;
}

header("Content-type: image/png");
//$ret=system("$command");


$err1=passthru("$command",$err);


//print $ret;

print "\n$command\n";

print "$err $err1";


function bind()
{
global $command,$loc;

$src=$_GET[src];

if(isset($_GET[start]))
{$start=$_GET[start];}
else
{
$start="end-1d";
}

$ulimit=100;

$width=800;
$height=400;
$command="rrdtool graph - -a PNG --start $start DEF:success=$loc/bind/$src.rrd:success:AVERAGE CDEF:suc=sucess,PREV,sucess,-,-1,* LINE2:suc#ff0000:\"DIF\" ";


}


function vgs_s()
{
global $command,$loc;
$src=$_GET[src];
if(!isset($_GET[src]))
{
$src="data";
}

$height=100;
$width=400;

if(isset($_GET[start]))
{$start=$_GET[start];}
else
{
$start="end-1d";
}

$command="rrdtool graph - -a PNG --end now --start $start DEF:total=$loc/vgs/vgs-$src.rrd:size:AVERAGE DEF:free=$loc/vgs/vgs-$src.rrd:free:AVERAGE LINE1:free#FF0000:\"Free Space\" LINE1:total#0000ff:\"Total Space\" CDEF:used=total,free,- LINE1:used#00ff00:\"Used Space\"  --height $height --width $width";
}

function vgs_n()
{
global $command,$loc;
$src=$_GET[src];
if(!isset($_GET[src]))
{
$src="data";
}

$height=100;
$width=400;

if(isset($_GET[start]))
{$start=$_GET[start];}
else
{
$start="end-1d";
}

$command="rrdtool graph - -a PNG --end now --start $start DEF:lvs=$loc/vgs/vgs-$src.rrd:lvs:AVERAGE DEF:pvs=$loc/vgs/vgs-$src.rrd:pvs:AVERAGE LINE1:lvs#FF0000:\"# Logical Volumes\" LINE1:pvs#0000ff:\"# Physcial Volumes\" --height $height --width $width";
}
function disk_p()
{

}

function disk_a()
{
global $command,$loc;
$src=$_GET[src];

$height=100;
$width=400;

if(isset($_GET[start]))
{$start=$_GET[start];}
else
{
$start="end-1d";
}

$command="rrdtool graph - -a PNG --end now --start $start DEF:total=$loc/disk-space/$src.rrd:dsksp-total:AVERAGE DEF:used=$loc/disk-space/$src.rrd:dsksp-used:AVERAGE LINE2:used#FF0000:\"Used Space\" LINE3:total#0000ff:\"Total Space\" --height $height --width $width";
}


function disk_ap()
{
global $command,$loc;
$src=$_GET[src];

$height=100;
$width=400;

if(isset($_GET[start]))
{$start=$_GET[start];}
else
{
$start="end-1d";
}

$command="rrdtool graph - -a PNG --end now --start $start DEF:total=$loc/disk-space/$src.rrd:dsksp-total:AVERAGE DEF:used=$loc/disk-space/$src.rrd:dsksp-used:AVERAGE CDEF:perused=used,total,/,100,* LINE1:perused#FF0000:\"Used Space %\"  --height $height --width $width";
}



function cpu()
{
global $command,$loc;

if(isset($_GET[start]))
{$start=$_GET[start];}
else
{
$start="end-1d";
}

$ulimit=100;

$width=800;
$height=460;
$command="rrdtool graph - -a PNG --start $start --base 1000 -r --lower-limit 0 --upper-limit $ulimit DEF:system=$loc/cpu/cpu-info.rrd:system:AVERAGE DEF:user=$loc/cpu/cpu-info.rrd:user:AVERAGE DEF:nice=$loc/cpu/cpu-info.rrd:nice:AVERAGE DEF:idle=$loc/cpu/cpu-info.rrd:idle:AVERAGE DEF:iowait=$loc/cpu/cpu-info.rrd:iowait:AVERAGE DEF:irq=$loc/cpu/cpu-info.rrd:irq:AVERAGE DEF:softirq=$loc/cpu/cpu-info.rrd:softirq:AVERAGE AREA:system#3300ff:\"system\" STACK:user#ff0000:\"user\" STACK:nice#ccff00:\"nice\" STACK:iowait#ff9999:\"iowait\" STACK:irq#993399:\"irq\" STACK:softirq#9933cc:\"softirq\" STACK:idle#99ff33:\"idle\"  --height $height --width $width";


}

function cpu_i()
{
global $command,$loc;

if(isset($_GET[start]))
{$start=$_GET[start];}
else
{
$start="end-1d";
}

$ulimit=100;
$width=700;
$height=400;
$command="rrdtool graph - -a PNG --start $start --base 1000 -r --lower-limit 0 --upper-limit $ulimit DEF:system=$loc/cpu-info.rrd:system:AVERAGE DEF:user=$loc/cpu-info.rrd:user:AVERAGE DEF:nice=$loc/cpu-info.rrd:nice:AVERAGE  DEF:iowait=$loc/cpu-info.rrd:iowait:AVERAGE DEF:irq=$loc/cpu-info.rrd:irq:AVERAGE DEF:softirq=$loc/cpu-info.rrd:softirq:AVERAGE AREA:system#ff0000:\"system\" STACK:user#00ff00:\"user\" STACK:nice#0000ff:\"nice\" STACK:iowait#00ffff:\"iowait\" STACK:irq#ffff00:\"irq\" STACK:softirq#b08f00:\"softirq\"   --height $height --width $width";

}


/* disk
rrdtool graph s2.png --end now --start end-1d DEF:il=data2/---dev---hda1.rrd:dsksp-total:AVERAGE DEF:io=data2/---dev---hda1.rrd:dsksp-used:AVERAGE LINE2:io#FF0000:"com" LINE3:il#0000ff:"cd"
*/
/* cpu
rrdtool graph /Supported/atackscomputers.co.uk/www/wwwroot/g.png --base 1000 -r --lower-limit 0 --upper-limit 100 DEF:system=/root/mon/data/cpu-info.rrd:system:AVERAGE DEF:user=/root/mon/data/cpu-info.rrd:user:AVERAGE DEF:nice=/root/mon/data/cpu-info.rrd:nice:AVERAGE DEF:idle=/root/mon/data/cpu-info.rrd:idle:AVERAGE DEF:iowait=/root/mon/data/cpu-info.rrd:iowait:AVERAGE DEF:irq=/root/mon/data/cpu-info.rrd:irq:AVERAGE DEF:softirq=/root/mon/data/cpu-info.rrd:softirq:AVERAGE AREA:system#ff0000:"system" STACK:user#00ff00:"user" STACK:nice#0000ff:"nice" STACK:idle#ff00ff:"idle" STACK:iowait#00ffff:"iowait" STACK:irq#ffff00:"irq" STACK:softirq#b08f00:"softirq" --height 350 --width 800 --upper-limit 101

*/
?>
