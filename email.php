<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendEmail($to, $subject, $message)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';   // Gmail SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'edu.xploree@gmail.com';  // Your Gmail email address
    $mail->Password = 'npbw gsdm wkaf xkvq';       // App Password (NOT your Gmail password)
    $mail->SMTPSecure = 'tls';                         // Use TLS encryption
    $mail->Port = 587;                           // SMTP port for TLS

    // Recipients
    $mail->setFrom('edu.xploree@gmail.com');
    $mail->addAddress($to);

    // Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $message;

    // Send email
    $mail->send();
}



?>