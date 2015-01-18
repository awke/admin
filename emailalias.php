<?php
$HEADER=0;
include('functions.inc');

if ($_SESSION[secstore][editalias][deleted] == 1)
{
unset($_SESSION[secstore][editalias][deleted]);
header("location: domdisp.php?D_ID=$_INFO[D_ID]");
exit();
}

sqlconn();

$sql="SELECT alias, aliased, disabled, A_ID FROM aliases WHERE domain='$_INFO[D_ID]' ORDER BY alias";
$result=gosql($sql,0);

if (mysql_num_rows($result) == 0)
{
$_SESSION[error][]="You do not have any Email Aliases to edit";

header("location: domdisp.php?D_ID=$_INFO[D_ID]");

exit();
}

head1();
top();
print "<div class=\"infolabel\">Edit Email Alias</div>";
print "<div class=\"subtabinfo\">";
$keys=array("Alias","Email User","Deactivated");
$functions=array(array("Edit","<a href=\"emailaliasedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"emailaliasdel.php?id=%UNIQ%\">Delete</a>"));

$location=0;

$lookup=array("0"=>"No","1"=>"Yes");

while ($row = mysql_fetch_assoc($result)) {

$state=$lookup[$row[disabled]];

$data[$location]=array($row[alias],$row[aliased],$state);
$uniq[$location]=$location;
$_SESSION[secstore][editalias][$location]=$row[A_ID];

$location++;
}

$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;
print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 
bottom();
?>
