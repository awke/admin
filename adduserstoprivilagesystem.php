<?php
$HEADER=1;
include('functions.inc');
sqlconn();
/*
if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}
*/

//disparray($_SESSION);

print <<<END
<div class="infolabel">Privilage System</div>
<div class="subtabinfo">
<form method="post" action="addusertoprivilagesystemu.php">
<p>Add User to privilage system</p>
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");
$lookup2=array("1"=>"Yes", "0"=>"No");
$row=array("","username"=>"","passwd"=>"","description"=>"");
$desc=array("","Username","Password","Description");
$function=array(array(1),array(0),array(0),array(0),array(0));
edittable("add",0,"usual",$row,$desc,$function);

print <<<END
</form>
<p>Grant Permissions on Privilage System</p>
<form method="post" action="addusertoprivilagesystemg.php">
END;



$sql="SELECT idauthusers,CONCAT(username, ' - ', description) as describ FROM authusers";
$re2=gosql($sql,0);
while ($res2=mysql_fetch_assoc($re2))
{
//print_r($res2);
$d=$res2[idauthusers];
$lookp[$d]=$res2[describ];
}
//print_r($lookp);

$D_ID=$_SESSION[INFO][D_ID];
$sql="SELECT userpriv.D_ID as domainid,iduserpriv,domains.description as domsdesc, domainn,deactivated FROM userpriv LEFT JOIN domains ON domains.D_ID = userpriv.D_ID WHERE  idauthusers='$_SESSION[idauthusers]' GROUP BY domainn ORDER BY domainn";
$results=gosql($sql,0);

$loc=0;
$row=mysql_fetch_assoc($results);
unset($data);
if($row[domainid]==0)
{
// SUPERUSER
$REMOVEABILITY=1;
$sql="SELECT * from privdev";
$results=gosql($sql,0);
$data[0]="ALL PRIVILAGES";
}
else
{
$sql="SELECT userpriv.D_ID as domainid,description,iduserpriv,idauthusers,idauthusers,privdev_idprivdev as idprivdev,privdev_idprivdev FROM userpriv,privdev WHERE  privdev.idprivdev = userpriv.privdev_idprivdev  AND idauthusers='$_SESSION[idauthusers]' AND userpriv.D_ID='$D_ID' ";
$results=gosql($sql,0);
$row=mysql_fetch_assoc($results);
if($row[idprivdev]==0)
{
$REMOVEABILITY=1;
$sql="SELECT * from privdev";
$results=gosql($sql,0);
$data[0]="ALL PRIVILAGES";
}
else
{
$data[$row[idprivdev]]=$row[description];
}
}
while ( $row=mysql_fetch_assoc($results) )
{
$data[$row[idprivdev]]=$row[description];
}
asort($data);
//print "<pre>";
//print_r($data);
$row=array("idauthusers"=>"");
$desc=array("User");
$function=array(array(2,$lookp));
//print_r($lookup);
$lookup2=array("0"=>"No","1"=>"Yes");

foreach($data as $k => $v)
{

$row[$k]="0";
$desc[]=$v;
$function[]=array(2,$lookup2);
}
edittable("add",0,"usual",$row,$desc,$function);

if($REMOVEABILITY==1)
{
$sql="SELECT * from privdev";
$results=gosql($sql,0);
$privinfo[0]="ALL PRIVILAGES";
while ( $row=mysql_fetch_assoc($results) )
{
$privinfo[$row[idprivdev]]=$row[description];
}
$sql="SELECT userpriv.D_ID as domainid,description,iduserpriv,idauthusers,idauthusers,privdev_idprivdev as idprivdev,privdev_idprivdev,iduserpriv FROM userpriv,privdev WHERE  privdev.idprivdev = userpriv.privdev_idprivdev AND userpriv.D_ID='$D_ID' ";
$results=gosql($sql,0);

$loc=0;
unset($datadisp);
$num_rows = mysql_num_rows($results);
if ($num_rows)
	{
	while($row=mysql_fetch_assoc($results))
		{
		$user=$lookp[$row[idauthusers]];
		$priv=$privinfo[$row[idprivdev]];
		$datadisp[]=array($user,$priv);
		$uniq[$loc]=$loc;
		$_SESSION[userpriv][uniq][$loc]=$row[iduserpriv];
		$loc++;
		}
	$function=array(array("Delete","<a href=\"addusertoprivilagesystemdel.php?id=%UNIQ%\">Delete</a>"));
	$keys=array("User","Privilage");
	$rt=disptable($keys,$datadisp,$uniq,$function,"usual",1);
	print "<p>Delete users From Privilage System</p>" . $rt;
	}
}

print "<br><br><br></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>";

bottom();
?>
