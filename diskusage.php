<?php

include('functions.inc');
sqlconn();
print "<div class=\"session\"><b>$_SESSION[idauthusers]</b> -- $_SESSION[authuserdescription]</div>";
$domain=$_SESSION[INFO][D_ID];
unset($_SESSION[secstore][domain]);

$sql="SELECT directory, username, maillistconfig from domains WHERE D_ID='$domain'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
$user=$row[username];
$directory=$row[directory];

$mapping="/dev/mapper/storage-" . $user;
$mailsubtract="0";
print <<<END
<div class="infolabel">Disk Usage</div>
<div class="maindomlist">
END;


/*
if ($row[maillistconfig]=="1")
	{
	$mailsubtract="29118";
	}



$fname="/dev/mapper/storage-$user";

$fname=str_replace("/","---",$fname);


exec("df $mapping", $usage);
$details=explode(" ",$usage[2]);
$i=0;
foreach ($details as $key => $value) {
  if (!$value=="") 
	{
	$diskspace[$i] = $value;
    $i++; 
	}
}

$totalunf=($diskspace[0]-(32840+$mailsubtract))/1024;
$usedunf=($diskspace[1]-(32840+$mailsubtract))/1024;


$remainingunf=($diskspace[2])/1024;

*/



$totalunf=((disk_total_space("/Supported/domains/$directory")/1024)-32840)/1024;
$remainingunf=disk_free_space("/Supported/domains/$directory")/1024/1024;

//$totalunf=disk_total_space("/Supported/domains/$directory");
//$remainingunf=disk_free_space("/Supported/domains/$directory");
$usedunf=$totalunf-$remainingunf;

$total=sprintf("%.2f",$totalunf);
$used=sprintf("%.2f",$usedunf);
$remaining=sprintf("%.2f",$remainingunf);
print <<<END
<br>
<table class="usual">
  <tr>
    <th>Total Available Disk Space (MB)</th>
    <th>Used Disk Space (MB)</th>
    <th>Free Disk Space (MB)</th>
  </tr>
  <tr>
    <td>$total</td>
    <td>$used</td>
    <td>$remaining</td>
  </tr>
</table>







<p>30 mins</p>
<img alt="diskgraph" src="https://secure.awke.co.uk/admin/diskgraph.php?start=now-1800seconds">
<p>2 hours</p>
<img alt="diskgraph" src="https://secure.awke.co.uk/admin/diskgraph.php?start=now-2hours">
<p>1 day</p>

<img alt="diskgraph" src="https://secure.awke.co.uk/admin/diskgraph.php?start=now-2days">
<p>1 week</p>

<img alt="diskgraph" src="https://secure.awke.co.uk/admin/diskgraph.php?start=now-1week">
<p>1 month</p>

<img alt="diskgraph" src="https://secure.awke.co.uk/admin/diskgraph.php?start=now-1month">
<p>1 year</p>

<img alt="diskgraph" src="https://secure.awke.co.uk/admin/diskgraph.php?start=now-1year">

END;



print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 
bottom();
?>
