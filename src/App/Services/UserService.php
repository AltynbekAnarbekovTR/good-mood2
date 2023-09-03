<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require __DIR__.'/../../PHPMailer/src/Exception.php';
require __DIR__.'/../../PHPMailer/src/PHPMailer.php';
require __DIR__.'/../../PHPMailer/src/SMTP.php';

class UserService
{
    public function __construct(private Database $db)
    {
    }

    public function isEmailTaken(string $email)
    {
        $emailCount = $this->db->query(
                "SELECT COUNT(*) FROM users WHERE email = :email",
                [
                        'email' => $email,
                ]
        )->count();

        if ($emailCount > 0) {
            throw new ValidationException(['email' => ['Email taken']]);
        }
    }

    public function create(array $formData)
    {
        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'altynbek290697@gmail.com';
            $mail->Password = 'tetkmlmuirvbzwwj';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('altynbek290697@gmail.com', 'localhost');
            $mail->addAddress('altyn_312@mail.ru', 'Yo');
            $mail->isHTML(true);
            $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);
            $mail->Subject = 'Email verification from Positive News';
            $verify_token=md5((string) rand());
            $mail->Body = "<p>Your verification code is: <b style='font-size: 30px;'>$verification_code</b></p><br/><a href='http://localhost/verify-email.php?token=$verify_token'>Click to verify your email</a>";
            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        $this->db->query(
                "INSERT INTO users(email,password) VALUES(:email, :password )",
                [
                        'email'    => $formData['email'],
                        'password' => $password,
                ]
        );

        session_regenerate_id();

        $_SESSION['user'] = $this->db->id();
    }

    public function login(array $formData)
    {
        $user = $this->db->query(
                "SELECT * FROM users WHERE email = :email",
                [
                        'email' => $formData['email'],
                ]
        )->find();

        $passwordsMatch = password_verify(
                $formData['password'],
                $user['password'] ?? ''
        );

        if (!$user || !$passwordsMatch) {
            throw new ValidationException(['password' => ['Invalid credentials']]);
        }

        session_regenerate_id();

        $_SESSION['user'] = $user['id'];
    }

    public function logout()
    {
        unset($_SESSION['user']);

        session_regenerate_id();
    }
}
