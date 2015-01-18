<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$telid=$_SESSION[secdata][customers][index.php][tid][$_GET[id]];
if($telid=="")
{
$telid=$_SESSION[secdata][customers][edit.php][telid];
}//print $customerid;






print <<<END

<div class="infolabel">Editing Customer Telephone</div>
<div class="subtabinfo">
<form method="post" action="tupdate.php">

END;


$sql="SELECT * from custtel WHERE idcusttel='$telid'";


$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);

$desc=array("","","Number","Primary","Description");
$function=array(array(1),array(1),array(0),array(2,array("0" => "No","1" => "Yes")),array(0));
//disparray($function);
edittable("tel",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][telid]=$telid;
unset($_SESSION[secdata][customers][index.php][tid]);


//phpinfo();

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"cindex.php\">Back</a></td></tr></table></div>";


bottom();

//disparray($_SESSION);
?>



