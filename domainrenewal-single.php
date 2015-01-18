<?php
$HEADER=1;
include('functions.inc');
sqlconn();
$t=date ("F d Y H:i:s.", filemtime("/Supported/temp/whoisinfo.txt"));

print "<div class=\"infolabel\">Domain Renewal Single - Data Updated on -- $t</div>";
print "<div class=\"subtabinfo\">";

print"TYPE:$_GET[type]";


print <<<END
<br><a href="domainrenewal-single.php">sort by DOMAIN</a><br>
<a href="domainrenewal-single.php?type=renewal">sort by RENEWAL DATE (Month & Day)</a><br>
<a href="domainrenewal-single.php?type=invoice">sort by INVOICE DATE</a><br>
<a href="domainrenewal-single.php?type=paiddate">sort by PAID DATE</a><br>
<a href="domainrenewal-single.php?REBUILD">REBUILD DATA FROM WHOIS<a><br>

END;

if(isset($_GET[REBUILD]))
{
exec("php5 /Supported/scripts/whoisrebuild.php & </dev/null >/dev/null 2>&1");
}






$file=file("/Supported/temp/whoisinfo.txt");
$data=unserialize($file[0]);
//print_r($file);
//$data=eval(implode("",$file));

/*
print "<pre>";
print_r($data);
*/

//exit();	

/*
print"<pre>";
print_r($data);
*/



sqlconn();

$sql="SELECT * from domains WHERE deactivated='0'";// WHERE deactivated='0'";//  ORDER BY domainn";

$result=gosql($sql,0);

//Record expires on 
//Renewal Date:   
$cnt=0;


while($row=mysql_fetch_assoc($result))
{
//print "<h1>$row[domainn]</h1><br>";
//print "<pre>\n";
$name=$row[domainn];
/*
if(strpos($name,".uk")===FALSE)
{
}
else
{
//sleep(1.1);
}

*/

$domains[]=$name;
$dlookup[$name]=$row[D_ID];

$disabled=$row[deactivated];
$data[$name][ret][deactivated]=$disabled;


$D_ID=$dlookup[$name];
$sql="SELECT * from customers,custdom WHERE customers.idcustomers=custdom.customers_idcustomers AND domains_D_ID='$D_ID'";
$res222=gosql($sql,0);
$cust=mysql_fetch_assoc($res222);

$returned[customer]="$cust[fname] $cust[lname]";
$data[$name][ret][customer]=$returned[customer];

//print_r($cust);

$sql="SELECT * from invdom,invoices WHERE invdom.domainid='$D_ID' AND invdom.invoiceid=invoices.idinvoices AND void='0' ORDER BY invoicedate DESC LIMIT 1";
$res222=gosql($sql,0);
$inv=mysql_fetch_assoc($res222);

//print_r($inv);
$returned[invd]=$inv[invoicedate];
$returned[cdate]=$inv[createdate];

$data[$name][ret][invrows]=mysql_num_rows($res222);
$data[$name][ret][invd]=$inv[invoicedate];
$data[$name][ret][cdate]=$inv[createdate];

$sql="SELECT *,accounts.dat as paiddate from invdom,invoices,accounts WHERE invdom.domainid='$D_ID' AND accounts.invoice=invdom.invoiceid=invoices.idinvoices AND void='0' AND paid='1' ORDER BY paiddate DESC LIMIT 1";
$res222=gosql($sql,0);
$paid=mysql_fetch_assoc($res222);
$data[$name][ret][paiddate]=$paid[paiddate];
$data[$name][ret][paidrows]=mysql_num_rows($res222);




$ren=$data[$name][ret][RenewalDate];
$ren=strtotime($ren);
$ren=date('Y-m-d',$ren);

$sql="SELECT * from domainrenewals WHERE D_ID='$D_ID' AND renewaldate='$ren' LIMIT 1";

$res222=gosql($sql,0);
$rens=mysql_fetch_assoc($res222);
//print_r($rens);
$data[$name][ret][stage]=$rens[stage];

$renstat=$rens[renew];
if($renstat==0)
{
$data[$name][ret][renstat]="UNKNOWN";
}
elseif($renstat==-1)
{
$data[$name][ret][renstat]="NOT RENEWED";
}
elseif($renstat==1)
{
$data[$name][ret][renstat]="RENEWED";
}
if($rens[stage]<1)
{
$data[$name][ret][renstat]="";
}




}

/*
print"<pre>";
print_r($data);
*/


//load do array with the names in he order you want them
$do=$domains;

