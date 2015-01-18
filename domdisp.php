<?php



include('functions.inc');

sqlconn();



print "<div class=\"session\"><b>$_SESSION[idauthusers]</b> -- $_SESSION[authuserdescription]</div>";


//disparray($_SESSION,_SESSION);
//disparray($_POST,_GET);

clredittable("add",0);


$sql="SELECT userpriv.D_ID as domainid,iduserpriv,domains.description as domsdesc, domainn,deactivated FROM userpriv LEFT JOIN domains ON domains.D_ID = userpriv.D_ID WHERE  idauthusers='$_SESSION[idauthusers]' GROUP BY domainn ORDER BY domainn";





//$sql="SELECT userpriv.D_ID as domainid,privdev_idprivdev,iduserpriv,privdev.description as privdevdesc,type_2,webpage,webpageop,domains.description as domsdesc, domainn,deactivated from userpriv,domains,privdev WHERE domains.D_ID=userpriv.D_ID AND privdev.idprivdev=privdev_idprivdev AND idauthusers='$_SESSION[idauthusers]' ORDER BY domainn,privdevdesc";

$results=gosql($sql,0);

$num_rows = mysql_num_rows($results);

if($num_rows==0)
{
print "NO PRIVALAGES!";
}
else

{

$row=mysql_fetch_assoc($results);
if($row[domainid]==0)
{
$ALL=1;
}
unset($row);
//mysql_free_result($result);

//disparray($ALL,ALL);

$sql="SELECT userpriv.D_ID as domainid,iduserpriv,privdev.description as privdevdesc, webpage,webpageop,idprivdev FROM userpriv,privdev WHERE userpriv.privdev_idprivdev=idprivdev AND idauthusers='$_SESSION[idauthusers]' AND D_ID='$_GET[D_ID]' ORDER BY description";

$results=gosql($sql,0);

$num_rows = mysql_num_rows($results);

if($num_rows==0 && $ALL==1)
{

}



$domain=$_GET[D_ID];
$_SESSION[secstore][domain]=$domain;
$sql="SELECT * from domains WHERE D_ID='$domain'";
$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
$domainn=$row[domainn];
print <<<END

<div class="infolabel">DOMAIN $domainn</div>

<div class="domlisttab">
END;
if (isset($_SESSION[privsyspass]))
	{
	print "Your password was set to " . $_SESSION[privsyspass] . "<br><br>";
	unset($_SESSION[privsyspass]);
	}
/*
$sql="SELECT userpriv.D_ID as domainid,iduserpriv,privdev.description as privdevdesc, webpage,webpageop,idprivdev FROM userpriv,privdev WHERE userpriv.privdev_idprivdev=idprivdev AND idauthusers='$_SESSION[idauthusers]' AND D_ID='$_GET[D_ID]' ORDER BY idprivdev";

$results=gosql($sql,0);


$row=mysql_fetch_assoc($results);
if($row[idprivdev]==0 || $ALL==1)
{
$sql="SELECT privdev.description as privdevdesc, webpage,webpageop,idprivdev FROM privdev WHERE idprivdev!='0' ";

$results=gosql($sql,0);
$row=mysql_fetch_assoc($results);
}
$a=1;
$_SESSION[SELECT1][$a]=Array($domain,$row[idprivdev]);
print "<tr><td>$row[privdevdesc]</td><td><a href=\"go.php?go=$a\">Edit</a></td></tr>";
$a++;

while ($row=mysql_fetch_assoc($results))
{
$_SESSION[SELECT1][$a]=Array($domain,$row[idprivdev]);
print "<tr><td>$row[privdevdesc]</td><td><a href=\"go.php?go=$a\">Edit</a></td></tr>";
$a++;

}

*/

$keys=array("Section");
$functions=array(array("Edit","<a href=\"go.php?go=%UNIQ%\">Edit</a>"));

$location=0;


//$sql="SELECT userpriv.D_ID as domainid,iduserpriv,privdev.description as privdevdesc, webpage,webpageop,idprivdev FROM userpriv,privdev WHERE userpriv.privdev_idprivdev=idprivdev AND idauthusers='$_SESSION[idauthusers]' AND D_ID='$_GET[D_ID]'";
$sql="SELECT userpriv.D_ID as domainid,iduserpriv,privdev.description as privdevdesc, webpage,webpageop,idprivdev FROM userpriv,privdev WHERE userpriv.privdev_idprivdev=idprivdev AND idauthusers='$_SESSION[idauthusers]' AND D_ID='$_GET[D_ID]' ORDER BY description";

$result=gosql($sql,0);


$row=mysql_fetch_assoc($result);
if($row[idprivdev]==0 || $ALL==1)
{
$sql="SELECT privdev.description as privdevdesc, webpage,webpageop,idprivdev FROM privdev WHERE idprivdev!='0' ORDER BY description";

$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
}
//$numb=mysql_num_rows($result);
//disparray($row,"$numb: - $sql");
$a=1;
$_SESSION[SELECT1][$location]=Array($domain,$row[idprivdev]);
//print "<tr><td>$row[privdevdesc]</td><td><a href=\"go.php?go=$a\">Edit</a></td></tr>";
$data[$location]=array($row[privdevdesc]);
$uniq[$location]=$location;
$location++;

while ($row = mysql_fetch_assoc($result)) {




//$data[$location]=array($row[alias],$row[aliased],$state);
//$uniq[$location]=$location;
//$_SESSION[secstore][editaliasid][$location]=$row[A_ID];


$_SESSION[SELECT1][$location]=Array($domain,$row[idprivdev]);
//print "<tr><td>$row[privdevdesc]</td><td><a href=\"go.php?go=$a\">Edit</a></td></tr>";
$data[$location]=array($row[privdevdesc]);
$uniq[$location]=$location;
//$location++;

// you will need to use the following to get the A_ID back

// $passedid=$_GET[id];
// $A_ID=$_SESSION[secstore][editaliasid][$passedid];
// unset($_SESSION[secstore][editaliasid]);

//disparray($location,loc);

$location++;
}

//disparray($keys,keys);
//disparray($data,data);
//disparray($uniq,uniq);
//disparray($functions,functions);
//disparray($_SESSION,session);
errordisp();
print "<br>";
$tempstr=disptable($keys,$data,$uniq,$functions,"usual");
print $tempstr;


print <<<END

</div>

END;




// domains.D_ID = userpriv.D_ID 
} // end if no priv
bottom();


//print "<a href=\"main.php\">Main</a>";

//print "<a href=\"logout.php\">Logout</a>";

?>

