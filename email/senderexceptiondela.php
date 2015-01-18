<?php
$HEADER=0;
include('functions.inc');
$email = $_SESSION[email];
$emailtoremove = $_POST['emailtoremove'];
$to = "admin@awke.co.uk";
$subj = "Request for sender exception removal";
$body = "$email has requested the removal of the following email exception:\n\n$emailtoremove";
$head = "from: $email";
$from = "admin@awke.co.uk";
sock_mail($to, $subj, $body, $head, $from);
header("Location: emailuser.php");
?>
