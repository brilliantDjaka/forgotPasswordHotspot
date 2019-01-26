<?php
/**
 * Created by PhpStorm.
 * User: brian
 * Date: 25/01/19
 * Time: 13:05
 */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer-master/src/Exception.php';
require './PHPMailer-master/src/PHPMailer.php';
require './PHPMailer-master/src/SMTP.php';

$mail = new PHPMailer(true);
$mail->isSMTP();
$mail->SMTPDebug = 2;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "brilliant.djaka21@gmail.com";
$mail->Password = "brianrofiq123";
$mail->setFrom('brilliant.djaka21@gmail.com', 'First Last');
$mail->addAddress('brian.rofiq@gmail.com', 'John Doe');
$mail->Subject = 'PHPMailer GMail SMTP test';
$mail->msgHTML('<p>Tefa Jaya</p>');
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";

}