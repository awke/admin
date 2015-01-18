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

<p>PLEASE NOTE the contents of this file are emailed to the administrators, if they disapprove of it they will remove lines/contents as they see fit for the security of the system etc</p>
<br>

<p> Currently set to $_SESSION[webhostname]</p>


END;

while($rows=mysql_fetch_assoc($res))
{
$base="$_SESSION[webhostname].$rows[domainn]";
//print "DEBUG:$base";
$datadisp[]=array("<a href=\"webhostsextraedit.php?webhostname=$rows[name]\">$rows[name]</a>");
}
$keys=array("Set Host");

//$datadisp[]=array("Last 12 Months Full","<a href=\"anweblogfull.php/\">Go</a>");
//$datadisp[]=array("Any month","<a href=\"anweblogany.php/\">Go</a>");

$_SESSION[basenamesaved]=$base;

$function=array(array("",""));
$uniq=array("1");
$rt1=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print "<br>" . $rt1 . "<br><br><br>";
unset($datadisp);
$orig="/Supported/apache/extraconf/$base";
$saved="/Supported/temp/apache/extraconf/$base";
$_SESSION[filenamesaved]=$saved;


if(is_file($saved))
{
$file=file_get_contents($saved);
print"<p>Loading the previously edited file that hasnt been activated yet</p>";
}
else
{
$file=file_get_contents($orig);
}

$row=array("","username"=>"","password"=>"","","","homedir"=>"","","","","deactivated"=>"","");

$desc=array("","Username","Password","","","Homedir","","","","Deactivated","");
$function=array(array(1),array(0),array(0),array(1),array(1),array(0),array(1),array(1),array(1),array(2,$lookup),array(1));

$row=array($file);
$desc=array("Webhosts Apache<br>Extra Config Contents");
$function=array(array(4,60,20));

print <<<END
<form method="post" action="webhostsextraeditu.php">
END;
edittable("add",0,"usual",$row,$desc,$function);
print <<<END
<p>Please note it can take as much as 10 minutes for your changes to activate</p>
END;

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"domdisp.php?D_ID=$_INFO[D_ID]\">Back</a></td></tr></table></div>"; 


bottom();

//print "<a href=\"main.php\">Main</a><br>"; 
//print "<a href=\"logout.php\">Logout</a>"; 
?>
