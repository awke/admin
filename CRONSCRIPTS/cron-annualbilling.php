#!/usr/bin/php
<?php
$dbhost = "localhost";
$dbname = "adminInfo";
$dbuser = "awkeadminsoft";
$dbpasswd = "y3ipRLp";
$NOBOOKING=1;
$PRELOGIN=1;
$HEADER=0;
require_once('/Supported/domains/awke.co.uk/secure/wwwroot/admin/functions.inc');



require_once('/Supported/phpcode/sql.php');
require_once('/Supported/phpcode/email.php');

sqlconn();

require_once("/Supported/domains/awke.co.uk/secure/wwwroot/admin/invoices.php");





$sql="SELECT * from domains WHERE deactivated='0'";

$resulting=gosql($sql,0); 

while($rows=mysql_fetch_assoc($resulting))
{
$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND domains_D_ID='$rows[D_ID]' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";

$res=gosql($sql);
$fetched=mysql_fetch_assoc($res);

$dataa[$rows[D_ID]]=$fetched[idcustomers];

}
asort($dataa);
print_r($dataa);

foreach($dataa as $keys=>$values)
{

//print "$keys:$values\n";


$sql="SELECT * from domains WHERE D_ID='$keys'";

$resulting=gosql($sql,0); 
$rows=mysql_fetch_assoc($resulting);
print "DOMAIN::$rows[domainn]\n";

$domain=$rows[domainn];


$today=dispdate2(getcurdate());
$dat=$today; //$_SESSION[invoice][top][date];


$sql="SELECT * from packdom WHERE domains_D_ID='$rows[D_ID]' AND psdate<='$dat' AND pedate>='$dat'";

$res=gosql($sql,0);

if(mysql_num_rows($res)<1)
{
//error no package
print "ERROR NO PACKAGE for $rows[D_ID] $rows[domainn]\n";



$to="admin@awke.co.uk"; // change to $email
//$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE ANNUAL INVOICE <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="DOMAIN $domain is missing package data";
$mid="";
$mid=$mid . "Domain ($domain) is missing package data for its annual invoice\n";

$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


// CHANGE TO USE NEW EMAILER









}
else
{
unset($_SESSION[invoice]);

$row1=mysql_fetch_assoc($res);


$package=$row1[packages_idpackages];

$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND domains_D_ID='$rows[D_ID]' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
$customerid=$custdet[idcustomers];
$custname=$custdet[fname];
$gotemail=1;
}
else
{
//no customer email



$to="admin@awke.co.uk"; // change to $email
//$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE ANNUAL INVOICE <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="customer $custdet[fname] $custdet[idcustomers] for $domain is missing email";
$mid="";
$mid=$mid . "customer $custdet[fname] $custdet[idcustomers] for $domain is missing email\n";
$mid=$mid. print_r($rows,TRUE);
$mid=$mid."\n$sql\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


}


$invoice=new invoices();

// FOR TESTING PURPOSES

$invoice->testing();


$invoice->setCustomerID($customerid);



$curdate=getcurdate();
$invoice->setInvoiceCreateDate($curdate);

// fudge to make the 1st Feb if run later. such as 2011 while waiting for new bank account.

$curdate[0]=1;
$curdate[1]=2;

$invoice->setInvoiceDate(dispdate2($curdate));


$dt[Quantity]=1;

$dt[Domain]=$rows[domainn];
$dt[Description]="";
$dt[Package]=$package;

$invoice->addPackage($dt);


// CHANGE BELOW TO USE THE new invoice function addPackage($dt)



//credits

$sql="SELECT * from accountcredits WHERE domain='$rows[D_ID]' AND year=YEAR(NOW())";

$rest=gosql($sql);

if(mysql_num_rows($rest)>0)
{
$datt=mysql_fetch_assoc($rest);

if($package==1)
{
if($datt[credit]<0)
{
$packs=38;
$dt[Description]="To Bring Billing to standard invoice date";
}
else
{
$packs=37;
$dt[Description]="Credit applied to your account";
}
}
elseif($package==17)
{
$packs=17;
$dt[Description]="Correction factor (17)";
}
elseif($package==15)
{
$packs=15;
$dt[Description]="Correction factor (15)";
}
elseif($package==32)
{
$packs=39;
$dt[Description]="Correction factor (32)";
}
else
{
$packs=36;
$dt[Description]="ERROR UNKNOWN PACKAGE FOR CORRECTION";
print " ****** DISCOUNT ERROR ****** ";
}

$dt[Quantity]=abs($datt[credit]);

$dt[Domain]=$rows[domainn];

$dt[Package]=$packs;

if($dt[Quantity]!=0)
{
$invoice->addPackage($dt);
}



}
else
{
// no credits needed
}



//print_r($_SESSION);


// CHANGE REPLACE WITH NEW INVOICE CLASS to create invoices

$subject="AWKE Annual Invoice #@IRN@ for $rows[domainn]";
$domainname=$rows[domainn];
$body="@FNAME@ @LNAME@,\n\nHi your AKWE annual invoice number @IRN@ for $domainname has been generated and is attached and is also available to view at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=@UNIQID@\nPlease use your invoice number as a reference on your payment and then send an email to accounts@awke.co.uk\n\nIf you have any technical queries then please feel free to contact us at support@awke.co.uk.\n\nPlease note that if your invoice is not paid within 28 days then your account will be automatically suspended.  A reminder email will be sent shortly before this date.\nThanks\n\nAWKE Accounts\n";


$invoice->setemailsubject($subject);
$invoice->setemailbody($body);

$invoice->commit();

//print_r($returned);
}




}
