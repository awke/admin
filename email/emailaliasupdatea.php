<?php
$HEADER=0;
include('functions.inc');
sqlconn();

$array=retedittable("add",0);

if($array[0]==1)
{
$first=0;
$sqlins="";
$sqlins1="";
$error=0;

if (strcmp($array[1][alias],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter an alias";
}

if (ereg("[[:space:]]", $array[1][alias]))
{
$error=1;
$_SESSION[error][]="Please correct alias";
}

if (ereg("@", $array[1][alias]))
{
$error=1;
$_SESSION[error][]="Only type the first part of the email, not the full email address";
}

if (strcmp($array[1][aliased],"")==0)
{
$error=1;
$_SESSION[error][]="Please enter an aliased";
}

if (ereg("[[:space:]]", $array[1][aliased]))
{
$error=1;
$_SESSION[error][]="Please correct aliased";
}

if($error==0)
{
$email = $_SESSION[email];
$domainid = $_SESSION[domainid];
$domain = $_SESSION[domain];
$alias = $array[1][alias];
$aliased = $array[1][aliased];
foreach($array[1] as $key => $value)
{
	if($first==0)
	{
		$sqlins=$sqlins." domain='$domainid', ";
		$first=1;

	}
	else
	{
		$sqlins=$sqlins . ", ";
	}
	$sqlins=$sqlins . "$key ='$value' ";
}

$sql="INSERT INTO aliases SET $sqlins";
gosql($sql,0);
clredittable("add",0);

$to = "admin@awke.co.uk";
$subj = "New email alias added";
$body = "$email has added the following email alias:\n\n$alias@$domain is being redirected to $aliased";
$head = "from: $email";
$from = "admin@awke.co.uk";
sock_mail($to, $subj, $body, $head, $from);
header("Location: emailuser.php");

}
else
{//error=1 therefor error

header("Location: emailaliasadd.php");
exit();
}

}
?>
