<?php
// enviar_email_verificacao.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// do Composer phpmailer
require_once '../../composer/phpmailer/vendor/autoload.php';
require_once '../../config/env/env.php';

function enviarEmailVerificacao($email, $token, $usuarionome) {
    $mail = new PHPMailer(true);
    
    try {
        // Define a codificação para UTF-8
        $mail->CharSet = 'UTF-8';
        
        // Configuração do SMTP usando variáveis do .env
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        
        $mail->setFrom(getenv('SMTP_FROM'), 'Nome do Site');
        $mail->addAddress($email);
        
        $mail->isHTML(true);
        $mail->Subject = 'Confirmação de Cadastro';
        
        // Cria o link de verificação (ajuste o domínio conforme seu ambiente)
        $link = "https://seusite.com/verificar.php?token=" . $token;
        $mail->Body = '<!DOCTYPE html>
    <html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ativação de Conta</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f8f8;
                margin: 0;
                padding: 0;
                color: #333;
            }
            .email-container {
                max-width: 600px;
                margin: 40px auto;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                border: 1px solid #ddd;
            }
            .header {
                background-color: #0666e3;
                color: #fff;
                text-align: center;
                padding: 20px;
            }
            .header h1 {
                font-size: 24px;
                margin: 0;
            }
            .content {
                padding: 20px 30px;
            }
            .content h2 {
                color: #0666e3;
                font-size: 20px;
                margin-bottom: 10px;
            }
            .content p {
                line-height: 1.6;
                font-size: 16px;
                margin: 10px 0;
            }
            .activation-code {
                background-color: #f1f1f1;
                border: 1px dashed #0666e3;
                color: #333;
                font-weight: bold;
                font-size: 18px;
                text-align: center;
                padding: 15px;
                border-radius: 5px;
                margin: 20px 0;
            }
            .cta {
                text-align: center;
                margin: 20px 0;
            }
            .cta a {
                background-color: #0666e3;
                color: #fff;
                text-decoration: none;
                padding: 12px 20px;
                border-radius: 5px;
                font-size: 16px;
                font-weight: bold;
                display: inline-block;
            }
            .cta a:hover {
                background-color: #0550b0;
            }
            .footer {
                background-color: #f8f8f8;
                text-align: center;
                padding: 15px;
                font-size: 14px;
                color: #777;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                <h1>Paradox Arena - Ativação de Conta</h1>
            </div>
            <div class="content">
                <h2>Olá, '. $usuarionome .' </h2>
                <p>Bem-vindo(a) à Paradox Arena! Seu cadastro foi realizado com sucesso. Para começar a aproveitar todos os nossos recursos, é necessário ativar sua conta.</p>
                <div class="cta">
                    <a href="https://seusite.com/ativar_conta.php?token=<?php echo $activation_token; ?>">Ativar Minha Conta</a>
                </div>
                <p>Se você não solicitou este cadastro, por favor ignore este e-mail.</p>
            </div>
            <div class="footer">
                <p>© 2025 Paradox Arena. Todos os direitos reservados.</p>
            </div>
        </div>
    </body>
</html>
';
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Retorna a mensagem de erro para depuração
        return $e->getMessage();
    }
}
?>
