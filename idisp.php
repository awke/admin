<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$skip=$_GET[skip];

if($skip!=1)
{
$SESSION[invoice][irn]=$_SESSION[secdata][idinvoices][$_GET[id]];
$irn=$SESSION[invoice][irn];
$sql="SELECT * from adminInfo.invoices WHERE idinvoices='$irn'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);

//$string=mysql_escape_string(gzencode($invoice,9));
$_SESSION[invoice]=(unserialize(gzuncompress($row[variable])));

}

if($skip==1)
{
if($_GET[valid]!=1)
{
print "<a href=\"idisp.php?skip=1&valid=1\">ACCEPT INVOICE</a><br>";
print "<a href=\"iadd.php\">CHANGE INVOICE</a><br>";

}
}
if($skip==1)
{
if($_GET[valid]==1)
{
$customerid=$_SESSION[invoice][top][customer_number];
$today=dispdate2(getcurdate());
$date=$_SESSION[invoice][top][date];

$sql="insert into invoices set customer='$customerid',invoicedate='$date',createdate='$today'";
gosql($sql,0);
$_SESSION[invoice][irn]=mysql_insert_id();

}
}
print <<<END

<!-- MeadCo ScriptX -->
<object id=factory style="display:none"
  classid="clsid:1663ed61-23eb-11d2-b92f-008048fdd814"
  codebase="https://secure.awke.co.uk/admin/smsx.cab#Version=6,3,435,20">
</object>

END;
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

/*
$invoice=$invoice. sprintf ("<div align=right>");

$invoice=$invoice. sprintf ("<table class=invoicetopbox>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Invoice Reference Number:</th><td>$irn</td></tr>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Invoice Date:</th><td>$date</td></tr>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Todays Date:</th><td>$today</td></tr>");
$invoice=$invoice. sprintf ("<tr><th align=\"right\">Customer Reference Number:</th><td>$crn</td></tr>");
$invoice=$invoice. sprintf ("</table>");

$invoice=$invoice. sprintf ("</div>");

//print "<div align=left>";

//print "<table border=1>";

$custdet="$customer[fname] $customer[lname]<br>$customer[addrheader]<br>$customer[housenumb] $customer[street]<br>$customer[town]<br>$customer[county]<br>$customer[postcode]";
$awkeaddr="admin@awke.co.uk<br><br>AWKE<br>59, Beech Avenue<br>Beeston<br>Nottingham<br>Nottinghamshire<br>NG9 1QH";

//print "<div align=\"right\"><table><tr><td  align=right><img src=\"http://www.awke.co.uk/Awke%20logo.jpg\" width=\"158\" height=\"89\">$awkeaddr</td><tr></table></div>";
//print "<div align=\"left\"><table border=1><tr><td align=left>$custdet</td></tr></table></div>";

//print "<table width=\"100%\" ><tr><td>";
$invoice=$invoice. sprintf ("<table><div align=\"left\"><table><tr><td align=left nowrap>$custdet</td>");

$invoice=$invoice. sprintf ("<td width=\"100%%\"> </td><td  align=right><table><tr><td><img src=\"http://www.awke.co.uk/Awke%%20logo.jpg\" width=\"158\" height=\"89\"></td><td>$awkeaddr</td></tr></table></td><tr></table>");
//print "</td></tr></table>";
*/

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




function invoicetable($data)
{
$ret=$ret. sprintf ("<table class=\"invoicetable\"><thead><tr>");

$headers=array("Quantity","Package","Description","Domain","Cost Each","Cost");
foreach($headers as $value)
{
$ret=$ret. sprintf ("<th>$value</th>");
}
$ret=$ret. sprintf ("</tr></thead>");

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

$ret=$ret. sprintf ("<tr $class>");
$total=$total+$value["Cost"];
$value["Cost"]=sprintf("�%.02f",$value["Cost"]);
$value["CostEach"]=sprintf("�%.02f",$value["CostEach"]);
foreach($value as $item)
{
$ret=$ret. sprintf ("<td>$item</td>");
}
$ret=$ret. sprintf ("</tr>");
}
$ret=$ret. sprintf ("<tr><td colspan=\"6\"><hr></td></tr>");
$total=sprintf("�%.02f",$total);
$ret=$ret. sprintf ("<tr class=\"TOTAL\"><td>TOTAL</td><td colspan=\"5\" align=right>$total</td></tr>");
$ret=$ret. sprintf ("</table>");

return $ret;
}


$invoice=$invoice. invoicetable($_SESSION[invoice][data]);
$invoice=$invoice. sprintf ("<br>");
if($_SESSION[invoice][top][Comment]!="")
{
$comm=$_SESSION[invoice][top][Comment];
$invoice=$invoice. sprintf( "<p><b>Comment:</b>$comm</p>");
}
/*
$payment[]="Make Cheques Payble to Either Simon Atack Or Peter Knight And Post to either";
//$payment[]="<tr><td colspan=\"3\" align=center>And Post to</td></tr>";
$payment[]="Simon Atack, 59 Beech Avenue, Beeston, Nottingham, Nottinghamshire, NG9 1QH";
//$payment[]="<td align=center></td>";

$payment[]="Peter Knight, Flat 24, Cur, Ealing, London, W";
$payment[]="OR ELSE USE our bank details and make an electronic payment/Standing Order";
*/
$payment=file("paymenttechniques.txt");

$conditions="Every service supplied and/or items brought & Sold Subject to Terms and Conditions avaliable on request and on website.";
$conditions1="Payment should be made as soon as possible";//within 14 days of the invoice date";

$invoice=$invoice. sprintf ("<hr><p class=\"conditions\">$conditions</p><p class=\"conditions\">$conditions1</p>");
$invoice=$invoice. sprintf ("<hr>");
foreach($payment as $value)
{
$invoice=$invoice. sprintf ("<p class=\"payment\">$value</p>");
}
$invoice=$invoice. sprintf ("<hr>");

print $invoice;
if($skip==1)
{
if($_GET[valid]==1)
{
$string=mysql_escape_string(gzcompress($invoice,9));
$variable=mysql_escape_string(gzcompress(serialize($_SESSION[invoice]),9));
$numbitems=count($_SESSION[invoice][data]);
$total=0;
foreach($_SESSION[invoice][data] as $v)
{
$total=$total+$v[Cost];
}
$t=time();
$customerid=$_SESSION[invoice][top][customer_number];
$today=dispdate2(getcurdate());
$date=$_SESSION[invoice][top][date];
$uniqid=sha1("$customerid$date$today$t");

$sql="UPDATE invoices set string='$string',variable='$variable',total='$total',numbitems='$numbitems',custref='$crn',uniqueidinvoice='$uniqid' WHERE idinvoices='$irn'";

//disparray($sql,":LKLKL");
//print strlen($string).":".strlen($invoice)."    ".strlen($variable).":".strlen(serialize($_SESSION[invoice]));


gosql($sql,1);
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
gosql($sql);
}

}

}
}

