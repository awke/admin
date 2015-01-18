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







function invoicetable($data)
{


$ret=$ret. sprintf ("\n\n\r\n<table class=\"invoicetable\"><thead><tr>");

$headers=array("Quantity","Package","Description","Domain","Cost Each","Cost");
foreach($headers as $value)
{
$ret=$ret. sprintf ("<th>$value</th>");
}
$ret=$ret. sprintf ("</tr></thead>\n\r\n");

$row=0;
foreach($data as $key=>$value)
{
if($row==0)
{
$class="even";
$row=0;
}
else
{
$class="odd";
$row=1;
}

$ret=$ret. sprintf ("\n\r\n<tr class=\"$class\">");
$total=$total+$value["Cost"];
$value["Cost"]= "&pound;" . sprintf("%.02f",$value["Cost"]);
$value["CostEach"]= "&pound;" . sprintf("%.02f",$value["CostEach"]);

$ret=$ret. sprintf ("<td>$value[Quantity]</td>");

$ret=$ret. sprintf ("<td>$value[Package]</td>");

$ret=$ret. sprintf ("<td>$value[Description]</td>");

$ret=$ret. sprintf ("<td>$value[Domain]</td>");

$ret=$ret. sprintf ("<td>$value[CostEach]</td>");

$ret=$ret. sprintf ("<td>$value[Cost]</td>");



$ret=$ret. sprintf ("</tr>\n\r\n");
}
$ret=$ret. sprintf ("<tr><td colspan=\"6\"><hr></td></tr>\n\r\n");
$total=sprintf("&pound;%.02f",$total);
$ret=$ret. sprintf ("<tr class=\"TOTAL\"><td>TOTAL</td><td colspan=\"5\" align=right>$total</td></tr>\n\r\n");
$ret=$ret. sprintf ("</table>");

return $ret;
}


