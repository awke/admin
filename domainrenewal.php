<?php

global $db;

function sqlconn()
{
global $db;
$dbhost = "localhost";

$dbname = "adminInfo";

$dbuser = "awkeadminsoft";

$dbpasswd = "y3ipRLp";

global $db;
         if(!$db = mysql_connect("$dbserver", "$dbuser", "$dbpasswd"))
           die("<font color=\"#FF0000\">Error, I could not connect to the database at $dbserver. Using username $dbuser and password $dbpass1.<BR>Please go back and try again.");

$db_selected = mysql_select_db("$dbname", $db);
if (!$db_selected) {
   die ("Can\'t use $dbname : " . mysql_error());
}

}

function gosql($sql,$verbose=0)
{
global $db;//,$verbose;
//$verbose=1;
	         if ($result=mysql_query($sql))
	          {
	          if($verbose) 
	          	echo("<P>Sucessful - $sql</P>");
	          }
	          else
	          {
	          if($verbose)
	          	echo("<P>Error creating item: " .             mysql_error() . " in <br><b> $sql</b></P>");
	          }
	          //$result1=mysql_fetch_assoc($result);
	   return $result;

}

sqlconn();

$sql="SELECT * from domains ORDER BY domainn";

$result=gosql($sql);


while ($row=mysql_fetch_assoc($result))
{
print "<h1>$row[domainn]</h1><br>";
print "<pre>\n";
$name=$row[domainn];
$whois=`whois $name`;
print $whois;

print "</pre><hr><br>\n";


}
