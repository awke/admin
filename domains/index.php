<?php
$DONOTINDEX=1;
$MENU=1;// autofind files needed
$dirloc=dirname($_SERVER["SCRIPT_FILENAME"]);
$a=18; // this many deep
$done=0;
while ($a>0 && $done<1)
{
if(file_exists("$dirloc/masterloc.inc"))
{
include ("$dirloc/masterloc.inc");
$done=1;
}
$dirloc=dirname($dirloc);
$a--;
}



$title="XEN Control panel not setup yet"; //Domain Registered but no pages uploaded"; 

$DONOTINDEX=1;


//include("$fileloc/inc/header.php"); 


echo 'This xen control panel has not been activated yet.'; //domain has been registered, but no pages have yet been uploaded by the customer - please use our <a href="domainlookup.php" target="_self">domain name lookup</a> form for more information';

include("$fileloc/inc/footer.php"); ?>
