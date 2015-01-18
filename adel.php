<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][idaccounts][$_GET[id]];


$sql="DELETE FROM accounts WHERE idaccounts='$customerid'";
gosql($sql);
header("Location: aindex.php");





