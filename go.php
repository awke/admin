<?php
$HEADER=0;
//$PRELOGIN=1;

include('functions.inc');

sqlconn();



//print "<h1><b>$_SESSION[idauthusers]</b> -- $_SESSION[authuserdescription]</h1>";


//disparray($_SESSION,_SESSION);
//disparray($_GET,_GET);


$ret=$_SESSION[SELECT1][$_GET[go]];

//disparray($ret,ret);


unset($_SESSION[SELECT1]);

$sql="SELECT privdev.description as privdevdesc, webpage,webpageop,idprivdev FROM privdev WHERE idprivdev='$ret[1]' ";

$result=gosql($sql,0);

$row=mysql_fetch_assoc($result);

$webpage=$row[webpage];

$_SESSION[INFO][D_ID]=$ret[0];
$_SESSION[INFO][idprivdev]=$ret[1];


header("Location: $webpage");
print "<a href=\"$webpage\">click</a>";

