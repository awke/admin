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

<div class="infolabel">Adding Customer Telephone</div>
<div class="subtabinfo">
<form method="post" action="tupdatea.php">

END;


//$sql="SELECT * from customers WHERE idcustomers='$customerid'";


//$result=gosql($sql,0);
//$row=mysql_fetch_assoc($result);
$row=array("","","tel"=>"","pri"=>"0","description"=>"");
$desc=array("","","Number","Primary","Description");
$function=array(array(1),array(1),array(0),array(2,array("0" => "No","1" => "Yes")),array(0));
//disparray($function);
edittable("tel",0,"usual",$row,$desc,$function);



$_SESSION[secdata][customers][edit.php][custid]=$customerid;
unset($_SESSION[secdata][customers][index.php][cid]);


//phpinfo();

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"cindex.php\">Back</a></td></tr></table></div>";

bottom();

//disparray($_SESSION);
?>



