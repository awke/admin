<?php


$dbhost = "raspberrypi";
$dbname = "adminInfo";
$dbuser = "root";
$dbpasswd = "QAZqaz123";

require_once('sql.php');
//require_once('email.php');

session_name("AWKE-user-set-SESS-ID");

//session_set_cookie_params ( 1600);

session_start();
if ($PRELOGIN != 1)
	{
	if ($_SESSION[expiretime] < time())
		{
		header("Location: login.php");
		print "<a href=\"login.php\">click here to login to main.php</a>";
		exit;
		}
	}
$_SESSION[expiretime] = time()+1600;
//session_set_cookie_params ( 1600);

global $_INFO;

$_INFO[D_ID]=$_SESSION[INFO][D_ID];
$_INFO[idprivdev]=$_SESSION[INFO][idprivdev];

function disparray($arr,$exp="")
{
$t=$$arr;

//print "#$t<br>";
print "<b>$exp</b>::&nbsp;<pre>";
print_r($arr);
print "</pre>";
//foreach ($arr as $key => $value) {
  //  echo "Key: $key; Value: $value<br>\n";
//}
}


function errordisp()
{
if(isset($_SESSION[error]))
{
foreach($_SESSION[error] as $value)
{
print "<span class=\"editerror\">";
print "ERROR - ";
print $value ."\n<br>";
print "</span>";
}
unset($_SESSION[error]);
}
}


function disptable($descheaders,$data,$datauniq,$functions,$tableclass="",$uniqdata=0)
{
$ret="";
//disparray($datauniq,datauniq);

$tablestyle="border=1";
//$descheaders is an array of headers
//$functions is an array of functions, 
if(isset($tableclass))
{
$tableclassop="class=\"$tableclass\"";
}
$ret=$ret. sprintf ("<table $tablestyle $tableclassop>\n");
if($descheaders[0]!="")
{
$ret=$ret. sprintf ("<tr>\n");
foreach ($descheaders as $header)
{
$ret=$ret. sprintf ("<th><div align=\"center\">$header</div></th>");
}
foreach ($functions as $header)
{
$head=$header[0];
$ret=$ret. sprintf ("<th><div align=\"center\">$head</div></th>");
}
}
$ret=$ret. sprintf ("</tr>\n");
$rowloc=0;
foreach ($data as $row)
{
//these are the rows
if(isset($tableclass))
{
$tditem="class=\"$tableclass--item\"";
$tdfunc="class=\"$tableclass--function\"";
if($rowloc%2==0)
{
$rowclass="$tableclass---even";
}
else
{
$rowclass="$tableclass---odd";
}
$classop="class=\"$rowclass\"";
}                             
//                           MAKE THE ROWS ALTERNATE CLASSES IF the tableclass is set
$ret=$ret. sprintf ("<tr $classop>\n");
foreach ($row as $item)
{
if($uniqdata==1)
{
$item=str_replace("%UNIQ%", $datauniq[$rowloc], $item);
} 
$item=str_replace("%TABCLASS%", $tableclass, $item);
$item=str_replace("%", "%%", $item);
$ret=$ret. sprintf ("<td $tditem>$item</td>\n");   //print standard item
}
foreach ($functions as $function)
{
$op=str_replace("%UNIQ%", $datauniq[$rowloc], $function[1]);  //replace %UNIQ% with data from datauniq array
$ret=$ret. sprintf ("<td $tdfunc>$op</td>");
}
$ret=$ret. sprintf ("</tr>\n");
$rowloc++;
}
$ret=$ret. sprintf ("</tr>");
$ret=$ret. sprintf ("</table>");

return $ret;
}








global $db;
/*
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

*/


function head1()
{
$title="AWKE User control";
print <<<END
<HTML><HEAD>


<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>$title</title>
<link href="../admin.css" rel="STYLESHEET">



</HEAD>
<body class="gradient">
<div class="backdrop"></div>
<div class="adminhead">Account Administration </div>
END;
}

