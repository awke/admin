<?php

include('functions.inc');
sqlconn();


errordisp();


//disparray($_SESSION);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

unset($_SESSION[invoice]);

unset($data);

if ($_GET[paid] || $_GET[void])
	{
	if ($_GET[paid] == "yes")
		{
		$filter = "WHERE paid='1'";
		}
	elseif ($_GET[paid] == "no")
		{
		$filter = "WHERE paid='0'";
		if($_GET[void] == "no")
			{
			$filter =" WHERE paid='0' AND void='0'";
			}
		}
	elseif ($_GET[void] == "yes")
		{
		$filter = "WHERE void='1'";
		}
	elseif ($_GET[void] == "no")
		{
		$filter = "WHERE void='0'";
		}
	}
//print "DEBUG $filter";
$sql="SELECT * from adminInfo.invoices $filter";

$result=gosql($sql,0);
while($row=mysql_fetch_assoc($result))
{
//$customers[][data]=$row;
$data[]=$row;
//$UNIQ[$loc++]=$row[idinvoices];

}

$loc=0;
unset($_SESSION[secdata][idinvoices]);
$gtotal=0;
foreach($data as $v)
{
unset($datapd);
$idinvoices=$v[idinvoices];
$sql="SELECT * from adminInfo.customers WHERE idcustomers='$v[customer]'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
$customerdata="$row[idcustomers] - $row[fname] $row[lname]";
$total=sprintf("&#163;%.02f",$v[total]);
$gtotal=$gtotal+$v[total];
if($v[void]==1)
$v[void]="<font color=#FF0000>YES</font>";
else
$v[void]="no";
if($v[paid]==1)
$v[paid]="<font color=#00FF00><b>yes</b></font>";
else
$v[paid]="<font color=#FF0000><b>NO</b></font>";

$uniqref="<a href=\"https://secure.awke.co.uk/admin/invoicedisplay.php?ID=$v[uniqueidinvoice]\">Customer Link</a>";
$v[uniqref]=$uniqref;

$sql="SELECT * from adminInfo.invdom WHERE invoiceid='$idinvoices'";
$res=gosql($sql);
if(mysql_num_rows($res)>0)
{
unset($dpackd);
while($rw=mysql_fetch_assoc($res))
{
$dpackd[]=$rw;
}
$lc=0;
foreach($dpackd as $v1)
{
$uniqpd[$lc]=$lc++;
$dt[Domain]=$v1[domainid];
$dt[Package]=$v1[packageid];

$sql="SELECT description from packages WHERE idpackages='$dt[Package]'";
$res=gosql($sql);
$rw=mysql_fetch_assoc($res);
$dt[Package]=$rw[description];

if($dt[Domain]!=0)
{
$sql="SELECT * from domains WHERE D_ID='$dt[Domain]'";
$res=gosql($sql,0);
$rw=mysql_fetch_assoc($res);
$dt[Domain]=$rw[domainn];
}
else
{
$dt[Domain]="";
}
$datapd[]=array($dt[Domain],$dt[Package]);

}
$keyspd=array("Domain","Package");

//$uniqpd[$lc]=$lc;
//$_SESSION[secdata][idinvoices][$lc]=$v[invoices]

$funcpd=array(array("",""));

$dompack=disptable($keyspd,$datapd,$uniqpd,$funcpd,"usual",0);
}
else

{
$dompack="";
}
$datadisp[]=array($v[idinvoices],$customerdata,$v[invoicedate],$v[createdate],$total,$v[numbitems],$v[void],$v[custref],$v[paid],$dompack,$v[uniqref],$v[comment]);
$uniq[$loc]=$loc;
$_SESSION[secdata][idinvoices][$loc]=$idinvoices;//package id
$loc++;
}

//disparray($customers);

$function=array(array("View","<a href=\"idisp.php?id=%UNIQ%\">View</a>"),array("Void","<a href=\"iedit.php?id=%UNIQ%\">Void</a>"));

$function=array(array("Void","<a href=\"iedit.php?id=%UNIQ%\">Void</a>"));

//$keys=array("First Name","Last Name","Address","Telephone","Email","Domains");
$keys=array("Invoice Number","customer","Invoice Date","Created Date","Total","Numb Items","Void","Customer Ref","Paid","Domains/Packages","Customer View - Link","Comment");


print <<<END
<div class="infolabel">Invoices</div>
<div class="subtabinfo">
<table>
<tr><td><a href="iindex.php?paid=yes">View only paid invoices</a></td><td><a href="iindex.php?void=yes">View only void invoices</a></td></tr>
<tr><td><a href="iindex.php?paid=no">View only unpaid invoices</a></td><td><a href="iindex.php?void=no">View only non void invoices</a></td></tr>
<tr><td colspan="2"><a href="iindex.php">View all invoices</a></td><td><a href="iindex.php?void=no&paid=no">View only unpaid unvoided invoices</a></td></tr></table><br>

END;
//disparray($datadisp);

$rt=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $rt;
//disparray($uniq,"uniq");
//disparray($function,"function");
//disparray($_SESSION,session);

