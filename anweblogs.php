<?php
$HEADER=1;
include('functions.inc');

sqlconn();



print "<div class=\"infolabel\"><b>$_SESSION[idauthusers]</b> -- $_SESSION[authuserdescription]</h1></div>";
// print some helpful text here eg what this is for etc


print <<<END

<div class="maindomlist">
END;

//$_INFO is array that contains info
//$_INFO[D_ID] is the domain ID
//$_INFO[idprividev] should be the uniq id for this page from db table 


//disparray($_SESSION,_SESSION);
//disparray($_POST,_GET);
//disparray($_INFO,_INFO);

// assume privilage is OK to get here.


if(!isset($_SESSION[webhostname]))
{
$_SESSION[webhostname]="www";
}

if(isset($_GET[webhostname]))
{
$_SESSION[webhostname]="$_GET[webhostname]";

}
$sql="SELECT domainn,name from webhosts,domains WHERE webhosts.D_ID='$_INFO[D_ID]' AND webhosts.D_ID=domains.D_ID";

$res=gosql($sql,0);
print <<<END
<p> Currently set to $_SESSION[webhostname]</p>

END;

while($rows=mysql_fetch_assoc($res))
{
$website="/Supported/webalizer/http/$rows[name].$rows[domainn]";
/*
print "<PRE>";
print_r($rows);
print_r($website);
print "</pre>";
*/
if(is_dir($website))
{
$datadisp[]=array("<a href=\"anweblogs.php?webhostname=$rows[name]\">$rows[name]</a>");
}
}
$keys=array("Set Host");

//$datadisp[]=array("Last 12 Months Full","<a href=\"anweblogfull.php/\">Go</a>");
//$datadisp[]=array("Any month","<a href=\"anweblogany.php/\">Go</a>");

$function=array(array("",""));
$uniq=array("1");
$rt1=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print "<br>" . $rt1;
unset($datadisp);


$keys=array("","Go");
unset($datadisp);
$datadisp[]=array("Last 12 Months Full","<a href=\"anweblogfull.php/\">Go</a>");
$datadisp[]=array("Any month","<a href=\"anweblogany.php/\">Go</a>");

$function=array(array("",""));
$uniq=array("1");

if(is_dir("/Supported/webalizer/http/$_SESSION[webhostname].$rows[domainn]"));
{
$rt1=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print "<br><br><br>" . $rt1;
}

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 


bottom();

//print "<a href=\"main.php\">Main</a><br>"; 
//print "<a href=\"logout.php\">Logout</a>"; 
?>
