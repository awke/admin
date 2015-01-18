<?php
$NOBOOKING=1;
$PRELOGIN=1;
$HEADER=1;
include('functions.inc');

?>
<div class="maintext">Please use the form below to log in to your account</div>

<form class="login" method="POST" action="auth.php">
<table border=1>
<tr>
<td>
Username
</td>
<td>
<input type="text" name="uname">
</td>
</tr>
<tr>
<td>
password
</td>
<td>
<input type="password" name="passwd">
</td>
</tr>
<tr>
<td>
</td>
<td>
<input type="submit" value="Submit" name="B1">
</td>
</tr>
</table>
</form>
</body>
</html>




