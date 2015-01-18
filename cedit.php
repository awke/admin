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

<div class="infolabel">Editing Customer Infomation</div>
<div class="subtabinfo">
<form method="post" action="cupdate.php">

END;


$sql="SELECT * from customers WHERE idcustomers='$customerid'";


$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);

$desc=array("","First Name","Last Name","Address Header","House Name/Number","Street","Town","County","Postcode");
$function=array(array(1),array(0),array(0),array(0),array(0),array(0),array(0),array(0),array(0));
edittable("cust",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][custid]=$customerid;
unset($_SESSION[secdata][customers][index.php][cid]);


//phpinfo();

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"cindex.php\">Back</a></td></tr></table></div>";


bottom();

//disparray($_SESSION);

?>


