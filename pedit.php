<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][pindex.php][pid][$_GET[id]];
if($customerid=="")
{
$customerid=$_SESSION[secdata][customers][edit.php][pid];
}//print "cid" . $customerid;






print <<<END

<div class="infolabel">Editing Package</div>
<div class="subtabinfo">
<form method="post" action="pupdate.php">

END;


$sql="SELECT * from packages WHERE idpackages='$customerid'";


$result=gosql($sql,0);
$row=mysql_fetch_assoc($result);
//disparray($row);
$desc=array("","Description","Notes");
$function=array(array(1),array(0),array(0));
edittable("pack",0,"usual",$row,$desc,$function);


$_SESSION[secdata][customers][edit.php][pid]=$customerid;
unset($_SESSION[secdata][customers][index.php][cid]);


//phpinfo();

print <<<END
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="pindex.php">Back</a></td></tr></table></div>
END;



bottom();

//disparray($_SESSION);

?>


