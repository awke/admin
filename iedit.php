<?php
$HEADER=0;
include('functions.inc');
sqlconn();

require_once("/Data/websites/awke.co.uk/secure/wwwroot/admin/invoices.php");

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][idinvoices][$_GET[id]];


$sql="SELECT * FROM invoices WHERE idinvoices='$customerid'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
if($row[void]==1)
{
$row[void]=0;
$sql="UPDATE invoices set void='$row[void]' WHERE idinvoices='$customerid'";

gosql($sql);

$_SESSION[error][]="YOU NEED TO MANUALLY inform the user that the invoice is now not voided.";
}
else
{
$row[void]=1;
require_once("invoices.php");

$invoice=new invoices();

//$invoices->loadInvoice($customerid);
$invoice->void($customerid);

$_SESSION[error][]="USER HAS BEEN EMAILED TO INFORM THEM";
}



header("Location: iindex.php");





