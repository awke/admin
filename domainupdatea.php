<?php 
$HEADER=0;
include('functions.inc');
sqlconn();


require_once('./invoices.php');
require_once('/Supported/phpcode/email.php');


if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$array=retedittable("add",0);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

$array[1][directory]=$array[1][domainn];
$array[1][lastupdated]=date('YmdHis');

$domainname=$array[1][domainn];
$sql="SELECT domainn FROM domains WHERE domainn='$domainname'";
$result=gosql($sql,0);

if(mysql_num_rows($result)>=1)
{
$error=1;
$_SESSION[error][]="Domain name already in use";
}


if ($array[1][registerdomain]!=0 || strcmp($array[1][registrant],"")!=0 || strcmp($array[1][registrantcompany],"")!=0)
{
$error=1;
$_SESSION[error][]="AUTOMATIC DOMAIN REGISTRATION with InterntetBS IS NOT ENABLED YET";
}




if (strcmp($array[1][username],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter a username";
}

if (ereg("[[:space:]]", $array[1][username]))
{ 
$error=1;
$_SESSION[error][]="Please correct username";
}

if (ereg("[[:space:]]", $array[1][domainn]))
{ 
$error=1;
$_SESSION[error][]="Please correct domain name";
}

if (strlen($array[1][username]) >= 9)
{
$error=1;
$_SESSION[error][]="User Name must be 8 or less characters";
}

if (strcmp($array[1][password1],"")==0)
{
$array[1][password1]=passgen();
}
if (strcmp($array[1][password2],"")==0)
{
$array[1][password2]=passgen();
}

if ($array[1][customerid] == 0 )
{
$error=1;
$_SESSION[error][]="Choose a customer";
}

if ($array[1][packageid] == 0 )
{
$error=1;
$_SESSION[error][]="Choose a package";
}
if($error==0)
{

$data=print_r($array[1],TRUE);
require_once('/Supported/phpcode/email.php');

$to="admin@awke.co.uk"; // change to $email
$from="admin@awke.co.uk";
$head="From: AWKE ADD NEW DOMAIN PAGE debug <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="AWKE Add new domain page DEBUG";
$mid="";
$mid=$mid . "This is debug array[1] contains\n\n $data";
//$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$wantedemail=new my_phpmailer(TRUE);
try
{
  $wantedemail->AddAddress("admin@awke.co.uk", "AWKE Admin");
  $wantedemail->SetFrom('admin@awke.co.uk', 'AWKE ADD NEW DOMAIN PAGE (Script)');
// $wantedemail->AddCC('admin@awke.co.uk','AWKE Admin'); 
//$wantedemail->ClearReplyTos();
// $wantedemail->AddReplyTo('support@awke.co.uk', 'AWKE Accounts');
 $wantedemail->Subject="AWKE Add new domain page $domainname";
$wantedemail->Body="This is debug array[1] contains\n\n $data";
$wantedemail->Send();
}
 catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}


$packageid=$array[1][packageid];
unset($array[1][packageid]);
$customerid=$array[1][customerid];
unset($array[1][customerid]);

$userprivid=$array[1][privuser];
unset($array[1][privuser]);
$domainregistered=$array[1][domainregistered];
unset($array[1][domainregistered]);

$registerdomains=$array[1][registerdomain];
unset($array[1][registerdomain]);
$registerdomainsname=$array[1][registrant];
unset($array[1][registrant]);
$registerdomainscompany=$array[1][registrantcompany];
unset($array[1][registrantcompany]);
$registerdomainstype=$array[1][registranttype];
unset($array[1][registranttype]);


	

foreach($array[1] as $key => $value)
{
if ($key != "customerid")
	{
	if($first==0)
		{
		$first=1;
		}
	else
		{
		$sqlins=$sqlins . ", ";
		}
	$sqlins=$sqlins . "$key ='$value' ";
	}
}
$sql="INSERT INTO domains SET $sqlins";
gosql($sql,0);
clredittable("add",0);

$domain=mysql_insert_id();

if (($domain!==0) && ($domain!==false))
	{
	/*
	$date=date('YmdHis');
	$dnsdata=dnsdetails();

	foreach ($dnsdata as $key => $value)
		{
		$details = explode(" ", $value);
		if (isset($details[3]))
			{
			$sql="INSERT INTO dnsrecords SET domain_id='$domain', name='$details[0]', type='$details[1]', content='$details[2]', prio='$details[3]', change_date='$date'";
			gosql($sql,0);
			}
			else
			{
			$sql="INSERT INTO dnsrecords SET domain_id='$domain', name='$details[0]', type='$details[1]', content='$details[2]', change_date='$date'";
			gosql($sql,0);
			}
		}
	*/


$sql="INSERT into packdom SET packages_idpackages='$packageid',domains_D_ID='$domain'";

gosql($sql,0);



$sql="INSERT into userpriv SET idauthusers='$userprivid',D_ID='$domain',privdev_idprivdev='0'";

gosql($sql,0);

	$sql="INSERT INTO superusercommands SET command='10',  parameters='$domain'"; //, lastupdated='$date'";
	gosql($sql,0);

	if ($customerid != "0")
		{
		$sql="INSERT INTO custdom SET customers_idcustomers='$customerid', domains_D_ID='$domain'";
		gosql($sql,0);
		}
		
		
		
//invoicing		
		
		
		$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND idcustomers=$customerid AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
$custdet=mysql_fetch_assoc($res);
$customeremail=$custdet[email];

		$invoice=new invoices();


		
$invoice->setCustomerID($customerid);
$invoice->setInvoiceDate(dispdate2(getcurdate()));
$date=dispdate2(getcurdate());





// domain registration
if($domainregistered==1)
{
//$domain=$domainname;

$oit=explode(".",$domainname);

$cnt1=count($oit);
$inv=0;
if($cnt1>2)
{
// could be uk

if(strcasecmp("uk",$oit[2])== 0)
{
$inv=1;
$package=7;
}
}
$c=$cnt1-1;
$lastpart=$oit[$c];
$c=$lastpart;

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


$dt[Quantity]=1;

$dt[Domain]=$domainname;
$dt[Description]="Purchase of domain name";
$dt[Package]=$package;

$invoice->addPackage($dt);

}


// hosting

$dt[Quantity]=1;
$dt[Domain]=$domainname;
$dt[Description]="";
$dt[Package]=$packageid;

$invoice->addPackage($dt);

$checktotal=$invoice->getTotal();

// do calculation for crediting to end feb
if($checktotal>0)
{
$tsendfeb=mktime(0,1,1,2,1,date("Y"));
$tsnow=time();

if($tsnow >= $tsendfeb)
{
$Year=date("Y")+1;
}
else
{
$Year=date("Y");
}
$tsendfebc=mktime(0,1,1,2,1,$Year);

$tsdiff=$tsendfebc-$tsnow;

$tsdiffc=$tsdiff/60/60/24/30;


//months difference
$tsdiffr=round($tsdiffc,0);

$refundquant=12-$tsdiffr;


$packs=37;
$dt[Description]="Refund to bring billing to 1st Feb";
$dt[Quantity]=$refundquant;
$dt[Domain]=$domainname;

$dt[Package]=$packs;
if($refundquant>0)
{

$invoice->addPackage($dt);

if($refundquant>8 && $refundquant!=12)
{

if($domainregistered==1)
{
//Carry on partial bill as small hosting due with domain renewal is sensible
}
else
{
$debt=12-$refundquant;
$debt=$debt*-1;

$sql="INSERT into accountcredits VALUES  domain='$rows[D_ID]',year=YEAR(NOW()),credit=$debt ON DUPLICATE KEY UPDATE credit=credit+$debt ";

$res=gosql($sql);

$_SESSION[error][] = "Not sending invoice as its only a small amount and no domain registration,adjusted credit value so on annual bill it will correct";
$invNOCOMMIT=1;
}

}

}

}

$invoice->setemailsubject("AWKE Invoice @IRN@ for services for $domainname on AWKE");
$invoice->setemailbody("@FNAME@ @LNAME@,\n\nHi your invoice @IRN@ for purchase of services for $domainname is attached and is also available to view at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=@UNIQID@\nThanks\n\nAWKE Accounts\n");

$invoice->commit();

$uniqid=$invoice->getUniqID();

//$to=$customeremail;
$to="admin@awke.co.uk"; //replace with customers email

$from="admin@awke.co.uk";
$head="From: AWKE Account   <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="Invoice for services for $domain on AWKE";
$mid="Should be sent to customer but this is not for debug reasons please check and if ok send customer an email will invoice id info\n";
$mid="";
$mid=$mid . "Hi your invoice for purchase of services $domain is available to view now at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid\nThanks\n\nAWKE Accounts\n";

$subject="DEBUG -- " .$subject." -- DEBUG";

//$return=sock_mail($auth,$to, $subject, $mid, $head, $from);




		
		
//	}


/*
else
	{ 
	$_SESSION[error][] = "MySQL Error";
	header("Location: domainadd.php");
	exit();
	}
	*/
	
//require_once('/Supported/phpcode/superuser.php');
//$sql="SELECT * FROM domains WHERE D_ID='$domain'";
//$result45=gosql($sql);
//$domainrow=mysql_fetch_assoc($result45);
//dnsinitalise($domainrow); 
header("Location: domain.php");
}
else
{//error=1 therefor error

header("Location: domainadd.php");
exit();
}

}
}

	$_SESSION[error][] = "OPPS got to end and not gone through - no error here but look at previous errors";
	header("Location: domainadd.php");
?>