function top()
{
print <<<END
END;
}

function bottom($parm="")
{
print <<<END
<br>
END;
if($parm==1)
print "<a href=\"../\">UP</a><br>";
print <<<END
<div class="bottom">
<table class="logout" width="111" border="0">
  <tr>
    <td><a href="/admin/index.php">Main</a><br></td>
  </tr>
  <tr>
    <td><a href="/admin/logout.php">Logout</a></td>
  </tr>
</table>
</div>
</body>
</html>
END;
}

if(!isset($_SESSION[idauthusers]))
{

if($PRELOGIN==1)
{
//print "PRELOGIN";
}
else
{
//header("\nLocation: login.php\n\r");
//header("Location: login.php");
header("Location: login.php");
print "<a href=\"login.php\">click here to login to main.php</a>";
}

}


function doheaders()
{
head1();
top();
}


if(!isset($HEADER))
{
doheaders();
}
//print "klkl" . $HEADER ."RFD";


if(!($HEADER==0))
{
doheaders();
}

function fix($test)
{
$escaped=mysql_escape_string($test);
$trimmed=trim($escaped);
return $trimmed;
}

function fixpost()
{
foreach ($_POST as $key => $value)
{
$_POST[$key]=fix($value);
}
}

function fixget()
{
foreach ($_GET as $key => $value)
{
$_GET[$key]=fix($value);
}
}


function lookupswitch($var) //switches key/value pairs to value/key pair [inverse lookup]
{
	foreach ($var as $key => $value)
	{
		$ret[$value]=$key;
	}
	return $ret;
}



function checkpostupdates($pre,$val,$row)
{
//disparray($row,cehckpostupdates);
foreach($row as $key => $value)
{
//print "<br>_SESSION[reterrors][$pre][$val][values][$key]<br>";
if(isset($_SESSION[reterrors][$pre][$val][values][$key]))
{
//print "@1@";
$row[$key]=$_SESSION[reterrors][$pre][$val][values][$key];
}
}
unset($_SESSION[reterrors][$pre][$val]);

//disparray($row,checkpostupdates);
return $row;
}


function edittable($pre,$val,$tableclass,$data,$desc="",$functions="")
{
$data=checkpostupdates($pre,$val,$data);
unset($_SESSION[reterrors][$pre][$val][values]);

	if(strcmp($tableclass,"")!=1)
	{
		$tableclassop="class=\"$tableclass\"";
	}
	$tablestyle="border=1";
	print <<<END
	<table $tablestyle $tableclassop>
	<tr><th>Field</th><th>Value</th></tr>
END;
	$keyv=0;
	foreach($data as $key => $value)
	{

		if($functions[$keyv][0]==1)
		{
		}
		else
		{
			print "<tr>";
			if($desc!="")
			{
				print "<td>$desc[$keyv]</td>";
			}
			else
			{
				print "<td>$key</td>";
			}
			if($functions[$keyv][0]==0)
			{
if(isset($functions[$keyv][1]))
$length=$functions[$keyv][1];
else
$length=20;
				print <<<END
				<td><input type="text" name="edittable[$pre][$val][$key]" value="$value" size="$length"></td></tr>
END;
			}
elseif($functions[$keyv][0]==5)
{
print <<<END
<td>
$value
</td>
</tr>
END;
}
			elseif($functions[$keyv][0]==2)
			{

				//this uses $function[$keyv][1] as a lookup array
				$lookup=$functions[$keyv][1];

				print <<<END
				<td><select name="edittable[$pre][$val][$key]">
END;
				foreach($lookup as $key1 =>$value1)
				{
					$selected="";
					if(strcmp($value,$key1)==0)
					$selected="SELECTED";
					print <<<END
					<option value="$key1" $selected>$value1</option>
END;
				}
				print <<<END
				</select></td></tr>
END;

			}
			elseif($functions[$keyv][0]==4)
			{
if(isset($functions[$keyv][1]))
$length=$functions[$keyv][1];
else
$length=60;
if(isset($functions[$keyv][2]))
$height=$functions[$keyv][2];
else
$height=15;
				print <<<END
				<td>
<textarea name="edittable[$pre][$val][$key]" cols="$length" rows="$height">$value</textarea>
</td></tr>
END;
			}
			elseif($functions[$keyv][0]==3)
			{
if(isset($functions[$keyv][2]))
$length=$functions[$keyv][2];
else
$length=20;
				//this uses $function[$keyv][1] as a lookup array
				$lookup=$functions[$keyv][1];
				print <<<END
				<td><input type="text" name="edittable[$pre][$val][$key][0]" value="$value" size="$length"><br>
END;

				print <<<END
				<select name="edittable[$pre][$val][$key][1]">
END;
				foreach($lookup as $key1 =>$value1)
				{
					$selected="";
					if(strcmp($value,$key1)==0)
					$selected="SELECTED";
					print <<<END
					<option value="$key1" $selected>$value1</option>
END;
				}
				print <<<END
				</select></td></tr>
END;

			}
		}
		$keyv++;
	}
	print <<<END
	<tr><td></td><td><input type="submit" name="submit[edittable][$pre][$val]" value="Submit"></td></tr>
	</table>
END;

	$_SESSION[edittable][$pre][$val][funct]=$functions;
print "\n\n";	
}

