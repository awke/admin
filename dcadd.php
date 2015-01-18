<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];
if($customerid=="")
{
$customerid=$_SESSION[secdata][customers][edit.php][custid];
}//print $customerid;






print <<<END

<div class="infolabel">Adding Customer Infomation</div>
<div class="subtabinfo">
<form method="post" action="dcupdatea.php">

END;


//$sql="SELECT * from customers WHERE idcustomers='$customerid'";


//generate lookup table
$sql="SELECT D_ID,domainn from domains ORDER BY domainn";

$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result) )
{
$lookup[$row[D_ID]]=$row[domainn];
}

$row=array("","","domains_D_ID"=>"","billed"=>"");
$desc=array("","","Domain","Billed");
$function=array(array(1),array(1),array(2,$lookup),array(0));
$function=array(array(1),array(1),array(2,$lookup),array(2,array("0" => "No","1" => "Yes")) );

//disparray($function);
edittable("domcl",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][custid]=$customerid;
unset($_SESSION[secdata][customers][index.php][cid]);


//phpinfo();

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"cindex.php\">Back</a></td></tr></table></div>";


bottom();

//disparray($_SESSION);
?>



