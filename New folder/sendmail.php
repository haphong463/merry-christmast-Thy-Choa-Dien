<?php

require_once 'phpMailer/Exception.php';
require_once 'phpMailer/PHPMailer.php';
require_once 'phpMailer/SMTP.php';
require_once 'phpMailer/POP3.php';
require_once 'phpMailer/OAuth.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mailer
{
    public function dathangmail($title, $content, $email)
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'haphong2134@gmail.com';
            $mail->Password = 'vvvjfqjecanwjrvn';
            $mail->SMTPSecure = "tls";
            $mail->Port = '587';
            $mail->setFrom('haphong2134@gmail.com', 'Mailer');
            $mail->addAddress($email, 'Hà Phong');
            $mail->addCC('haphong2134@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = $title;
            $mail->Body = $content;

            $mail->send();
        } catch (Exception $e) {
            echo 'Không thể gửi thư. Lỗi Mailer: ' . $mail->ErrorInfo;
        }
    }
}
