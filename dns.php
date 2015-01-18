<?php
//require_once("/Supported/phpcode/usmachine.php");


$HEADER=0;
include('functions.inc');

/*
if ($_SESSION[secstore][editdns][deleted] == 1)
{
unset($_SESSION[secstore][editdns][deleted]);
header("location: domdisp.php?D_ID=$_INFO[D_ID]");
exit();
}
*/

sqlconn();

$sql="SELECT name,ttl,type,prio,content,nocreate, id FROM dnsrecords WHERE domain_id='$_INFO[D_ID]'";

$where="domain_id='$_INFO[D_ID]'";
//$result=dnsselect($where);



//print "DEBUG";
//print_r($result);




//print_r($result);
//exit();


$result=gosql($sql,1);

if ( !isset($result))   // == 0)
{
$_SESSION[error][]=" You do not have any dns records to edit";

header("location: domdisp.php?D_ID=$_INFO[D_ID]");

exit();
}



head1();
top();
print "<div class=\"infolabel\">Edit DNS records</div>";
print "<div class=\"subtabinfo\">";
print <<<END
<div class="description"><table><tr><td>
</b>If you dont know what you are doing you will likely break your site.
<br>name is the domain name eg www, @ is curent location
<br>TTL is normally blank
<br>Priority is only needed for MX  RRs
<br>example RRs are NS, MX, A, TXT, SPF
<br>SPECIAL RRs
<br>SOA1 has the nameserver in name and the email address in content
<br>SOA2 has refresh seconds in the name and retry seconds in the content
<br>SOA3 has expire secons in the name and minimum seconds in the content
<br>SERIAL has the RR serial number in the content
<br>content where the record points eg 212.13.194.27 or deltaserver.awke.co.uk.
</td></tr></table>
</div>
END;
$keys=array("Name","TTL","RR<br>[type]","Priority<br>[MX]","Content","Disabled");
$functions=array(array("Edit","<a href=\"dnsedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"dnsdel.php?id=%UNIQ%\">Delete</a>"));

$location=0;

$lookup=array("0"=>"No","1"=>"Yes");
//foreach($result as $row)
//{
while ($row = mysql_fetch_assoc($result)) {

$state=$lookup[$row[nocreate]];

$data[$location]=array($row[name],$row[ttl],$row[type],$row[prio],$row[content],$state);
$uniq[$location]=$location;
$_SESSION[secstore][editdns][$location]=$row[id];

$location++;
}

$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;
print <<<END
<br>


END;

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr><tr><td><a href=\"dnsadd.php?D_ID=$_INFO[D_ID]\">Add Record</a></td></tr></table></div>"; 
bottom();
?>
