<?php

include('functions.inc');
sqlconn();

print <<<END

<div class="infolabel">Add FTP User</div>
<div class="subtabinfo">
<table><tr><td>
<div class="description">An FTP user will be able to log in to your server directory and upload/download files.  Choose a username and password (leave the password field blank for the server to automatically generate one).
<br><br>If you wish to allow a user but limit their access to a certain directory enter the directory name in the homedir field, .i.e. to limit a user to your web server directory add '/www/wwwroot/' to the homedir field.<br><br></div></td></tr><tr><td>
<form method="post" action="ftpupdatea.php">
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");

$row=array("","username"=>"","password"=>"","","","homedir"=>"","","","","deactivated"=>"","");

$desc=array("","Username","Password","","","Homedir","","","","Deactivated","");
$function=array(array(1),array(0),array(0),array(1),array(1),array(0),array(1),array(1),array(1),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);
print "</td></tr></table></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 

bottom();

?>



