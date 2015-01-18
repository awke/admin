<?php

require_once('/Supported/phpcode/email.php');
require_once('invoices.php');


$inv=new invoices();




print "TEST2";

$mail = new my_phpmailer(TRUE);

try {
$mail->addAddress("admin@awke.co.uk","SENDER ADDRESS");
$mail->Subject ="This is the test message subject";
$mail->Body = "THis is the body";
// $mail->Send();
  echo "Message Sent OK</p>\n";
} catch (phpmailerException $e) {
  echo $e->errorMessage(); //Pretty error messages from PHPMailer
} catch (Exception $e) {
  echo $e->getMessage(); //Boring error messages from anything else!
}
