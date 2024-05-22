<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    require "./phpmailer/vendor/autoload.php";

    $mail = new PHPMailer(true);

    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = "smtp-mail.outlook.com";
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->Username = "supp0rt.queuingsys@outlook.com";
    $mail->Password = "vtlbsohbpysrsusd";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
?>