<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][pindex.php][pdid][$_GET[id]];


$sql="DELETE FROM packagedata WHERE idpackagedata='$customerid'";
gosql($sql,0);
header("Location: pindex.php");