//TABLE IS INVOICESTEST and disabled last gosql on loop for invdom table
function invoicecreate($t)
{
$_SESSION[invoice]=$t;

$customerid=$_SESSION[invoice][top][customer_number];
$today=dispdate2(getcurdate());
$date=$_SESSION[invoice][top][date];

$sql="insert into invoices set customer='$customerid',invoicedate='$date',createdate='$today'";
gosql($sql,0);
$_SESSION[invoice][irn]=mysql_insert_id();


$invoice=sprintf("<table width=\"100%%\" ><tr><td width=\"33%%\"><img src=\"http://www.
awke.co.uk/awke-logo.jpg\" width=\"248\" height=\"89\"> </td>");
$invoice=$invoice. sprintf("<td><center><h1><a href=\"iindex.php\"  class=\"noA\">INVOICE</a></h1></center></td><td width=\"33%%\">&nbsp;</td></tr></table>");


$customerid=$_SESSION[invoice][top][customer_number];
$sql="SELECT * from customers WHERE  idcustomers='$customerid'";

$result=gosql($sql,0);

$customer=mysql_fetch_assoc($result);

$irn=$_SESSION[invoice][irn];
$crn=$_SESSION[invoice][top][customer_reference_number];

//$irn="1";

$today=dispdate(getcurdate());
$_SESSION[invoice][created]=getcurdate();
$date=dispdate(sqldate($_SESSION[invoice][top][date]));


//*****ADDDED

$custdet="$customer[fname] $customer[lname]<br>$customer[addrheader]<br>$customer[housenumb] $customer[street]<br>$customer[town]<br>$customer[county]<br>$customer[postcode]";
$awkeaddr="<b>Queries to:</b> admin@awke.co.uk<br><br>AWKE<br>59, Beech Avenue<br>Beeston<br>Nottingham<br>Nottinghamshire<br>NG9 1QH";

//print "<div align=\"right\"><table><tr><td  align=right><img src=\"http://www.awke.co.uk/Awke%20logo.jpg\" width=\"158\" height=\"89\">$awkeaddr</td><tr></table></div>";
//print "<div align=\"left\"><table border=1><tr><td align=left>$custdet</td></tr></table></div>";

//print "<table width=\"100%\" ><tr><td>";
$invoice=$invoice. sprintf ("<table><tr><td align=left nowrap>$custdet</td>");

//$invoice=$invoice. sprintf ("<td width=\"100%%\"> </td><td  align=right><img src=\"http://www.awke.co.uk/Awke%%20logo.jpg\" width=\"158\" height=\"89\">$awkeaddr</td><tr></table>");
//print "</td></tr></table>";
$invoice=$invoice. sprintf("<td width=\"100%%\">&nbsp;");
//$invoice=$invoice. sprintf("</td><td nowrap>");

$invoice=$invoice. sprintf ("<div align=right>");

$invoice=$invoice. sprintf ("<table class=\"invoicetopbox\">");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Invoice Reference Number:</th><td>$irn</td>
</tr>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Invoice Date:</th><td>$date</td></tr>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Todays Date:</th><td>$today</td></tr>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Customer Reference Number:</th><td>$crn</td
></tr>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Queries to:</th><td>admin@awke.co.uk</td></tr>");
$invoice=$invoice. sprintf ("</table>");

$invoice=$invoice. sprintf ("</div>");
$invoice=$invoice. sprintf ("</td></tr></table><br>");





$invoice=$invoice. invoicetable($_SESSION[invoice][data]);
$invoice=$invoice. sprintf ("<br>");
if($_SESSION[invoice][top][Comment]!="")
{
$comm=$_SESSION[invoice][top][Comment];
$invoice=$invoice. sprintf( "<p><b>Comment:</b>$comm</p>");
}
$payment=file("/Supported/domains/awke.co.uk/secure/wwwroot/admin/paymenttechniques.txt");

$conditions="Every service supplied and/or items brought & Sold Subject to Terms and Conditions avaliable on request and on website.";
$conditions1="Payment should be made within 28 days of the invoice date";

$invoice=$invoice. sprintf ("<hr><p class=\"conditions\">$conditions</p><p class=\"conditions\">$conditions1</p>");
$invoice=$invoice. sprintf ("<hr>");
foreach($payment as $value)
{
$invoice=$invoice. sprintf ("<p class=\"payment\">$value</p>");
}
$invoice=$invoice. sprintf ("<hr>");
$string=mysql_escape_string(gzcompress($invoice,9));
$variable=mysql_escape_string(gzcompress(serialize($_SESSION[invoice]),9));
$numbitems=count($_SESSION[invoice][data]);
$total=0;
foreach($_SESSION[invoice][data] as $v)
{
$total=$total+$v[Cost];
}
//$t=time();
$t=microtime();
//print "microtime:$t **";
$customerid=$_SESSION[invoice][top][customer_number];
$today=dispdate2(getcurdate());
$date=$_SESSION[invoice][top][date];
$uniqid=sha1("$customerid$$date$today$irn$t");

$sql="UPDATE invoices set string='$string',variable='$variable',total='$total',numbitems='$numbitems',custref='$crn',uniqueidinvoice='$uniqid' WHERE idinvoices='$irn'";

gosql($sql,0);

foreach($_SESSION[invoice][data] as $v)
{
if($v[Domain]!="")
{
$sql="SELECT * from domains WHERE domainn='$v[Domain]'";
$result=gosql($sql);
$rw=mysql_fetch_assoc($result);
$did=$rw[D_ID];

$sql="SELECT * from packages WHERE description='$v[Package]'";
$result=gosql($sql);
$rw=mysql_fetch_assoc($result);
$pid=$rw[idpackages];

$sql="INSERT into invdom set invoiceid='$irn',domainid='$did',packageid='$pid'";
$doms[]=$did;
gosql($sql);
}

}


return(array($uniqid,$total,$irn,$did,$invoice));
}





$sql="SELECT * from domains WHERE deactivated='0'";

$resulting=gosql($sql,0); 

while($rows=mysql_fetch_assoc($resulting))
{

print "DOMAIN:$rows[domainn]:";

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
}






$_SESSION[invoice][top][customer_number]=$customerid;
$_SESSION[invoice][top][date]=dispdate2(getcurdate());