function retedittable($pre,$val)
{
	$function=$_SESSION[edittable][$pre][$val][funct];
//disparray($function,"[edittable][$pre][$val][funct]");
	if(!isset($_POST[submit][edittable][$pre][$val]))
	{
		return array(0);
	}

	$dataset=fix($_POST[edittable][$pre][$val]);
	$funcv=0;

	foreach($function as $key =>$value)
	{
		if($value[0]!=1)
		{
			$function2[$funcv]=$value;
			$funcv++;
		}
	}
//disparray($function,"FUNCTION");
//disparray($function2,"FUNCTION2");
	$function=$function2;
$keyv=0;
	foreach($_POST[edittable][$pre][$val] as $key =>$value)
	{

//disparray($value,"key:$key");
		if($function[$keyv][0]==0)
		{
			$ret[$key]=$value;
		}
		if($function[$keyv][0]==4)
		{
			$ret[$key]=$value;
		}
		elseif($function[$keyv][0]==2)
		{
//disparray($function,func);
//			$lookup=lookupswitch($function[$keyv][1]);
//disparray($lookup,lookup);
//			$key1=$_POST[edittable][$pre][$val][$key];
//disparray($key1,"[edittable][$pre][$val][$key]");
//			$ret[$key]=$lookup[$key1];	
			$ret[$key]=$value;

		}
		elseif($function[$keyv][0]==3)
		{
//disparray($function,func);
//			$lookup=lookupswitch($function[$keyv][1]);
//disparray($lookup,lookup);
//			$key1=$_POST[edittable][$pre][$val][$key][1];
//disparray($key1,"[edittable][$pre][$val][$key]");
//			$ret[$key]=$lookup[$key1];	
if($value[0]!="")
			$ret[$key]=$value[0];
else
$ret[$key]=$value[1];
		}


		$keyv++;
	}	

unset($_SESSION[edittable][$pre][$val][funct]);
$_SESSION[reterrors][$pre][$val][values]=$ret;
	return array(1,$ret);
}


function clredittable($pre,$val)
{
unset($_SESSION[reterrors][$pre][$val]);
}


function datecalc($di,$da)
{
if($da<0)
{
//echo "<br>sub<br>";
$do=datesub($di,abs($da));
}
else
{
//echo "<br>add<br>";
$do=dateadd($di,$da);
}
return $do;
}


