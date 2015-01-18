<?php


//consider adding a display section in as well.

$LOGGER_ID="INVOICE FUNCTION";
$NOBOOKING=1;
$PRELOGIN=1;
$HEADER=0;

$dbhost = "localhost";
$dbname = "adminInfo";	
$dbuser = "awkeadminsoft";
$dbpasswd = "y3ipRLp";

require_once("/Supported/phpcode/sql.php");
require_once('/Supported/phpcode/email.php');
require_once('/Supported/phpcode/logging.php');

require_once('/Data/websites/awke.co.uk/secure/wwwroot/admin/functions.inc');

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

protected $queryret;

protected $TESTING;

protected $table;
protected $pdf;

protected $paid;
protected $void;

public function invoices()
{
$this->email= new my_phpmailer(TRUE);
$this->TESTING=FALSE;
$this->table="invoices";
$this->paid=0;
$this->void=0;
}

public function testing()
{
$this->TESTING=TRUE;
logsys(" invoice mode set to testing");
$this->table="invoicestest";
}
public function datadebug()
{
var_export($this->data);

}
public function debug()
{
print "<pre>";
print "<b>TESTING: ". print_r($this->TESTING,TRUE) ."</b>\n";
print "data:" .print_r($this->data,TRUE);
print "\n";
print "UniqID:" .print_r($this->UniqID,TRUE);
print "\n";
print "void:" .print_r($this->void,TRUE);

print "\n";
print "paid:" .print_r($this->paid,TRUE);

print "\n";
print "Subject:" .print_r($this_>Subject,TRUE);
//print "<blockquote>";
print "invoice:" .htmlspecialchars(print_r($this->invoice,TRUE));
print "\n";
//print "string:" .print_r($this->string,TRUE);
//print "variable:" .print_r($this->variable,TRUE);
//print "Query Data Returned:" . print_r($this->queryret,TRUE);
print "</pre>";
print "</pre>";
}

public function debug222()
{
print "stingsss $this->table";
$sql="INSERT into $this->table VALUES dd='dds'";
print "   $sql\n   ";
}

public function setemailsubject($subject)
{
$this->email->Subject=$subject;
}

public function setemailbody($body)
{
$this->email->Body=$body;
}
public function recreateString($id)
{
if(!isset($this->data[irn]))
{
$this->loadInvoice($id);
}

$this->createPDF();
$this->string=mysql_real_escape_string(gzcompress($this->invoice,9));
$sql1="UPDATE $this->table set void='1',string='$this->string' WHERE idinvoices='$id'";
gosql($sql1);
}

public function simonfix()
{

$safix=array (
'top' =>
  array (
    'customer_number' => '68',
    'date' => '2011-09-15',
  ),
  'data' =>
  array (
    0 =>
    array (
      'Quantity' => 1,
      'Domain' => 'ready2gotransport.com',
      'Description' => '',
      'Package' => 'Standard Webhosting Package',
      'CostEach' => '30',
      'Cost' => 30,
    ),
        1 =>
        array (
      'Quantity' => 1,
      'Domain' => 'ready2gotransport.com',
      'Description' => '10%% extra charged due to extremely late payment',
      'Package' => 'Late & Account Reactivation Fee',
      'CostEach' => '3',
      'Cost' => 3,
    ),
  ),
  'irn' => 10109,
  'email' =>
  array (
    'subject' => '',
  ),
);

print_r($safix);
$this->data=$safix;

$this->createPDF();

$this->string=mysql_real_escape_string(gzcompress($this->invoice,9));

$this->variable=mysql_real_escape_string(gzcompress(serialize($safix),9));
$irn=$this->data[irn];

print $irn;

$string=$this->string;
$variable=$this->variable;

$sql="UPDATE $this->table set string='$string',variable='$variable' WHERE idinvoices='$irn'";




gosql($sql,1);



}

