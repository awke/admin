<?
// ClamAV - log analyzer
// 30.Jan 2005  by HammerJoe

$version=0.6;
$top=10;
include "lang-en.inc";
$os = "linux";
//$os = "windows";
#$defaulview="Today";
print $os;
$defaultview="Week";


if ($sort=="") {$sort=$defaulview;};
/*
if($os == "windows"){$lastupdate = date ("j. F Y H:i:s.", filemtime("freshclam.log"));} else 
*/
{$lastupdate = date ("j. F Y H:i:s.", filemtime("/var/log/clamav/freshclam.log"));};
$warning="none";
/*
if($os == "windows"){$filename = "freshclam.log";} else 
*/
{$filename = "/var/log/clamav/freshclam.log.1";};
$fh = fopen($filename, "r");
while(!feof($fh)){  $freshclam = htmlspecialchars(fgets($fh, 1024));
//    echo ("$output<br />");
if (preg_match ("/^WARNING.*!/", $freshclam )) {$casti = explode(":", $freshclam);$warning = $casti[1];};
if (preg_match ("/sigs: [0-9]{5},/", $freshclam )) {$casti = explode(" ", $freshclam);$sigs = $casti[8];};
if (preg_match ("/sigs: [0-9]{4},/", $freshclam )) {$casti = explode(" ", $freshclam);$sigs2 = $casti[8];};}
//sigs: .....
fclose($fh);



// Priradenie udajov zo suboru clamd.log
/*
if($os == "windows"){$lines = file ('clamd.log');} else 
*/
{$lines = file ('/var/log/clamav/clamav.log.1');};

