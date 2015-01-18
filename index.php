<?php

include('functions.inc');

sqlconn();

print "<div class=\"infolabel\"><b>$_SESSION[idauthusers]</b> -- $_SESSION[authuserdescription]</div>";


//disparray($_SESSION,_SESSION);

unset($_SESSION[webhostname]);



$sql="SELECT userpriv.D_ID as domainid,iduserpriv,domains.description as domsdesc, domainn,deactivated FROM userpriv LEFT JOIN domains ON domains.D_ID = userpriv.D_ID WHERE  idauthusers='$_SESSION[idauthusers]' GROUP BY domainn ORDER BY domainn";





//$sql="SELECT userpriv.D_ID as domainid,privdev_idprivdev,iduserpriv,privdev.description as privdevdesc,type_2,webpage,webpageop,domains.description as domsdesc, domainn,deactivated from userpriv,domains,privdev WHERE domains.D_ID=userpriv.D_ID AND privdev.idprivdev=privdev_idprivdev AND idauthusers='$_SESSION[idauthusers]' ORDER BY domainn,privdevdesc";

$results=gosql($sql,0);

$num_rows = mysql_num_rows($results);

if($num_rows==0)
{
print "NO PRIVALAGES!";
exit();
}
else

{
//print <<<END

//<table border=1>

//END;/

$loc=0;
$row=mysql_fetch_assoc($results);
if($row[domainid]==0)
{
$sql="SELECT D_ID as domainid,domainn,deactivated,description as domaindesc FROM domains WHERE D_ID!=-20 ORDER BY deactivated,domainn";

$_SESSION[secarea][authuserdata][SUPERUSER]=1;
$results=gosql($sql,0);
}
else
{
if($row[deactivated]==1)
{
$datadisp[]=array("<B>DEACTIVATED</b>$row[domainn]",$row[domaindesc]);
}
else
{
$datadisp[]=array($row[domainn],$row[domaindesc]);
}
$uniq[$loc]=$row[domainid];
$loc++;
}

while ( $row=mysql_fetch_assoc($results) )
{
if(strlen($row[domaindesc])>60)
{
$DD=substr($row[domaindesc],0,60)."<BR>".substr($row[domaindesc],60,120);
}
else
{
$DD=$row[domaindesc];
}
if($row[deactivated]==1)
{
$datadisp[]=array("<B>DEACTIVATED</b><br>$row[domainn]",$DD);
}
else
{
$datadisp[]=array($row[domainn],$DD);
}
$uniq[$loc]=$row[domainid];
$loc++;
} // end while mysqlfetch_results

print <<<END

<div class="maindomlist">
END;

} // end else of if num_rows==0
$function=array(array("Edit","<a href=\"domdisp.php?D_ID=%UNIQ%\">Edit</a>"));

$keys=array("domain","Description");
$rt=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $rt;

if($_SESSION[secarea][authuserdata][SUPERUSER]==1)
{
$keys=array("Superuser Functions","Edit");
unset($datadisp);
$datadisp[]=array("Customers","<a href=\"cindex.php\">Edit</a>");
$datadisp[]=array("Packages","<a href=\"pindex.php\">Edit</a>");
$datadisp[]=array("Domain-Package","<a href=\"dindex.php\">Edit</a>");
$datadisp[]=array("Invoices","<a href=\"iindex.php\">Edit</a>");
$datadisp[]=array("Accounts","<a href=\"aindex.php\">Edit</a>");
$datadisp[]=array("Domains","<a href=\"domain.php\">Edit</a>");
$datadisp[]=array("Domain Renewal","<a href=\"domainrenewal.php\">Edit</a>");
$datadisp[]=array("Domain Renewal Single","<a href=\"domainrenewal-single.php\">Edit</a>");
$datadisp[]=array("Traffic Stats","<a href=\"traffic.php\">Edit</a>");
$datadisp[]=array("User Privileges  ","<a href=\"userprivileges.php\">Edit</a>");



$function=array(array("",""));
$uniq=array("1");
$rt1=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print "<br><br><br>". $rt1;
print "<br><br><br><br><h1>DONT FORGET THE <a href=\"/admin/wiki/\">WIKI</a></h1><br><br></div>";
$divdone=1;
}



if ($divdone != 1)
{
print "</div>";
}
bottom();
//print "<a href=\"logout.php\">Logout</a>";
?>
