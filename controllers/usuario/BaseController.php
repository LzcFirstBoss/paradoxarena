<?php
// controllers/BaseController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/CarteiraStatus.php';
require_once __DIR__ . '/../../models/auth/User.php'; // Model de usuário

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class BaseController {
    protected $pdo;
    protected $userData;
    protected $walletData;

    public function __construct() {
        // Verifica se o usuário está autenticado
        if (!isset($_SESSION['usuario'])) {
            header('Location: /paradoxarena/public/login');
            exit;
        }

        // Conexão com o banco de dados
        $this->pdo = (new Database())->connect();

        // Instancia o model User
        $userModel = new User($this->pdo);

        // Obtém os dados do usuário e da carteira
        $this->userData = $userModel->findById($_SESSION['usuario']['id']);
        $this->walletData = $userModel->getWalletData($_SESSION['usuario']['id']); // Buscando os dados da carteira
    }

    // Método para retornar os dados do usuário autenticado
    public function getUserData() {
        return $this->userData;
    }

    // Método para retornar os dados da carteira do usuário autenticado
    public function getWalletData() {
        return $this->walletData;
    }
}
