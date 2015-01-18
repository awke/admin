<?php

include('functions.inc');
sqlconn();

require_once("invoices.php");

//disparray($_SESSION);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

if(!isset($_SESSION[invoicefunc]))
{
$invoices=new invoices();
}
else
{
$invoices=unserialize($_SESSION[invoicefunc]);
}


//print"<pre><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>\n";


$array=retedittable("itop",0);
if($array[0]==1)
{
//disparray($array[1]);
$_SESSION[invoice][top]=$array[1];

$curdate=getcurdate();
$invoices->setInvoiceCreateDate($curdate);
$invoices->setInvoiceDate($array[1][date]);
$invoices->setCustomerID($array[1][customer_number]);




clredittable("itop",0);
}


$_SESSION[invoicefunc]=serialize($invoices);

//disparray($_SESSION[invoice]);


$array=retedittable("icustom",0);
if($array[0]==1)
{

$array[1][Cost]=$array[1][CostEach]*$array[1][Quantity];

$invoices->addLineItem($array[1]);
//$invoices->addPackage($array[1]);

clredittable("icustom",0);

}


$array=retedittable("icommit",0);
if($array[0]==1)
{
$invoices->commit();
//$invoices->addPackage($array[1]);

unset($_SESSION[invoicefunc]);

header("Location:iindex.php");

clredittable("icommit",0);

}

$array=retedittable("ireset",0);
if($array[0]==1)
{

$invoices=new invoices();


unset($_SESSION[invoices]);

unset($_SESSION[invoice]);

unset($_SESSION[INVOICES]);

//uprint_r($_SESSION);

//$invoices->addPackage($array[1]);

clredittable("ireset",0);

}


$array=retedittable("ibottom",0);
if($array[0]==1)
{
$invoices->addPackage($array[1]);

clredittable("ibottom",0);

/*
$dt=$array[1];
//disparray($dt);
$dat=$_SESSION[invoice][top][date];
$sql="SELECT cost from packagedata,packages WHERE packages_idpackages=idpackages AND idpackages='$dt[Package]' AND sdate<='$dat' AND edate>='$dat'";
$result=gosql($sql,0);
if(mysql_num_rows($result)<1)
{
$dt["Cost"]="ERROR IN PACKAGE DATA";
$dt["CostEach"]=$dt["Cost"];
}
else
{
$row=mysql_fetch_assoc($result);
$dt["CostEach"]=$row[cost];
$dt["Cost"]=$dt["Quantity"]*$row[cost];
}

$sql="SELECT description from packages WHERE idpackages='$dt[Package]'";
$result=gosql($sql);
$row=mysql_fetch_assoc($result);
$dt[Package]=$row[description];


//$dt["CostEach"];
//disparray($dt,dtafter);
$_SESSION[invoice][data][]=$dt;

//DO CONVERSION OF VALUES BACK TO ITEM OR ELSE AS I AM DOING HERE JUST ADD IT SO THAT IT RETURNS THEM FROM FORM by passing both sides the SAME

*/
}



//$_SESSION[invoice] contains data on invoice
//unset($_SESSION[invoice][data]);
//$_SESSION[invoice][data][]=array("Quantity"=>"1","Package"=>"Domain Registration","Description"=>"TEST.co.uk","Domain"=>"","CostEach"=>"12.00","Cost"=>"12.00");
//$_SESSION[invoice][data][]=array("Quantity"=>"1","Package"=>"Domain Setup","Description"=>"","Domain"=>"TEST.co.uk","CostEach"=>"2.00","Cost"=>"2.00");
//$_SESSION[invoice][data][]=array("Quantity"=>"6","Package"=>"Web Hosting","Description"=>"Per Month","Domain"=>"TEST.co.uk","CostEach"=>"5.00","Cost"=>"30.00");
//$_SESSION[invoice][data][]=array("Quantity"=>"6","Package"=>"Web Hosting","Description"=>"Per Year","Domain"=>"TEST.com","CostEach"=>"6.00","Cost"=>"36.00");


//unset($_SESSION[invoice][top]);
if(!isset($_SESSION[invoice][top]))
{
$date=dispdate2(getcurdate());

$_SESSION[invoice][top]=array("date"=>"$date","customer_reference_number"=>"","customer_number"=>"","Comment"=>"");
}






$sql="SELECT idcustomers,fname,lname from customers ORDER BY lname";

