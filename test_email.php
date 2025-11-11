<?php
require 'app/libs/PHPMailer/src/Exception.php';
require 'app/libs/PHPMailer/src/PHPMailer.php';
require 'app/libs/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'talentflowxyz@gmail.com';
$mail->Password = 'dsuh xmco cjxy jsdo'; // Replace with App Password
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('talentflow1@gmail.com', 'TalentFlow HR Test');
$mail->addAddress('yourpersonalemail@gmail.com');
$mail->isHTML(true);
$mail->Subject = 'PHPMailer Test';
$mail->Body = '<b>This is a test email from TalentFlow system.</b>';

if ($mail->send()) {
  echo "✅ Test email sent successfully!";
} else {
  echo "❌ Failed to send: " . $mail->ErrorInfo;
}
