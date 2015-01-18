<?php
include('functions.inc');
sqlconn();
head1();
$errors = errordisp();

$sql="SELECT SenderAddress FROM exceptions_sender";
$results = mysql_query($sql);

while ($row = mysql_fetch_assoc($results))
{
$lookup[]=$row[SenderAddress];
}

print "<div class=\"subtabinfo2\">";
print "The email you select below will NOT automatically be removed. An email will be sent to admin@awke.co.uk to request its removal.<br><br>";
if ($errors != "")
	{
	print $errors . "<br>";
	}

print "<form method=\"post\" action=\"senderexceptiondela.php\">";
print "Email to remove <select name=\"emailtoremove\">";
foreach ($lookup as $key)
{
print "<option value=\"$key\">$key";
}
print "</select>";
print "<br><br><div align=\"center\"><INPUT type=\"submit\" value=\"Submit\"></div>";
print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"emailuser.php\">Back</a></td></tr></table></div>";

footer();
?>
