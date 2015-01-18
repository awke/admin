<?php
include('functions.inc');
$HEADER=1;
sqlconn();

print "<div class=\"session\">$_SESSION[idauthusers] -- $_SESSION[authuserdescription]</div>";

$passedid=$_GET[id];  

if (isset($_SESSION[secstore][editdns][ftperrid])) {
$ftpid=$_SESSION[secstore][editdns][ftperrid];
unset($_SESSION[secstore][editdns]);
$_SESSION[secstore][editdns][$id]=$ftpid;
} 
else {
$ftpid=$_SESSION[secstore][editdns][$passedid];
unset($_SESSION[secstore][editdns]);          
$_SESSION[secstore][editdns][$id]=$ftpid;
}

$sql="SELECT name,ttl,type,prio,content,nocreate,id FROM dnsrecords WHERE id='$ftpid'";
$result=gosql($sql,0);

$row = mysql_fetch_assoc($result);

print <<<END
<div class="infolabel">Editing dns details for record '$row[name]'</div>
<div class="subtabinfo2">
<form method="post" action="dnsupdate.php">
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");

$desc=array("Name","TTL","RR<br>[type]","Priority<br>[MX]","Content","Disabled","");

//$desc=array("","","name","","","Homedir","","","","Deactivated","");
//$function=array(array(1),array(1),array(0),array(1),array(1),array(0),array(1),array(1),array(1),array(2,$lookup),array(1));
$function=array(array(0),array(0),array(0),array(0),array(0),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);


print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"dns.php\">Back</a></td></tr></table></div>"; 
bottom();

?>
