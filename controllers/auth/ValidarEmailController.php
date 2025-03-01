<?php
// controllers/Auth/ValidarEmailController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/auth/UsuarioToken.php';
require_once __DIR__ . '/../../models/auth/user.php';


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
                    echo "Token expirado. Por favor, solicite um novo link de ativação.";
                } else {
                    // Conecta ao banco e instancia o model de usuário
                    $pdo = (new Database())->connect();
                    $userModel = new User($pdo);
                    
                    // Marca o e-mail como verificado
                    $userModel->markEmailVerified($tokenData['user_id']);
                    
                    // Remove o token utilizado
                    $tokenModel->deleteToken($tokenData['id']);
                    
                    echo "Seu e-mail foi confirmado com sucesso! Agora você pode acessar sua conta.";
                }
            } else {
                echo "Token inválido.";
            }
        } else {
            echo "Token não fornecido.";
        }
    }
}
