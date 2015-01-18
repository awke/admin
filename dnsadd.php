<?php

include('functions.inc');
sqlconn();

print <<<END

<div class="infolabel">Add FTP User</div>
<div class="subtabinfo">
<table><tr><td>
<div class="description">See previous page<br><br></div></td></tr><tr><td>
<form method="post" action="dnsupdatea.php">
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");

$sql="SELECT name,ttl,type,prio,content,nocreate,id FROM dnsrecords WHERE id='$ftpid'";

$row=array("name"=>"","ttl"=>"","type"=>"","prio"=>"","content"=>"","nocreate"=>"0","");


$desc=array("Name","TTL","RR<br>[type]","Priority<br>[MX]","Content","Disabled","");

//$desc=array("","","name","","","Homedir","","","","Deactivated","");
//$function=array(array(1),array(1),array(0),array(1),array(1),array(0),array(1),array(1),array(1),array(2,$lookup),array(1));
$function=array(array(0),array(0),array(0),array(0),array(0),array(2,$lookup),array(1));


//$function=array(array(1),array(0),array(0),array(1),array(1),array(0),array(1),array(1),array(1),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);
print "</td></tr></table></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 

bottom();

?>



