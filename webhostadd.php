<?php

include('functions.inc');
sqlconn();


errordisp();

print <<<END

<div class="infolabel">Add Website for Webhosting</div>
<div class="subtabinfo">
<table><tr><td>
<div class="description">
Allows another website for webhosting
</div></td></tr><tr><td>
<form method="post" action="webhostadda.php">
END;

$lookup=array("standard"=>"Normal","secure"=>"Secure server (DO NOT USE)");



$row=array("WH_ID"=>"","D_ID"=>"46","name"=>"","type"=>"standard","ip"=>"212.13.216.213","port"=>"80","lastupdated"=>"");

$desc=array("","","Webhost subdomain","Host Type","IP Address","Port Number","");
$function=array(array(1),array(1),array(0),array(2,$lookup),array(0),array(0),array(1));

edittable("add",0,"usual",$row,$desc,$function);
print "</td></tr></table></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 

bottom();

?>



