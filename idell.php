<?php
$HEADER=0;
include('functions.inc');
sqlconn();



require_once("invoices.php");



//disparray($_GET,get);
//disparray($_SESSION,before);

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



$rem=$_SESSION[invoices][ilid][$_GET[ID]];
//print $rem;
unset($_SESSION[invoice][data][$rem]);

$invoices->deleteLineItem($_GET[ID]);

//$invoices->debug();

//disparray($_SESSION,"after _SESSION[invoice][data][$_GET[ID]]");
$_SESSION[invoicefunc]=serialize($invoices);
header("Location:iadd.php");

