<?php



include('functions.inc');

sqlconn();



print "<h1><b>$_SESSION[idauthusers]</b> -- $_SESSION[authuserdescription]</h1>";
// print some helpful text here eg what this is for etc




//$_INFO is array that contains info
//$_INFO[D_ID] is the domain ID
//$_INFO[idprividev] should be the uniq id for this page from db table 


disparray($_SESSION,_SESSION);
disparray($_POST,_GET);
disparray($_INFO,_INFO);

fixpost();
// assume privilage is OK to get here.

// change checkbox data into correct form for disabled column in table

if ($_POST[disabled]=="") { 
     $disable=0; 
} else { 
     $disable=1; 
}

$aliasid=$_SESSION[secstore][editalias.php][aliasid];
unset($_SESSION[secstore][editalias.php][aliasid]);



if ($_POST[alias] == "")
{
	$_SESSION[secstore][savealias.php][aliasid]=$aliasid;
   echo "Please enter an alias";
print("<FORM><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1);return true;\"> </FORM>");
	exit();
}
 elseif ($_POST[aliased] == "")
{
	$_SESSION[secstore][savealias.php][aliasid]=$aliasid;
   echo "Please enter aliased";
print("<FORM><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1);return true;\"> </FORM>");
	exit();
}

$pos = strrpos($_POST[alias], " ");
if ($pos >= 1) 
{ 
$_SESSION[secstore][savealias.php][aliasid]=$aliasid;
echo "Please remove spaces from alias";
print("<FORM><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1);return true;\"> </FORM>");
exit();
}

$pos = strrpos($_POST[aliased], " ");
if ($pos >= 1) 
{ 
$_SESSION[secstore][savealias.php][aliasid]=$aliasid;
echo "Please remove spaces from aliased";
print("<FORM><INPUT TYPE=\"button\" VALUE=\"Back\" onClick=\"history.go(-1);return true;\"> </FORM>");
exit();
}

//sql statements or functiosn to do function
$sql = "UPDATE testaliases SET alias='$_POST[alias]', aliased='$_POST[aliased]', disabled='$disable' WHERE A_ID='$aliasid'";

$result=gosql($sql,1);


print "<a href=\"main.php\">Main</a><br>"; 
print "<a href=\"logout.php\">Logout</a>"; 

?>