$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result) )
{
$lookup[$row[idcustomers]]="$row[fname] $row[lname]";
}
$desc=array("Date","Customer Reference Number","Customer","Comment");

$function=array(array(0),array(0),array(2,$lookup));






print <<<END

<div class="infolabel">Add Invoice</div>
<div class="subtabinfo">
<form method="post">
END;


edittable("itop",0,"usual",$_SESSION[invoice][top],$desc,$function);

print "<br>";

$datacount=$invoices->getKeysLineItems();

foreach($datacount as $dataval)
{

$invdata[$dataval]=$invoices->getLineItem($dataval);

}

if(isset($_SESSION[invoice][data]))
{
$lco=0;
$total=0;
foreach($_SESSION[invoice][data] as $key => $value)
{
$uniq[$lco]=$lco;
$_SESSION[invoices][ilid][$lco]=$key;
$lco++;
$total=$total+$value[Cost];

}

}
$total=$invoices->getTotal();


$lu=$_SESSION[invoice][top][customer_number];
//print "DEBBBB:$lu  $lookp[$lu]";
print '<p>First text is the info for the top of the invoice (first text box must be submited, you can make up the invoice using the next 2 boxes the first box uses package data to make an invoice the second box is all free form, once top section and at least one line of items has been added to invoice a table with a submit button to commit invoice and email customer appears, finally there is a reset submission button. There is a line item delete at the end of each line item<br><br> If customer doesnt exist go out and create the customer and then come back</b></p><br>
<table>
<tr>
<td>Invoice date</td><td>'.$_SESSION[invoice][top][date].'</td>
</tr><tr>
<td>Customer Reference Number</td><td>'.$_SESSION[invoice][top][customer_reference_number].'</td>
</tr><tr>
<td>Customer</td><td>'.$_SESSION[invoice][top][customer_number].' -- ' .$lookup[$lu].'</td>
</tr><tr>
<td>Comment</td><td>'.$_SESSION[invoice][top][Comment].'</td>
</tr><tr>

</table>
';

unset($lookup);
$uniq=array_keys($invdata);
$keys=array("Quantity","Package","Description","Domain","Cost Each","Cost");

$function=array(array("Delete","<a href=\"idell.php?ID=%UNIQ%\">Delete</a>"));

$rt=disptable($keys,$invdata,$uniq,$function,"usual",1);
print $rt;
print "TOTAL:£$total<br>";


print "<br>";

$sql="SELECT D_ID,domainn from domains ORDER BY domainn";

$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result) )
{
$lookup[$row[domainn]]="$row[domainn]";
}
$lookup[""]="";

$sql="SELECT idpackages,description from packages ORDER BY description";

$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result) )
{
$lookup2[$row[idpackages]]="$row[description]";
}





$desc=array("Quantity","Package","Description","Domain");

$function=array(array(0),array(2,$lookup2),array(0),array(2,$lookup),array(1));


$blank=array("Quantity"=>"","Package"=>"","Description"=>"","Domain"=>"","CostEach"=>"");



edittable("ibottom",0,"usual",$blank,$desc,$function);

$desc=array("Quantity","Package","Description","Domain","Cost Each");
$function=array(array(0),array(0,60),array(0,60),array(0,60),array(0));
edittable("icustom",0,"usual",$blank,$desc,$function);

if($_SESSION[invoice][top][customer_number]>0 && $invoices->countLineItems()>0)
{

$desc=array("Question");
$function=array(array(5));
$data=array("<font size='+2'><b>ARE YOU SURE THIS COMMITS INVOICE & EMAILS CUSTOMER<b></font>");
edittable("icommit",0,"usual",$data,$desc,$function);


}

$desc=array("Question");
$function=array(array(5));
$data=array("<font size='+2'>WIPE ALL DATA</font>");
edittable("ireset",0,"usual",$data,$desc,$function);



print <<<END
<br><br><br><br>
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="idisp.php?skip=1">Next--></a></td></tr></table></div>

<div class="bbuttontab2"><table class="backbutton2" width=111><tr><td>
<a href="iindex.php">Back</a></td></tr></table></div>

END;


//print_r($_SESSION[invoice]);

print "</pre>";



$_SESSION[invoicefunc]=serialize($invoices);
//disparray($lookup2,lk2);
//disparray($_SESSION[invoice]);
bottom();
