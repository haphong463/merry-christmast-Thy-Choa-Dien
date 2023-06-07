<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once 'phpMailer/Exception.php';
require_once 'phpMailer/PHPMailer.php';
require_once 'phpMailer/SMTP.php';

$mail = new PHPMailer(true);

$postData = $statusMsg = $valErr = '';
$status = 'error';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Validate form fields 
    if (empty($name)) {
        $valErr .= 'Please enter your name.<br/>';
    }
    if (empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $valErr .= 'Please enter a valid email.<br/>';
    }
    if (empty($subject)) {
        $valErr .= 'Please enter subject.<br/>';
    }
    if (empty($message)) {
        $valErr .= 'Please enter your message.<br/>';
    }

    if (empty($valErr)) {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'haphong2134@gmail.com';
        $mail->Password = 'vvvjfqjecanwjrvn';
        $mail->SMTPSecure = "tls";
        $mail->Port = '587';

        $mail->setFrom($email);
        $mail->addAddress('haphong2134@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Message received from Contact: ' . $name;
        $mail->Body = "Name: $name <br>Email: $email <br>Subject: $subject <br>Message: $message";

        $mail->send();

        $status = 'success';
        $statusMsg = 'Thank you! Your contact request has submitted successfully, we will get back to you soon.';
        $postData = '';
    } else {
        $statusMsg = '<p>Please fill all the mandatory fields:</p>' . trim($valErr, '<br/>');
    }

    
}
