<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][pindex.php][pid][$_GET[id]];
if($customerid=="")
{
$customerid=$_SESSION[secdata][customers][edit.php][pid];
}//print $customerid;






print <<<END

<div class="infolabel">Adding Domain Package</div>
<div class="subtabinfo">
<form method="post" action="dpupdatea.php">

END;

$sql="SELECT idpackages,description from packages ORDER BY description";

$result=gosql($sql,0);

while ($row=mysql_fetch_assoc($result) )
{
$lookup[$row[idpackages]]=$row[description];
}

//$sql="SELECT * from packdom WHERE idpackdom='$customerid'";


//$result=gosql($sql,0);
//$row=mysql_fetch_assoc($result);
$row=array("","packages_idpackages"=>"","","psdate"=>"","pedate"=>"","pbdate"=>"","ppdate"=>"");
$desc=array("","Package","","Start Date","End Date","Last Billed Date","Last Paid Date");
$function=array(array(1),array(2,$lookup),array(1),array(0),array(0),array(0),array(0));
//disparray($function);
edittable("email",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][pid]=$customerid;
unset($_SESSION[secdata][customers][index.php][dpid]);


//phpinfo();

print <<<END
<br><br><br><br>
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="dindex.php">Back</a></td></tr></table></div>


END;


bottom();

//disparray($_SESSION);

?>


