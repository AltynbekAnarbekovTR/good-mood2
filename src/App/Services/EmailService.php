<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Paths;

require Paths::ROOT .'libraries/PHPMailer/src/Exception.php';
require Paths::ROOT .'libraries/PHPMailer/src/PHPMailer.php';
require Paths::ROOT .'libraries/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
  public function sendVerificationEmail(string $authCode, string $email)
  {
    $mail = new PHPMailer(true);
    try {
      $mail->SMTPDebug = 0;
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = getenv('EMAIL_NAME');
      $mail->Password = getenv('EMAIL_PASSWORD');
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;
      $mail->setFrom(getenv('EMAIL_NAME'), getenv('EMAIL_FROM'));
      $mail->addAddress($email);
      $mail->isHTML(true);
      $mail->Subject = 'Email verification from Good Mood';
      $mail->Body = "<p>Welcome to Good Mood! Click the link below to verify your account</p><br/><a href='http://localhost/verifyAccount?code=$authCode&email=$email'>Click to verify your email</a>";
      $mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
}

