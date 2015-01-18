<?php

include('functions.inc');
sqlconn();
global $lc;
$lci=0;
//disparray($_SESSION);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$sql="SELECT pridomain,deactivated,D_ID,domainn,comments from domains ORDER BY domainn";

$result=gosql($sql);

while($row=mysql_fetch_assoc($result))
{

$customers[][data]=$row;
}



unset($_SESSION[reterrors]);

$loc=0;
$tloc=0;
$eloc=0;
foreach($customers as $id=>$data)
{
$customerid=$data[data][D_ID];
//disparray($data,DATA);
$sql="SELECT idcustdom,customers_idcustomers,fname,lname,billed from custdom,customers WHERE customers_idcustomers=idcustomers AND domains_D_ID='$customerid'";
$result=gosql($sql,0);



if(mysql_num_rows($result)>0)
{
$tloc1=0;
while($row=mysql_fetch_assoc($result))
{

if($row[billed]==1)
{
$tdatadisp[]=array("<b>$row[fname]</b>","<b>$row[lname]</b>");
}
else
{
$tdatadisp[]=array($row[fname],$row[lname]);

}
$tuniq[$tloc1]=$tloc;
$_SESSION[secdata][customers][pindex.php][cdid][$tloc]=$row[idcustdom];
$tloc++;
$tloc1++;
}
$tkeys=array("First Name","Last Name");

$tfunction=array(array("",""));


//$tkeys=array("Cost","Description","Start Date","End Date");
//$tfunction=array(array("Edit","<a href=\"pdedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"pddel.php?id=%UNIQ%\">Delete</a>"));

$teldata=disptable($tkeys,$tdatadisp,$tuniq,$tfunction,"usual",1);
unset($tdatadisp);
$teldata=$teldata."<br><a href=\"cdadd.php?id=%UNIQ%\">Add Customer Link</a>";
}
else
{
$teldata="<br><a href=\"cdadd.php?id=%UNIQ%\">Add Customer Link</a>";
}


$sql="SELECT * from packdom,packages WHERE packages_idpackages=idpackages AND domains_D_ID=$customerid";
$result=gosql($sql);

//$emaildata="<table class=\"%TABCLASS%-email\"><tr><th>Description</th><th>Email</th></tr>";
if(mysql_num_rows($result)>0)
{
$eloc1=0;
while($row=mysql_fetch_assoc($result))
{
//print_r($row);

$sql="SELECT * from invdom WHERE domainid='$customerid' AND packageid='$row[idpackages]'";
$res=gosql($sql);
unset($invoiclist);
while($rw=mysql_fetch_assoc($res))
{
$invoiclist[]=$rw[invoiceid];
}
$frst=1;
if(count($invoiclist)>0)
{
unset($invoicelist);
foreach($invoiclist as $tttv)
{
if($frst==1)
{
$frst=1;
$invoicelist .=" '$tttv' ";
}
else
{
$invoicelist .=", '$tttv' ";
}
}
//$invoicelist now contains list of comma seperated invoiceids;

$sql="SELECT max(invoicedate) as a from invoices WHERE idinvoices IN ($invoicelist)";
$res=gosql($sql,0);
$rw=mysql_fetch_assoc($res);
$row[pbdate]=$rw[a];

$sql="SELECT max(invoicedate) as a from invoices WHERE paid='1' AND idinvoices IN ($invoicelist) ";
$res=gosql($sql,0);
$rw=mysql_fetch_assoc($res);
$row[ppdate]=$rw[a];
}


//$edatadisp[]=array("$row[description]","$row[psdate]",$row[pedate],$row[pbdate],$row[ppdate]);
$edatadisp[]=array("$row[description]","$row[psdate]",$row[pedate]);


$timenow=time();
$timepsd=strtotime($row[psdate]);
$timeped=strtotime($row[pedate]);



if($timenow>=$timepsd)
{
if($timeped>=$timenow)
{
if($data[data][deactivated]==0)
{
$sql="SELECT * from packagedata WHERE packages_idpackages='$row[packages_idpackages]' AND NOW()>sdate AND NOW()<edate";

$datap=gosql($sql,0);
$datapr=mysql_fetch_assoc($datap);


$countpackagedata[$row[description]]++;
$countpackagedatacost[$row[description]]=$datapr[cost];
//print "$row[description] $row[cost]<br>\n";
}
}
}
//print "now:$timenow@@timepsd:$timepsd@@timeped:$timeped<br>\n";

$euniq[$eloc1]=$eloc;
$_SESSION[secdata][customers][index.php][dpid][$eloc]=$row[idpackdom];
$eloc++;
$eloc1++;
}
//disparray($euniq);

//$ekeys=array("Package<br>Description","Start Date","End Date","Last Billed<br>Date","Last Paid<br>Date");
$ekeys=array("Package<br>Description","Start Date","End Date");

$efunction=array(array("Edit","<a href=\"dpedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"dpdel.php?id=%UNIQ%\">Delete</a>"));

$emaildata=disptable($ekeys,$edatadisp,$euniq,$efunction,"usual-email",1);
unset($edatadisp);
$emaildata=$emaildata."<br><a href=\"dpadd.php?id=%UNIQ%\">Add Domain Package</a>";
}
else
{
$emaildata="<br><a href=\"dpadd.php?id=%UNIQ%\">Add Domain Package</a>";
}







if($data[data][deactivated]==1)
$add="DEACTIVATED ";
else
$add="";
if($data[data][pridomain]!=0)
$add=$add."<u>SUBDOMAIN:  ";
unset($invdata);
unset($uniqi);
$sql="SELECT invoiceid from invdom WHERE domainid='$customerid' GROUP BY invoiceid";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
$lc=0;
while($rw=mysql_fetch_assoc($res))
{
$_SESSION[secdata][idinvoices][$lci]=$rw[invoiceid];
$uniqi[$lc++]=$lci++;
$sql="SELECT * from invoices where idinvoices='$rw[invoiceid]'";
$res1=gosql($sql);
$r=mysql_fetch_assoc($res1);
if($r[void]==1)
$r[void]="<font color=#FF0000>YES</font>";
else
$r[void]="no";
if($r[paid]==1)
$r[paid]="<font color=#FFFF00>yes</font>";
else
$r[paid]="<font color=#FF0000>NO</font>";

$invdata[]=array($rw[invoiceid],$r[custref],$r[invoicedate],$r[createdate],$r[void],$r[paid]);

}

$invk=array("InvoiceId","CustRef","Invoice Date","Create Date","Void","Paid");
$invf=array(array("View","<a href=\"idisp.php?id=%UNIQ%\">View</a>"));

$invoicedata=disptable($invk,$invdata,$uniqi,$invf,"usual",1);
}
else
{
$invoicedata="";
}



