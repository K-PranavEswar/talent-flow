<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';

class MailService {
  public static function send(string $to, string $subject, string $html, string $fromName = 'TalentFlow HR'): bool {
    $mail = new PHPMailer(true);

    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;

      // ✅ TalentFlow Gmail credentials
      $mail->Username = 'talentflowxyz@gmail.com';
      $mail->Password = 'dsuh xmco cjxy jsdo'; // Gmail App Password
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      // ✅ Set “From” name dynamically
      $mail->setFrom('talentflowxyz@gmail.com', $fromName);

      // ✅ Add recipient(s)
      if (strpos($to, ',') !== false) {
        foreach (explode(',', $to) as $email) {
          $mail->addAddress(trim($email));
        }
      } else {
        $mail->addAddress(trim($to));
      }

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $html;

      $mail->send();
      return true;
    } catch (Exception $e) {
      error_log("Mailer Error: " . $mail->ErrorInfo);
      return false;
    }
  }
}
