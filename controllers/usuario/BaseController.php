<?php
// controllers/BaseController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../services/CarteiraStatus.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class BaseController {
    protected $pdo;
    protected $userData;
    protected $walletBalance;

    public function __construct() {
        // Verifica se o usuário está autenticado
        if (!isset($_SESSION['usuario'])) {
            header('Location: /paradoxarena/public/login');
            exit;
        }

        // Armazena os dados do usuário (por exemplo, id, nome, email)
        $this->userData = $_SESSION['usuario'];

        // Cria a conexão com o banco de dados
        $this->pdo = (new Database())->connect();

        // Instancia o serviço da carteira e carrega o saldo
        require_once __DIR__ . '/../../services/CarteiraStatus.php';
        $CarteiraStatus = new CarteiraStatus($this->pdo);
        $this->walletBalance = $CarteiraStatus->getBalance($this->userData['id']);
    }
}
