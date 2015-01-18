<?php
include('functions.inc');
sqlconn();
head1();
$errors = errordisp();

$passedid=$_GET[id];
if (isset($_SESSION[secstore][alias]))
	{
	$aliasid=$_SESSION[secstore][alias];
	}
else
	{
	$aliasid=$_SESSION[secstore][editalias][$passedid];
	unset($_SESSION[secstore][editalias]);
	}
$_SESSION[secstore][alias] = $aliasid;
$sql="SELECT * FROM aliases WHERE A_ID='$aliasid'";
$result=gosql($sql,0);
$row = mysql_fetch_assoc($result);

$sql2="SELECT domainn FROM domains WHERE D_ID='$row[domain]'";
$result2=gosql($sql2,0);
$row2 = mysql_fetch_assoc($result2);

print "<div class=\"subtabinfo2\">";
$aliased= $_SESSION[email] . " ($row[aliased])";
print "Currently $row[alias]@$row2[domainn] is redirected to $aliased<br><br>";
if ($errors != "")
	{
	print $errors . "<br>";
	}
print "<form method=\"post\" action=\"emailaliasupdate.php\">";

$lookup=array("0"=>"No","1"=>"Yes");

$desc=array("","Alias","","Aliased","Disabled","");
$function=array(array(1),array(0),array(1),array(0),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);

print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"emailuser.php\">Back</a></td></tr></table></div>";

footer();
?>
