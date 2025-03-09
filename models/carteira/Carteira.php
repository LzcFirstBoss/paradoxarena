<?php
class Wallet {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function getBalance($userId) {
        $stmt = $this->pdo->prepare("SELECT saldo FROM carteira WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? $data['saldo'] : 0;
    }
}