$dt[Quantity]=1;

$dt[Domain]=$rows[domainn];
$dt[Description]="";
$dt[Package]=$package;

$dat=$_SESSION[invoice][top][date];
$sql="SELECT cost from packagedata,packages WHERE packages_idpackages=idpackages AND idpackages='$dt[Package]' AND sdate<='$dat' AND edate>='$dat'";
$result33=gosql($sql,0);
if(mysql_num_rows($result33)<1)
{
$dt["Cost"]="ERROR IN PACKAGE DATA";
$dt["CostEach"]=$dt["Cost"];
}
else
{
$row33=mysql_fetch_assoc($result33);
$dt["CostEach"]=$row33[cost];
$dt["Cost"]=$dt["Quantity"]*$row33[cost];
}

$sql="SELECT description from packages WHERE idpackages='$dt[Package]'";
$result56=gosql($sql);
$row990=mysql_fetch_assoc($result56);
$dt[Package]=$row990[description];



//$dt["CostEach"];
//disparray($dt,dtafter);
$_SESSION[invoice][data][]=$dt;



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
$dt[Description]="Extra amount to bring billing to 1st Feb";
}
else
{
$packs=37;
$dt[Description]="Refund to bring billing to 1st Feb";
}
}
elseif($package==17)
{
$packs=17;
$dt[Description]="Correction factor to 1st Feb billing";
}
elseif($package==15)
{
$packs=15;
$dt[Description]="Correction factor to 1st Feb billing";
}
elseif($package==32)
{
$packs=39;
$dt[Description]="Refund to bring billing to 1st Feb";
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

$dat=$_SESSION[invoice][top][date];
$sql="SELECT cost from packagedata,packages WHERE packages_idpackages=idpackages AND idpackages='$dt[Package]' AND sdate<='$dat' AND edate>='$dat'";
$result33=gosql($sql,0);
if(mysql_num_rows($result33)<1)
{
$dt["Cost"]="ERROR IN PACKAGE DATA";
$dt["CostEach"]=$dt["Cost"];
}
else
{
$row33=mysql_fetch_assoc($result33);
$dt["CostEach"]=$row33[cost];
$dt["Cost"]=$dt["Quantity"]*$row33[cost];
}

$sql="SELECT description from packages WHERE idpackages='$dt[Package]'";
$result56=gosql($sql);
$row990=mysql_fetch_assoc($result56);
$dt[Package]=$row990[description];



//$dt["CostEach"];
//disparray($dt,dtafter);
$_SESSION[invoice][data][]=$dt;



}
else
{
// no credits needed
}



//print_r($_SESSION);

$returned=invoicecreate($_SESSION[invoice]);

file_put_contents("op/$returned[2]-$rows[domainn].html",$returned[4]);






$domain=$rows[domainn];

print "$returned[0]\n";

$uniqueid=$returned[0];

$to="admin@awke.co.uk"; // change to $email
$to=$email;
$from="admin@awke.co.uk";
$head="From: AWKE ANNUAL INVOICE <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="AWKE Anual Invoice for domain $domain";
$mid="Supposed to be sent to $email\n";
$mid="";
$mid=$mid . "Hi $custname,\n\nYour annual invoice for webhosting of ($domain) is now due, please follow the link below to view it:\n https://secure.awke.co.uk/admin/invoicedisplay1.php?ID=$uniqueid\n\n";

if($returned[1]==0)
{
$mid=$mid . "As your invoice is for £0 it has been automatically marked paid and therefore no further action is required.\n\n";
}

$mid=$mid."Please use your invoice number as a reference on your payment and then send an email to payments@awke.co.uk\n\nIf you have any queries then please feel free to contact us at admin@awke.co.uk.\n\nRegards\n\nAWKE Admin\nREPLACE invoicedisplay1.php with invoicedisplay.php?ID=";
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);



if($returned[1]==0)
{
$sql="UPDATE invoices set paid='1' WHERE idinvoices='$returned[2]'";
gosql($sql);
}


//print_r($returned);
}




}