<?php
// enviar_email_verificacao.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// do Composer phpmailer
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/env.php';



class EmailService {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        // Configuração do PHPMailer
        $this->mailer->isSMTP();
        $this->mailer->Host       = getenv('SMTP_HOST');
        $this->mailer->SMTPAuth   = true;
        $this->mailer->CharSet    = "UTF-8";
        $this->mailer->Username   = getenv('SMTP_USER');
        $this->mailer->Password   = getenv('SMTP_PASS');
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mailer->Port       = getenv('SMTP_PORT');
        $this->mailer->setFrom(getenv('SMTP_FROM'));
    }

    public function sendEmail($to, $subject, $body, $altBody = '') {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body    = $body;
            $this->mailer->AltBody = $altBody;
            $this->mailer->isHTML(true);
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            error_log("Erro no envio de email: " . $this->mailer->ErrorInfo);
            return false;
        }
    }
}

?>
