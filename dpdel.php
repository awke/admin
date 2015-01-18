<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][index.php][dpid][$_GET[id]];


$sql="DELETE FROM packdom WHERE idpackdom='$customerid'";
gosql($sql);
header("Location: dindex.php");





