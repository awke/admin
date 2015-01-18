<?php
include('functions.inc');
$HEADER=1;
sqlconn();

print "<div class=\"session\">$_SESSION[idauthusers] -- $_SESSION[authuserdescription]</div>";

$passedid=$_GET[id];  

if (isset($_SESSION[secstore][editftp][ftperrid])) {
$ftpid=$_SESSION[secstore][editftp][ftperrid];
unset($_SESSION[secstore][editftp]);
$_SESSION[secstore][editftp][$id]=$ftpid;
} 
else {
$ftpid=$_SESSION[secstore][editftp][$passedid];
unset($_SESSION[secstore][editftp]);          
$_SESSION[secstore][editftp][$id]=$ftpid;
}

$sql="SELECT * FROM ftpusers WHERE FTPU_ID='$ftpid'";
$result=gosql($sql,0);

$row = mysql_fetch_assoc($result);

print <<<END
<div class="infolabel">Editing FTP details for user '$row[username]'</div>
<div class="subtabinfo2">
<form method="post" action="ftpupdate.php">
END;
errordisp();
$lookup=array("0"=>"No","1"=>"Yes");

$desc=array("","","Password","","","Homedir","","","","Deactivated","");
$function=array(array(1),array(1),array(0),array(1),array(1),array(0),array(1),array(1),array(1),array(2,$lookup),array(1));

edittable("add",0,"usual",$row,$desc,$function);


print "</form></div><div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"ftpusers.php\">Back</a></td></tr></table></div>"; 
bottom();

?>
