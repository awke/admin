#!/usr/bin/php
<?php
$dbhost = "localhost";
$dbname = "adminInfo";
$dbuser = "awkeadminsoft";
$dbpasswd = "y3ipRLp";

require_once('/Supported/phpcode/sql.php');
require_once('/Supported/phpcode/email.php');

sqlconn();


$file=file("/Supported/temp/whoisinfo.txt");
$data=unserialize($file[0]);

//print_r($data);

foreach($data as $domain => $dat)
{
$Tag=$dat[ret][Tag];
$renewal=$dat[ret][RenewalDate];
//print ":$Tag";
if(strcmp($dat[ret][Tag],"UKHOSTS")==0)
{
$ts=strtotime($renewal);
$date=date('Y-m-d',$ts);
print "$domain";
print ":$renewal";
print ":$ts";
print ":$date";

$sha=sha1("$domain$renewal");

print ":$sha";



$sql="SELECT D_ID from domains where domainn='$domain'";
$res=gosql($sql);
$rows=mysql_fetch_assoc($res);

$D_ID=$rows[D_ID];

$sql="INSERT into domainrenewals set renewaldate='$date',D_ID='$D_ID', uniqueid='$sha'";
gosql($sql,0);

$sql="SELECT * from domainrenewals WHERE renewaldate='$date' AND D_ID='$D_ID'";
$res=gosql($sql);
$rows=mysql_fetch_assoc($res);
$stage=$rows[stage];
$renew=$rows[renew];
$uniqueid=$rows[uniqueid];
$id=$rows[DR_ID];

$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND domains_D_ID='$D_ID' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
$gotemail=1;
}
else
{
print ":   ****** NOVALIDEMAIL *****";
$gotemail=0;
$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND domains_D_ID='$D_ID' ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
$rows=mysql_fetch_assoc($res);
//print_r($rows);
$cust=$rows[fname] . " " . $rows[lname];
notvalidemail($domain,$cust);
}

$tnow=time();

$tcheck=$tnow+(60*24*60*60);


if($tcheck>$ts && $stage==0  && $gotemail==1  & $renew==0)
{
print ":   LESS THAN 60 days left";

$to="admin@awke.co.uk"; // change to $email
$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN RENEWAL REMINDER <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="Your domain $domain is due for renewal in about 60 days";
$mid="Supposed to be sent to $email\n";
$mid="";
$mid=$mid . "Hi,\nYour domain $domain is due for renewal within about 60 days ($renewal). Please click on the link below to request renewal or indicate it is not to be renewed\nhttps://secure.awke.co.uk/admin/userdomainrenewal.php?ID=$uniqueid\n\nThanks\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


$sql="UPDATE domainrenewals set stage='1' WHERE DR_ID='$id'";


gosql($sql);


}

$tcheck=$tnow+(30*24*60*60);


if($tcheck>$ts && $stage==1  && $gotemail==1  & $renew==0)
{
print ":   LESS THAN 30 days left";

$to="admin@awke.co.uk"; // change to $email
$email;
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN RENEWAL REMINDER <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="Your domain $domain is due for renewal in about a month";
$mid="Supposed to be sent to $email\n";
$mid="";
$mid=$mid . "Hi,\nYour domain $domain is due for renewal within about a month ($renewal). Please click on the link below to request renewal or indicate it is not to be renewed\nhttps://secure.awke.co.uk/admin/userdomainrenewal.php?ID=$uniqueid\n\nThanks\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


$sql="UPDATE domainrenewals set stage='2' WHERE DR_ID='$id'";


gosql($sql);


}

$tcheck=$tnow+(7*24*60*60);

if($tcheck>$ts && $stage==2  && $gotemail==1  & $renew==0)
{
print ":   LESS THAN 7 days left";

$to="admin@awke.co.uk"; // change to $email
$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN RENEWAL REMINDER <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="** IMPORTANT ** Your domain $domain is due for renewal in about a week";
$mid="Supposed to be sent to $email\n";
$mid="";
$mid=$mid . "Hi,\nYour domain $domain is due for renewal within about a week ($renewal). Please click on the link below to request renewal or indicate it is not to be renewed\nhttps://secure.awke.co.uk/admin/userdomainrenewal.php?ID=$uniqueid\n\nThanks\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


$sql="UPDATE domainrenewals set stage='3' WHERE DR_ID='$id'";


gosql($sql);


}

$tcheck=$tnow+(1*24*60*60);

if($tcheck>$ts && $stage==3  && $gotemail==1  & $renew==0)
{
print ":   LESS THAN 0 days left";

$to="admin@awke.co.uk"; // change to $email
$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN RENEWAL REMINDER <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="** URGENT ** Your domain $domain is due for renewal NOW";
$mid="Supposed to be sent to $email\n";
$mid="";
$mid=$mid . "Hi,\nURGENT: Your domain $domain is due for renewal NOW. Please click on the link below to request renewal or indicate it is not to be renewed\nhttps://secure.awke.co.uk/admin/userdomainrenewal.php?ID=$uniqueid\n\nThanks\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


$sql="UPDATE domainrenewals set stage='4' WHERE DR_ID='$id'";


gosql($sql);


}



$tcheck=$tnow;
$tsc=$ts+(2*24*60*60);
//stage 4
if($tcheck>$ts && $tsc<$tcheck && $stage==4  && $gotemail==1  & $renew==0)
{
print ":   OVERDUE";

$to="admin@awke.co.uk"; // change to $email
$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN RENEWAL REMINDER <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="*** URGENT *** Your domain $domain is OVERDUE for renewal";
$mid="Supposed to be sent to $email\n";
$mid="";
$mid=$mid . "Hi,\nURGENT: Your domain $domain is OVERDUE for renewal (due $renewal). Please click on the link below to request renewal or indicate it is not to be renewed\nhttps://secure.awke.co.uk/admin/userdomainrenewal.php?ID=$uniqueid\n\nThanks\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


$sql="UPDATE domainrenewals set stage='5' WHERE DR_ID='$id'";


gosql($sql);


}


$tcheck=$tnow;
$tsc=$ts+(7*24*60*60);
//stage 5
if($tcheck>$ts && $tsc<$tcheck && $stage==5  && $gotemail==1  & $renew==0)
{
print ":   FINAL REMINDER";

$to="admin@awke.co.uk"; // change to $email
$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN RENEWAL REMINDER <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="*** URGENT *** FINAL REMINDER Your domain $domain is OVERDUE for renewal";
$mid="Supposed to be sent to $email\n";
$mid="";
$mid=$mid . "Hi,\nURGENT: Your domain $domain is OVERDUE for renewal and this is you FINAL REMINDER it was due for renewal on $renewal. Please click on the link below to request renewal or indicate it is not to be renewed\nhttps://secure.awke.co.uk/admin/userdomainrenewal.php?ID=$uniqueid\n\nThanks\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


$sql="UPDATE domainrenewals set stage='6' WHERE DR_ID='$id'";


gosql($sql);


}



$tcheck=$tnow;
$tsc=$ts+(14*24*60*60);
//stage 6
if($tcheck>$ts && $tsc<$tcheck && $stage==6  && $gotemail==1  & $renew==0)
{
print ":   STATUS UPDATE TO AWKE";

$to="admin@awke.co.uk"; 
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN RENEWAL REMINDER SCRIPT <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="No responce to domain renewal of $domain";
$mid=$mid . "There has been no responce to the request to confirm or deny renewal of $domain which was due for renewal on $renewal.\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


$sql="UPDATE domainrenewals set stage='7' WHERE DR_ID='$id'";


gosql($sql);


}


print "\n";
}


}



function notvalidemail($domain,$cust)
{
$to="admin@awke.co.uk";
$from="admin@awke.co.uk";
$head="From: ADMIN DOMAIN RENEWAL REMINDER SCRIPT <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="no email address configured for customer ($cust)/ domain ($domain)";
$mid="There is no email address / customer configured for customer ($cust)/ domain ($domain) so we couldnt send any emails to them";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


}
