<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][index.php][tid][$_GET[id]];


$sql="DELETE FROM custtel WHERE idcusttel='$customerid'";
gosql($sql);
header("Location: cindex.php");





