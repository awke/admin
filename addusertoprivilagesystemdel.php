<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$iduserpriv = $_SESSION[userpriv][uniq][$_GET[id]];

$sql="DELETE FROM userpriv WHERE iduserpriv='$iduserpriv'";
gosql($sql,0);
if (strpos($_SERVER[HTTP_REFERER], 'userprivileges.php'))
	{
	header("Location: userprivileges.php");
	}
else
	{
	header("Location: domdisp.php?D_ID=$_INFO[D_ID]");
	}
?>