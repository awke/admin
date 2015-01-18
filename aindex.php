<?php
if(isset($_GET[csv]))
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}






errordisp();









$sql="SELECT * from adminInfo.accounts ORDER BY dat";

$result=gosql($sql);

unset($data);
while($row=mysql_fetch_assoc($result))
{
$data[]=$row;
}

$function=array(array("Edit","<a href=\"aedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"adel.php?id=%UNIQ%\">Delete</a>"));
$keys=array("date","amount","description","customer","invoice","Subtotal<br> Current","SubTotal<br> Reserve");

//could set below to initial value if skip etc is enabled
$subtotal=0;
$loc=0;
foreach($data  as $v)
{

$custid=$v[customer];
$invid=$v[invoice];

if($custid!=0)
{
$sql="SELECT * from adminInfo.customers WHERE idcustomers='$custid' ORDER by lname,fname";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
$customerdata="$row[idcustomers] - $row[fname] $row[lname]";
}
else
{
$customerdata="";
}

if($invid!=0)
{
$sql="SELECT * from adminInfo.invoices WHERE idinvoices='$invid'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
$invoicedata="$row[idinvoices] - Idate:$row[invoicedate] Cdate:$row[createdate] Total:$row[total] Cref:$row[custref]";
}
else
{
$invoicedata="";
}

if($v[account]==0)
{
$subtotal=$subtotal+$v[amount];
}
else
{
$ressubtotal=$ressubtotal+$v[amount];
}

$subtotalv=sprintf("£%.02f",$subtotal);
$ressubtotalv=sprintf("£%.02f",$ressubtotal);

$amountv=sprintf("£%.02f",$v[amount]);
$datadisp[]=array($v[dat],$amountv,$v[descrip],$customerdata,$invoicedata,$subtotalv,$ressubtotalv);
$uniq[$loc]=$loc;
$_SESSION[secdata][idaccounts][$loc]=$v[idaccounts];//package id
$loc++;
}


if(isset($_GET[csv]))
{
@ob_start();
$content_file = "";
//$content_file .= "cat_id,catname,sequence\r\n";
$first=0;
foreach($keys as $v)
{
if($first==0)
{
$content_file .= "\"$v\"";
$first=1;
}
else
{
$content_file .= ",\"$v\"";
}
}
$content_file .="\r\n";

//$sql = 'select * from categories';
//$statement = mysql_query($sql);
foreach($datadisp as $v)
{
$first=0;
foreach($v as $v1)
{
if($first==0)
{
$content_file .= "\"$v1\"";
$first=1;
}
else
{
$content_file .= ",\"$v1\"";
}
}
$content_file .= "\r\n";
}
$output_file = 'awke-accounts.csv';
@ob_end_clean();
@ini_set('zlib.output_compression', 'Off');
header('Pragma: public');
header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: pre-check=0, post-check=0, max-age=0');
header('Content-Transfer-Encoding: none');
//This should work for IE & Opera
header('Content-Type: application/octetstream; name="' . $output_file . '"');
//This should work for the rest
header('Content-Type: application/octet-stream; name="' . $output_file . '"');
header('Content-Disposition: inline; filename="' . $output_file . '"');
echo $content_file;
exit(); 
}

print <<<END
<div class="infolabel">Accounts</div>
<div class="subtabinfo">
END;

if(isset($_GET[limit]))
{
if(!isset($_GET[amount]))
{
$amount=50;
}
else
{
$amount=$_GET[amount];
}
$skip=$_GET[skip];
$count=count($datadisp);
$start=($skip*$amount);
$end=($start+$amount);
//disparray($count,"s:$start e:$end sk:$skip am:$amount");

if($end>($count))
$end=$count;
unset($temp);


for($a=$start;$a<$end;$a++)
{
$temp[]=$datadisp[$a];
}
$datadisp=$temp;
print "<div class=\"description\">Displaying records $start -> $end</div>";
}


$rt=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $rt;
//disparray($_GET);
if(!isset($_GET[limit]))
{
print <<<END
<br><br><br><br>
</div>
<div class="bbuttontab3"><table class="backbutton3" width=111><tr><td>
<a href="aindex.php?limit=1&skip=0">Limit Display</a></td></tr></table></div>

END;
$a=1;
}
else
{
if($_GET[skip]>0)
{
$prev=$_GET[skip]-1;
print "<div class=\"description\"><a href=\"aindex.php?limit=1&skip=$prev&amount=$amount\">Previous $amount</a><br></div>";
}
$sql="select * from adminInfo.accounts";
$result3= gosql($sql);

$count=mysql_num_rows($result3);
$tot=($_GET[skip]+1)*$amount;
//disparray($count,"s:$start e:$end sk:$skip am:$amount");
if($tot<$count)
{
//disparray(($tot<$count),"t:$tot c:$count");
$next=$_GET[skip]+1;
print "<div class=\"description\"><a href=\"aindex.php?limit=1&skip=$next&amount=$amount\">Next $amount</a><br></div>";
}
print <<<END
<br>
<span class="description">Limit to:&nbsp;&nbsp;&nbsp;<a href="aindex.php?limit=1&skip=0&amount=5">5</a></span>
<span class="description"><a href="aindex.php?limit=1&skip=0&amount=10">10</a></span>
<span class="description"><a href="aindex.php?limit=1&skip=0&amount=25">25</a></span>
<span class="description"><a href="aindex.php?limit=1&skip=0&amount=50">50</a></span>
<span class="description"><a href="aindex.php?limit=1&skip=0&amount=100">100</a></span>
<span class="description"><a href="aindex.php?limit=1&skip=0&amount=250">250</a></span>
<span class="description"><a href="aindex.php?limit=1&skip=0&amount=500">500</a>&nbsp;&nbsp;&nbsp;records per page</span>

<br><br><br><br>
</div>
<div class="bbuttontab3"><table class="backbutton3" width=111><tr><td>
<a href="aindex.php">No-Limit</a></td></tr></table></div>

END;
$a=1;
}
if ($a!=1){print "</div>";}
print <<<END
<br><br><br><br>



<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="aadd.php">Add Record</a></td></tr></table></div>
<div class="bbuttontab2"><table class="backbutton2" width=111><tr><td>
<a href="aindex.php?csv=1">Download csv</a></td></tr></table></div>


END;


bottom();
?>
