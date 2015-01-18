<?php
$HEADER=1;
include('functions.inc');
sqlconn();
print "<div class=\"infolabel\">Trafic Statistics</div>";
print "<div class=\"subtabinfo\">";

$fromdomdisp = strpos($_SERVER[HTTP_REFERER], "domdisp.php");
$fromadmin = strpos($_SERVER[HTTP_REFERER], "index.php");

if($fromdomdisp)
{
        $singledomain = "AND domains.D_ID='$_INFO[D_ID]'";
	$_SESSION[secarea][traffic][single]=1;

}
elseif($_SESSION[secarea][traffic][single]==1)
	{
	$singledomain = "AND domains.D_ID='$_INFO[D_ID]'";
$_SESSION[secarea][traffic][single]=1;
}
elseif ($_SESSION[secarea][authuserdata][SUPERUSER] == 1)// && $fromadmin && $_SESSION[secarea][traffic][single]!=1) || )
	{
	print getdomainlist();
	}
else
	{
	header("Location: index.php");
	exit;
	}
	



$YR=date('Y');
$MN=date('m');
print <<<END
<table border=1>
<tr>
<td colspan="6">
LIMIT BY YEAR/MONTH & YEAR
</td>
<tr>
<td colspan="6">
<a href="traffic.php">NO RESTRICTION</a>
</td></tr>
END;

for($YEAR=2006;$YEAR<=$YR;$YEAR++)
{
print <<<END
<tr><td colspan="6">
<a href="$PHP_SELF?YEAR=$YEAR">$YEAR</a>
</td></tr>
<tr>
END;

for($MONTH=1;$MONTH<=12;$MONTH++)
{
print <<<END
<td>
<a href="$PHP_SELF?YEAR=$YEAR&MONTH=$MONTH">$YEAR-$MONTH</a>
</td>
END;
if($MONTH==6)
{
print <<<END
</tr><tr>
END;
}


}
print <<<END
</tr>
END;
}
print <<<END
</table>
END;


if(isset($_GET[YEAR]))
{
$YRres=" AND YEAR(dat)='$_GET[YEAR]' ";
}
if(isset($_GET[MONTH]))
{
$MNres=" AND MONTH(dat)='$_GET[MONTH]'";
}

$sql="SELECT *,MONTH(dat) as MNTH,YEAR(dat) as YR,traffic.type as typ from traffic,domains WHERE traffic.d_id=domains.D_ID $singledomain $YRres $MNres ORDER BY domainn,dat,typ";

$result=gosql($sql,0);

while($row=mysql_fetch_assoc($result))
	{
	$type=$row[typ];
	$year=$row[YR];
	$month=$row[MNTH];
	$d_id=$row[D_ID];
	$domainn=$row[domainn];
	$bytes=$row[bytes];
	$data[$domainn][$year][$month][$type]=array($bytes,$row[inb],$row[outb]);
	$in=$row[inb];
$out=$row[outb];
}


print <<<END
<table class="usual">
<tr><th>DOMAIN</th><th>YEAR</th><th>MONTH</th><th>TYPE</th><th>size</th><th>in</th><th>out</th></tr>
END;
foreach($data as $DOMAIN => $v)
	{
	$TOTAL[DOMAIN]=0;
	$link = 0;
	foreach($v as $YEAR => $v1)
		{
		$TOTAL[YEAR]=0;
		foreach($v1 as $MONTH => $v2)
			{
			$TOTAL[MONTH]=0;
			$done = 0;
			foreach($v2 as $TYPE => $v3a)
				{
				$v3=$v3a[0];
$d1=bytes($v3a[1],TRUE);
$d2=bytes($v3a[2],TRUE);
$d=bytes($v3, TRUE);
				if ($TYPE == "http")
					{
					$typecolour = "class=\"typehttp\"";
					}
				elseif ($TYPE == "ftp")
					{
					$typecolour = "class=\"typeftp\"";
					}
				if ($link == 0)
					{
					print "<tr><td><span class=\"trafficlink\"><a name=\"$DOMAIN\">$DOMAIN</a></span></td><td>$YEAR</td><td>$MONTH</td><td $typecolour>$TYPE</td><td>$d</td><td>$d1</td><td>$d2</td></tr>";
					$link = 1;
					}
				else
					{
					print "<tr><td>$DOMAIN</td><td>$YEAR</td><td>$MONTH</td><td $typecolour>$TYPE</td><td>$d</td><td>$d1</td?<td>$d2</td></tr>";
					}
				$TOTAL[MONTH]=$TOTAL[MONTH]+$v3;
				$TOTAL[YEAR]=$TOTAL[YEAR]+$v3;
				$TOTAL[DOMAIN]=$TOTAL[DOMAIN]+$v3;
		$TOTAL[TOTAL]=$TOTAL[TOTAL]+$v3;
		}
			$d=bytes($TOTAL[MONTH], TRUE);
			print "<tr><td class=\"totalmonth\">$DOMAIN</td><td class=\"totalmonth\">$YEAR</td><td class=\"totalmonth\">$MONTH</td><td class=\"totalmonth\">TOTAL</td><td class=\"totalmonth\">$d</td></tr>";
			}
		$d=bytes($TOTAL[YEAR]);
		print "<tr><td class=\"totalyear\">$DOMAIN</td><td class=\"totalyear\">$YEAR</td><td class=\"totalyear\">TOTAL</td><td class=\"totalyear\"></td><td>$d</td></tr>";
		}
	$d=bytes($TOTAL[DOMAIN]);
	print "<tr><td class=\"totaldomain\">$DOMAIN</td><td class=\"totaldomain\">TOTAL</td><td class=\"totaldomain\"> </td><td class=\"totaldomain\"></td><td>$d</td></tr>";
	}
$d=bytes($TOTAL[TOTAL]);	
print <<<END
<tr><td class="totaltotal">TOTAL</td><td class="totaltotal">TOTAL</td><td class="totaltotal">TOTAL</td><td class="totaltotal">TOTAL</td><td>$d</td></tr>
END;
print <<<END
</table>
END;

print"<br><br><br><br></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"$_SERVER[HTTP_REFERER]\">Back</a></td></tr></table></div>"; 


bottom();
?>
