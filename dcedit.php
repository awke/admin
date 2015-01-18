<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$emailid=$_SESSION[secdata][customers][index.php][did][$_GET[id]];
if($emailid=="")
{
$emailid=$_SESSION[secdata][customers][edit.php][did];
}//print $customerid;






print <<<END

<div class="infolabel">Editing Domain Link</div>
<div class="subtabinfo">
<form method="post" action="dcupdate.php">

END;

$sql="SELECT D_ID,domainn from domains ORDER BY domainn";

$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result) )
{
$lookup[$row[D_ID]]=$row[domainn];
}
unset($row);
unset($result);//disparray($lookup);
$sql="SELECT * from custdom WHERE idcustdom='$emailid'";


$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);

//disparray($row,records);

$desc=array("","","Domain","Billed");
$function=array(array(1),array(1),array(2,$lookup),array(2,array("0" => "No","1" => "Yes")) );
//disparray($function);
edittable("email",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][did]=$emailid;
unset($_SESSION[secdata][customers][index.php][did]);


//phpinfo();

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"cindex.php\">Back</a></td></tr></table></div>";


bottom();

//disparray($_SESSION);




