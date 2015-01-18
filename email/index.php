<?php
include('functions.inc');
head1()
?>
<div class="maintext">Please use the form below to log in to your account</div>
<form class="login" method="POST" action="emailuser.php">
<table border=1>
<tr>
<td>
Email
</td>
<td>
<input type="text" name="email">
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