if(isset($_GET[type]))
{
switch($_GET[type])
{
case "renewal":
foreach($domains as $name)
{
$nm=$data[$name][ret];
$t=$nm[RenewalDate];
$t1=explode("-",$t);
$tt="$t1[0] $t1[1]";
$td[$name]=strtotime($tt);

}
asort($td);
unset($do);
foreach($td as $k => $v)
{
$do[]=$k;
}
break;
case "invoice":
foreach($domains as $name)
{
$nm=$data[$name][ret];
$tt=$nm[invd];
$td[$name]=strtotime($tt);

}
asort($td);
unset($do);
foreach($td as $k => $v)
{
$do[]=$k;
}
break;
case "paid":
foreach($domains as $name)
{
$nm=$data[$name][ret];
$tt=$nm[paiddate];
$td[$name]=strtotime($tt);

}
asort($td);
unset($do);
foreach($td as $k => $v)
{
$do[]=$k;
}
break;


default:

$do=$domains;
}
}


foreach($do as $name)
{

unset($whois);
//$whois1=exec("whois $name",$whois);;
//foreach($whois as $v)
//{
//print $v."\n";
//}


//print "</pre><hr><br>\n";

//disparray($whois,whois);


//print "$name :";
//print_r($data);

$returned=$data[$name][ret];


//$returned=retdata($whois,$row[domainn]);

//$data[$row[domainn]][ret]=$returned;
//$data[$row[domainn]][who]=$whois;

$ukh="#ffff33";
$ukht="";
/*
if($returned[NON]!=true && $returned[deactivated]==0 )
{

$fil=file("/Supported/ns/zones/$name");
$fil1=preg_grep("/IN NS/",$fil);
//$fil1=$fil;
$returned[awke0t]="";
$returned[awke1t]="";

$returned[vcsweb0t]="";
$returned[vcsweb1t]="";

$returned[lont]="";
$returned[sfot]="";
$returned[phlt]="";
$returned[sjct]="";
$returned[sout]="";


foreach($fil1 as $v)
{

//print "$v";
//awke0
$res=stristr($v,"ns.awke.co.uk");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[awke0t]=" <font color=\"olive\">**</font>";

$rt=exec("dig -t SOA $name @ns.awke.co.uk +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");
if(strlen($rt)==0)
{
$returned[awke0t].=" <font color=\"navy\">NO RES</font>";
}
}

//awke0
$res=stristr($v,"ns1.awke.co.uk");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[awke1t]=" <font color=\"olive\">**</font>";
$rt=exec("dig -t SOA $name @ns1.awke.co.uk +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[awke1t].=" <font color=\"navy\">NO RES</font>";
}
}

//awke0
$res=stristr($v,"ns0.vcsweb.com");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[vcsweb0t]=" <font color=\"olive\">**</font>";

$rt=exec("dig -t SOA $name @ns0.vcsweb.com +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[vcsweb0t].=" <font color=\"navy\">NO RES</font>";
}

}

//awke0
$res=stristr($v,"ns1.vcsweb.com");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[vcsweb1t]=" <font color=\"olive\">**</font>";
$rt=exec("dig -t SOA $name @ns1.vcsweb.com +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[vcsweb1t].=" <font color=\"navy\">NO RES</font>";
}
}

//awke0
$res=stristr($v,"ns0.lon.bitfolk.com");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[lont]=" <font color=\"olive\">**</font>";

$rt=exec("dig -t SOA $name @ns0.lon.bitfolk.com +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[lont].=" <font color=\"navy\">NO RES</font>";
}

}

//awke0
$res=stristr($v,"ns0.sfo.bitfolk.com");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[sfot]=" <font color=\"olive\">**</font>";

$rt=exec("dig -t SOA $name @ns0.sfo.bitfolk.com +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[sfot].=" <font color=\"navy\">NO RES</font>";
}

}
//awke0
$res=stristr($v,"phl.nameserver.net");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[phlt]=" <font color=\"olive\">**</font>";

$rt=exec("dig -t SOA $name @phl.nameserver.net +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[phlt].=" <font color=\"navy\">NO RES</font>";
}

}

//awke0
$res=stristr($v,"sjc.nameserver.net");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[sjct]=" <font color=\"olive\">**</font>";

$rt=exec("dig -t SOA $name @sjc.nameserver.net +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[sjct].=" <font color=\"navy\">NO RES</font>";
}


}
//awke0
$res=stristr($v,"sou.nameserver.net");
$cor=0;

if($res!==FALSE)
$cor=1;

if($cor==1)
{
//$sdataret=subretdata($v);
//$y=count($sdataret)-1;
$returned[sout]=" <font color=\"olive\">**</font>";
$rt=exec("dig -t SOA $name @sou.nameserver.net +norecurse +nostat +nostats +short +nocomments +nocmd +notrace +nottlid");

if(strlen($rt)==0)
{
$returned[sout].=" <font color=\"navy\">NO RES</font>";
}

}




}


}

*/

if($returned[Tag]=="INTERNET-BS" || $returned[Tag]=="InternetBS")
{
$ukh="#00ffff";
$ukht="<font color=\"blue\">**</font>";
}


if($returned[Tag]=="UKHOSTS")
{
$ukh="#00ff00";
$ukht="<font color=\"red\">**</font>";
}
if($returned[NON]==true)
{
$returned[Tag]="";
$ukh="#ffffff";
}


global $rows;
//disparray($returned);
$now=time();
$renwl=strtotime($returned[RenewalDate]);


$bgcolor="#00FF00";
$weeks6=$renwl-(60*60*24*7*6);
$weeks3=$renwl-(60*60*24*7*3);
$weeks0=$renwl-(60*60*24*4.5);
$weeks52=$renwl-(60*60*24*7*25);

if($now>=$weeks52)
{
$bgcolor="#99ff66";
}

if($now>=$weeks6)
{
$bgcolor="#ffff33";
}

if($now>=$weeks3)
{
$bgcolor="#ffcc00";
}


if($now>=$weeks0)
{
$bgcolor="#ff0000";
}

if($now>=$renwl)
{
$bgcolor="#cc33ff";

//print "<pre>ERROR: $name " . print_r($whois,true) . "<br>";


}
if($returned[NON]==true)
{
$bgcolor="#ffffff";
}

$purch=strtotime($returned[PurchaseDate]);

$yr6=$purch+(60*60*24*365*6);
//print "now:$now@@pu:$purch@@@6yrs:$yr6<br>";

$pcolor="#000077";

/*
if($purch> 1192424400)
{
$bitcolor="#FF0000";
$vcscolor="#00FF00";

}
else
{
$bitcolor="#FFFFFF";
$vcscolor="#FFFFFF";
}
*/
$bitcolor="#ffffff";
$vcscolor="#ffffff";

if(($purch+(60*60*24*365*6))>=$now)
{
$pcolor="#000099";
}

if(($purch+(60*60*24*365*4))>=$now)
{
$pcolor="#0000BB";
}
if(($purch+(60*60*24*365*2))>=$now)
{
$pcolor="#0000DD";
}
if(($purch+(60*60*24*365*1))>=$now)
{
$pcolor="#0000FF";
}

if($returned[NON]==true)
{
$returned[PurchaseDate]="<b>NOT REGISTERED</b>";
$pcolor="#ffffff";
}

if($returned[awke0]=="Yes")
{
$bgcolor1="bgcolor=\"#00FF00\" ";
}
else
{
$bgcolor1="bgcolor=\"#FF0000\" ";
}



if($returned[awke1]=="Yes") 
{
$bgcolor2="bgcolor=\"#00FF00\" "; 
}
else
{
$bgcolor2="bgcolor=\"#FF0000\" "; 
}



if($returned[lon]==true ||$returned[sfo]==true ||$returned[phl]==true ||$returned[sjc]==true ||$returned[sou]==true )
{
$bitcolor="#ff0000";
}
if($returned[vcsweb0]==true || $returned[vcsweb1]==true)
{
$vcscolor="#ff0000";
}

if($returned[NON]==true)
{
$bgcolor1="bgcolor=\"#ffffff\" ";
$bgcolor2="bgcolor=\"#ffffff\" ";

}





$invt=strtotime($returned[invd]);
$cdate=strtotime($returned[cdate]);
$paidt=strtotime($returned[paiddate]);
if($paidt<1000)
{
$paidt=0;
}
$now=mktime();
//print "$returned[invd] - $invt --- $now<br>";
$t365d=(60*60*24*365);
$tpaid=(60*60*24*62);
$temp3=$paidt-$cdate;
$dys=round($temp3/60/60/24);
$dys1=$dys;
if($dys1<0)
{
$dys1=$dys1*-1;
}

//print "$name::  paidt:$returned[paiddate]^$paidt cdate:$returned[cdate]^$cdate  inc:$dys^$dys1<br>";

$pdcolor="#00ff00";


if($returned[invrows]>0)
{
$icolor="#00ff00";
$treturned[invd]="<b>$returned[invd]</b><br><div><font size=\"-2\">Created:</font>$returned[cdate]</div>	";
if(($invt+$t365d)<mktime())
{
$icolor="#ff0000";
}
}
else
{
$icolor="#cc33ff";
$treturned[invd]="&nbsp;";
$pdcolor="#cc33ff";
}


if($dys1>62)
{
$pdcolor="#ff0000";
}


if($returned[invrows]==0)
{
$pdcolor="#cc33ff";
}

if($returned[customer]==" ")
{
$ccolor="#cc33ff";
}
else
{
$ccolor="";
}

if($returned[deactivated]==1)
{
$bgcolor="#000000";
$returned[name]=$name;
$bitcolor="#000000";
$bgcolor1="bgcolor=#000000";
$bgcolor2="bgcolor=#000000";
$pdcolor="#000000";
$icolor="#000000";
$ccolor="#000000";
$ukh="#000000";
$pcolor="#000000";
}

//print "domain:$returned[name]@@now:$now@@@renwl:$renwl@@@@weeks6:$weeks6@@@@@weeks3:$weeks3<br>\n";

$treturned[paidd]=$returned[paiddate];

//$r="<tr><td bgcolor=\"$bgcolor\">$returned[name]</td><td bgcolor=\"$pcolor\">$returned[PurchaseDate]</td><td bgcolor=\"$bgcolor\">$returned[RenewalDate]</td><td>$returned[atack0]</td>$returned[atack1]</td><td>$returned[awke0]</td><td>$returned[awke1]</td></tr>";

$r="<tr><td bgcolor=\"$bgcolor\">$returned[name]</td><td bgcolor=\"$pcolor\">$returned[PurchaseDate]</td><td bgcolor=\"$bgcolor\">$returned[RenewalDate] $ukht</td><td $bgcolor1 >$returned[awke0]$returned[awke0t]</td><td $bgcolor2 >$returned[awke1]$returned[awke1t]</td><td $bgcolor1 >$returned[awke2]$returned[awke2t]</td><td bgcolor=\"$vcscolor\">$returned[vcsweb0]$returned[vcsweb0t]</td><td  bgcolor=\"$vcscolor\">$returned[vcsweb1]$returned[vcsweb1t]</td><td bgcolor=\"$bitcolor\">$returned[lon]$returned[lont]</td><td bgcolor=\"$bitcolor\">$returned[sfo]$returned[sfot]</td><td  bgcolor=\"$bitcolor\">$returned[phl]$returned[phlt]</td><td  bgcolor=\"$bitcolor\">$returned[sjc]$returned[sjct]</td><td  bgcolor=\"$bitcolor\">$returned[sou]$returned[sout]</td><td bgcolor=\"$ukh\">$returned[Tag]</td><td>$returned[stage]</td><td>$returned[renstat]</td><td bgcolor=\"$ccolor\">$returned[customer]</td><td bgcolor=\"$icolor\">$treturned[invd]</td><td bgcolor=\"$pdcolor\">$treturned[paidd]</td></tr>";

$r="<tr><td bgcolor=\"$bgcolor\">$returned[name]</td><td bgcolor=\"#ffffff\">$returned[PurchaseDate]</td><td bgcolor=\"$bgcolor\">$returned[RenewalDate] $ukht</td><td $bgcolor1 >$returned[awke0]$returned[awke0t]</td><td $bgcolor2 >$returned[awke1]$returned[awke1t]</td><td $bgcolor1 >$returned[awke2]$returned[awke2t]</td><td bgcolor=\"$ukh\">$returned[Tag]</td><td>$returned[stage]</td><td>$returned[renstat]</td><td bgcolor=\"$ccolor\">$returned[customer]</td></tr>";

//print $r;
$row2s[]=$r;
}

//disparray($rows);

print <<<END
</pre>
<table class="usual">
<tr><th>Domain Name</th><th>Purchase Date</th><th>Renewal Date</th><th>ns<br>awke</th><th>ns1<br>awke</th><th>ns2<br>awke</th><th>TAG</th><th>Renewal Stage</th><th>Renewal Status</th><th>Customer</th></tr>
END;
$t=count($row2s);
//$t=40;


//disparray($row2s);

//}
$v=$row2s[$a];
foreach($row2s as $v)
{
//disparray(count ($row2s));
print "$v\n";
}
print "</table>";

print <<<END

<p>Total of $t hosted/parked domains</p>

END;
print  <<<END
<br><br>
<p>
Please note the NO RES under the dns box along with Yes means the DNS server is not responding to the query for this domain so should therefore be removed or fixed .  the olive ** under dns means that the zone files report that this is a valid server (if NO RES consider removing the dns entries for it)

</p>

<br><br></div>
END;
//file_put_contents(""/Supported/temp/whoisinfo.txt"",serialize($data));


bottom();
?>


