<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$emailid=$_SESSION[secdata][customers][pindex.php][pdid][$_GET[id]];
if($emailid=="")
{
$emailid=$_SESSION[secdata][customers][edit.php][pdid];
}//print $customerid;






print <<<END

<div class="infolabel">Editing Package Data</div>
<div class="subtabinfo">

<form method="post" action="pdupdate.php">

END;


$sql="SELECT * from packagedata WHERE idpackagedata='$emailid'";


$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);

$desc=array("","","Cost","Description","Start Date","End Date");
$function=array(array(1),array(1),array(0),array(0),array(0),array(0));
//disparray($function);
edittable("packdata",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][pdid]=$emailid;
unset($_SESSION[secdata][customers][index.php][pdid]);


//phpinfo();

print <<<END
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="pindex.php">Back</a></td></tr></table></div>
END;


bottom();

//disparray($_SESSION);

?>


