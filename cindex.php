<?php

include('functions.inc');
sqlconn();

//disparray($_SESSION);


clredittable("usual",0);
clredittable("email",0);
clredittable("tel",0);
clredittable("cust",0);

if($_SESSION[secarea][authuserdata][SUPERUSER]!=1)
{
die("NOT AUTHORISED");
}

$sql="SELECT * from adminInfo.customers ORDER BY lname";

$result=gosql($sql);

while($row=mysql_fetch_assoc($result))
{
$customers[][data]=$row;
}
$loc=0;
$tloc=0;
$eloc=0;
$dloc=0;
foreach($customers as $id=>$data)
{
$customerid=$data[data][idcustomers];
//disparray($data,DATA);
$sql="SELECT * from custtel WHERE idcustomers='$customerid'";
$result=gosql($sql,0);

//$teldata="<table class=\"%TABCLASS%-tel\"><tr><th>Description</th><th>Number</th></tr>";

if(mysql_num_rows($result)>0)
{
$tloc1=0;
while($row=mysql_fetch_assoc($result))
{
$customers[$id][tel][]=$row;
if($row[pri]==1)
{
$tdatadisp[]=array("<b>$row[description]</b>","<b>$row[tel]</b>");
}
else
{
$tdatadisp[]=array("$row[description]","$row[tel]");

}
$tuniq[$tloc1]=$tloc;
$_SESSION[secdata][customers][index.php][tid][$tloc]=$row[idcusttel];
$tloc++;
$tloc1++;
}

$tkeys=array("Description","Number");
$tfunction=array(array("Edit","<a href=\"tedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"tdel.php?id=%UNIQ%\">Delete</a>"));

$teldata=disptable($tkeys,$tdatadisp,$tuniq,$tfunction,"usual-tel",1);
unset($tdatadisp);
$teldata=$teldata."<br><a href=\"tadd.php?id=%UNIQ%\">Add Telephone Entry</a>";
}
else
{
$teldata="<br><a href=\"tadd.php?id=%UNIQ%\">Add Telephone Entry</a>";
}

$sql="SELECT * from custemail WHERE customers_idcustomers=$customerid";
$result=gosql($sql);

//$emaildata="<table class=\"%TABCLASS%-email\"><tr><th>Description</th><th>Email</th></tr>";
if(mysql_num_rows($result)>0)
{
$eloc1=0;
while($row=mysql_fetch_assoc($result))
{
$customers[$id][tel][]=$row;
if($row[pri]==1)
{
$edatadisp[]=array("<b>$row[description]</b>","<b>$row[email]</b>");
}
else
{
$edatadisp[]=array("$row[description]","$row[email]");

}
$euniq[$eloc1]=$eloc;
$_SESSION[secdata][customers][index.php][eid][$eloc]=$row[idcustemail];
$eloc++;
$eloc1++;
}
//disparray($euniq);

$ekeys=array("Description","Email");
$efunction=array(array("Edit","<a href=\"eedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"edel.php?id=%UNIQ%\">Delete</a>"));

$emaildata=disptable($ekeys,$edatadisp,$euniq,$efunction,"usual-email",1);
unset($edatadisp);
$emaildata=$emaildata."<br><a href=\"eadd.php?id=%UNIQ%\">Add Email Entry</a>";
}
else
{
$emaildata="<br><a href=\"eadd.php?id=%UNIQ%\">Add Email Entry</a>";
}







$sql="SELECT idcustdom,domains_D_ID,customers_idcustomers,billed,domainn from custdom,domains WHERE D_ID=domains_D_ID AND customers_idcustomers=$customerid and deactivated=0 ORDER BY domainn";
$result=gosql($sql);

//$emaildata="<table class=\"%TABCLASS%-email\"><tr><th>Description</th><th>Email</th></tr>";
if(mysql_num_rows($result)>0)
{
$dloc1=0;
while($row=mysql_fetch_assoc($result))
{
//$customers[$id][tel][]=$row;
if($row[billed]==1)
{
$ddatadisp[]=array("<b>$row[domainn]</b>");
}
else
{
$ddatadisp[]=array("$row[domainn]");
}

$duniq[$dloc1]=$dloc;
$_SESSION[secdata][customers][index.php][did][$dloc]=$row[idcustdom];
$dloc++;
$dloc1++;
}
//disparray($duniq);

$dkeys=array("Domain");
$dfunction=array(array("Edit","<a href=\"dcedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"dcdel.php?id=%UNIQ%\">Delete</a>"));

//disparray($ddatadisp);

$domdata=disptable($dkeys,$ddatadisp,$duniq,$dfunction,"usual",1);
unset($ddatadisp);
$domdata=$domdata."<br><a href=\"dcadd.php?id=%UNIQ%\">Add Domain Link Entry</a>";

}
else
{
$domdata="<br><a href=\"dcadd.php?id=%UNIQ%\">Add Domain Link Entry</a>";
}











$datadisp[]=array($data[data][fname],$data[data][lname],
$data[data][addrheader]."<br>".$data[data][housenumb] ." ". $data[data][street]."<br>".$data[data][town]."<br>".$data[data][county]."<br>".$data[data][postcode],
$teldata,$emaildata,$domdata);

$uniq[$loc]=$loc;
$_SESSION[secdata][customers][index.php][cid][$loc]=$customerid;
$loc++;
}



//disparray($customers);

$function=array(array("Edit","<a href=\"cedit.php?id=%UNIQ%\">Edit</a>"),array("Delete","<a href=\"cdel.php?id=%UNIQ%\">Delete</a>"));

$keys=array("First Name","Last Name","Address","Telephone","Email","Domains");



print <<<END
<div class="infolabel">Customers</div>
<div class="custlisttab">

END;
$rt=disptable($keys,$datadisp,$uniq,$function,"usual",1);
print $rt;
//disparray($uniq,"uniq");
//disparray($function,"function");
//disparray($_SESSION,session);

print <<<END
<br><br><br><br>
</div>

<div class="bbuttontab"><table class="backbutton" width=111><tr><td>
<a href="cadd.php">Add Customer</a></td></tr></table></div>

END;
bottom();
?>