function dateadd($di,$da)
{
$d=$di[0];
$m=$di[1];
$y=$di[2];
$daysofmonth = array (31,28,31,30,31,30,31,31,30,31,30,31);
if (floor($y%4)==0)
{
$daysofmonth[1]=29;
}

//echo "$d-$m-$y+$da<br>";
$d=$d+$da;
//echo "$daysofmonth[$m]<br>";
for($i=0;$i<9;$i++)
{
if ($d>$daysofmonth[$m-1])
{

$d=$d-$daysofmonth[$m-1];
$m++;
if($m>12)
{
$m=1;
$y++;
}
}
}
return (array ($d,$m,$y));
}


function datecomp($di1, $di2,$type)
{
$d1=$di1[0];
$m1=$di1[1];
$y1=$di1[2];

$d2=$di2[0];
$m2=$di2[1];
$y2=$di2[2];

if($d1==$d2)
{
if($m1==$m2)
{
if($y1==$y2)
{
return 0;
}
}
} //end exact

if($y1>$y2)
return 1; // y1 bigger
if($y1<$y2)
return -1; //y1 smaller

//same year

if($m1>$m2)
return 1; // m1 bigger
if($m1<$m2)
return -1; //m1 smaller

// same month

if($type==0)
{
return; //return if month only comparison
}
elseif($type==1) // day match
{
if($d1>$d2)
return 1; // d1 bigger
if($d1<$d2)
return -1; // d1 smaller
return "ERROR";
}

}

function datesub($di,$da)
{
$d=$di[0];
$m=$di[1];
$y=$di[2];$daysofmonth = array (31,28,31,30,31,30,31,31,30,31,30,31);
if (floor($y%4)==0)
{
$daysofmonth[1]=29;
}
//echo "$d-$m-$y-$da<br>";
$d1=$d-$da;
for($i=0;$i<9;$i++)
{
        if($d1==0)
                {
        //      echo "\xa3\xa3\xa3";
                $m--;
                        if($m<1)
                        {
                                $m=12;
                                $y--;
                        }
                $d1=$daysofmonth[$m-1];

                }
        if ($d1<1)
                {
//echo "<br>GGa$d1@$d:$m:$y<br>";
                $m--;
                        if($m<1)
                        {
                                $m=12;
                                $y--;
                        }
                $d1=$daysofmonth[$m-1]-abs($d1);
                }
}
$d=$d1;
return (array ($d,$m,$y));
}

function getcurdate()
{
$CYR = date( "Y", time() );
$CMN = date( "m", time() );
$CYD = date( "d", time() );
$ret=array($CYD,$CMN,$CYR);
return $ret;
}

function weekbeg($INPUTDATE)
{
global $remoff2;
$test = weekday ($INPUTDATE);
$de = $remoff2[$test];
$weekbeg=datecalc($INPUTDATE,$de);
return $weekbeg;
}


function dispdate($INPUTDATE)
{
return date('j F Y',mktime(0,0,0,$INPUTDATE[1],$INPUTDATE[0],$INPUTDATE[2]));
//return "$INPUTDATE[2]-$INPUTDATE[1]-$INPUTDATE[0]";
}

function dispdate1($INPUTDATE)
{
return mktime(0,0,0,$INPUTDATE[1],$INPUTDATE[0],$INPUTDATE[2]);
//return "$INPUTDATE[2]-$INPUTDATE[1]-$INPUTDATE[0]";
}
function dispdate2($INPUTDATE)
{
//return mktime(0,0,0,$INPUTDATE[1],$INPUTDATE[0],$INPUTDATE[2]);
return "$INPUTDATE[2]-$INPUTDATE[1]-$INPUTDATE[0]";
}

function sqldate($INPUTDATE)
{
$dates=split("-",$INPUTDATE);
return array($dates[2],$dates[1],$dates[0]);
}




function monthadd($di,$m)
{
$d=$di[0];
$m=$di[1];
$y=$di[2];
$m+$m;
if($m>12)
$y++;
return array($d,$m,$y);
}


