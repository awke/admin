<?php

include('functions.inc');
sqlconn();
PRINT <<<END
<div class="infolabel">Add User to privilege system</div>
<div class="subtabinfo">
<table><tr><td>
<form method="post" action="userprivilegesadduserupdatea.php">
END;
$row=array("username"=>"","passwd"=>"","description"=>"");
$desc=array("Username","Password","Description");
$function=array(array(0),array(0),array(0));

edittable("add",0,"usual",$row,$desc,$function);
print "</td></tr></table></div>";
print "<div class=\"bbuttontab\"><table class=\"backbutton\" width=111><tr><td>";
print "<a href=\"userprivileges.php\">Back</a></td></tr></table></div>"; 
bottom();

?>



