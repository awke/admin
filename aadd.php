<?php

include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}


print <<<END

<div class="infolabel">Adding Account Record</div>
<div class="subtabinfo">
<form method="post" action="aupdatea.php">
END;



$sql="SELECT descrip FROM accounts ORDER BY descrip";

$result=gosql($sql,0);
$lookup0[""]="";
while ($row=mysql_fetch_assoc($result) )
{
$lookup0[$row[descrip]]=$row[descrip];
}

$sql="SELECT amount FROM accounts ORDER BY descrip";

$result=gosql($sql,0);
$lookup4[""]="";
while ($row=mysql_fetch_assoc($result) )
{
$lookup4[$row[amount]]=$row[amount];
}

$sql="SELECT dat FROM accounts ORDER BY descrip";

$result=gosql($sql,0);
$lookup5[""]="";
while ($row=mysql_fetch_assoc($result) )
{
$lookup5[$row[dat]]=$row[dat];
}

//old

$sql="SELECT idcustomers,fname,lname from customers ORDER BY fname,lname";

$result=gosql($sql,0);
$lookup1[""]="";
while ($row=mysql_fetch_assoc($result) )
{
$lookup1[$row[idcustomers]]="$row[idcustomers] - $row[fname] $row[lname]";
}

$sql="SELECT idinvoices,customer,invoicedate,createdate,total,custref from invoices WHERE paid=0 AND void=0";

$result=gosql($sql,0);

$lookup2[""]="";
while ($row=mysql_fetch_assoc($result) )
{
$sql="SELECT idcustomers,fname,lname from customers WHERE idcustomers='$row[customer]'";

$result1=gosql($sql,0);
$rows=mysql_fetch_assoc($result1);
$custinfo="$rows[fname] $rows[lname]";


$lookup2[$row[idinvoices]]="$row[idinvoices] - Cust:$custinfo Idate:$row[invoicedate] Cdate:$row[createdate] Total:$row[total] Cref:$row[custref]";
}

$row=array("","dat"=>"","amount"=>"","descrip"=>"","customer"=>"","invoice"=>"","account"=>"0","lastupdated"=>"");
$desc=array("","date","amount","Description","Customer","Invoice","Account","");

$lookup6=array("0"=>"Current Account","1"=>"Reserve Account");

$function=array(array(1),array(3,$lookup5),array(3,$lookup4),array(3,$lookup0,60),array(2,$lookup1),array(2,$lookup2),array(2,$lookup6),array(1));
//disparray($function);
edittable("std",0,"usual",$row,$desc,$function);


$row=array("dat"=>"","amount"=>"","Description"=>"","Direction"=>"0");
$desc=array("Date","Amount","Description","Transfer<br>Direction");
$lookup7=array("0"=>"From Current to Reserve","1"=>"From Reserve to Current");

$function=array(array(3,$lookup5),array(3,$lookup4),array(3,$lookup0,60),array(2,$lookup7));

print "<br><br><br>\n";
edittable("xfr",0,"usual",$row,$desc,$function);

print <<<END
<br><br><br><br>
</div>
<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="aindex.php">Back</a></td></tr></table></div>

END;

bottom();

?>
