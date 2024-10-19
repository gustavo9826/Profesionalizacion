<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Recolectar datos del formulario
$emails = filter_input(INPUT_POST, 'emails', FILTER_SANITIZE_STRING);
$subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

$emailsArray = explode(',', $emails);
$emailsArray = array_map('trim', $emailsArray);

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = 'smtp.office365.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'men.9812@hotmail.com';
    $mail->Password   = 'fxouyujqhiblqhys';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remitente
    $mail->setFrom('men.9812@hotmail.com', 'Gustavo Ortiz');

    // Añadir destinatarios
    foreach ($emailsArray as $email) {
        $mail->addAddress($email);
    }

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message;

    // Adjuntar archivos
    if (isset($_FILES['files'])) {
        foreach ($_FILES['files']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['files']['error'][$index] === UPLOAD_ERR_OK) {
                $mail->addAttachment($tmpName, $_FILES['files']['name'][$index]);
            }
        }
    }

    $mail->Charset = "UTF-8";
    $mail->send();
    echo '¡Correo enviado exitosamente!';
} catch (Exception $e) {
    echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
}
?>
