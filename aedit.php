<?php

include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$id=$_SESSION[secdata][idaccounts][$_GET[id]];

print <<<END

<div class="infolabel">Editing Account Record</div>
<div class="subtabinfo">
<form method="post" action="aupdate.php">
END;


$sql="SELECT idcustomers,fname,lname from customers";

$result=gosql($sql,0);
$lookup1[""]="";
while ($row=mysql_fetch_assoc($result) )
{
$lookup1[$row[idcustomers]]="$row[idcustomers] - $row[fname] $row[lname]";
}

$sql="SELECT idinvoices,customer,invoicedate,createdate,total,custref from invoices";

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

$sql="SELECT * from accounts WHERE idaccounts='$id'";
$result=gosql($sql);
$row=mysql_fetch_assoc($result);
//$row=array("","dat"=>"","amount"=>"","descrip"=>"","customer"=>"","invoice"=>"","lastupdated"=>"");
$desc=array("","date","amount","Description","Customer","Invoice","Account","");

$lookup6=array("0"=>"Current Account","1"=>"Reserve Account");

$function=array(array(1),array(0),array(0),array(0,60),array(2,$lookup1),array(2,$lookup2),array(2,$lookup6),array(1));
//disparray($function);
edittable("std",0,"usual",$row,$desc,$function);

$_SESSION[secdata][customers][edit.php][pid]=$id;

print <<<END
<br><br><br><br>
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="aindex.php">Back</a></td></tr></table></div>

END;

bottom();
?>
