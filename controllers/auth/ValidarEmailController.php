<?php
// controllers/Auth/ValidarEmailController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/auth/UsuarioToken.php';
require_once __DIR__ . '/../../models/auth/user.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


class ValidarEmailController {
    public function validate() {
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            
            // Cria instância do model de token
            $tokenModel = new UsuarioToken();
            $tokenData = $tokenModel->findByToken($token);
            
            if ($tokenData) {
                // Verifica se o token expirou
                if ($tokenData['expira_em'] < date('Y-m-d H:i:s')) {
                    $_SESSION['erros'] = ["Erro: Token expirado. Por favor, solicite um novo link de ativação."];
                    header('Location: /paradoxarena/public/login');
                    exit;
                } else {
                    // Conecta ao banco e instancia o model de usuário
                    $pdo = (new Database())->connect();
                    $userModel = new User($pdo);
                    
                    // Marca o e-mail como verificado
                    $userModel->markEmailVerified($tokenData['user_id']);
                    
                    // Remove o token utilizado
                    $tokenModel->deleteToken($tokenData['id']);
                    
                    $_SESSION['sucesso'] = "Sucesso: Seu e-mail foi confirmado com sucesso!.";
                    header('Location: /paradoxarena/public/login');
                    exit;
                }
            } else {
                $_SESSION['erros'] = ["Erro: token invalido"];
                header('Location: /paradoxarena/public/login');
                exit;
            }
        } else {
            $_SESSION['erros'] = ["Erro: Token não fornecido"];
            header('Location: /paradoxarena/public/login');
            exit;
        }
    }
}
