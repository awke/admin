<?php
$HEADER=1;
include('functions.inc');
sqlconn();

$_SESSION[fromdomainadd] = 1;

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

print <<<END
<div class="infolabel">Add New Domain</div>
<div class="subtabinfo">
<form method="post" action="domainupdatea.php">
END;
errordisp();
$sql="SELECT DISTINCT CONCAT(userid, ' ', domainn) as useridinfo FROM domains ORDER BY userid";
$result=gosql($sql,0);

$userid[get_next_userid()]=get_next_userid() . ' (This is the next free userid)';

while ($list=mysql_fetch_assoc($result))
	{
	$temp=explode(" ", $list[useridinfo]);
	$userid[$temp[0]]=$list[useridinfo];
	}

$sql="SELECT D_ID,domainn FROM domains";
$re2=gosql($sql,0);


while ($res2=mysql_fetch_assoc($re2))
{
//print_r($res2);
$d=$res2[D_ID];
$lookup3[$d]=$res2[domainn];
}
$lookup3[0]="none (use for new subdomains being full sites)";
asort($lookup3);

$lookup=array("1"=>"Yes", "0"=>"No");

$sql4="SELECT idcustomers, CONCAT(fname, ' ', lname) AS name FROM customers ORDER BY name";
$result4=gosql($sql4,0);
$lookup4[0]="none [add a customer if they dont exist]";
while ($row4=mysql_fetch_assoc($result4))
	{
	$lookup4[$row4[idcustomers]]=$row4[name];
	}
	
	
	
$sql5="SELECT idpackages, description FROM packages ORDER BY description";
$result5=gosql($sql5,0);
$lookup5[0]="NONE [Select a package]";
while ($row5=mysql_fetch_assoc($result5))
	{
	if(substr($row5[description],0,strlen("DOMAIN REGISTRATION"))!="DOMAIN REGISTRATION" && substr($row5[description],0,2)!="xx" && $row5[description]!="Account Adjustment"&& $row5[description]!="Credit"&& $row5[description]!="email monthly credit"&& $row5[description]!="Email storage excess"&& $row5[description]!="Account Adjustment" && substr($row5[description],0,strlen("MONTHLY"))!="Monthly" && substr($row5[description],0,strlen("SETUP"))!="SETUP")
	{

	$lookup5[$row5[idpackages]]=$row5[description];
	}
	}
	
	
	
$sql6="SELECT CONCAT(username,' --- ',description) as data,idauthusers FROM authusers ORDER BY username";	
$result6=gosql($sql6,0);
$lookup6[0]="NONE [GRANT NO PRIVILAGES FOR DOMAIN]";
while ($row6=mysql_fetch_assoc($result6))
{
$lookup6[$row6[idauthusers]]=$row6[data];
}

$lookup7=array("NOTUK"=>"NOT UK DOMAIN","IND"=>"UK Individual (representing self) (IND)","STRA"=>"UK Sole Trader (STRA)","LLP"=>"UK Limited Liability Partnership (LLP)","OTHER"=>"UK Entity that does not fit into any of the above (OTHER)","LTD"=>"UK Limited Company (LTD)","PLC"=>"UK Public Limited Company (PLC)","PTNR"=>"UK Partnership (PTNR)","IP"=>"UK Industrial/Provident Registered Company (IP)","SCH"=>"UK School (SCH)","RCHAR"=>"UK Registered Charity (RCHAR)","GOV"=>"UK Government Body (GOV)","CRC"=>"UK Corporation by Royal Charter (CRC)","STAT"=>"UK Statutory Body (STAT)","FIND"=>"Non-UK Individual (representing self) (FIND)","FCORP"=>"Non-UK Corporation (FCORP)","FOTHER"=>"Non-UK Entity that does not fit into any of the above (FOTHER)");

$row=array("","domainn"=>"","userid"=>"","username"=>"","description"=>"","password1"=>"","password2"=>"","","pridomain"=>"0","deactivated"=>"0","","type"=>"master","dns"=>"1","emaildisabled"=>"0","createconfigs"=>"1","wwwconfig"=>"1","ftpconfig"=>"1","maillistconfig"=>"0","sqlconfig"=>"1","numbsqlusers"=>"1","numbsqldatab"=>"1","customerid"=>"","packageid"=>"","comments"=>"","domainregistered"=>"1","privuser"=>"0","registerdomain"=>"0","registrant"=>"","registrantcompany"=>"","registranttype"=>"");
$desc=array("","Domain Name","User ID","Username","Description","Password 1","Password 2","","Primary Domain","Deactivated","","Type","DNS","Disabled Email Handling<br /><b>THIS SHOULD BE NO<br /></b>But if someone else is using email elsewhere then set this to yes<br />and the above dns is likely to be no</b>","Create Configs","WWW Config","FTP Config","Mailing List Server Install [Seperate Mailman installation]<br /><b>THIS SHOULD BE NO</b>","SQL Config","Number of SQL Users","Number of SQL Databases","Customer","Package (ONLY VALID SHOWN)","Comments","Bill for Domain Registration","Give all privilages to user","Register domain with InternetBS (PROVISIONAL)","Domain Registration:<br>Name to be registered as (PROVISIONAL)","Domain Registration:<br>Company Name (PROVISIONAL)","Domain Registration:<br>Type of entity(PROVISIONAL)");
$function=array(array(1),array(0),array(2,$userid),array(0),array(0),array(0),array(0),array(1),array(2,$lookup3),array(2,$lookup),array(1),array(0),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(2,$lookup),array(0),array(0),array(2,$lookup4),array(2,$lookup5),array(4,40,10),array(2,$lookup),array(2,$lookup6),array(2,$lookup),array(0,40),array(0,40),array(2,$lookup7));
edittable("add",0,"usual",$row,$desc,$function);

print "<br><br><br></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domain.php\">Back</a></td></tr><tr><td><a href=\"cadd.php\">Add Customer</a></td></tr></table></div>";
bottom();
?>
