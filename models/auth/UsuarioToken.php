<?php
// models/auth/UsuarioToken.php

require_once __DIR__ . '/../../config/database.php';

class UsuarioToken {
    private $pdo;
    
    public function __construct() {
        $this->pdo = (new Database())->connect();
    }
    
    public function findByToken($token) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario_tokens WHERE token = :token");
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function deleteToken($id) {
        $stmt = $this->pdo->prepare("DELETE FROM usuario_tokens WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
}
