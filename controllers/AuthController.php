<?php
// controllers/AuthController.php

// Inclua o model e demais dependências se necessário
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../services/EmailService.php';


class AuthController {

    // Exibe o formulário de cadastro (view)
    public function exibirCadastro() {
        require_once __DIR__ . '/../views/cadastro.php';
    }
    
    // Processa o cadastro de usuário (ação para requisição POST)
    public function cadastrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cadastro');
            exit;
        }
        
        // Função para validar CPF
        function validarCPF($cpf) {
            $cpf = preg_replace('/\D/', '', $cpf);
            if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
            for ($t = 9; $t < 11; $t++) {
                $soma = 0;
                for ($i = 0; $i < $t; $i++) {
                    $soma += $cpf[$i] * (($t + 1) - $i);
                }
                $resto = $soma % 11;
                $digitoVerificador = $resto < 2 ? 0 : 11 - $resto;
                if ($cpf[$t] != $digitoVerificador) {
                    return false;
                }
            }
            return true;
        }
        
        // Função para formatar data de nascimento
        function formatarDataNascimento($data) {
            $dateObj = DateTime::createFromFormat('Y-m-d', $data);
            return $dateObj ? $dateObj->format('d/m/Y') : "Data inválida";
        }
        
        // Captura e sanitiza os dados do formulário
        $nome_completo = trim($_POST['nome_completo']);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $nickname = trim($_POST['nickname']);
        $cpf = preg_replace('/\D/', '', $_POST['cpf']);
        $senha = $_POST['senha'];
        $confirmar_senha = $_POST['confirmar_senha'];
        $data_nascimento = $_POST['data_nascimento'];
        $dataFormatada = formatarDataNascimento($data_nascimento);
        $genero = $_POST['genero'];
        $chave = trim($_POST['chave']);
        
        // Verifica se todos os campos obrigatórios foram preenchidos
        if (!$nome_completo || !$email || !$nickname || !$cpf || !$senha || !$confirmar_senha || !$data_nascimento || !$genero || !$chave) {
            die("Erro: Todos os campos obrigatórios devem ser preenchidos.");
        }
        
        // Validação do CPF
        if (!validarCPF($cpf)) {
            die("Erro: CPF inválido.");
        }
        
        // Verificação de senha e confirmação
        if ($senha !== $confirmar_senha) {
            die("Erro: As senhas não coincidem.");
        }
        
        // Hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        
        // Verifica se o usuário tem 18 anos ou mais
        $hoje = new DateTime();
        $dataNascimento = new DateTime($data_nascimento);
        $idade = $hoje->diff($dataNascimento)->y;
        if ($idade < 18) {
            die("Erro: Você deve ter pelo menos 18 anos para se cadastrar.");
        }
        
        // Conecta ao banco de dados
        $pdo = (new Database())->connect();
        
        // Cria instância do model de usuário
        $userModel = new User($pdo);
        
        // Verifica se o e-mail ou CPF já estão cadastrados
        if ($userModel->findByEmail($email)) {
            die("Erro: Este e-mail já está cadastrado.");
        }
        if ($userModel->findByCPF($cpf)) {
            die("Erro: Este CPF já está cadastrado.");
        }
        
        // Dados para inserção
        $userData = [
            'nome_completo'   => $nome_completo,
            'email'           => $email,
            'nickname'        => $nickname,
            'cpf'             => $cpf,
            'senha'           => $senha_hash,
            'data_nascimento' => $dataFormatada,
            'genero'          => $genero
        ];
        
        // Insere o usuário no banco
        if (!$userModel->create($userData)) {
            die("Erro: Não foi possível cadastrar o usuário.");
        }
        
        // Pega o ID do usuário recém-inserido
        $user_id = $pdo->lastInsertId();
        
        // Insere a chave de pagamento (Pix) na tabela correspondente
        $stmt = $pdo->prepare("INSERT INTO chaves_de_pagamento (chave, user_id) VALUES (:chave, :user_id)");
        $stmt->execute([
            'chave'   => $chave,
            'user_id' => $user_id
        ]);
        
        // Gera um token único para verificação do e-mail
        $token = $cpf . bin2hex(random_bytes(32));
        $expira_em = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');
        $stmt = $pdo->prepare("INSERT INTO usuario_tokens (user_id, token, expira_em) VALUES (:user_id, :token, :expira_em)");
        $stmt->execute([
            'user_id'  => $user_id,
            'token'    => $token,
            'expira_em'=> $expira_em
        ]);
        
        // Envia o e-mail de verificação
        $emailService = new EmailService();
        $subject = "Verificação de email";
        $link = "https://1270-143-202-224-19.ngrok-free.app/apostasonline/controllers/auth/validar_email.php?token=" . $token;
        $body = '<!DOCTYPE html>
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
                <h2>Olá, '. $nome_completo .' </h2>
                <p>Bem-vindo(a) à Paradox Arena! Seu cadastro foi realizado com sucesso. Para começar a aproveitar todos os nossos recursos, é necessário ativar sua conta.</p>
                <div class="cta">
                    <a href="'.$link.'" >Ativar Minha Conta</a>
                </div>
                <p>Se você não solicitou este cadastro, por favor ignore este e-mail.</p>
            </div>
            <div class="footer">
                <p>© 2025 Paradox Arena. Todos os direitos reservados.</p>
            </div>
        </div>
    </body>
</html';
        if ($emailService->sendEmail($email, $subject, $body)) {
            echo "Email enviado com sucesso!";
        } else {
            echo "Erro ao enviar o email.";
}
    }
}
