<?php

require_once("/Supported/domains/awke.co.uk/secure/wwwroot/admin/invoices.php");



//exit();

$domain="smegfirk.co.uk";
$package=7;
$renewaldate="2009-04-06";
$customerid=12;
//$package is correct package;
$invoice=new invoices();


$invoice->setCustomerID($customerid);
$invoice->setInvoiceDate(dispdate2(getcurdate()));
$date=dispdate2(getcurdate());



$dt[Quantity]=1;

$dt[Domain]=$domain;
$dt[Description]="Renewal (Expires: $renewaldate)";
$dt[Package]=$package;

$invoice->addPackage($dt);



$invoice->createString();


//$invoice->loadInvoice(296);
$invoice->commit();

$uniqid=$invoice->getUniqID();

$invoice->debug();


exit();



$email="banabh@hotmail.com";
$to1="admin@awke.co.uk";
$to="$email";
$from="admin@awke.co.uk";
$head="From: AWKE Account   <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="Invoice for domain renewal of  $domain  - CORRECTED";
$mid="Should be sent to UKHOSTS but this is not for debug reasons\n";
$mid="";
$mid=$mid . "Hi your invoice for renewal of $domain is available to view now at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid\nThanks\n\nAWKE Accounts\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$subject1=$subject." -- COPY";
$return=sock_mail($auth,$to1, $subject1, $mid, $head, $from);





exit();


print $invoicesobj->getTotal();

print "\n";

print $invoicesobj->getCountItems();
print "\n";
print "\n";
print "\n";

//print $out; 
