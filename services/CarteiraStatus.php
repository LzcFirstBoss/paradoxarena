<?php
require_once __DIR__ . '/../models/carteira/Carteira.php';

class CarteiraStatus {
    private $walletModel;

    public function __construct($pdo) {
        // Instancia o model da carteira
        $this->walletModel = new Wallet($pdo);
    }

    public function getBalance($userId) {
        return $this->walletModel->getBalance($userId);
    }
}
?>