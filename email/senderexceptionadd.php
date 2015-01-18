<?php
include('functions.inc');
sqlconn();
head1();
$errors = errordisp();
$row=array("SenderAddress"=>"");

$desc=array("Sender Address");
$function=array(array(0));
print "<div class=\"subtabinfo\">";
print "Any email to the email address you enter below will NOT be filtered for spam or viruses - add entries with caution<br><br>";
print "<form method=\"post\" action=\"senderexceptionsupdatea.php\">";
print $errors;
edittable("add",0,"usual",$row,$desc,$function);

print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"emailuser.php\">Back</a></td></tr></table></div>";

footer();

?>



