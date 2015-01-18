<?php
include('functions.inc');
$HEADER=1;
sqlconn();

print "<div class=\"session\">$_SESSION[idauthusers] -- $_SESSION[authuserdescription]</div>";
$passedid=$_GET[id];  

if (isset($_SESSION[secstore][editalias][aliaserrid])) {
$aliasid=$_SESSION[secstore][editalias][aliaserrid];
unset($_SESSION[secstore][editalias]);
$_SESSION[secstore][editalias][$id]=$aliasid;
} 
else {
$aliasid=$_SESSION[secstore][editalias][$passedid];
unset($_SESSION[secstore][editalias]);          
$_SESSION[secstore][editalias][$id]=$aliasid;
}

$sql="SELECT * FROM aliases WHERE A_ID='$aliasid'";
$result=gosql($sql,0);

$row = mysql_fetch_assoc($result);

print <<<END
<div class="infolabel">Editing Email Alias</div>
<div class="subtabinfo2">
<form method="post" action="emailaliasupdate.php">
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");

$desc=array("","alias","","Email User","disabled","");
$function=array(array(1),array(0),array(1),array(0),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);
print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"emailalias.php\">Back</a></td></tr></table></div>"; 
bottom();

?>
