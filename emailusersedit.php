<?php
include('functions.inc');
$HEADER=1;
sqlconn();

print "<div class=\"session\">$_SESSION[idauthusers] -- $_SESSION[authuserdescription]</div>";

$passedid=$_GET[id];  

if (isset($_SESSION[secstore][editemail][emailerrid])) {
$emailid=$_SESSION[secstore][editemail][emailerrid];
unset($_SESSION[secstore][editemail]);
$_SESSION[secstore][editemail][$id]=$emailid;
} 
else {
$emailid=$_SESSION[secstore][editemail][$passedid];
unset($_SESSION[secstore][editemail]);          
$_SESSION[secstore][editemail][$id]=$emailid;
}

$sql="SELECT * FROM emailusers WHERE EU_ID='$emailid'";
$result=gosql($sql,0);

$row = mysql_fetch_assoc($result);

print <<<END
<div class="infolabel">Editing Email details for user '$row[email]'</div>
<div class="subtabinfo2">
<form method="post" action="emailupdate.php">
END;

$lookup=array("0"=>"No","1"=>"Yes");

$desc=array("","","","Password 1","Password 2","","Deactivated","");
$function=array(array(1),array(1),array(1),array(0),array(0),array(1),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);

print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"emailusers.php\">Back</a></td></tr></table></div>"; 
bottom();


?>
