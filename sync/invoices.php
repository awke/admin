<?php


//consider adding a display section in as well.


$NOBOOKING=1;
$PRELOGIN=1;
$HEADER=0;

$dbhost = "localhost";
$dbname = "adminInfo";	
$dbuser = "awkeadminsoft";
$dbpasswd = "y3ipRLp";

require_once("/Supported/phpcode/sql.php");
require_once('/Supported/phpcode/email.php');

require_once('/Supported/domains/awke.co.uk/secure/wwwroot/admin/functions.inc');

sqlconn();
 
class invoices
{
protected $data;
protected $UniqID;
protected $invoice;
protected $string;
protected $variable;

protected $nosend=FALSE;

protected $email;
protected $emaildata;

public function invoices()
{
$this->email= new my_phpmailer(TRUE);
}

public function setemailsubject($subject)
{
$this->email->Subject=$subject;
}

public function setemailbody($body)
{
$this->email->Body=$body;
}

public function paid($id)
{
$sql1="UPDATE invoices set paid='1' WHERE idinvoices='$id'";
gosql($sql1);
if($this->nosend!=FALSE)
{{

$this->paidemail($id);
}

}

public function paidemail($id)
{

if(!isset($this->data[top][customer_number]))
{
$this->loadInvoice(id);
}
$this->email->ClearAddresses();
$this->email->ClearAllRecipients(); 
$this->email->ClearAttachments();
$this->email->ClearCustomHeaders();



$custid=$this->data[top][customer_number];
$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' AND pri='1'";


$res=gosql($sql,0);

$row=mysql_fetch_assoc($res);



//print_r($row);


if(strlen($row[email])>2)
{
 // $mail->AddReplyTo('name@yourdomain.com', 'First Last');
try {
$this->email->Subject="Payment for invoice $id has been received";
  $this->email->AddAddress($row[email], "$row[fname] $row[lname]");
  $this->email->SetFrom('admin@awke.co.uk', 'AWKE Accounts');
 $this->email->AddCC('admin@awke.co.uk','AWKE Admin'); 
$this->email->ClearReplyTos();
 $this->email->AddReplyTo('support@awke.co.uk', 'AWKE Accounts');
$this->email->Body="$row[fname] $row[lname],\r\n\r\nThank you for your payment of invoice $id, and this invoice has now been marked paid.\r\n\r\nAWKE Accounts\r\n";
$this->email->Send();
}
 catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}


}

public function nosend($set=TRUE)
{
$this->nosend=$set;
}

public function debug()
{
print "data:" .print_r($this->data,TRUE);
print "\n";
print "UniqID:" .print_r($this->UniqID,TRUE);
print "\n";
print "invoice:" .print_r($this->invoice,TRUE);
print "\n";
//print "string:" .print_r($this->string,TRUE);
//print "variable:" .print_r($this->variable,TRUE);

}

public function loadUniqInvoice($uniqid)
{
$sql="SELECT * from adminInfo.invoices WHERE uniqueidinvoice='$uniqid'";
$result=gosql($sql,0);

if(mysql_num_rows($result)<1)
{
print "ERROR no records found for that id";
exit();
}
$row=mysql_fetch_assoc($result);

$this->UniqID=$uniqid;
$this->invoice=((gzuncompress($row['string'])));
$this->string=$row[string];
$this->data=(unserialize(gzuncompress($row[variable])));


}

public function loadInvoice($id)
{

$sql="SELECT * from adminInfo.invoices WHERE idinvoices='$id'";
$result=gosql($sql,0);


if(mysql_num_rows($result)<1)
{
print "ERROR no records found for that id";
exit();
}
$row=mysql_fetch_assoc($result);


$this->invoice=(gzuncompress($row[string]));
$this->string=$row[string];
$this->data=(unserialize(gzuncompress($row[variable])));
$this->UniqID=$row[uniqueidinvoice];

}

public function setComment($comment)
{
$this->data[top][comment]=$comment;
}

public function setInvoiceDate($date)
{
$this->data[top][date]=$date;
}

public function setCustomerID($ID)
{
$this->data[top][customer_number]=$ID;
}

public function addLineItem($dat)
{
$this->data[data][]=$dat;
return($this->getCountItems());

}

public function addPackage($dt)
{

$dat=$this->data[top][date];
$sql="SELECT cost from packagedata,packages WHERE packages_idpackages=idpackages AND idpackages='$dt[Package]' AND sdate<='$dat' AND edate>='$dat'";
$result=gosql($sql,0);
if(mysql_num_rows($result)<1)
{
$dt["Cost"]="ERROR IN PACKAGE DATA";
$dt["CostEach"]=$dt["Cost"];
}
else
{
$row=mysql_fetch_assoc($result);
$dt["CostEach"]=$row[cost];
$dt["Cost"]=$dt["Quantity"]*$row[cost];
}

$sql="SELECT description from packages WHERE idpackages='$dt[Package]'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
$dt[Package]=$row[description];


//$dt["CostEach"];
//disparray($dt,dtafter);
$this->data[data][]=$dt;
return($this->getCountItems());


}


public function getString()
{
return($this->invoice);
}

public function createString()
{
$invoice=sprintf("<table width=\"100%%\" ><tr><td width=\"33%%\"><img src=\"/awke-logo.jpg\" width=\"248\" height=\"89\"> </td>");
$invoice=$invoice. sprintf("<td><center><h1>INVOICE</h1></center></td><td width=\"33%%\">&nbsp;</td></tr></table>");


$customerid=$this->data[top][customer_number];
$sql="SELECT * from customers WHERE  idcustomers='$customerid'";

$result=gosql($sql,0);

$customer=mysql_fetch_assoc($result);

$irn=$this->data[irn];
$crn=$this->data[top][customer_reference_number];

//$irn="1";

$today=dispdate(getcurdate());
$this->data[created]=getcurdate();
$date=dispdate(sqldate($this->data[top][date]));

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




$invoice=$invoice. $this->invoicetable($this->data[data]);
$invoice=$invoice. sprintf ("<br>");
if($this->data[top][Comment]!="")
{
$comm=$this->data[top][Comment];
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



$this->invoice=$invoice;


}

private function invoicetable($data)
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

$ret=$ret. sprintf ("<tr class=\"$class\">");
$total=$total+$value["Cost"];
$value["Cost"]=sprintf("£%.02f",$value["Cost"]);
$value["CostEach"]=sprintf("£%.02f",$value["CostEach"]);

$ret=$ret. sprintf ("<td>$value[Quantity]</td>");

$ret=$ret. sprintf ("<td>$value[Package]</td>");

$ret=$ret. sprintf ("<td>$value[Description]</td>");

$ret=$ret. sprintf ("<td>$value[Domain]</td>");

$ret=$ret. sprintf ("<td>$value[CostEach]</td>");

$ret=$ret. sprintf ("<td>$value[Cost]</td>");


$ret=$ret. sprintf ("</tr>");
}
$ret=$ret. sprintf ("<tr><td colspan=\"6\"><hr></td></tr>");
$total=sprintf("£%.02f",$total);
$ret=$ret. sprintf ("<tr class=\"TOTAL\"><td>TOTAL</td><td colspan=\"5\" align=right>$total</td></tr>");
$ret=$ret. sprintf ("</table>");

return $ret;
}


public function commit()
{

$customerid=$this->data[top][customer_number];
$today=dispdate2(getcurdate());
$date=$this->data[top][date];

$sql="insert into invoices set customer='$customerid',invoicedate='$date',createdate='$today'";
gosql($sql,0);
$this->data[irn]=mysql_insert_id();
$irn=$this->data[irn];
$crn=$this->data[top][customer_reference_number];
$this->createString();

$total=$this->getTotal();
$numbitems=$this->getCountItems();


$this->string=mysql_escape_string(gzcompress($this->invoice,9));
$this->variable=mysql_escape_string(gzcompress(serialize($this->data),9));


$t=time();
$customerid=$this->data[top][customer_number];
$today=dispdate2(getcurdate());
$date=$this->data[top][date];

if(strlen($this->UniqID)<5)
{
$uniqid=sha1("$customerid$date$today$t");
$this->UniqID=$uniqid;
}
else
{
$uniqid=$this->UniqID;
}

$variable=$this->variable;
$string=$this->string;



$sql="UPDATE invoices set string='$string',variable='$variable',total='$total',numbitems='$numbitems',custref='$crn',uniqueidinvoice='$uniqid' WHERE idinvoices='$irn'";


gosql($sql,0);
foreach($this->data[data] as $v)
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



$custid=$this->data[top][customer_number];
$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' AND pri='1'";


$res=gosql($sql,0);

$row=mysql_fetch_assoc($res);



//print_r($row);

if($this->nosend!=FALSE)
{
if(strlen($row[email])>2)
{
 // $mail->AddReplyTo('name@yourdomain.com', 'First Last');
try {
if(strlen($this->email->Subject)<5)
{
$this->email->Subject="AWKE Invoice $irn";
}
else
{
$search=array("@FNAME@","@LNAME@","@IRN@","@UNIQID@");
$replace=array("$row[fname]","$row[lname]","$irn","$uniqid");
$this->email->Subject=str_replace($search,$replace,$this->email->Subject);

}
  $this->email->AddAddress($row[email], "$row[fname] $row[lname]");
  $this->email->SetFrom('admin@awke.co.uk', 'AWKE Accounts');
 $this->email->AddCC('admin@awke.co.uk','AWKE Admin'); 
$this->email->ClearReplyTos();
 $this->email->AddReplyTo('support@awke.co.uk', 'AWKE Accounts');

 if(strlen($this->email->Body)<5)
{
$this->email->Body="$row[fname] $row[lname],\r\n\r\nHi your AWKE invoice $irn is available to view now at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid\r\n\r\nAWKE Accounts\r\n";
}
else
{
$search=array("@FNAME@","@LNAME@","@IRN@","@UNIQID@");
$replace=array("$row[fname]","$row[lname]","$irn","$uniqid");
$this->email->Body=str_replace($search,$replace,$this->email->Body);

}
$this->email->Send();
}
 catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}
}

if($total==0)
{
$this->paid($irn);
}




}


public function getTotal() 
{
$total=0;
foreach($this->data[data] as $k => $v)
{
$total=$total+$v[Cost];
}

return($total);
}

public function getIRN()
{
return($this->data[irn]);
}

public function getUniqID()
{
return($this->UniqID);
}

public function getCountItems()
{
return(count($this->data[data]));
}


}
