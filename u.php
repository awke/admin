<?php
$NOBOOKING=1;
$PRELOGIN=1;
$HEADER=1;
unset($_SESSION);
require_once('functions.inc');
require_once('/Supported/phpcode/email.php');
require_once('./invoices.php');
sqlconn();
$id=$_GET[ID];

$sql="SELECT * from domainrenewals,domains WHERE uniqueid='$id' AND domainrenewals.D_ID=domains.D_ID";

$res=gosql($sql,0);

if(mysql_num_rows($res)<1)
{
print <<<END
<div class="infolabel">NO data found for this ID</div>

END;
exit();
}

$rows=mysql_fetch_assoc($res);

$domain=$rows[domainn];
$renid=$rows[DR_ID];
$renewaldate=$rows[renewaldate];
print_r($_POST);

if(isset($_POST[renew]) && $rows[renew]==1)
{
$to="admin@awke.co.uk";
$from="admin@awke.co.uk";
$head="From: USER DOMAIN RENEWAL  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="customer has confirmed they want $domain renewed";
$mid="customer has confirmed they want $domain renewed via the website, UKHOSTS have already been emailed with the request!";
//$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$to="admin@awke.co.uk"; //replace with sales@ukhosts.com
$to="support@ukhosts.com";
$from="admin@awke.co.uk";
$head="From: AWKE Admin (Script)  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="Please renew $domain";
$mid="Should be sent to UKHOSTS but this is not for debug reasons\n";
$mid="";
$mid=$mid . "Hi could you please renew $domain.\nThanks\n\nAWKE Admin\n";
//$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$sql="UPDATE domainrenewals set renew='1' WHERE DR_ID='$renid'";

gosql($sql);

//invoice creation 

$D_ID=$rows[D_ID];


$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND domains_D_ID='$D_ID' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
$customerid=$custdet[idcustomers];
$gotemail=1;
}

$oit=explode(".",$domain);

$cnt1=count($oit);
$inv=0;
print <<<END
<div class="subtabinfo">
END;
print_r($oit);
print "de:$oit:::$cnt1@@";

if($cnt1>2)
{
// could be uk

if(strcasecmp("uk",$oit[2])== 0)
{
$inv=1;
$package=7;
}
}
$ci=$cnt1-1;
$lastpart=$oit[$ci];
$c=$lastpart;



print "<BR>hu:$ci:$lastpart:$c{{";
if(strcasecmp("info",$c)==0)
{
$inv=1;
$package=8;
}
if(strcasecmp("com",$c)==0)
{
$inv=1;
$package=8;
}
if(strcasecmp("net",$c)==0)
{
$inv=1;
$package=8;
}
if(strcasecmp("org",$c)==0)
{
$inv=1;
$package=8;
}



print "DEBUG\nDEBUG\n";

print "DEB:$inv::$package\n";

if($inv==1)
{
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

print_r($invoice);

//$invoice->commit();

$uniqid=$invoice->getUniqID();


$to1="admin@awke.co.uk"; //replace with sales@ukhosts.com
$to="$email";
$from="admin@awke.co.uk";
$head="From: AWKE Account   <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="Invoice for domain renewal of  $domain";
$mid="Should be sent to UKHOSTS but this is not for debug reasons\n";
$mid="";
$mid=$mid . "Hi your invoice for renewal of $domain is available to view now at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid\nThanks\n\nAWKE Accounts\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$subject1=$subject." -- COPY";
//$return=sock_mail($auth,$to1, $subject1, $mid, $head, $from);


$rows[renew]=1;
}

}
if(isset($_POST[notwanted]) && $rows[renew]==0)
{
//renew NOT wanted
$to="admin@awke.co.uk";
$from="admin@awke.co.uk";
$head="From: USER DOMAIN RENEWAL  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="customer has confirmed they DO NOT want $domain renewed";
$mid="customer has confirmed they DO NOT want $domain renewed via the website, UKHOSTS have already been emailed with the request!";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$to="admin@awke.co.uk"; //replace with sales@ukhosts.com
$to="sales@ukhosts.com";
$from="admin@awke.co.uk";
$head="From: AWKE Admin (Script)  <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="DO NOT RENEW $domain";
$mid="Should be sent to UKHOSTS but this is not for debug reasons\n";
$mid="";
$mid=$mid . "Hi please DO NOT RENEW $domain.\nThanks\n\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$sql="UPDATE domainrenewals set renew='-1' WHERE DR_ID='$renid'";

gosql($sql);

$rows[renew]=-1;

}




print <<<END
<div class="infolabel">DOMAIN RENEWAL FOR $rows[domainn]</div>
<div class="subtabinfo">

END;

//print_r($rows);
if($rows[renew]==1)
{
print <<<END

<form method="POST">
<input type="submit" name="renew" value="RENEW for further period">***
<br><br>



<input type="submit" name="notwanted" value="NOT WANTED anymore do NOT renew">
<br>

<p>***A further period is 2 years for .uk domain normally £12 for the period. For most other domains the period is 1 year and for many this is £12 for the period as well.  You are welcome to contact us to confirm this info</p>

END;

}
else
{
if($rows[renew]==-1)
{
$status="NOT RENEW";
}
else
{
$status="RENEW, domain is now confirmed for a further period";
}
print <<<END
<p>You have already made your decision about this domain, if this is incorrect please email <a href="mailto:admin@awke.co.uk">admin@awke.co.uk</a> or else ring us</a></p>
<h2>You decided to $status</b></h2>
END;
}
