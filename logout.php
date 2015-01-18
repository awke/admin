<?php
$HEADER=1;

$PRELOGIN=1;
include('functions.inc');

unset($_SESSION[idauthusers]);
//setcookie( session_name() ,"",0,"/");
session_destroy();
?>
<div class="maintext">LOGGED OUT</div>
<div class="bbuttontab4"><table class="backbutton4" width=111><tr><td>
<a href="main.php">Login</a></td></tr></table></div>
</body>
</html>


