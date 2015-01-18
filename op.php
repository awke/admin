<?php
$NOBOOKING=1;
$PRELOGIN=1;
$HEADER=1;
include('functions.inc');
sqlconn();

//disparray($_SESSION);



$SESSION[invoice][irn]=$_SESSION[secdata][idinvoices][$_GET[id]];
$irn=$SESSION[invoice][irn];
$sql="SELECT * from adminInfo.invoices";
$result=gosql($sql,0);
while($row=mysql_fetch_assoc($result))
{
$dnm=$row[idinvoices];
$SESSION[$dnm][invoice]=(unserialize(gzuncompress($row[variable])));

}

print_r($SESSION);

