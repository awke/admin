<?php
$HEADER=0;
include('functions.inc');
sqlconn();
$ftpid=$_SESSION[secstore][editdns][$id];
unset($_SESSION[secstore][editdns]); 
$array=retedittable("add",0);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

if($error==0)
{
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins." domain_id='$_INFO[D_ID]', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
if( (strcmp($key,"prio")==0) && (strlen($value)==0) )
{
        $sqlins=$sqlins . "prio = NULL ";
	}
	elseif( (strcmp($key,"ttl")==0) && (strlen($value)==0)  )
	{
	        $sqlins=$sqlins . "ttl = NULL ";
		
		}
		else
		{
		        $sqlins=$sqlins . "$key ='$value' ";
			}
			
			}
$sql="UPDATE dnsrecords SET $sqlins WHERE id='$ftpid'";
gosql($sql,0);
clredittable("add",0);
//header("Location: domdisp.php?D_ID=$_INFO[D_ID]");
header("Location: dns.php");
}
else
{//error=1 therefor error
$_SESSION[secstore][editdns][ftperrid]=$ftpid;
header("Location: dnsedit.php");
exit();
}

}
?>
