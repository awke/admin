<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$emailid=$_SESSION[secdata][customers][index.php][eid][$_GET[id]];
if($emailid=="")
{
$emailid=$_SESSION[secdata][customers][edit.php][telid];
}//print $customerid;






print <<<END

<div class="infolabel">Editing Customer Email</div>
<div class="subtabinfo">
<form method="post" action="eupdate.php">

END;


$sql="SELECT * from custemail WHERE idcustemail='$emailid'";


$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);

$desc=array("","","Email","Primary","Description");
$function=array(array(1),array(1),array(0),array(2,array("0" => "No","1" => "Yes")),array(0));
//disparray($function);
edittable("email",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][emailid]=$emailid;
unset($_SESSION[secdata][customers][index.php][eid]);


//phpinfo();

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"cindex.php\">Back</a></td></tr></table></div>";


bottom();

//disparray($_SESSION);
?>



