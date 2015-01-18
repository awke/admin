<?php

include('functions.inc');
sqlconn();

print <<<END

<div class="infolabel">Add Email User</div>
<div class="subtabinfo">
<table><tr><td>
<div class="description">An email user will have their own email account on the server with a unique email address and password.
<br>You only need to enter the email users name in the email user field, the text before the @ symbol.  If you wanted info@yourdomain.com just enter 'info' into the email user field.
<br>You can either choose your own passwords or let the server randomly generate them for you, in this case just leave the password fields blank.
<br>Pasword 1 is for the POP3 and IMAP access, password 2 is for sending emails through our SMTP server if this is enabled for your domain.<br><br></div></td></tr><tr><td>
<form method="post" action="emailupdatea.php">
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");

$row=array("","email"=>"","","p1clear"=>"","p2clear"=>"","","deactivated"=>"","");

$desc=array("","Email User","","Password 1","Password 2","","Deactivated","");
$function=array(array(1),array(0),array(1),array(0),array(0),array(1),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);
print "</td></tr></table></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 

bottom();

?>



