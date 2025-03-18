<?php

// controllers/DashboardController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/carteira/Carteira.php'; 
require_once __DIR__ . '/../../Controllers/usuario/BaseController.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class DashboardController extends BaseController{
    public function index() {
        $userData = $this->getUserData();
        $walletData = $this->getWalletData(); // Agora pega a carteira corretamente
        // Passe essas informações para a view
        include_once __DIR__ . '/../../views/usuario/dashboard.php';
    }
}

?>