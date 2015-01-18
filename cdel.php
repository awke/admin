<?php
$HEADER=0;
include('functions.inc');
sqlconn();

//disparray($_SESSION,session);
//disparray($_POST,POST);
$customerid=$_SESSION[secdata][customers][index.php][cid][$_GET[id]];


$sql="DELETE FROM customers WHERE idcustomers='$customerid'";
gosql($sql,00);
header("Location: cindex.php");





