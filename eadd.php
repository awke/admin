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

<div class="infolabel">Adding Customer Email Infomation</div>
<div class="subtabinfo">
<form method="post" action="eupdatea.php">

END;


//$sql="SELECT * from customers WHERE idcustomers='$customerid'";


//$result=gosql($sql,0);
//$row=mysql_fetch_assoc($result);
$row=array("","","email"=>"","pri"=>"","description"=>"");
$desc=array("","","Email","Primary","Description");
$function=array(array(1),array(1),array(0),array(2,array("0" => "No","1" => "Yes")),array(0));
//disparray($function);
edittable("email",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][custid]=$customerid;
unset($_SESSION[secdata][customers][index.php][cid]);


//phpinfo();

print "</div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"cindex.php\">Back</a></td></tr></table></div>";


bottom();

//disparray($_SESSION);
?>



