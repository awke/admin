#!/usr/bin/php
<?php
$MIN=10;
$MINPERCENT=20;
$PERCENT=91;

require_once('/Supported/phpcode/email.php');

require_once('/Supported/phpcode/files.php');

require_once('/Supported/phpcode/logging.php');

require_once('/Supported/phpcode/sql.php');

$LOGGER_ID='DiskSpace Warning';


$dbhost = "localhost";
$dbname = "adminInfo";
$dbuser = "awkeadminsoft";
$dbpasswd = "y3ipRLp";
 $reload=0;

sqlconn();
logsys("Started");


function notvalidemail($domain,$cust)
{
$to="admin@awke.co.uk";
$from="admin@awke.co.uk";
$head="From: ADMIN DOMAIN DISK SPACE SCRIPT <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="no email address configured for customer ($cust)/ domain ($domain)";
$mid="There is no email address / customer configured for customer ($cust)/ domain ($domain) so we couldnt send any emails to them";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


}


$dirs=scandir("/Supported/domains/");
unset($dirs[0]);
unset($dirs[1]);
//print_r($dirs);

foreach($dirs as $v)
{
$diskspacefree=(disk_free_space("/Supported/domains/$v")/1024/1024);
$diskspacetotal=(disk_total_space("/Supported/domains/$v")/1024/1024);
$diskspaceused=$diskspacetotal-$diskspacefree;
$percent=$diskspaceused/$diskspacetotal*100; 
$doms[$v][free]=$diskspacefree;
$doms[$v][used]=$diskspaceused;
$doms[$v][total]=$diskspacetotal;
$doms[$v][percent]=$percent;


//print "$v: $diskspacetotal $diskspaceused $diskspacefree $percent\n";

$lowspace=0;
if($diskspacefree<$MIN)
{
$lowspace=1;
logsys(" $v $diskspacefree Mb left");
}
if($percent>$PERCENT && $diskspacefree<$MINPERCENT)
{
$lowspace=1;
logsys("$v $diskspacefree Mb -> $percent%");
}

if($lowspace==1)
{
$warning[$v]=1;
$doms[$v][warning]=1;
//print "WARNING";
}


}

foreach($doms as $k => $v)
{
if($v[warning]==1)
{
$sql="SELECT * from domains WHERE directory='$k' ";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);

$D_ID=$row[D_ID];
$domain=$row[domainn];
$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND domains_D_ID='$D_ID' AND CHAR_LENGTH(email)>4 ORDER BY pri DESC LIMIT 1";
$res=gosql($sql,0);
if(mysql_num_rows($res)>0)
{
$custdet=mysql_fetch_assoc($res);
$email=$custdet[email];
$gotemail=1;
}
else
{
logsys("$D_ID:   ****** NOVALIDEMAIL *****");
$gotemail=0;
$sql="SELECT * from customers,custdom,custemail WHERE custdom.customers_idcustomers=idcustomers AND custemail.customers_idcustomers=idcustomers AND domains_D_ID='$D_ID' ORDER BY pri DESC LIMIT 1";
$res=gosql($sql);
$rows=mysql_fetch_assoc($res);
//print_r($rows);
$cust=$rows[fname] . " " . $rows[lname];
notvalidemail($domain,$cust);
}

$percent=sprintf('%0.2f',$v[percent]);
$free=sprintf('%0.2f',$v[free]);
$total=sprintf('%0.2f',$v[total]);


$to="admin@awke.co.uk"; // change to $email
//$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE DOMAIN DISK SPACE WARNING <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="Your domain $domain is running out of disk space";
$mid="Supposed to be sent to $email\n";
//$mid="";

if($total<232)
{
$morespace=<<<END
When we create the account we automatically assign 66M of space +33M overhead, as many people dont use the full amount [upto 233M  (including overhead) is available for no extra charge with the standard webhosting package, after this charge can be applied.]  If you wish us to increase your amount of disk space please email/contact us and let us know how much more you want allocated.
END;
}
else
{
$morespace=<<<END
As you already have used the included disk space allowance (for standard webhosting package) any more space you request is subject to availability & us charging extra.  If you wish us to increase your amount of disk space please contact us and we can discuss your requirements.
END;
}

$mid=$mid . "Hi,\nYour domain $domain is running out of diskspace.  It currently has $free Mb free from a total of $total Mb = $percent% used.  Please note there is a 33Mb overhead of space that the system uses shown in the used & total figures. $morespace\n\nThanks\nAWKE Admin\n";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);


}
}
logsys("Finishing");


