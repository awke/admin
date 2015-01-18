<?php
include('functions.inc');
sqlconn();
head1();
$errors = errordisp();
$domain=$_SESSION[domain];
$row=array("","alias"=>"","","aliased"=>"","","");

$desc=array("","Alias","","Aliased","","");
$function=array(array(1),array(0),array(1),array(0),array(1),array(1));
print "<div class=\"subtabinfo\">";
print "Your new alias will be redirected from (alias)@$domain to the email specified in the aliased field<br>Just enter the (alias) part in the alias field, not the full email address.<br><br>";
print "<form method=\"post\" action=\"emailaliasupdatea.php\">";
print $errors;
edittable("add",0,"usual",$row,$desc,$function);

print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"emailuser.php\">Back</a></td></tr></table></div>";

footer();

?>



