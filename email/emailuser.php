<?php
include('functions.inc');
sqlconn();
head1();

if ((isset($_SESSION[email])) && (isset($_SESSION[password])))
	{
	$email = $_SESSION[email];
	$password = $_SESSION[password];
	}
else
	{
	$email = $_POST['email'];
	$password = $_POST['passwd'];
	$_SESSION[email] = $email;
	$_SESSION[password] = $password;
	}
	
$parts = explode("@", $email);
$user = $parts[0];
$domain = $parts[1];
$location=0;

$sql="SELECT D_ID, domainn FROM domains WHERE domainn='$domain'";
$results = mysql_query($sql);
$row = mysql_fetch_assoc($results);
$domainid = $row[D_ID];
$_SESSION[domainid] = $domainid;
$_SESSION[domain] = $domain;

$sql="SELECT email, p1clear, p2clear, maildir, EU_ID FROM emailusers WHERE (email='$user' AND p1clear='$password' AND domain='$domainid')";
$results = mysql_query($sql);

$num_rows = mysql_num_rows($results);
print "<div class=\"subtabinfo\">";

if ($num_rows == 0)
	{
	print "There is no record with these details</div>";
	print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
	print "<a href=\"index.php\">Back</a></td></tr></table></div>";
	exit;
	}
print "Details for " . $email . "<br><br>";
$row = mysql_fetch_assoc($results);
$_SESSION[emailid] = $row[EU_ID];
$keys=array("Email User","Password 1","Password 2");
$functions=array(array("Edit","<a href=\"emailedit.php\">Edit</a>"));
$data[$location]=array($row[email],$row[p1clear],$row[p2clear]);
$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;
print "<br>";
$sql="(SELECT aliases.A_ID AS A_ID, aliases.aliased AS aliased, aliases.alias AS alias, aliases.disabled AS disabled, domains.domainn AS domainn FROM aliases, domains WHERE aliases.aliased='$user' AND aliases.domain='$domainid' AND domains.D_ID=aliases.domain) UNION (SELECT aliases.A_ID AS A_ID, aliases.aliased AS aliased, aliases.alias AS alias, aliases.disabled AS disabled, domains.domainn AS domainn FROM aliases, domains WHERE aliased='$email' AND domains.D_ID=aliases.domain) UNION (SELECT aliases.A_ID AS A_ID, aliases.aliased AS aliased, aliases.alias AS alias, aliases.disabled AS disabled, domains.domainn AS domainn FROM aliases, domains WHERE aliased LIKE '$user--%@$domain' AND domains.D_ID=aliases.domain) UNION (SELECT aliases.A_ID AS A_ID, aliases.aliased AS aliased, aliases.alias AS alias, aliases.disabled AS disabled, domains.domainn AS domainn FROM aliases, domains WHERE aliased LIKE '$user+%@$domain' AND domains.D_ID=aliases.domain) UNION (SELECT aliases.A_ID AS A_ID, aliases.aliased AS aliased, aliases.alias AS alias, aliases.disabled AS disabled, domains.domainn AS domainn FROM aliases, domains WHERE aliases.aliased LIKE '$user--%' AND aliases.domain='$domainid' AND domains.D_ID=aliases.domain) UNION (SELECT aliases.A_ID AS A_ID, aliases.aliased AS aliased, aliases.alias AS alias, aliases.disabled AS disabled, domains.domainn AS domainn FROM aliases, domains WHERE aliases.aliased LIKE '$user+%' AND aliases.domain='$domainid' AND domains.D_ID=aliases.domain) ORDER BY alias";

$result = mysql_query($sql);

$num_rows = mysql_num_rows($result);

if ($num_rows == 0)
	{
	print "</div></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
	print "<a href=\"logout.php\">Logout</a></td></tr></table></div>";
	exit;
	}
if ($num_rows == 1)
	{
	print "The following email alias are redirected to $email<br><br>";
	}
else
	{
	print "The following email aliases are redirected to $email<br><br>";
	}

$lookup=array("0"=>"No","1"=>"Yes");
$keys=array("Alias","Alias's Domain","Aliased","Disabled");
$functions=array(array("Edit","<a href=\"emailaliasedit.php?id=%UNIQ%\">Edit</a>"));
unset($_SESSION[secstore][editalias]);
unset($_SESSION[secstore][alias]);
while ($row = mysql_fetch_assoc($result)) {
$disabled = $lookup[$row[disabled]];
$data[$location]=array($row[alias],$row[domainn],$row[aliased],$disabled);
$uniq[$location]=$location;
$_SESSION[secstore][editalias][$location]=$row[A_ID];

$location++;
}
$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;
print "<br>Click <a href=\"emailaliasadd.php\">here</a> to add a new alias<br><br>";
print "The table below lists the email address that are NOT spam or virus checked.<br><br>";

$sql2="SELECT SenderAddress FROM exceptions_sender";
$results2 = mysql_query($sql2);
$keys2=array("Sender Address");
$functions2=array();
$location=0;
while ($row2 = mysql_fetch_assoc($results2))
{
$data2[$location]=array($row2[SenderAddress]);
$location++;
}
$tempstr2=disptable($keys2,$data2,"",$functions2,"usual");
print $tempstr2;
print "<br>Click <a href=\"senderexceptionadd.php\">here</a> to add a new sender exception or <a href=\"senderexceptiondel.php\">here</a> if you want an entry removed<br><br>";
/*
$filename = "filter.txt";
if (file_exists($filename))
{
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
}
PRINT <<<END
<form name="myform" action="filtersupdate.php" method="POST">
Use the area below to edit your filters file<br><br>
<textarea class="textarea" cols="40" rows="5" name="filters">$contents</textarea>
<br><br>
<div align="center"><input type="submit" value="Submit"></div>
</form>
END;
*/
print "</div>";
footer();
?>