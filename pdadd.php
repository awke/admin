<?php

include('functions.inc');
sqlconn();

$customerid=$_SESSION[secdata][customers][pindex.php][pid][$_GET[id]];
if($customerid=="")
{
$customerid=$_SESSION[secdata][customers][edit.php][pdid];
}//print $customerid;


//disparray($_SESSION,session);
//disparray($_POST,POST);




print <<<END

<div class="infolabel">Adding Package</div>
<div class="subtabinfo">
<form method="post" action="pdupdatea.php">

END;


//$sql="SELECT * from packagedata WHERE idpackagedata='$customerid'";


//$result=gosql($sql,0);
//$row=mysql_fetch_assoc($result);
$row=array("","","cost"=>"","costdescription"=>"","sdate"=>"2004-01-01","edate"=>"2030-12-31");
$desc=array("","","Cost","Description","Start Date","End Date");
$function=array(array(1),array(1),array(0),array(0),array(0),array(0));
edittable("packdata",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][pdid]=$customerid;
unset($_SESSION[secdata][customers][pindex.php][cid]);


//phpinfo();

print <<<END
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="pindex.php">Back</a></td></tr></table></div>
END;


bottom();

//disparray($_SESSION);


?>

