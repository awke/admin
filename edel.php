<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][index.php][eid][$_GET[id]];


$sql="DELETE FROM custemail WHERE idcustemail='$customerid'";
gosql($sql);
header("Location: cindex.php");





