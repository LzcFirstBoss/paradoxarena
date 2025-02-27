<?php

class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByCPF($cpf) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE cpf = :cpf");
        $stmt->execute(['cpf' => $cpf]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO usuario 
             (nome_completo, email, nickname, cpf, senha, data_nascimento, genero, email_verificado, termos) 
             VALUES (:nome_completo, :email, :nickname, :cpf, :senha, :data_nascimento, :genero, 0, :termos)"
        );
        return $stmt->execute($data);
    }
    
    // Insere a chave de pagamento (Pix)
    public function insertPaymentKey($user_id, $chave, $tipodechave) {
        $stmt = $this->pdo->prepare("INSERT INTO chaves_de_pagamento (chave, user_id, tipodechave) VALUES (:chave, :user_id, :tipodechave)");
        return $stmt->execute([
            'chave'   => $chave,
            'user_id' => $user_id,
            'tipodechave' => $tipodechave
        ]);
    }
    
    // Insere o token de verificação do e-mail
    public function insertUserToken($user_id, $token, $expira_em) {
        $stmt = $this->pdo->prepare("INSERT INTO usuario_tokens (user_id, token, expira_em) VALUES (:user_id, :token, :expira_em)");
        return $stmt->execute([
            'user_id'  => $user_id,
            'token'    => $token,
            'expira_em'=> $expira_em
        ]);
    }
}
