<?php
// controllers/Auth/CadastroController.php

// Inclua o model e demais dependências
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/auth/user.php';
require_once __DIR__ . '/../../services/EmailService.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class CadastroController {
    // Exibe o formulário de cadastro (view)
    public function exibirCadastro() {
        require_once __DIR__ . '/../../views/auth/cadastro.php';
    }
    
    // Processa o cadastro de usuário (ação para requisição POST)
    public function cadastrarUsuario() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /paradoxarena/public/cadastro');
            exit;
        }
        
        // Função para validar CPF (você pode mantê-la aqui ou mover para um helper)
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
            $dateObj = \DateTime::createFromFormat('Y-m-d', $data);
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
        $termos = isset($_POST['termos']) ? 1 : 0;
        $tipodechave = trim($_POST['tipodechave']);

        
        // Array para acumular erros
        $erros = [];
        
        // Verifica se todos os campos obrigatórios foram preenchidos
        if (!$nome_completo || !$email || !$nickname || !$cpf || !$senha || !$confirmar_senha || !$data_nascimento || !$genero || !$chave || !$termos) {
            $erros[] = "Erro: Todos os campos obrigatórios devem ser preenchidos.";
        }
        
        // Validação do CPF
        if (!validarCPF($cpf)) {
            $erros[] = "Erro: CPF inválido.";
        }
        
        // Verificação de senha e confirmação
        if ($senha !== $confirmar_senha) {
            $erros[] = "Erro: As senhas não coincidem.";
        }
        
        // Verifica se o usuário tem 18 anos ou mais
        $hoje = new \DateTime();
        $dataNascimentoObj = new \DateTime($data_nascimento);
        $idade = $hoje->diff($dataNascimentoObj)->y;
        if ($idade < 18) {
            $erros[] = "Erro: Você deve ter pelo menos 18 anos para se cadastrar.";
        }
        
        // Se houver erros, armazene-os na sessão e redirecione de volta para o formulário
        if (!empty($erros)) {
            $_SESSION['erros'] = $erros;
            header('Location: /paradoxarena/public/cadastro');
            exit;
        }
        
        // Conecta ao banco de dados
        $pdo = (new \Database())->connect();
        
        // Cria instância do model de usuário
        $userModel = new \User($pdo);
        
        // Verifica se o e-mail ou CPF já estão cadastrados
        if ($userModel->findByEmail($email)) {
            $_SESSION['erros'] = ["Erro: Este e-mail já está cadastrado."];
            header('Location: /paradoxarena/public/cadastro');

            exit;
        }
        if ($userModel->findByCPF($cpf)) {
            $_SESSION['erros'] = ["Erros: Este CPF já está cadastrado."];
            header('Location: /paradoxarena/public/cadastro');

            exit;
        }
        
        // Dados para inserção
        $userData = [
            'nome_completo'   => $nome_completo,
            'email'           => $email,
            'nickname'        => $nickname,
            'cpf'             => $cpf,
            'senha'           => password_hash($senha, PASSWORD_DEFAULT),
            'data_nascimento' => $dataFormatada,
            'genero'          => $genero,
            'termos'          => $termos
        ];
        
        // Insere o usuário no banco
        if (!$userModel->create($userData)) {
            $_SESSION['erros'] = ["Erro: Não foi possível cadastrar o usuário."];
            header('Location: /paradoxarena/public/cadastro');

            exit;
        }
        
        // Pega o ID do usuário recém-inserido
        $user_id = $pdo->lastInsertId();
        
        // Insere a chave de pagamento usando o model
        if (!$userModel->insertPaymentKey($user_id, $chave, $tipodechave)) {
            $_SESSION['erros'] = ["Erro: Erro ao inserir a chave de pagamento."];
            header('Location: /paradoxarena/public/cadastro');

            exit;
        }
        
        // Gera um token único para verificação do e-mail
        $token = $cpf . bin2hex(random_bytes(32));
        $expira_em = (new \DateTime('+10 minutes'))->format('Y-m-d H:i:s');
        
        // Insere o token usando o model
        if (!$userModel->insertUserToken($user_id, $token, $expira_em)) {
            $_SESSION['erros'] = ["Erro: Erro ao inserir o token."];
            header('Location: /paradoxarena/public/cadastro');
            exit;
        }
        
        // Envia o e-mail de verificação usando o serviço de email
        $emailService = new \EmailService();
        $subject = "Verificação de email";
        $link = "https://seu-dominio.com/controllers/auth/validar_email.php?token=" . $token;
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
            // Mensagem de sucesso (você pode também armazenar na sessão e redirecionar para uma página de sucesso)
            $_SESSION['sucesso'] = "Sucesso: Cadastro realizado com sucesso! Verifique seu email para ativar sua conta!.";
            header('Location: /paradoxarena/public/cadastro');
            exit;
        } else {
            $_SESSION['erros'] = ["Erro: Erro ao enviar o email."];
            header('Location: /paradoxarena/public/cadastro');
            exit;
        }
    }
}