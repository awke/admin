<?php

$HEADER=1;
include('functions.inc');
sqlconn();

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$sql="SELECT * from domains ORDER BY domainn";
$result=gosql($sql,0);

print "<div class=\"infolabel\">Domains</div>";
print "<div class=\"subtabinfo\">";

$keys=array("Domain ID","Domain Name","User ID","Username","Description","Password 1","Password 2","Directory","Primary Domain","Deactivated","Last Updated","Type","DNS","Create Configs","WWW Config","FTP Config","Maillist Config","SQL Config","Number of SQL Users","Number of SQL Databases","Customer","Comments");
$functions=array(array("Edit","<a href=\"domainedit.php?id=%UNIQ%\">Edit</a>"));


$functions=array(array("","To Edit use phpmyadmin"));




$location=0;
$lookup=array("0"=>"No","1"=>"<b>YES</b>");
while ($row = mysql_fetch_assoc($result)) 
	{
	$statedeactivated=$lookup[$row[deactivated]];
	$statenodns=$lookup[$row[dns]];
	$statenocreateconfigs=$lookup[$row[createconfigs]];
	$statenowwwconfig=$lookup[$row[wwwconfig]];
	$statenoftpconfig=$lookup[$row[ftpconfig]];
	$statenomaillistconfig=$lookup[$row[maillistconfig]];
	$statenosqlconfig=$lookup[$row[sqlconfig]];
	
	$sql2 = "SELECT domainn FROM domains WHERE D_ID='$row[pridomain]'";
	$results2 = mysql_query($sql2);
	$row2 = mysql_fetch_assoc($results2);
	if ($row[pridomain] == "0")
		{
		$primarydomain = "";
		}
	else
		{
		$primarydomain=$row[pridomain] . " - " . $row2[domainn];
		}
	
	$sql3 = "SELECT CONCAT(fname,' ',lname) AS name FROM customers, custdom WHERE custdom.domains_D_ID='$row[D_ID]' AND custdom.customers_idcustomers=customers.idcustomers";
	$results3 = mysql_query($sql3);
	$row3 = mysql_fetch_assoc($results3);
	
	$data[$location]=array($row[D_ID],$row[domainn],$row[userid],$row[username],$row[description],$row[password1],$row[password2],$row[directory],$primarydomain,$statedeactivated,$row[lastupdated],$row[type],$statenodns,$statenocreateconfigs,$statenowwwconfig,$statenoftpconfig,$statenomaillistconfig,$statenosqlconfig,$row[numbsqlusers],$row[numbsqldatab],$row3[name],$row[comments]);
	$uniq[$location]=$location;
	$_SESSION[secstore][domains][$location]=$row[D_ID];
	$location++;
	}
$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;
print "<br><br><br><br>";

$keys=array("Config to Rebuild","Rebuild");
unset($datadisp);
$datadisp[]=array("All","<a href=\"domainrebuild.php?rebuild=0\">Go</a>");
$datadisp[]=array("WWW","<a href=\"domainrebuild.php?rebuild=1\">Go</a>");
$datadisp[]=array("FTP","<a href=\"domainrebuild.php?rebuild=2\">Go</a>");
$datadisp[]=array("DNS","<a href=\"domainrebuild.php?rebuild=3\">Go</a>");
$datadisp[]=array("Mailing List","<a href=\"domainrebuild.php?rebuild=4\">Go</a>");
$datadisp[]=array("SQL","<a href=\"domainrebuild.php?rebuild=5\">Go</a>");

$function=array(array("",""));
$uniq=array("1");
$rt1=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $rt1;


print"<br><br><br><br></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domainadd.php\">Add Domain</a></td></tr></table></div></table></div>"; 
bottom();


?>