public function void($id)
{

if(!isset($this->data[irn]))
{
$this->loadInvoice($id);
}


$this->void=1;
$this->createPDF();
$this->string=mysql_real_escape_string(gzcompress($this->invoice,9));
$sql1="UPDATE $this->table set void='1',string='$this->string' WHERE idinvoices='$id'";
gosql($sql1);


if($this->nosend==TRUE) //$this->nosend!=FALSE)
{
logsys("nosend for id $id" . var_export($this->nosend,TRUE));

}
else
{
$this->voidemail($id);
}


}

public function paid($id)
{
if(!isset($this->data[irn]))
{
$this->loadInvoice($id);
}
$this->paid=1;
$this->createPDF();
$this->string=mysql_real_escape_string(gzcompress($this->invoice,9));
$sql1="UPDATE $this->table set paid='1',string='$this->string' WHERE idinvoices='$id'";
gosql($sql1);



if($this->nosend==TRUE) //$this->nosend!=FALSE)
{
logsys("nosend for id $id" . var_export($this->nosend,TRUE));

}
else
{
$this->paidemail($id);
}

}


public function voidemail($id)
{

//should optomize the routines for email sending to combine most of this void and paid into one 1 function call with modified parameters!

if(!isset($this->data[top][customer_number]))
{
$this->loadInvoice($id);
}
$this->email->ClearAddresses();
$this->email->ClearAllRecipients(); 
$this->email->ClearAttachments();
$this->email->ClearCustomHeaders();



$custid=$this->data[top][customer_number];
$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' AND pri='1'";

$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' ORDER BY pri DESC LIMIT 1";//AND pri='1'";


$res=gosql($sql,0);

$row=mysql_fetch_assoc($res);

$sql="SELECT * from adminInfo." .$this->table ." WHERE idinvoices='$id'";
$res=gosql($sql,0);
$uniqr=mysql_fetch_assoc($res);
$uniqid=$uniqr[uniqueidinvoice];

if(strlen($row[email])<2)
{
$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' AND pri='0' LIMIT 1";
$res=gosql($sql,0);

$row=mysql_fetch_assoc($res);
logsys("NO EMAIL FOR SENDING PAYMENT MADE EMAIL for custid:$custid ");


}

//print_r($row);


if(strlen($row[email])>2)
{
 // $mail->AddReplyTo('name@yourdomain.com', 'First Last');
try {


if($this->TESTING!=TRUE)
{
$this->email->Subject="Invoice $id has been voided";
  $this->email->AddAddress($row[email], "$row[fname] $row[lname]");
}
else
{
$this->email->Subject="TESTING Invoice $id has been voided";

$this->email->AddAddress("admin@awke.co.uk", "TESTING  $row[fname] $row[lname] $row[email]");
}
  $this->email->SetFrom('admin@awke.co.uk', 'AWKE Accounts');
 $this->email->AddCC('admin@awke.co.uk','AWKE Admin'); 
$this->email->ClearReplyTos();
 $this->email->AddReplyTo('accounts@awke.co.uk', 'AWKE Accounts');
$this->email->Body="$row[fname] $row[lname],\r\n\r\nThis is to inform you that invoice $id has been voided.\r\nWe have attached the voided invoice to this email or else you can view the voided invoice via the web https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid\r\n\r\nAWKE Accounts\r\n";

$irno=$id;
$string=$this->pdf->Output("AWKE-Invoice-$irno.pdf","S");

$this->email->AddStringAttachment($string,"awke-invoice-$irno.pdf","base64","application/pdf");


$this->email->Send();
}
 catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}


}




