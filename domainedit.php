<?php
$HEADER=1;
include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

print "<div class=\"session\">$_SESSION[idauthusers] -- $_SESSION[authuserdescription]</div>";

if (isset($_SESSION[secstore][editdomain][domainerrid])) {
$domainid=$_SESSION[secstore][editdomain][domainerrid];
unset($_SESSION[secstore][editdomain]);
$_SESSION[secstore][domains][domainid]=$domainid;
} 
else {
$passedid=$_GET[id];  
$domainid=$_SESSION[secstore][domains][$passedid];
unset($_SESSION[secstore][domains]);          
$_SESSION[secstore][domains][domainid]=$domainid;
}

$sql="SELECT * FROM domains WHERE D_ID='$domainid'";
$result=gosql($sql,0);

$row = mysql_fetch_assoc($result);

print <<<END
<div class="infolabel">Editing Domain</div>
<div class="subtabinfo2">
<form method="post" action="domainupdate.php">
END;

$lookup=array("0"=>"No","1"=>"Yes");

$primarydomainlookup[0]="none (use for new subdomains being full sites)";
$sql2 = "SELECT D_ID,domainn FROM domains ORDER BY domainn";
$results2 = mysql_query($sql2);
while ($row2 = mysql_fetch_assoc($results2))
	{
	$primarydomainlookup[$row2[D_ID]]=$row2[D_ID] . " - " . $row2[domainn];
	}

$sql4="SELECT idcustomers,CONCAT(fname, ' ', lname) AS name FROM customers ORDER BY name";
$result4=gosql($sql4,0);
$lookup4[0]="none (not linked to any current customer)";
while ($row4=mysql_fetch_assoc($result4))
	{
	$lookup4[$row4[idcustomers]]=$row4[name];
	}
	
$sql3 = "SELECT customers_idcustomers FROM custdom WHERE domains_D_ID='$domainid'";
$results3 = mysql_query($sql3);
$row3 = mysql_fetch_assoc($results3);
	
$row[customerid]=$row3[customers_idcustomers];
$desc=array("","","","","Description","Password 1","Password 2","","Primary Domain","Deactivated","","","DNS","Create Configs","WWW Config","FTP Config","Mailing List Config","SQL Config","Comments","Number of SQL Users","Number of SQL Databases","Customer");
$function=array(array(1),array(1),array(1),array(1),array(0),array(0),array(0),array(1),array(2,$primarydomainlookup),array(2,$lookup),array(1),array(1),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(4,40,10),array(0),array(0),array(2,$lookup4));
edittable("add",0,"usual",$row,$desc,$function);
print "</form>";
print "<div class=\"description\"><a href=\"domaindel.php\" title=\"Are you sure you want to do this?  You will NOT be asked to confirm!\">Deactivate Domain</a></div><br>";

$keys=array("Config to Rebuild","Rebuild");
unset($datadisp);
$datadisp[]=array("All","<a href=\"domainrebuilde.php?rebuild=0\">Go</a>");
$datadisp[]=array("WWW","<a href=\"domainrebuilde.php?rebuild=1\">Go</a>");
$datadisp[]=array("FTP","<a href=\"domainrebuilde.php?rebuild=2\">Go</a>");
$datadisp[]=array("DNS","<a href=\"domainrebuilde.php?rebuild=3\">Go</a>");
$datadisp[]=array("Mailing List","<a href=\"domainrebuilde.php?rebuild=4\">Go</a>");
$datadisp[]=array("SQL","<a href=\"domainrebuilde.php?rebuild=5\">Go</a>");

$function=array(array("",""));
$uniq=array("1");
$rt1=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $rt1;

print "<br><br><br><br></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domain.php\">Back</a></td></tr></table></div>"; 
bottom();

?>
