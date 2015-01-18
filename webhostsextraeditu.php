<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$array=retedittable("add",0);
//print"<PRE>";
//print_r($array);
//print_r($_SESSION);
if($array[0]==1)
{
unset($file);
$file[]=$array[1][0];

//disparray($file);
$b=$_SESSION[basenamesaved];
$m=$array[1][0];
Array2File($file,"/Supported/temp/apache/extraconf/$b"); //$_SESSION[filenamesaved]);
Array2File($file,"/tmp/$b");
$b=$_SESSION[basenamesaved];
$m=$array[1][0];
$to="admin@awke.co.uk";
$from="admin@awke.co.uk";
$head="From: ADMIN WEBSITE SCRIPT <admin@awke.co.uk>\r\nX-AWKE-Admin-Site: TRUE\r\n";
$subject="[WEBHOSTS APACHE EXTRA CONF] for $b";
$mid="Below is the modified Apache Extra Conf - $_SESSION[filenamesave]\n$m"; 
$return=sock_mail($auth,$to, $subject, $mid, $head, $from);

$error=0;


if($error==0)
{

// if ok


$sql="INSERT INTO superusercommands SET command='6', parameters='$_SESSION[filenamesaved]'";
gosql($sql,0);



//$sql="INSERT INTO superusercommands SET command='1', parameters=''";
//gosql($sql,0);


clredittable("add",0);
header("Location: domdisp.php?D_ID=$_INFO[D_ID]");
//header("Location: webhostsextraedit.php");

}
else
{
//error=1 therefor error

header("Location: webhostsextraedit.php");
exit();
}

}

?>