public function paidemail($id)
{

if(!isset($this->data[top][customer_number]))
{
$this->loadInvoice($id);
}
$this->email->ClearAddresses();
$this->email->ClearAllRecipients(); 
$this->email->ClearAttachments();
$this->email->ClearCustomHeaders();



$custid=$this->data[top][customer_number];
$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' AND pri='1'";

$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' ORDER BY pri DESC LIMIT 1";//AND pri='1'";


$res=gosql($sql,0);

$row=mysql_fetch_assoc($res);

$sql="SELECT * from adminInfo." .$this->table ." WHERE idinvoices='$id'";
$res=gosql($sql,0);
$uniqr=mysql_fetch_assoc($res);
$uniqid=$uniqr[uniqueidinvoice];

if(strlen($row[email])<2)
{
$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' AND pri='0' LIMIT 1";
$res=gosql($sql,0);

$row=mysql_fetch_assoc($res);
logsys("NO EMAIL FOR SENDING PAYMENT MADE EMAIL for custid:$custid ");


}

//print_r($row);


if(strlen($row[email])>2)
{
 // $mail->AddReplyTo('name@yourdomain.com', 'First Last');
try {

if(!isset($this->email->Subject))
{
$this->email->Subject="Payment for invoice $id has been received";
}
else
{
$this->email->Subject="PAID: " . $this->email->Subject;
$search=array("@FNAME@","@LNAME@","@IRN@","@UNIQID@");
$replace=array("$row[fname]","$row[lname]",$this->data[irn],"$uniqid");
$this->email->Subject=str_replace($search,$replace,$this->email->Subject);
}



if($this->TESTING!=TRUE)
{
//$this->email->Subject="Payment for invoice $id has been received";
  $this->email->AddAddress($row[email], "$row[fname] $row[lname]");
}
else
{
$this->email->Subject="TESTING ". $this->email->Subject; //Payment for invoice $id has been received";

$this->email->AddAddress("admin@awke.co.uk", "TESTING  $row[fname] $row[lname] $row[email]");
}
  $this->email->SetFrom('admin@awke.co.uk', 'AWKE Accounts');
 $this->email->AddCC('admin@awke.co.uk','AWKE Admin'); 
$this->email->ClearReplyTos();
 $this->email->AddReplyTo('accounts@awke.co.uk', 'AWKE Accounts');
$this->email->Body="Hi $row[fname] $row[lname],\r\n\r\nThank you for your payment of invoice $id.\n\nThis invoice has now been marked as paid.\n\nWe attach the paid invoice for your reference which can also be view online at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid\r\n\r\nIf you have any technical queries then please feel free to contact us at support@awke.co.uk.\r\n\nRegards\n\r\nSimon, Ben & Peter (AWKE)\r\n";

$irno=$id;
$string=$this->pdf->Output("AWKE-Invoice-$irno.pdf","S");

$this->email->AddStringAttachment($string,"awke-invoice-$irno.pdf","base64","application/pdf");


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
logsys("nosend HAS BEEN SET for id $id" . var_export($this->nosend,TRUE));

$this->nosend=$set;
}

public function loadUniqInvoice($uniqid)
{
$sql="SELECT * from adminInfo." .$this->table ." WHERE uniqueidinvoice='$uniqid'";
$result=gosql($sql,0);

if(mysql_num_rows($result)<1)
{
$this->table="invoicesold";

//$this->table="invoicestest";

$sql="SELECT * from adminInfo." .$this->table ." WHERE uniqueidinvoice='$uniqid'";
$result=gosql($sql,0);

if(mysql_num_rows($result)<1)
{
print "ERROR no records found for that id";
exit();
}
}
$row=mysql_fetch_assoc($result);

$this->queryret=$row;
$this->UniqID=$uniqid;
$this->invoice=((gzuncompress($row['string'])));
$this->string=$row[string];
$this->data=(unserialize(gzuncompress($row[variable])));
$this->email->Subject=$this->data[email][subject];
$this->paid=$row[paid];
$this->void=$row[void];

}

public function loadInvoice($id)
{

$sql="SELECT * from adminInfo." .$this->table ." WHERE idinvoices='$id'";
$result=gosql($sql,0);



if(mysql_num_rows($result)<1)
{
$this->table="invoices-old";
$sql="SELECT * from adminInfo." .$this->table ." WHERE idinvoices='$id'";
$result=gosql($sql,0);

if(mysql_num_rows($result)<1)
{
print "ERROR no records found for that id";
exit();
}
}
$row=mysql_fetch_assoc($result);

$this->queryret=$row;
$this->invoice=(gzuncompress($row[string]));
$this->string=$row[string];
$this->data=(unserialize(gzuncompress($row[variable])));
$this->email->Subject=$this->data[email][subject];
$this->UniqID=$row[uniqueidinvoice];
$this->paid=$row[paid];
$this->void=$row[void];
}


public function displayInvoice()
{
//protected $data;
//protected $invoice;
//protected $row;

$row=$this->queryret;
$data=$this->data;

//print $this->createString();
print $this->getString();
}

public function setComment($comment)
{
$this->data[top][comment]=$comment;
}

public function setInvoiceDate($date)
{
$this->data[top][date]=$date;
}

public function setInvoiceCreateDate($date)
{
$this->data[created]=$date;
}

public function setCustomerID($ID)
{
$this->data[top][customer_number]=$ID;
}

public function getCustomerID()
{
return($this->data[top][customer_number]);
}

public function addLineItem($dat)
{
$this->data[data][]=$dat;
return($this->getCountItems());
/*  Array containing:
                    [Quantity] => 1
                    [Domain] => SOMEDOMAIN.com.uk
                    [Description] => Renewal (Expires: )
                    [Package] => DOMAIN REGISTRATION .com,.org,.eu,info (1 Years)
                    [CostEach] => 12
                    [Cost] => 12
*/
}

public function deleteLineItem($numb)
{
unset($this->data[data][$numb]);
}

public function getKeysLineItems()
{
return(array_keys($this->data[data]));
}

public function getLineItem($ID)
{
return($this->data[data][$ID]);
}

public function countLineItems()
{
return(count($this->data[data]));
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

// CHANGE SO EMAILS ADMIN

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
/*
$invoice=sprintf("<table width=\"100%%\" ><tr><td width=\"33%%\"><img src=\"/awke-logo.jpg\" width=\"248\" height=\"54\"> </td>");
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



//*****ADDDED

$custdet="$customer[fname] $customer[lname]<br>$customer[addrheader]<br>$customer[housenumb] $customer[street]<br>$customer[town]<br>$customer[county]<br>$customer[postcode]";
$awkeaddr="<b>Queries to:</b> admin@awke.co.uk<br><br>AWKE<br>59, Beech Avenue<br>Beeston<br>Nottingham<br>Nottinghamshire<br>NG9 1QH";

$invoice=$invoice. sprintf ("<table><tr><td align=left nowrap>$custdet</td>");


$invoice=$invoice. sprintf("<td width=\"100%%\">&nbsp;");

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

*/
}

private function invoicetable($data)
{
// diabled
}

public function commit()
{

if(count($this->data[created])<=0)
{
$this->setInvoiceCreateDate(getcurdate());
}


$customerid=$this->data[top][customer_number];
$today=dispdate2(getcurdate());
$date=$this->data[top][date];

$sql="insert into $this->table set customer='$customerid',invoicedate='$date',createdate='$today'";
gosql($sql,0);
$this->data[irn]=mysql_insert_id();
$irn=$this->data[irn];
$crn=$this->data[top][customer_reference_number];


$this->createPDF();


//$this->createString();

$total=$this->getTotal();
$numbitems=$this->getCountItems();
$this->data[email][subject]=$this->email->Subject; //Subject;


$this->string=mysql_real_escape_string(gzcompress($this->invoice,9));
$this->variable=mysql_real_escape_string(gzcompress(serialize($this->data),9));




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



$sql="UPDATE $this->table set string='$string',variable='$variable',total='$total',numbitems='$numbitems',custref='$crn',uniqueidinvoice='$uniqid' WHERE idinvoices='$irn'";


gosql($sql,0);



if($this->TESTING!=TRUE)
{
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

}



$custid=$this->data[top][customer_number];
$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' AND pri='1'";
// ORDER BY pri DESC LIMIT 1

$sql="SELECT * from customers,custemail WHERE customers_idcustomers=customers.idcustomers AND idcustomers='$custid' ORDER BY pri DESC LIMIT 1";//AND pri='1'";
$res=gosql($sql,0);

$row=mysql_fetch_assoc($res);



//print_r($row);

if($this->nosend!=TRUE) // chaned simon 2010-07-13 due to no email send  FALSE)
{
if(strlen($row[email])>2)
{
 // $mail->AddReplyTo('name@yourdomain.com', 'First Last');

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

if($this->TESTING!=TRUE)
{
  $this->email->AddAddress($row[email], "$row[fname] $row[lname]");
}
else
{
  $this->email->AddAddress("admin@awke.co.uk" ,"TESTING $row[fname] $row[lname] $row[email]");
  $this->email->Subject="TESTING " . $this->email->Subject;
}

  $this->email->SetFrom('accounts@awke.co.uk', 'AWKE Accounts');
 $this->email->AddCC('admin@awke.co.uk','AWKE Admin'); 
$this->email->ClearReplyTos();
 //$this->email->AddReplyTo('accounts@awke.co.uk', 'AWKE Accounts');

 if(strlen($this->email->Body)<5)
{
$this->email->Body="$row[fname] $row[lname],\r\n\r\nHi your AWKE invoice $irn is available to view now at https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$uniqid it is also attached as a pdf.\r\n\r\nAWKE Accounts\r\n";
}
else
{
$search=array("@FNAME@","@LNAME@","@IRN@","@UNIQID@");
$replace=array("$row[fname]","$row[lname]","$irn","$uniqid");
$this->email->Body=str_replace($search,$replace,$this->email->Body);

}
if(!isset($this->pdf))
{
$this->createPDF();
}


//$this->createPDF();
$irno=$this->data[irn];
$string=$this->pdf->Output("AWKE-Invoice-$irno.pdf","S");

$this->email->AddStringAttachment($string,"awke-invoice-$irno.pdf","base64","application/pdf");


if($total!=0)
{
try {
$this->email->Send();
}
 catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
}

}
else
{
logsys("NO EMAIL FOR SENDING INVOICE custid:$custid ");



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

public function createPDF($html="",$topmargin=58,$bottommargin=33)
{


require_once("/Supported/phpcode/mpdf50/mpdf.php");

$mpdf=new mPDF('win-1252','A4','','',20,15,$topmargin,$bottommargin,10,10);
$mpdf->useOnlyCoreFonts = true;    // false is default
//$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("AWKE - Invoice");
$mpdf->SetAuthor("Atack Whyte Knight Enterprises");
//$mpdf->SetWatermarkText("Paid");
$mpdf->showWatermarkText = true;
$mpdf->watermark_font = 'DejaVuSansCondensed';
$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');

$invdate=dispdate(sqldate($this->data[top][date]));
$createdate=dispdate($this->data[created]);




$customerid=$this->data[top][customer_number];
$sql="SELECT * from customers WHERE  idcustomers='$customerid'";

$result=gosql($sql,0);

$customer=mysql_fetch_assoc($result);


$crn=$this->data[top][customer_reference_number];

//$irn="1";

$today=dispdate(getcurdate());



//*****ADDDED

//$custdet="$customer[fname] $customer[lname]<br>$customer[addrheader]<br>$customer[housenumb] $customer[street]<br>$customer[town]<br>$customer[county]<br>$customer[postcode]";
$custdet="$customer[fname] $customer[lname]<br />$customer[addrheader]";
if(strlen($customer[addrheader])>1)
{
$custdet=$custdet."<br />";
}
$custdet=$custdet."$customer[housenumb] $customer[street]";
if(strlen("$customer[housenumb] $customer[street]")>3)
{
$custdet=$custdet."<br />";
}
$custdet=$custdet.$customer[town];
if(strlen($customer[town])>1)
{
$custdet=$custdet."<br />";
}
$custdet=$custdet."$customer[county]";
if(strlen($customer[county])>1)
{
$custdet=$custdet."<br />";
}
$custdet=$custdet."$customer[postcode]";

$htmltop='
<table width="100%" border="0"><tr>
<td width="50%" style="color:#0000BB;"><img src="/Supported/phpcode/AWKE-small-logo.png" width="259" height="56"><br />
6 The Furrow,<br />Littleport, Ely<br />, Cambridgeshire,<br /> CB6 1GL<br />
<span style="font-size: 15pt;">&#9742;</span> 07747 193447<br />

</td>
<td width="50%" style="text-align: right;">Invoice No: <span style="font-weight: bold; font-size: 14pt;">' . $this->data[irn] .'</span><br />
Invoice Date: <span style="font-weight: bold; ">'. $invdate.'</span><br />
Created On: <span style="font-weight: bold; ">' . $createdate.'</span>
<br /></span>

Email: <span style="font-weight: bold; ">accounts@awke.co.uk</span>

</td>
</tr></table>

';

$htmlbottom='
<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
Page {PAGENO} of {nb}
</div>
<div font-size: 4pt;>
Atack Whyte Knight Enterprise (AWKE) LLP (Registration OC360741)<br />Registered address: 6 The Furrow, Littleport, Ely, Cambridgeshire, CB6 1GL</div>
';

if(strlen($html)<10)
{
//print "STRLEN SMALL";
$html = '
<html>
<head>
<style>
body {font-family: sans-serif;
    font-size: 10pt;
}
p {    margin: 0pt;
}
td { vertical-align: top; }
.items td {
    border-left: 0.1mm solid #000000;
    border-right: 0.1mm solid #000000;
}
table thead td { background-color: #EEEEEE;
    text-align: center;
    border: 0.1mm solid #000000;
}
.items td.blanktotal {
    background-color: #FFFFFF;
    border: 0mm none #000000;
    border-top: 0.1mm solid #000000;
    border-right: 0.1mm solid #000000;
}
.items td.totals {
    text-align: right;
    border: 0.1mm solid #000000;
}
</style>
</head>
<body>

<!--mpdf
<htmlpageheader name="myheader">
' . $htmltop .'
</htmlpageheader>

<htmlpagefooter name="myfooter">
' . $htmlbottom. '
</htmlpagefooter>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
<sethtmlpagefooter name="myfooter" value="on" />
mpdf-->


<table width="100%" border=0 style="font-family: serif;" cellpadding="10">
<tr>
<td width="45%" style="border: 0.1mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans;">SOLD TO:</span><br /><br />' . $custdet .'</td>
<td width="10%">&nbsp;</td>
<td width="45%" style="border: 0.0mm solid #888888;"><span style="font-size: 7pt; color: #555555; font-family: sans;"></span></td>
</tr>
</table>
<br />

<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
<thead>
<tr>
<td width="5%">QTY</td>
<td>PACKAGE</td>
<td>DESCRIPTION</td>
<td>DOMAIN</td>
<td width="10%">UNIT PRICE</td>
<td width="10%">AMOUNT</td>
</tr>
</thead>
<tbody>
<!-- ITEMS HERE -->


';


foreach($this->data[data] as $key=>$value)
{

$html=$html."<tr>";
$total=$total+$value["Cost"];
$value["Cost"]=sprintf("&pound;%.02f",$value["Cost"]);
$value["CostEach"]=sprintf("&pound;%.02f",$value["CostEach"]);

$html=$html. sprintf ("<td>$value[Quantity]</td>\r\n");

$html=$html. sprintf ("<td>$value[Package]</td>\r\n");

$html=$html. sprintf ("<td>$value[Description]</td>\r\n");

$html=$html. sprintf ("<td>$value[Domain]</td>\r\n");

$html=$html. sprintf ("<td>$value[CostEach]</td>\r\n");

$html=$html. sprintf ("<td>$value[Cost]</td>\r\n");


$html=$html. sprintf ("</tr>\r\n");
}
$total=sprintf("&pound;%.02f",$total);
$html=$html.'
<!-- END ITEMS HERE -->
<tr>
<td class="blanktotal" colspan="4" rowspan="2"></td>
<td class="totals">TOTAL:</td>
<td class="totals">'.$total.'</td>
';

$payby=dispdate(dateadd($this->data[created],28));

$html=$html.'
</tbody>
</table>


<br />

<hr>
<p>Every service supplied and/or items brought & Sold Subject to Terms and Conditions avaliable on request and on website.</p>

<p>Payment should be made within 28 days (<span style="font-weight: bold; ">'.$payby.'</span>)</p>
<hr>
<p><span style="font-weight: bold; ">Please use your invoice number as a reference on the payment and then send an email to payments@awke.co.uk</span></p><br />

<p>Please make electronic payment (<span style="font-weight: bold; ">Strongly Preferred</span>) to:</p><br />

<span style="font-weight: bold; ">Bank Details</span><br />
Sort Code: <span style="font-weight: bold; font-size: 11pt;">09 01 27</span><br />
Account Number: <span style="font-weight: bold; font-size: 11pt;">89386949</span><br />
Branch: <span style="font-weight: bold; font-size: 11pt;">London</span><br />
Bank: <span style="font-weight: bold; font-size: 11pt;">Santander</span><br />

Holders Name: <span style="font-weight: bold; font-size: 11pt;">AWKE</span></p>
<br />
<p>OR make cheques payable to AWKE and post to:<br />
AWKE, 6 The Furrow, Littleport, Ely, Cambridgeshire, CB6 1GL
</p>

</html>';
}




//print $html;
//$this->data[top][customer_reference_number];

//<span style="font-weight: bold; font-size: 14pt;">Atack Whyte Knight Enterprises (AWKE) LLP (OC 360741)</span><br />


$htmlextra="";

if($this->void==1)
{
$mpdf->SetWatermarkText("VOID");

$htmlextra="<h1>VOID</h1><br>";
}
elseif($this->paid==1)
{
$mpdf->SetWatermarkText("Paid");
$htmlextra="<h1>PAID</h1><br>";
}
else
{
$mpdf->SetWatermarkText("");
$htmlextra="";
}

$mpdf->WriteHTML($html); 
$this->pdf=$mpdf;



// UPDATE DB WITH PDF

if(isset($this->data[irn]))
{
$pdfstr=$this->pdf->Output("AWKE-Invoice-$this->data[irn].pdf","S");
$pdfstr=mysql_real_escape_string($pdfstr);
//$pdfstr=gzcompress($pdfstr,9); // seems pointless db still uses same amount of space
$irn=$this->data[irn];
$sql="UPDATE $this->table SET pdf='$pdfstr' WHERE idinvoices='$irn'";
gosql($sql,0);
}
//print $sql;
$str=$htmltop;

$a=strpos($str,"/Supported");
$b=strpos($str,"awke-logo.jpg\"");
$str=substr_replace($str,"/",$a,$b-$a);
$htmltop=$str;
$this->invoice= $htmlextra . $htmltop . $html . $htmlbottom;

// /Supported/domains/awke.co.uk/secure/wwwroot/awke-logo.jpg



}


public function displayPDF()
{
$this->createPDF();
$this->pdf->Output("AWKE-Invoice-$this->data[irn].pdf","I");
}

public function outputPDF()
{
$pdfd=$this->queryret[pdf];
$irn=$this->data[irn];
header( "Content-type: application/pdf");
header( "Content-length: ".strlen($pdfd)."");
header( "Content-Disposition: inline; filename=awke-invoice-$irn.pdf");
echo $pdfd; 

}

}
