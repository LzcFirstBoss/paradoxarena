<?php
require '../../config/public/database.php'; // Conexão com o banco

function validarCPF($cpf) {
    $cpf = preg_replace('/\D/', '', $cpf); // Remove caracteres não numéricos

    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false; // Verifica se tem 11 dígitos e não são todos iguais
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

function formatarDataNascimento($data) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $data);
    return $dateObj ? $dateObj->format('d/m/Y') : "Data inválida";
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário e sanitiza
    $nome_completo = trim($_POST['nome_completo']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $nickname = trim($_POST['nickname']);
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Remove caracteres não numéricos
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];
    $data_nascimento = $_POST['data_nascimento'];
    $dataFormatada = formatarDataNascimento($data_nascimento);
    $genero = $_POST['genero'];
    $chave = trim($_POST['chave']);
    $tipo_de_chave = trim($_POST['tipode_de_chave']);

    //  Verificação de campos obrigatórios
    if (!$nome_completo || !$email || !$nickname || !$cpf || !$senha || !$confirmar_senha || !$data_nascimento || !$genero || !$chave || !$tipo_de_chave) {
        die("Erro: Todos os campos obrigatórios devem ser preenchidos.");
    }

    //  Verificação do CPF
    if (!validarCPF($cpf)) {
        die("Erro: CPF inválido.");
    }

    //  Verificação de senha e confirmação de senha
    if ($senha !== $confirmar_senha) {
        die("Erro: As senhas não coincidem.");
    }

    //  Hash da senha para segurança
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    //  Verifica se a idade do usuário é maior de 18 anos
    $hoje = new DateTime();
    $dataNascimento = new DateTime($data_nascimento);
    $idade = $hoje->diff($dataNascimento)->y;

    if ($idade < 18) {
        die("Erro: Você deve ter pelo menos 18 anos para se cadastrar.");
    }

    //  Conectar ao banco de dados
    $pdo = (new Database())->connect();

    //  Verificar se o e-mail já existe
    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetch()) {
        die("Erro: Este e-mail já está cadastrado.");
    }

    //  Verificar se o CPF já existe
    $stmt = $pdo->prepare("SELECT id FROM usuario WHERE cpf = :cpf");
    $stmt->execute(['cpf' => $cpf]);
    if ($stmt->fetch()) {
        die("Erro: Este CPF já está cadastrado.");
    }

    //  Inserir usuário no banco
    $stmt = $pdo->prepare("INSERT INTO usuario (nome_completo, email, nickname, cpf, senha, data_nascimento, genero) 
                           VALUES (:nome_completo, :email, :nickname, :cpf, :senha, :data_nascimento, :genero)");

    $stmt->execute([
        'nome_completo' => $nome_completo,
        'email' => $email,
        'nickname' => $nickname,
        'cpf' => $cpf,
        'senha' => $senha_hash,
        'data_nascimento' => $dataFormatada,
        'genero' => $genero
    ]);

    //  Pega o ID do usuário inserido para vincular a chave Pix
    $user_id = $pdo->lastInsertId();

    //  Inserir chave de pagamento no banco
    $stmt = $pdo->prepare("INSERT INTO chaves_de_pagamento (chave, tipode_de_chave, user_id) 
                           VALUES (:chave, :tipode_de_chave, :user_id)");

    $stmt->execute([
        'chave' => $chave,
        'tipode_de_chave' => $tipo_de_chave,
        'user_id' => $user_id
    ]);

    echo "Cadastro realizado com sucesso!";
}
?>
