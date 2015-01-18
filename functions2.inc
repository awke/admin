<?php

session_name("AWKE-user-set-SESS-ID");

session_set_cookie_params ( 600);

session_start();

session_set_cookie_params ( 600);

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


function disptable($descheaders,$data,$datauniq,$functions,$tableclass="",$uniqdata=0)
{
//disparray($datauniq,datauniq);

$tablestyle="border=1";
//$descheaders is an array of headers
//$functions is an array of functions, 
if(isset($tableclass))
{
$tableclassop="class=\"$tableclass\"";
}
print "<table $tablestyle $tableclassop>\n";
print "<tr>\n";
foreach ($descheaders as $header)
{
print "<th><div align=\"center\">$header</div></th>";
}
foreach ($functions as $header)
{
$head=$header[0];
print "<th><div align=\"center\">$head</div></th>";
}
print "</tr>\n";
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
print "<tr $classop>\n";
foreach ($row as $item)
{
if($uniqdata==1)
{
$item=str_replace("%UNIQ%", $datauniq[$rowloc], $item);
} 
$item=str_replace("%TABCLASS%", $tableclass, $item);

print "<td $tditem>$item</td>\n";   //print standard item
}
foreach ($functions as $function)
{
$op=str_replace("%UNIQ%", $datauniq[$rowloc], $function[1]);  //replace %UNIQ% with data from datauniq array
print "<td $tdfunc>$op</div></td>";
}
print "</tr>\n";
$rowloc++;
}
print "</tr>";
print "</table>";
}








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
	{
		die("<font color=\"#FF0000\">Error, I could not connect to the database at $dbserver. Using username $dbuser and password $dbpass1.<BR>Please go back and try again.");
	}
	$db_selected = mysql_select_db("$dbname", $db);
	if (!$db_selected) 
	{
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
		{
	        	echo("<P>Sucessful - $sql</P>");
		}
	}
	else
	{
		if($verbose)
		{
			echo("<P>Error creating item: " .             mysql_error() . " in <br><b> $sql</b></P>");
		}
	}
	//$result1=mysql_fetch_assoc($result);
	return $result;

}


function head1()
{
	$title="AWKE User control";
	print <<<END
	<HTML><HEAD>


	<meta http-equiv="Content-Language" content="en-gb">
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>$title</title>
	<link href="admin.css" rel="STYLESHEET">

	</head>

	</HEAD>
	<body>
	END;
}





function top()
{
	print <<<END

	END;
}


function bottom()
{
	print <<<END
	<br><br
	<a href="main.php">Main</a><br>
	<a href="logout.php">Logout</a>

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


if(!isset($HEADER))
{
	head1();
	top();
}
//print "klkl" . $HEADER ."RFD";


if(!($HEADER==0))
{
	head1();
	top();
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


//function disptable($descheaders,$data,$datauniq,$functions,$tableclass="",$uniqdata=0)



function edittable($pre,$tableclass,$data,$desc="",$functions="")
{
 
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
				print <<<END
				<td><input type="text" name="edittable[$pre][$key]" value="$value"></td></tr>
				END;
			}
			elseif($functions[$keyv][0]==2)
			{
				//this uses $function[$keyv][1] as a lookup array
				$lookup=$function[$keyv][1];

				print <<<END
				<td><select name="edittable[$pre][$key]">
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
	<tr><td></td><td><input type="submit" name="submit[edittable][$pre]" value="Submit"></td></tr>
	</table>
	END;
	
}


function retedittable($pre,$function="")
{
	if(!isset($_POST[submit][edittable][$pre]))
	{
		return array(0);
	}

	$dataset=$_POST[edittable][$pre];
	$funcv=0;
	foreach($function as $key =>$value)
	{
		if($value[0]!=1)
		{
			$function2[$funcv]=$value;
			$funcv++;
		}
	}
	$function=$function2;
	foreach($function as $key =>$value)
	{
		if($value[0]==0
		{
			$ret[$key]=$_POST[edittable][$pre][$key];
		}
		elseif($value[0]==2)
		{
			$lookup=lookupswitch($value[1]);
			$key1=$_POST[edittable][$pre][$key];
			$ret[$key]=$lookup[$key1];	
		}
	}	


	return array(1,$ret);
}





?>