foreach ($lines as $line_num =>
$line) {
if (preg_match ("/clamd daemon/", $line )) {$clamverparts = explode(" ", $line);$clamver=$clamverparts[8];};
if (preg_match ("/ FOUND$/", $line )) {$casti = explode(" ", $line);

if (trim($casti[7]<>"stream:")) {
$pole[] = array ("month" =>trim($casti[1]),"day" =>trim($casti[2]),"time" =>trim($casti[3]),"year" =>trim($casti[4]),"virus" =>trim($casti[7])); }
if (trim($casti[7]=="stream:")) {
  $pole[] = array ("month" =>trim($casti[1]),"day" =>trim($casti[3]),"time" =>trim($casti[4]),"year" =>trim($casti[5]),"virus" =>trim($casti[8])); }
  }
  }


  $tried="virus";
  $lp=(count($pole)-1);
  $lastvirus=$pole[$lp][virus];
  $lastdate=$pole[$lp][day]." ".$pole[$lp][month]." ".$pole[$lp][year]." ".$pole[$lp][time];

  foreach($pole as $res)
       $sortAux[] = $res[$tried];
       array_multisort($sortAux, SORT_ASC, $pole);

       $dnes = 0;
       $tyzden = 0;
       $mesiac = 0;
       $rok = 0;
       $pocetnost = 0;
       $variant = 0;
       $medzipocet = 0;
       for ($i=0; $i<count($pole); $i++) {
       // pocitadla
       if ($pole[$i][day]==date("j")) { if ($pole[$i][month]==date("M")) {if ($pole[$i][year]==date("Y")) {$dnes++ ;  };};};
       if ($pole[$i][day] > date(j)-7) { if ($pole[$i][month]==date("M")) {if ($pole[$i][year]==date("Y")) {$tyzden++ ;};};};
       if ($pole[$i][month]==date("M")) { if ($pole[$i][year]==date("Y")) {$mesiac++ ;};};
       if ($pole[$i][year]==date("Y")) { $rok++ ;};
       if ($pole[$i-1][virus]==$pole[$i][virus]) { $pocetnost++ ;  }
       if ($pole[$i-1][virus]<>$pole[$i][virus]) { $medzipocet=0; $pocetnost=1 ; $variant++ ;  }
       //($i+1)." ".$variant.". ".
       $data = $medzipocet." ".$pole[$i][day]." ".$pole[$i][month]." ".$pole[$i][year]." ".$pole[$i][time]." <a href=http://www.viruslist.com/en/find?search_mode=full&words=".$pole[$i][virus]."&x=0&y=0>".$pole[$i][virus]."</a> ".$pocetnost."<BR>";

       // if i find how to count same values in array, i will replace whole block
       if ($sort=="Today"){if ($pole[$i][day]==date("j")) { if ($pole[$i][month]==date("M")) {if ($pole[$i][year]==date("Y")) { $celkomhelp=$dnes; $medzipocet++ ;};};};};
       if ($sort=="Week"){if ($pole[$i][day] > date(j)-7)  { if ($pole[$i][month]==date("M")) {if ($pole[$i][year]==date("Y")) {  $celkomhelp=$tyzden; $medzipocet++ ; };};};};
       if ($sort=="Month"){if ($pole[$i][month]==date("M")) { if ($pole[$i][year]==date("Y")) { $celkomhelp=$mesiac; $medzipocet++; };};};
       if ($sort=="Year"){if ($pole[$i][year]==date("Y")) {  $celkomhelp=$rok; $medzipocet++ ;};};
       {  $celkomhelp=count($pole); $medzipocet++ ;};
       if ($sort=="Unique") { $celkomhelp=count($pole); $medzipocet++ ;};
       // echo $data;
       // if i find how to count same values in array, i will replace whole block

       //if ($sort==""){
         if ($pole[$i+1][virus]<>$pole[$i][virus]) {
	 $pole2[] = array ("pocet" =>$medzipocet, "month" =>$pole[$i][month],"day" =>$pole[$i][day],"time" =>$pole[$i][time],"year" =>$pole[$i][year],"virus" =>$pole[$i][virus],"pocetnost"=>$pocetnost);
	 ;};
	 //};

	 }

	 if ($sort=="Unique") {$top=count($pole2);};
	 //if ($sort==""){
	 rsort($pole2);
	 $hlavicka="<html>
	 <head>
	 <meta http-equiv=Expires content=Tue, 20 Aug 1996 14:25:27 GMT>
	 <meta http-equiv=Expire content=now>
	 <meta http-equiv=Pragma content=no-cache>
	 <meta http-equiv=Cache-control content=no-cache>
	 <meta http-equiv=Content-Type content=text/html; charset=windows-1250>
	 <STYLE TYPE=text/css>
	 <!--
	 TD{font-family: Arial; font-size: 9pt;}
	 body{font-family: Arial; font-size: 10pt; }
	 --->
	 </STYLE>
	 </head>
	 <body>
	   <center><h3>TOP ".$top." - ".$sort." ".$lang[0]."</h3><br><br>
	     <table border=1 cellpadding=2 cellspacing=0>
	         <tbody>
		       <tr>
		               <td><strong>$lang[1]</strong></td>
			               <td><strong>Status</strong></td>
				               <td><strong>$lang[2]</strong></td>
					               <td><strong>$lang[3]</strong></td>
						               <td><strong><center>$lang[4]</center></strong></td>
							               <td><strong>$lang[5]</strong></td>
								               <td><strong>Prva infekcia</strong></td>
									             </tr>
										     ";

										     $position=1;
										     echo $hlavicka;
										     for ($i=0; $i<count($pole2); $i++) {

										     // ak napocita $top tak prestan vypisovat
										     if ($position>$top){break;}

										     // najdenie datumu prveho vyskytu
										     for ($k=count($pole); $k>0; $k--) {
										     if ($pole[$k][virus] == $pole2[$i][virus]) {$firstdate = $pole[$k][day]." ".$pole[$k][month]." ".$pole[$k][year]." ".$pole[$k][time];$firstdate = trim($firstdate);
										     stop;};};


										     if ($pole2[$i][year]==date("Y")) { $status="DOWN" ;};
										     if ($pole2[$i][month]==date("M")) { if ($pole2[$i][year]==date("Y")) {$status="DOWN" ;};};
										     if ($pole2[$i][day] > date(j)-7) { if ($pole2[$i][month]==date("M")) {if ($pole2[$i][year]==date("Y")) {$status="UP" ;};};};
										     if ($pole2[$i][day]==date("j")) { if ($pole2[$i][month]==date("M")) {if ($pole2[$i][year]==date("Y")) {$status="LIVE" ;};};};

										     //total position - ($i+1)

										     $data2="      <tr>
										             <td><center>".$position.".</center></td>
											             <td><center>".$status."</center></td>
												             <td><a href=http://www.viruslist.com/en/find?search_mode=full&words=".$pole2[$i][virus]."&x=0&y=0>".$pole2[$i][virus]."</a></td>
													             <td><P align=right>".$pole2[$i][pocet]." x</td>
														             <td><P align=right>".round(((($pole2[$i][pocet])/($celkomhelp))*100),2)." %</td>
															             <td><P align=right>".$pole2[$i][day]." ".$pole2[$i][month]." ".$pole2[$i][year]." ".$pole2[$i][time]."</td>
																             <td><P align=right>".$firstdate."</td>
																	           </tr>
																		   ";
																		   // set default view

																		   if ($sort=="Today"){if ($pole2[$i][day]==date("j")) { if ($pole2[$i][month]==date("M")) {if ($pole2[$i][year]==date("Y")) {echo $data2;$position++;};};};};
																		   if ($sort=="Week"){if ($pole2[$i][day] > date(j)-7)  { if ($pole2[$i][month]==date("M")) {if ($pole2[$i][year]==date("Y")) {echo $data2;$position++;};};};};
																		   if ($sort=="Month"){if ($pole2[$i][month]==date("M")) { if ($pole2[$i][year]==date("Y")) {echo $data2;$position++;};};};
																		   if ($sort=="Year"){if ($pole2[$i][year]==date("Y")) { echo $data2;$position++;};};
																		   if ($sort=="All") { echo $data2;$position++;};
																		   if ($sort=="Unique") {echo $data2;$position++;};





																		   };
																		   for ($i=$top; $i<count($pole2); $i++) {$rest=$rest+$pole2[$i][pocet]; $restperc=$restperc+(round(((($pole2[$i][pocet])/($celkomhelp))*100),2));};
																		   $pata="    </tbody>
																		         <tr>
																			         <td><center>></center></td>
																				         <td><center>></center></td>
																					         <td><a href=clamdstat.php?sort=Unique>$lang[6]</a></td>
																						         <td><P align=right>".$rest." x</strong></td>
																							         <td><P align=right>".$restperc." %</td>
																								         <td><P align=right><</td>
																									         <td><P align=right><</td>
																										       </tr>
																										         </table>
																											   <div style=text-align: center;><br>
																											   ";

																											   echo $pata ;
																											   //};

																											   echo "<BR>".$lang[7]." <a href=clamdstat.php?sort=All>".$lang[8]."<a>:".count($pole)." ".$lang[9]." <a href=clamdstat.php?sort=Unique>".$lang[10]."</a> ".$variant." ".$lang[11]." <a href=clamdstat.php?sort=Year>".$lang[12]."</a>".$rok." ".$lang[11]." <a href=clamdstat.php?sort=Month>".$lang[13]."</a>".$mesiac." ".$lang[14]." <a href=clamdstat.php?sort=Week>".$lang[15]."</a>".$tyzden." <a href=clamdstat.php?sort=Today>".$lang[16]."</a>".$dnes."<br><br>DB Aktualizovana:".$lastupdate." Vzoriek:".$sigs." daily:".$sigs2." <br>Posledny zachyteny virus: ".$lastvirus." dna:".$lastdate."<br>ClamAV version:".$clamver." Warning:".$warning." <br>Prehlad za obdobie:".$sort." Report vygenerovany dna:".date ("j. M Y H:i:s.")."<br><br>
																											   <center><a href=http://sourceforge.net/projects/clamdstat>ClamAV - php Log Analyzer</a> ver. ".$version." by HammerJoe
																											   </body>
																											   </html>";


																											   ?>


