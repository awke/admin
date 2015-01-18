<?php
$t=0;
if(!isset($_SERVER['PATH_INFO']))
{
$t=1;
}
if(strcmp($_SERVER['PATH_INFO'],"/")==0)
{
$t=1;
}
if($t==0)
{
$HEADER=0;
}
if($t==1)
{
$HEADER=1;
}
include('functions.inc');

sqlconn();

//disparray($_SESSION,_SESSION);
//disparray($_POST,_GET);
//disparray($_INFO,_INFO);
$D_ID=$_SESSION[INFO][D_ID];

$sql="SELECT domainn FROM domains WHERE domains.D_ID='$D_ID'";
$results=gosql($sql,0);
$row=mysql_fetch_assoc($results);

$website="/Supported/webalizer/http/$_SESSION[webhostname].$row[domainn]";

$t=0;
if(!isset($_SERVER['PATH_INFO']))
{
$t=1;
}
if(strcmp($_SERVER['PATH_INFO'],"/")==0)
{
$t=1;
}

unset($filearr);


if($t==1)
{
foreach(glob("$website/usage_??????.html") as $filename)
{
$filename=basename($filename);
//print "$filename<br>\n";
$filearr[]=array(substr($filename,6,6),$filename,substr($filename,6,4),substr($filename,10,2));
}

array_multisort($filearr);
//disparray($filearr,filearr);


$keys=array("Year","Month","Go");
unset($datadisp);

foreach($filearr as $k => $v)
{
$datadisp[]=array("$v[2]","$v[3]","<a href=\"$v[1]\">Go</a>");
}
//$datadisp[]=array("Any month","<a href=\"anweblogany.php/\">Go</a>");

$function=array(array("",""));
$uniq=array("1");
$rt1=disptable($keys,$datadisp,$uniq,$function,"usual",1);

print "<link href=\"/admin/admin.css\" rel=\"STYLESHEET\">";
print "<div class=\"infolabel\">Display Analysed web log from</div><br><br><br>";

print "<div class=\"maindomlist\">";

print  $rt1;

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"/admin/anweblogs.php\">Back</a></td></tr></table></div>"; 

bottom();

exit();


}




$base=$website; #'/websites/intranet-base';
$path_info=$_SERVER['PATH_INFO'];
$file=$base . $path_info;

$temp=pathinfo($_SERVER['PATH_INFO']);
if(strcmp($temp['extension'],"jpg")==0)
{
header("Content-Type: image/jpeg");
}
elseif(strcmp($temp['extension'],"gif")==0)
{
header("Content-Type: image/gif");
}
elseif(strcmp($temp['extension'],"png")==0)
{
header("Content-Type: image/jpeg");
}
elseif(strcmp($temp['extension'],"html")==0)
{
header("Content-Type: text/html");
}
elseif(strcmp($temp['extension'],"htm")==0)
{
header("Content-Type: text/html");
}
else
{
header("Content-Type: application/octet-stream");
}

//disparray($temp,pathinfo);

//disparray($file,file);
//disparray($_SERVER,server);

readfile($file);

//phpinfo();
?>
