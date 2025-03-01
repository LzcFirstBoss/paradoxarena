<?php
// controllers/Auth/TwoFactorController.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class TwoFactorController {
    public function validate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Se o código foi enviado como array (um dígito por input), concatena os dígitos
            if (isset($_POST['codigo']) && is_array($_POST['codigo'])) {
                $inputCode = implode('', array_map('trim', $_POST['codigo']));
            } else {
                $inputCode = trim($_POST['codigo'] ?? '');
            }

            // Verifica se o campo foi preenchido
            if (empty($inputCode)) {
                $_SESSION['erros'] = ["Erro: O código de verificação é obrigatório."];
                header('Location: /paradoxarena/public/codigo');
                exit;
            }

            // Verifica se a sessão codigo existe
            if (!isset($_SESSION['codigo'])) {
                $_SESSION['erros'] = ["Erro: Sessão codigo expirou ou não foi iniciada."];
                header('Location: /paradoxarena/public/login');
                exit;
            }

            $twoFactorData = $_SESSION['codigo'];

            // Verifica se o código expirou
            $expiresAt = new DateTime($twoFactorData['expires_at']);
            $now = new DateTime();
            if ($now > $expiresAt) {
                $_SESSION['erros'] = ["Erro: O código de verificação expirou. Por favor, faça login novamente."];
                unset($_SESSION['codigo']);
                header('Location: /paradoxarena/public/login');
                exit;
            }

            // Compara o código digitado com o armazenado
            if ($inputCode !== $twoFactorData['code']) {
                $_SESSION['erros'] = ["Erro: Código de verificação incorreto."];
                header('Location: /paradoxarena/public/codigo');
                exit;
            }

            // Código verificado: finaliza o login e armazena o usuário na sessão
            $_SESSION['usuario'] = $twoFactorData['user'];
            unset($_SESSION['codigo']);

            echo "logado";
        } else {
            // Se não for POST, exibe o formulário de verificação codigo
            require_once __DIR__ . '/../../views/auth/codigo.php';
        }
    }
}
