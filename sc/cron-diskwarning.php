#!/usr/bin/php5
<?php

exec('df -x tmpfs',$rt);

//print_r($rt);

$a=1;
$done=0;

while($done==0)
{
//print $a;
//print_r($rt[$a]);
$r=preg_split("/[\s,]+/",$rt[$a]); //explode(" ",$rt[a]);
//print_r($r);


if(count($r)==1)
{
$title=$r[0];
$a++;
$r=preg_split("/[\s,]+/",$rt[$a]);
}
else
{
$title=$r[0];
}

$t=preg_split("/[%]/",$r[4]);

$data[$title]=array("blocks"=>$r[1],"used"=>$r[2],"avail"=>$r[3],"percu"=>$t[0],"mountd"=>$r[5]);




$got=0;
$a++;
if($a==count($rt))
{
$done=1;

}




}

foreach($data as $key => $value)
{
//print "$key: '$value[percu]' '$value[avail]\n'";

if(intval($value[percu])>91 && intval($value[avail])<20000)
{
$state[$key]=1;

}
if(intval($value[avail])<10000)
{
$state[$key]=1;
}

}


require_once("/Supported/phpcode/email.php");


if(count($state)>0)
{
$message="Disk Space Warnings for the following\n\n";

foreach($state as $key1 => $value1)
{
$v=$data[$key1];
$message.="$key1 mounted on $v[mountd] has $v[avail] blocks available from $v[blocks] blocks = $v[percu]%  or $v[used] Used\n";
//print_r($data[$key1]);

$headers="From: \"AWKE Disk Warning\" <admin@awke.co.uk>\r\n";
sock_mail($auth,"admin@awke.co.uk","DISK SPACE WARNING for $v[mountd]",$message,$headers,"admin@awke.co.uk");
}

}

print_r($message);