$datadisp[]=array("$add".$data[data][domainn],$teldata,$emaildata,$invoicedata);


$uniq[$loc]=$loc;
$_SESSION[secdata][customers][pindex.php][pid][$loc]=$customerid;//package id
$loc++;


}


//disparray($customers);

//$function=array(array("Edit","<a href=\"pedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"pdel.php?id=%UNIQ%\">Delete</a>"));
$function=array(array("",""));
$function=array(array("",""));

$keys=array("Domain","Customers","Packages","Invoices",);



print <<<END
<div class="infolabel">Domain -- Packages</div>
<div class="subtabinfo">

END;
//disparray($data);

$rt=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $rt;
//disparray($uniq,"uniq");
//disparray($function,"function");
//disparray($_SESSION,session);

print <<<END
<br><br><br><br>
END;


unset($datadisp);
unset($uniq);
unset($function);
unset($keys);

$count=0;
ksort($countpackagedata);
//print_r($countpackagedata);
foreach($countpackagedata as $k => $v)
{
//print "$k:$v<br>";
$kv=$countpackagedatacost[$k];
$datadisp[]=array($k,$v,"£$kv");
$uniq[89]="89";
$count=$count+$v;
$maxcost=$maxcost+$kv*$v;

}
$datadisp[]=array("<b>Totals</b>","<b>$count</b>","<b>£$maxcost</b>");


$keys=array("Package","Count","Package Cost");
$function=array(array("",""));
$ty=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $ty;

print <<<END
<br><br><br>
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="padd.php">Add Package</a></td></tr></table></div>


END;
bottom();
?>
