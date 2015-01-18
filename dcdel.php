<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][index.php][did][$_GET[id]];


$sql="DELETE FROM custdom WHERE idcustdom='$customerid'";
gosql($sql);
header("Location: cindex.php");





