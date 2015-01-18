<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$did=$_SESSION[secdata][customers][pindex.php][pid][$_GET[id]];

$array=retedittable("domcom",0);
clredittable("domcom",0);
if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins."domainid='$did', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="INSERT INTO domcom SET $sqlins";
gosql($sql,0);
}

if(isset($_GET[del]))
{
$iddomcom=$_SESSION[secdata][domcom][iddomcom][$_GET[del]];
$sql="DELETE from domcom WHERE iddomcom='$iddomcom'";
gosql($sql);
}



if($_GET[edit]==1)
$add="&edit=1";

print <<<END
<div class="infolabel">Domain Comments</div>
<div class="subtabinfo">
<form method="post" action="dcomm.php?id=$_GET[id]$add">

END;

$sql="SELECT *,UNIX_TIMESTAMP(lastupdated) as uts from domcom WHERE domainid='$did' ORDER BY lastupdated DESC";
$result=gosql($sql);



unset($com);
while($row=mysql_fetch_assoc($result))
{
$com[]=$row;
}

if(count($com)>0)
{
foreach($com as $v)
{
$iddomcom=$v[iddomcom];
$array=retedittable("domcom",$iddomcom);
clredittable("domcom",$iddomcom);
if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}
$sql="UPDATE domcom SET $sqlins WHERE iddomcom='$iddomcom'";
gosql($sql,0);
}

}
}


if($_GET[edit]==1)
{
if(count($com)>0)
{
$lc=0;
foreach($com as $v)
{
$time=date("Y-m-d H:i:s",$v[uts]);
$row=$v;
$desc=array("","","Comment","","");
$function=array(array(1),array(1),array(4),array(1),array(1));
print "<span class=\"description\">$time</span><br>";
edittable("domcom",$v[iddomcom],"usual",$row,$desc,$function);
$_SESSION[secdata][domcom][iddomcom][$lc]=$v[iddomcom];
print "<span class=\"description\"><a href=\"dcomm.php?edit=1&del=$lc\">Delete</a></span><hr><br>";
$lc++;

}

$row=array("","","comments" =>"","","");
$desc=array("","","Comment","","");
$function=array(array(1),array(1),array(4),array(1),array(1));
print "<span class=\"description2\">Add</span><br>";
edittable("domcom",0,"usual",$row,$desc,$function);
print "<hr><br>";
print <<<END
</div>
<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="dcomm.php?id=$_GET[id]">Normal Mode</a></td></tr></table></div>

<div class="bbuttontab2"><table class="backbutton2" width=111><tr><td>
<a href="dindex.php">Back</a></td></tr></table></div>


END;
$a=1;
}

}
else
{
if(count($com)>0)
{
foreach($com as $v)
{

$time=date("Y-m-d H:i:s",$v[uts]);

print "<div class=\"description\">$time<br>$v[comments]</div>";
print "<hr><br>";
}
}

$row=array("","","comments" =>"","","");
$desc=array("","","Comment","","");
$function=array(array(1),array(1),array(4),array(1),array(1));
print "<span class=\"description2\">Add</span><br>";
edittable("domcom",0,"usual",$row,$desc,$function);
print "<hr><br>";

print <<<END
</div>
<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="dcomm.php?id=$_GET[id]&edit=1">Edit Mode</a></td></tr></table></div>

<div class="bbuttontab2"><table class="backbutton2" width=111><tr><td>
<a href="dindex.php">Back</a></td></tr></table></div>


END;
$a=1;

}
if ($a!=1){print "</div>";}
bottom();
