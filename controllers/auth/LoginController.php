<?php
// controllers/Auth/LoginController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/auth/user.php';
require_once __DIR__ . '/../../services/EmailService.php';
require_once __DIR__ . '/../../services/TokenService.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

date_default_timezone_set('America/Sao_Paulo');

class LoginController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura e sanitiza os dados do formulário
            $cpf = isset($_POST['cpf']) ? preg_replace('/\D/', '', $_POST['cpf']) : '';
            $senha = isset($_POST['senha']) ? $_POST['senha'] : '';
            
            $erros = [];
            if (!$cpf || !$senha) {
                $erros[] = "Erro: CPF e senha são obrigatórios.";
            }
            
            if (!empty($erros)) {
                $_SESSION['erros'] = $erros;
                header('Location: /paradoxarena/public/login');
                exit;
            }
            
            // Conecta ao banco de dados e instancia o model
            $pdo = (new Database())->connect();
            $userModel = new User($pdo);
            
            // Busca o usuário pelo CPF
            $usuario = $userModel->findByCPF($cpf);
            if (!$usuario) {
                $_SESSION['erros'] = ["Erro: CPF não cadastrado."];
                header('Location: /paradoxarena/public/login');
                exit;
            }
            
            // Verifica a senha informada com a armazenada
            if (!password_verify($senha, $usuario['senha'])) {
                $_SESSION['erros'] = ["Erro: Senha incorreta."];
                header('Location: /paradoxarena/public/login');
                exit;
            }
            
            // Verifica se o e-mail foi validado
            if (!$usuario['email_verificado']) {
                $_SESSION['erros'] = ["Erro: E-mail não verificado. Por favor, verifique seu e-mail."];
                header('Location: /paradoxarena/public/login');
                exit;
            }
            
            // Gera um código de verificação de 6 dígitos para codigo
            $tokenService = new TokenService();
            $verificationCode = $tokenService->generateVerificationCode(); // Ex: 6 dígitos
            $expiresAt = (new DateTime('+10 minutes', new DateTimeZone('America/Sao_Paulo')))->format('Y-m-d H:i:s');
            
            // Verifica se já existe um código cadastrado para o usuário
            $existingToken = $userModel->findUserToken($usuario['id']);
            if ($existingToken) {
                $result = $userModel->updateUserToken($usuario['id'], $verificationCode, $expiresAt);
            } else {
                $result = $userModel->insertUserToken($usuario['id'], $verificationCode, $expiresAt);
            }
            
            if (!$result) {
                $_SESSION['erros'] = ["Erro: Não foi possível armazenar o código de verificação."];
                header('Location: /paradoxarena/public/login');
                exit;
            }
            
            // Envia o código de verificação por e-mail
            $emailService = new EmailService();
            $subject = "Código de Verificação - codigo";
            $body = '<!doctype html><html lang=en><head><meta charset=UTF-8><meta name=viewport content="width=device-width,initial-scale=1"><title>Código de Rastreio</title><style>body{font-family:Arial,sans-serif;background-color:#f8f8f8;margin:0;padding:0;color:#333}.email-container{max-width:600px;margin:40px auto;background-color:#fff;border-radius:8px;box-shadow:0 4px 6px rgba(0,0,0,.1);overflow:hidden;border:1px solid #ddd}.header{background-color:#7ed957;color:#fff;text-align:center;padding:20px}.header h1{font-size:24px;margin:0}.content{padding:20px 30px}.content h2{color:#247203;font-size:20px;margin-bottom:10px}.content p{line-height:1.6;font-size:16px;margin:10px 0}.tracking-code{background-color:#f1f1f1;border:2px dashed #7ed957;color:#333;font-weight:700;font-size:18px;text-align:center;padding:15px;border-radius:5px;margin:20px 0}.cta{text-align:center;margin:20px 0}.cta a{background-color:#0666e3;color:#fff;text-decoration:none;padding:12px 20px;border-radius:5px;font-size:16px;font-weight:700;display:inline-block}.cta a:hover{background-color:#0666e3}.footer{background-color:#f8f8f8;text-align:center;padding:15px;font-size:14px;color:#777}</style></head><body><div class=email-container><div class=header><h1>Paradox Arena - Código de login</h1></div><div class=content><h2>Código de rastreio</h2><p>Você solicitou o acesso à sua conta.</p><div class=tracking-code>' . $verificationCode .'</div><p>Este código é válido por 10 minutos. Caso não tenha sido você, por favor, ignore este e-mail.</p><p>Se precisar de ajuda, entre em contato conosco através do nosso suporte.</p></div><div class=footer><p>© 2025 Paradox Arena. Todos os direitos reservados.</p></div></div></body></html>' ;
            
            //if (!$emailService->sendEmail($usuario['email'], $subject, $body)) {
                //$_SESSION['erros'] = ["Erro: Não foi possível enviar o código de verificação."];
                //header('Location: /paradoxarena/public/login');
                //exit;
           // }else
            
            // Armazena temporariamente os dados do usuário e o código na sessão para verificação futura
            $_SESSION['codigo'] = [
                'code'       => $verificationCode,
                'expires_at' => $expiresAt,
                'user'       => [
                    'id'            => $usuario['id'],
                    'nome_completo' => $usuario['nome_completo'],
                    'email'         => $usuario['email'],
                ]
            ];
            
            header('Location: /paradoxarena/public/codigo');
            exit;
            
        } else {
            // Se não for POST, exibe o formulário de login
            require_once __DIR__ . '/../../views/auth/login.php';
        }
    }

    public function logout() {
     
        unset($_SESSION['usuario']);
        unset($_SESSION['codigo']);
        session_destroy();
        header('Location: __DIR__ . /../../login');
        exit;
    }

}
