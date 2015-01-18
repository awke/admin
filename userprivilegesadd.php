<?php
$HEADER=1;
include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

print <<<END
<div class="infolabel">Add Privileges</div>
<div class="subtabinfo">
<form method="post" action="userprivilegesupdatea.php">
END;

$sql="SELECT username,idauthusers FROM authusers ORDER BY username";
$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result))
	{
	$userid[$row[idauthusers]]=$row[username];
	}

$sql="SELECT D_ID,domainn FROM domains ORDER BY domainn";
$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result))
	{
	$domain[$row[D_ID]]=$row[domainn];
	}

$sql="SELECT idprivdev,description FROM privdev ORDER BY idprivdev";
$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result))
	{
	$privdev[$row[idprivdev]]=$row[description];
	}
	
$row=array("idauthusers"=>"","D_ID"=>"","privdev_idprivdev"=>"");
$desc=array("Username","Domain","Privilege");
$function=array(array(2,$userid),array(2,$domain),array(2,$privdev));
edittable("add",0,"usual",$row,$desc,$function);

print "<br><br><br></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"userprivileges.php\">Back</a></td></tr></table></div>";
bottom();
?>
