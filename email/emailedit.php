<?php
include('functions.inc');
$HEADER=1;
sqlconn();
head1();
$eid = $_SESSION[emailid];

$sql="SELECT * FROM emailusers WHERE EU_ID='$eid'";
$result=gosql($sql,0);

$row = mysql_fetch_assoc($result);

print <<<END
<div class="subtabinfo2">
<form method="post" action="emailupdate.php">
END;
print errordisp();

$desc=array("","","","Password 1","Password 2","","","");
$function=array(array(1),array(1),array(1),array(0),array(0),array(1),array(1),array(1));
edittable("add",0,"usual",$row,$desc,$function);
print "</div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"emailuser.php\">Back</a></td></tr></table></div>";
footer();
?>
