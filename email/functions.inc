<?php
session_name("AWKE-user-email-SESS-ID");
session_set_cookie_params (1600);
session_start();
session_set_cookie_params (1600);

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
$output .= "<span class=\"editerror\">";
$output .= "ERROR - ";
$output .= $value ."\n<br>";
$output .= "</span>";
}
unset($_SESSION[error]);
}
return $output;
}

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

function head1()
{
$title="AWKE User control";
print <<<END
<HTML><HEAD>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>$title</title>
<link href="admin.css" rel="STYLESHEET">
</HEAD>
<body class="gradient">
<div class="backdrop"></div>
<div class="adminhead">Email Administration </div>
END;
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

function fix($test)
{
$escaped=mysql_escape_string($test);
$trimmed=trim($escaped);
return $trimmed;
}

function clredittable($pre,$val)
{
unset($_SESSION[reterrors][$pre][$val]);
}

function passgen()
{
return exec('/usr/bin/gpw 1 8');
}

function footer()
{
print "</div></div><div class=\"bbuttontab2\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"logout.php\">Logout</a></td></tr></table></div>";
}


function sock_mail($to, $subj, $body, $head, $from) {
    $lb="\r\n";                     			//linebreak
    $body_lb="\r\n";                			//body linebreak
    $loc_host = "localhost";        			//localhost
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
            if (!strstr($req[1],substr($result,0,3))) echo"$req[2].$result<br>";
        }
    }
    $result = @fgets($fp, 1024);
    
    // close socket
    @fclose($fp);
    return 1;
}
?>
