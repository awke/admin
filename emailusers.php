<?php
$HEADER=0;
include('functions.inc');

if ($_SESSION[secstore][editemail][deleted] == 1)
{
unset($_SESSION[secstore][editemail][deleted]);
header("location: domdisp.php?D_ID=$_INFO[D_ID]");
exit();
}


sqlconn();

$sql="SELECT email, p1clear, p2clear, maildir, EU_ID FROM emailusers WHERE domain='$_INFO[D_ID]'";
$result=gosql($sql,0);

if (mysql_num_rows($result) == 0)
{
$_SESSION[error][]=" You do not have any Email Users to edit";

header("location: domdisp.php?D_ID=$_INFO[D_ID]");

exit();
}

head1();
top();
print "<div class=\"infolabel\">Edit Email User</div>";
print "<div class=\"subtabinfo\">";
$keys=array("Email User","Password 1","Password 2","Deactivated");
$functions=array(array("Edit","<a href=\"emailusersedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"emailusersdel.php?id=%UNIQ%\">Delete</a>"));

$location=0;

$lookup=array("0"=>"No","1"=>"Yes");

while ($row = mysql_fetch_assoc($result)) {

$state=$lookup[$row[deactivated]];

$data[$location]=array($row[email],$row[p1clear],$row[p2clear],$state);
$uniq[$location]=$location;
$_SESSION[secstore][editemail][$location]=$row[EU_ID];

$location++;
}

$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;
print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>";
bottom();
?>
