<?php
// Arquivo: registro.php
// Localização recomendada: em uma pasta de scripts privados (ex.: app/)

// Inclua a conexão com o banco e o carregamento das variáveis de ambiente
require_once '../../config/env/env.php';
require_once '../../config/public/database.php';
require_once 'emails.php'; // Função enviarEmailVerificacao()

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    $tipo_de_chave = trim($_POST['tipode_de_chave']);

    // Verifica se todos os campos obrigatórios foram preenchidos
    if (!$nome_completo || !$email || !$nickname || !$cpf || !$senha || !$confirmar_senha || !$data_nascimento || !$genero || !$chave || !$tipo_de_chave) {
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

    // Conectar ao banco de dados
    $pdo = (new Database())->connect();

    // Verifica se o e-mail já está cadastrado
    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        die("Erro: Este e-mail já está cadastrado.");
    }

    // Verifica se o CPF já está cadastrado
    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE cpf = :cpf");
    $stmt->execute(['cpf' => $cpf]);
    if ($stmt->fetch()) {
        die("Erro: Este CPF já está cadastrado.");
    }

    // Insere o usuário na tabela "usuario" com email_verificado padrão (0)
    $stmt = $pdo->prepare("INSERT INTO usuario (nome_completo, email, nickname, cpf, senha, data_nascimento, genero, email_verificado) 
                           VALUES (:nome_completo, :email, :nickname, :cpf, :senha, :data_nascimento, :genero, 0)");
    $stmt->execute([
        'nome_completo'   => $nome_completo,
        'email'           => $email,
        'nickname'        => $nickname,
        'cpf'             => $cpf,
        'senha'           => $senha_hash,
        'data_nascimento' => $dataFormatada,
        'genero'          => $genero
    ]);

    // Pega o ID do usuário recém-inserido
    $user_id = $pdo->lastInsertId();

    // Insere a chave de pagamento (Pix) na tabela correspondente
    $stmt = $pdo->prepare("INSERT INTO chaves_de_pagamento (chave, tipode_de_chave, user_id) 
                           VALUES (:chave, :tipode_de_chave, :user_id)");
    $stmt->execute([
        'chave'           => $chave,
        'tipode_de_chave' => $tipo_de_chave,
        'user_id'         => $user_id
    ]);

    // Gera um token único para verificação do e-mail
    $token = bin2hex(random_bytes(32));
    // Define o prazo de expiração para 10 minutos a partir de agora
    $expira_em = (new DateTime('+10 minutes'))->format('Y-m-d H:i:s');

    // Insere o token na tabela "usuario_tokens"
    $stmt = $pdo->prepare("INSERT INTO usuario_tokens (user_id, token, expira_em) VALUES (:user_id, :token, :expira_em)");
    $stmt->execute([
        'user_id'  => $user_id,
        'token'    => $token,
        'expira_em'=> $expira_em
    ]);

    // Envia o e-mail de verificação para o usuário
    if (enviarEmailVerificacao($email, $token, $nome_completo)) {
        echo "Cadastro realizado com sucesso! Verifique seu e-mail para confirmar seu cadastro.";
    } else {
        echo "Cadastro realizado, mas houve um erro ao enviar o e-mail de verificação.";
    }
}
?>
