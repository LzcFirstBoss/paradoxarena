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
                    
                    // Remove o token utilizado do banco
                    $tokenModel->deleteToken($tokenData['id']);
                    
                    // Recupera os dados do usuário
                    $usuario = $userModel->findById($tokenData['user_id']);
                    if (!$usuario) {
                        $_SESSION['erros'] = ["Erro: Usuário não encontrado."];
                        header('Location: /paradoxarena/public/login');
                        exit;
                    }
                    
                    // Cria a sessão de login com os dados do usuário
                    $_SESSION['usuario'] = [
                        'id' => $usuario['id'],
                        'nome_completo' => $usuario['nome_completo'],
                        'email' => $usuario['email']
                    ];
                    
                    $_SESSION['sucesso'] = "Sucesso: Seu e-mail foi confirmado e você está logado!";
                    header('Location: /paradoxarena/public/dashboard');
                    exit;
                }
            } else {
                $_SESSION['erros'] = ["Erro: Token inválido."];
                header('Location: /paradoxarena/public/login');
                exit;
            }
        } else {
            $_SESSION['erros'] = ["Erro: Token não fornecido."];
            header('Location: /paradoxarena/public/login');
            exit;
        }
    }
}