$RETURNURL=$_SERVER["HTTP_REFERER"];



function passgen()
{
return exec('/usr/bin/gpw 1 8');
}

/*
function sock_mail($auth, $to, $subj, $body, $head, $from) {
    $lb="\r\n";                     			//linebreak
    $body_lb="\r\n";                			//body linebreak
    $loc_host = "alphaserver.awke.co.uk";        			//localhost
    $smtp_acc = "";				    			//account
    $smtp_pass="";    							//password
    $smtp_host="localhost";  		//server SMTP
    $hdr = explode($lb,$head);      			//headers
    
    if($body) {$bdy = preg_replace("/^\./","..",explode($body_lb,$body));}
    
    // build the array for the SMTP dialog. Line content is array(command, success code, additonal error message)
    if($auth == 1) {// SMTP authentication methode AUTH LOGIN, use extended HELO "EHLO"
        $smtp = array(
            // call the server and tell the name of your local host
            array("EHLO ".$loc_host.$lb,"220,250","HELO error: "),
            
            // request to auth
            array("AUTH LOGIN".$lb,"334","AUTH error:"),
            
            // username
            array(base64_encode($smtp_acc).$lb,"334","AUTHENTIFICATION error : "),
            
            // password
            array(base64_encode($smtp_pass).$lb,"235","AUTHENTIFICATION error : "));
    } 
    else {// no authentication, use standard HELO    
        $smtp = array(
            // call the server and tell the name of your local host
            array("HELO ".$loc_host.$lb,"220,250","HELO error: "));
    }
    
    // envelop
    $smtp[] = array("MAIL FROM: <".$from.">".$lb,"250","MAIL FROM error: ");
    $smtp[] = array("RCPT TO: <".$to.">".$lb,"250","RCPT TO error: ");
    
    // begin data        
    $smtp[] = array("DATA".$lb,"354","DATA error: ");
    
    // header
    $smtp[] = array("Subject: ".$subj.$lb,"","");
    $smtp[] = array("To: ".$to.$lb,"","");        
    foreach($hdr as $h) {$smtp[] = array($h.$lb,"","");}
    
    // end header, begin the body
    $smtp[] = array($lb,"","");
    if($bdy) {foreach($bdy as $b) {$smtp[] = array($b.$body_lb,"","");}}
    
    // end of message
    $smtp[] = array(".".$lb,"250","DATA(end)error: ");
    $smtp[] = array("QUIT".$lb,"221","QUIT error: ");

    // open socket
    $fp = @fsockopen($smtp_host, 25);
    if (!$fp) echo "<b>Error:</b> Cannot connect to ".$smtp_host."<br>";
    
    $banner = @fgets($fp, 1024);
    // perform the SMTP dialog with all lines of the list
    foreach($smtp as $req){
        $r = $req[0];
        // send request
        @fputs($fp, $req[0]);
        // get available server messages and stop on errors
        if($req[1]){
            while($result = @fgets($fp, 1024)){if(substr($result,3,1) == " ") { break; }};
            if (!strstr($req[1],substr($result,0,3))) /* echo"$req[2].$result<br>" * /;
        }
    }
    $result = @fgets($fp, 1024);
    
    // close socket
    @fclose($fp);
    return 1;
}
*/


function get_next_userid()
{
$sql="SELECT DISTINCT userid FROM domains ORDER BY userid";
$results=gosql($sql,0);

$limit=mysql_num_rows($results);

while($row=mysql_fetch_assoc($results))
	{
	$data[]=$row[userid];
	}

for ($j=1; $j<=($limit-1); $j++)
	{
	if (($data[$j]-$data[($j-1)]) != 1)
		{
		return ($data[($j-1)] + 1);
		}
	}	
}

