<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, UserService};

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../PHPMailer/src/Exception.php';
require __DIR__ . '/../../PHPMailer/src/PHPMailer.php';
require __DIR__ . '/../../PHPMailer/src/SMTP.php';

class AuthController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserService $userService
    ) {
    }

    public function registerView()
    {
        echo $this->view->render("register.php");
    }

    public function register()
    {
        $this->validatorService->validateRegister($_POST);

        $this->userService->isEmailTaken($_POST['email']);
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
            $mail->Subject = 'Email verification';
            $mail->Body = '<p>Your verification code is: <b style="font-size: 30px;">' . $verification_code . '</b></p>';
            $mail->send();
            $this->userService->create($_POST);
            redirectTo('/');
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    public function loginView()
    {
        echo $this->view->render("login.php");
    }

    public function login()
    {
        $this->validatorService->validateLogin($_POST);

        $this->userService->login($_POST);

        redirectTo('/');
    }

    public function logout()
    {
        $this->userService->logout();

        redirectTo('/login');
    }
}
