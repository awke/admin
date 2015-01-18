<?php
require_once("./invoices.php");

$invoice=new invoices();

$invoice->testing();
$invoice->debug222(); 

exit();

$invoice->loadInvoice(416);

$invoice->displayInvoice();

