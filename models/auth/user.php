<?php

class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    // Busca todos dados do usuario na tabela usuario
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        $stmt = $this->pdo->prepare("INSERT INTO carteira (chave_pix, user_id, tipo_de_chave) VALUES (:chave_pix, :user_id, :tipo_de_chave)");
        return $stmt->execute([
            'chave_pix'      => $chave,
            'user_id'    => $user_id,
            'tipo_de_chave'=> $tipodechave
        ]);
    }

    public function getWalletData($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM carteira WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna os dados da carteira
    }
    
    // Insere o token de verificação do e-mail
    public function insertUserToken($user_id, $token, $expira_em) {
        $stmt = $this->pdo->prepare("INSERT INTO usuario_tokens (user_id, token, expira_em) VALUES (:user_id, :token, :expira_em)");
        return $stmt->execute([
            'user_id'   => $user_id,
            'token'     => $token,
            'expira_em' => $expira_em
        ]);
    }
    

    // Deleta o token após a verifcação
    public function deleteUserToken($userId) {
        $stmt = $this->pdo->prepare("DELETE FROM usuario_tokens WHERE user_id = :user_id");
        return $stmt->execute(['user_id' => $userId]);
    }    
    
    // Atualiza o usuário para marcar o e-mail como verificado
    public function markEmailVerified($user_id) {
        $stmt = $this->pdo->prepare("UPDATE usuario SET email_verificado = 1 WHERE id = :id");
        return $stmt->execute(['id' => $user_id]);
    }

    // Busca um token de verificação para o usuário, se existir
    public function findUserToken($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario_tokens WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualiza o token de verificação para o usuário
    public function updateUserToken($user_id, $token, $expira_em) {
        $stmt = $this->pdo->prepare(
            "UPDATE usuario_tokens 
            SET token = :token, expira_em = :expira_em, criado_em = CURRENT_TIMESTAMP 
            WHERE user_id = :user_id"
        );
        return $stmt->execute([
            'token'      => $token,
            'expira_em'  => $expira_em,
            'user_id'    => $user_id
        ]);
    }


}