function dnsdetails() {
$dnsinfo[]="ns A 212.13.203.181";
$dnsinfo[]="ns.awke.co.uk. SOA1 dns.awke.co.uk.";
$dnsinfo[]="8H SOA2 2H";
$dnsinfo[]="4W SOA3 1D";
$dnsinfo[]="@ NS ns.awke.co.uk.";
$dnsinfo[]="@ MX mail.awke.co.uk. 10";
$dnsinfo[]="@ NS ns1.awke.co.uk.";
$dnsinfo[]="ns1 A 212.13.203.182";
$dnsinfo[]="www A 212.13.203.181";
$dnsinfo[]="mail A 212.13.203.181";
$dnsinfo[]="ftp A 212.13.203.181";
return ($dnsinfo);
}

require_once('files.php');
/*
function Array2File( $aryArray, $sFilename ){
   
   //Optimistic execution
   $retVal = true;
   
   //Try to open the file
   if (!$handle = fopen($sFilename, 'w+')) {
         print "Cannot open file ($sFilename)";
         $retVal = false;
   }else{

       //Convert the array to a string. Each element is one line of the string.
       $sArrayAsString = "";
       foreach( $aryArray as $thisElement ){
           $sArrayAsString .= $thisElement . "\n";    
       }
       
       //Be good and remove the trailing newline
       $sArrayAsString = substr( $sArrayAsString, 0, strlen( $sArrayAsString ) - 1 );

       //Write the data to the file
       if (!fwrite($handle, $sArrayAsString)) {
           print "Cannot write to file ($sFilename)";
           $retVal = false;
       }else{
           //Debug:
           //print "Success, wrote ($somecontent) to file ($filename)";        
           fclose($handle);
       }            
   }
   
   return $retVal;
}
*/

function bytes($a, $show = FALSE)
{
if ($show)
	{
	if ($a < 1024000000)
		{
		$colour = "<div class=\"less1g\">";
		}
	elseif ($a > 1024000000 && $a <2048000000)
		{
		$colour = "<div class=\"between1gand2g\">";
		}
	elseif ($a > 2048000000 && $a <5120000000)
		{
		$colour = "<div class=\"between2gand5g\">";
		}
	elseif ($a > 5120000000 && $a <10240000000)
		{
		$colour = "<div class=\"between5gand10g\">";
		}
	elseif ($a > 10240000000)
		{
		$colour = "<div class=\"greater10g\">";
		}
	}
	
$unim = array("B","KB","MB","GB","TB","PB");
$c = 0;
while ($a>=1024)
	{
    $c++;
    $a = $a/1024;
    }

$size = $colour . number_format($a,($c ? 2 : 0),".","~") . " " . $unim[$c] . "</div>";

return $size;
}

function getdomainlist()
{
$result = mysql_query("SELECT DISTINCT domainn from domains WHERE domainn!='BLANK DOMAIN ALIAS USE ONLY' ORDER BY domainn");

$num_rows = mysql_num_rows($result);
if ($num_rows > 0)
	{
	$domains[] = "blank";
	while ($row = mysql_fetch_assoc($result))
		{
		$domains[] = $row[domainn];
		}
		
	$maxrows = ceil($num_rows / 4);
	$maxrows2 = 2 * $maxrows;
	$maxrows3 = 3 * $maxrows;
	$output = "<div class=\"trafficlinks\"><table>";
	for ($i=1; $i<=$maxrows; $i++)
		{
		$output .= "<tr><td><a href=\"$PHP_SELF#$domains[$i]\">" . $domains[$i] . "</a></td><td><a href=\"$PHP_SELF#" . $domains[$i+$maxrows] . "\">" . $domains[$i+$maxrows] . "</a></td><td><a href=\"$PHPSELF#" . $domains[$i+$maxrows2] . "\">" . $domains[$i+$maxrows2] . "</a></td><td><a href=\"$PHP_SELF#" . $domains[$i+$maxrows3] . "\">" . $domains[$i+$maxrows3] . "</a></td></tr>";
		}
	$output .= "</table></div><br>";
	}
return $output;
}
?>
