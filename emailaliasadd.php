<?php

include('functions.inc');
sqlconn();
print <<<END

<div class="infolabel">Add Email Alias</div>
<div class="subtabinfo">
<table><tr><td>
<div class="description">An email alias is not a real email account.  Instead it is an address that forwards all email it receives to another email account.
<br>For example, if you establish an email alias called info@yourdomain.com to go to questions@yourdomain.com, put 'info' in the alias field and 'questions' in the email user field.  All email sent to info@yourdomain.com will be automatically forwarded to questions@yourdomain.com.
<br>You can setup an email alias so that email is forwarded to an account outside of your domain name as well.  For example, you can setup info@yourdomain.com to go to enquiries@otherdomain.com, in this case enter 'info' into the alias field and the full email address 'enquiries@otherdomain.com' in the email user field.
<br>You can also setup an email alias to forward to multiple email users or another alias.  Just enter the same alias for each alias or email address you wish to forward to.
<br>If you create an email alias with the same name as an email account in your domain all email will be forwarded to where the alias points and will not be delivered to the email users account.  To recieve the email in the email users account and have it forwarded, create two aliases, one which forwards to itself and one which forwards to the other email address, i.e. sales -> sales and sales -> questions@anotherdomain.com<br><br></div></td></tr><tr><td>
<form method="post" action="emailaliasupdatea.php">
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");

$row=array("","alias"=>"","","aliased"=>"","disabled"=>"","");

$desc=array("","Alias","","Email User","Disabled","");
$function=array(array(1),array(0),array(1),array(0),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);
print "</td></tr></table></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 
bottom();

?>



