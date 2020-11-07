<?php
include 'config.php';
$to      = 'sa2blv@sm2ampr.net';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: Systemguard@svxportal.sm2ampr.net' . "\r\n" .
    'Reply-To: Systemguard@svxportal.sm2ampr.net' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);
echo "sucsess";
?>