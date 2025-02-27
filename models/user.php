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
             (nome_completo, email, nickname, cpf, senha, data_nascimento, genero, email_verificado) 
             VALUES (:nome_completo, :email, :nickname, :cpf, :senha, :data_nascimento, :genero, 0)"
        );
        return $stmt->execute($data);
    }
}