print <<<END
<br><br>
GRAND TOTAL of invoices displayed is &pound;$gtotal<br><br><br><br>
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="iadd.php">Add Invoice</a></td></tr></table></div>


END;

bottom();


/*
$loc=0;
$tloc=0;
$eloc=0;
$dloc=0;
foreach($customers as $id=>$data)
{
$customerid=$data[data][idcustomers];
//disparray($data,DATA);
$sql="SELECT * from custtel WHERE idcustomers='$customerid'";
$result=gosql($sql,0);

//$teldata="<table class=\"%TABCLASS%-tel\"><tr><th>Description</th><th>Number</th></tr>";

if(mysql_num_rows($result)>0)
{
$tloc1=0;
while($row=mysql_fetch_assoc($result))
{
$customers[$id][tel][]=$row;
if($row[pri]==1)
{
$tdatadisp[]=array("<b>$row[description]</b>","<b>$row[tel]</b>");
}
else
{
$tdatadisp[]=array("$row[description]","$row[tel]");

}
$tuniq[$tloc1]=$tloc;
$_SESSION[secdata][customers][index.php][tid][$tloc]=$row[idcusttel];
$tloc++;
$tloc1++;
}

$tkeys=array("Description","Number");
$tfunction=array(array("Edit","<a href=\"tedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"tdel.php?id=%UNIQ%\">Delete</a>"));

$teldata=disptable($tkeys,$tdatadisp,$tuniq,$tfunction,"usual-tel",1);
unset($tdatadisp);
$teldata=$teldata."<br><a href=\"tadd.php?id=%UNIQ%\">Add Telephone Entry</a>";
}
else
{
$teldata="<br><a href=\"tadd.php?id=%UNIQ%\">Add Telephone Entry</a>";
}

$sql="SELECT * from custemail WHERE customers_idcustomers=$customerid";
$result=gosql($sql);

//$emaildata="<table class=\"%TABCLASS%-email\"><tr><th>Description</th><th>Email</th></tr>";
if(mysql_num_rows($result)>0)
{
$eloc1=0;
while($row=mysql_fetch_assoc($result))
{
$customers[$id][tel][]=$row;
if($row[pri]==1)
{
$edatadisp[]=array("<b>$row[description]</b>","<b>$row[email]</b>");
}
else
{
$edatadisp[]=array("$row[description]","$row[email]");

}
$euniq[$eloc1]=$eloc;
$_SESSION[secdata][customers][index.php][eid][$eloc]=$row[idcustemail];
$eloc++;
$eloc1++;
}
//disparray($euniq);

$ekeys=array("Description","Number");
$efunction=array(array("Edit","<a href=\"eedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"edel.php?id=%UNIQ%\">Delete</a>"));

$emaildata=disptable($ekeys,$edatadisp,$euniq,$efunction,"usual-email",1);
unset($edatadisp);
$emaildata=$emaildata."<br><a href=\"eadd.php?id=%UNIQ%\">Add Email Entry</a>";
}
else
{
$emaildata="<br><a href=\"eadd.php?id=%UNIQ%\">Add Email Entry</a>";
}







$sql="SELECT idcustdom,domains_D_ID,customers_idcustomers,billed,domainn from custdom,domains WHERE D_ID=domains_D_ID AND customers_idcustomers=$customerid";
$result=gosql($sql);

//$emaildata="<table class=\"%TABCLASS%-email\"><tr><th>Description</th><th>Email</th></tr>";
if(mysql_num_rows($result)>0)
{
$dloc1=0;
while($row=mysql_fetch_assoc($result))
{
//$customers[$id][tel][]=$row;
if($row[billed]==1)
{
$ddatadisp[]=array("<b>$row[domainn]</b>");
}
else
{
$ddatadisp[]=array("$row[domainn]");
}

$duniq[$dloc1]=$dloc;
$_SESSION[secdata][customers][index.php][did][$dloc]=$row[idcustdom];
$dloc++;
$dloc1++;
}
//disparray($duniq);

$dkeys=array("Domain");
$dfunction=array(array("Edit","<a href=\"dcedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"dcdel.php?id=%UNIQ%\">Delete</a>"));

//disparray($ddatadisp);

$domdata=disptable($dkeys,$ddatadisp,$duniq,$dfunction,"usual",1);
unset($ddatadisp);
$domdata=$domdata."<br><a href=\"dcadd.php?id=%UNIQ%\">Add Domain Link Entry</a>";

}
else
{
$domdata="<br><a href=\"dcadd.php?id=%UNIQ%\">Add Domain Link Entry</a>";
}











$datadisp[]=array($data[data][fname],$data[data][lname],
$data[data][addrheader]."<br>".$data[data][housenumb] ." ". $data[data][street]."<br>".$data[data][town]."<br>".$data[data][county]."<br>".$data[data][postcode],
$teldata,$emaildata,$domdata);

$uniq[$loc]=$loc;
$_SESSION[secdata][customers][index.php][cid][$loc]=$customerid;
$loc++;
}


*/
?>
