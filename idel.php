<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}



//$_SESSION[invoice] contains data on invoice
unset($_SESSION[invoice][data]);
$_SESSION[invoice][data][]=array("1","Domain Registration","TEST.co.uk","","12.00","12.00");
$_SESSION[invoice][data][]=array("1","Domain Setup","","TEST.co.uk","2.00","2.00");
$_SESSION[invoice][data][]=array("6","Web Hosting","Per Month","TEST.co.uk","5.00","30.00");
//$_SESSION[invoice][data][]=array("6","Web Hosting","Per Month","TEST.co.uk","5.00","30.00");


if(isset($_SESSION[invoice][data]))
{
$lco=0;
$total=0;
foreach($_SESSION[invoice][data] as $key => $value)
{
$uniq[$lco]=$lco;
$_SESSION[invoices][iid]=$key;
$total=$total+$value[5];
}
$keys=array("Quantity","Package","Description","Domain","Cost Each","Cost");

$function=array(array("Delete","<a href=\"idel.php?D_ID=%UNIQ%\">Delete</a>"));

$rt=disptable($keys,$_SESSION[invoice][data],$uniq,$function,"usual",1);
print $rt;
print "TOTAL:£$total<br>";
disparray($_SESSION[invoice][data]);
}




