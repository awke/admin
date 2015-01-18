#!/usr/bin/php
<?php
$dbhost = "localhost";
$dbname = "adminInfo";
$dbuser = "awkeadminsoft";
$dbpasswd = "y3ipRLp";

require_once('/Supported/phpcode/sql.php');
require_once('/Supported/phpcode/email.php');

sqlconn();



$sql="SELECT * from invoices WHERE paid='0' AND void='0' AND ADDDATE(invoicedate,14)<=NOW() AND ADDDATE(invoicedate,15)>NOW()";

$result=gosql($sql,0);

while($rows=mysql_fetch_assoc($result))
{
$rows[string]="";

$rows[variable]=(unserialize(gzuncompress($rows[variable])));


$custid=$rows[variable][top][customer_number];



$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND idcustomers='$custid' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
//$rows[customer][email]=$email;
$rows[customer]=$custdet;
$gotemail=1;
}

$uniqid=$rows[uniqueidinvoice];
$custemail=$rows[customer][email];
$custname=$rows[customer][fname] . " " . $rows[customer][lname];


$to="admin@awke.co.uk";
$to=$custemail;
$from="admin@awke.co.uk";
$head="From: AWKE INVOICE REMINDER  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="AWKE invoice reminder [2 Week] for invoice number $rows[idinvoices] ";
$mid="AWKE Invoice number $rows[idinvoices] for the amount £$rows[total] was issued 2 weeks ago ($rows[invoicedate]) and according to our records has not been paid -(If you have made a payment within the last few days please ignore this) there is 2 weeks left before account associated with this will be disabled.  You can go to https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid to view the invoice and see methods of payment.\n\nAWKE Accounts";



//$mid="DEBUG:send to $custemail in the name of $custname\n".$mid;
//$subject="DEBUG:" . $subject;
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);




}




$sql="SELECT * from invoices WHERE paid='0' AND void='0' AND ADDDATE(invoicedate,25)<=NOW() AND ADDDATE(invoicedate,26)>NOW()";

$result=gosql($sql,0);

while($rows=mysql_fetch_assoc($result))
{
$rows[string]="";

$rows[variable]=(unserialize(gzuncompress($rows[variable])));


$custid=$rows[variable][top][customer_number];



$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND idcustomers='$custid' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
//$rows[customer][email]=$email;
$rows[customer]=$custdet;
$gotemail=1;
}

$uniqid=$rows[uniqueidinvoice];
$custemail=$rows[customer][email];
$custname=$rows[customer][fname] . " " . $rows[customer][lname];


$to="admin@awke.co.uk";
$to=$custemail;
$from="admin@awke.co.uk";
$head="From: AWKE INVOICE REMINDER  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="AWKE invoice reminder [25 day] for invoice number $rows[idinvoices] ";
$mid="AWKE Invoice number $rows[idinvoices] for the amount £$rows[total] was issued about 25 days ago ($rows[invoicedate]) and according to our records has not been paid - (If you have made a payment within the last few days please ignore this) there are only a few days left before account associated with this will be disabled.  You can go to https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid to view the invoice and see methods of payment.\n\nAWKE Accounts";



//$mid="DEBUG:send to $custemail in the name of $custname\n".$mid;
//$subject="DEBUG:" . $subject;
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);




}








$sql="SELECT * from invoices WHERE paid='0' AND void='0' AND ADDDATE(invoicedate,28)<=NOW() AND ADDDATE(invoicedate,29)>NOW()";

$result=gosql($sql,0);

while($rows=mysql_fetch_assoc($result))
{
$rows[string]="";

$rows[variable]=(unserialize(gzuncompress($rows[variable])));


$custid=$rows[variable][top][customer_number];



$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND idcustomers='$custid' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
//$rows[customer][email]=$email;
$rows[customer]=$custdet;
$gotemail=1;
}

$uniqid=$rows[uniqueidinvoice];

$custemail=$rows[customer][email];
$custname=$rows[customer][fname] . " " . $rows[customer][lname];


$to="admin@awke.co.uk";
$to=$custemail;
$from="admin@awke.co.uk";
$head="From: AWKE INVOICE REMINDER  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="AWKE invoice OVERDUE for invoice number $rows[idinvoices] ";
$mid="AWKE Invoice number $rows[idinvoices] for the amount of £$rows[total] was issued 4 weeks ago ($rows[invoicedate]) and according to our records has not been paid and is therefore overdue.  We will be starting disabling procedures imminently, if you believe this to be incorrect please email or phone us ASAP.  You can go to https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid to view the invoice and see methods of payment.\n\nAWKE Accounts";

//$mid="DEBUG:send to $custemail in the name of $custname\n".$mid;
//$subject="DEBUG:" . $subject;
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


}


$sql="SELECT * from invoices WHERE paid='0' AND void='0' AND ADDDATE(invoicedate,30)<=NOW() AND ADDDATE(invoicedate,31)>NOW()";

$result=gosql($sql,0);

while($rows=mysql_fetch_assoc($result))
{
$rows[string]="";

$rows[variable]=(unserialize(gzuncompress($rows[variable])));


$custid=$rows[variable][top][customer_number];



$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND idcustomers='$custid' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
//$rows[customer][email]=$email;
$rows[customer]=$custdet;
$gotemail=1;
}

$uniqid=$rows[uniqueidinvoice];

$custemail=$rows[customer][email];
$custname=$rows[customer][fname] . " " . $rows[customer][lname];
$domainsinfo="";
foreach ($rows[variable][data] as $var)
{
$domainsinfo.="$v[Package] for $v[Domain]\n";
}

$to="admin@awke.co.uk";
$from="admin@awke.co.uk";
$head="From: AWKE INVOICE REMINDER  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject=" ";
$mid="AWKE Invoice number $rows[idinvoices] for the amount of £$rows[total] has not been paid and is therefore overdue.  The accounts associated with this (listed below) need disabling (recommended is to go to phpmyadmin and edit the domain record and set deactivated=1 (leave dns=1 to keep dns alive) this will stop all emails.  Then you need to add [ RedirectMatch 307 .* http://www.awke.co.uk/NOTPAID.php ] into the extraconf of the web config. Also is it worth voiding the invoice as they arent going to pay\n\n$domainsinfo\n\nAWKE Accounts";

//$mid="DEBUG:send to $custemail in the name of $custname\n".$mid;
//$subject="DEBUG:" . $subject;
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


}
