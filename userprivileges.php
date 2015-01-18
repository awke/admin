<?php
$HEADER=0;
include('functions.inc');

sqlconn();

$sql="SELECT userpriv.iduserpriv AS iduserpriv,authusers.username AS username,authusers.passwd AS password,domains.domainn AS domain,privdev.description AS privilege FROM authusers,userpriv,privdev,domains WHERE userpriv.idauthusers=authusers.idauthusers AND userpriv.D_ID=domains.D_ID AND userpriv.privdev_idprivdev=privdev.idprivdev ORDER BY username,domainn";

$result=gosql($sql,0);

head1();
top();
print "<div class=\"infolabel\">User Privileges</div>";
print "<div class=\"subtabinfo\">";
$keys=array("Username","Password","Domain","Privilege");
$functions=array(array("Delete","<a href=\"addusertoprivilagesystemdel.php?id=%UNIQ%\">Delete</a>"));


$location=0;

$lookup=array("0"=>"No","1"=>"Yes");

while ($row = mysql_fetch_assoc($result)) {

$data[$location]=array($row[username],$row[password],$row[domain],$row[privilege]);
$uniq[$location]=$location;
$_SESSION[userpriv][uniq][$location]=$row[iduserpriv];

$location++;
}
$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;
print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"index.php\">Back</a></td></tr><tr><td><a href=\"userprivilegesadduser.php\">Add User</a></td></tr><tr><td><a href=\"userprivilegesadd.php\">Add Privileges</a></td></tr></table></div>"; 
bottom();
?>
