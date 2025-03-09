<?php

// controllers/DashboardController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/carteira/Carteira.php'; // Suponha que você tenha um model para a carteira
require_once __DIR__ . '/../../models/auth/user.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class DashboardController {
    public function index() {
        // Conecta ao banco
        $pdo = (new Database())->connect();
        
        // Obtenha o usuário da sessão
        if (!isset($_SESSION['usuario'])) {
            header('Location: /paradoxarena/public/login');
            exit;
        }
        
        $userId = $_SESSION['usuario']['id'];
        
        // Instancia o model ou serviço que trata da carteira
        // Suponha que o model Wallet tenha um método getBalance($userId)
        require_once __DIR__ . '/../../models/carteira/carteira.php';
        $walletModel = new Wallet($pdo);
        $walletBalance = $walletModel->getBalance($userId);
        
        // Passe essas informações para a view
        include_once __DIR__ . '/../../views/usuario/dashboard.php';
    }
}

?>