<?php

require_once __DIR__ . '/../../Controllers/usuario/BaseController.php';

class CarteiraController extends BaseController {  
    public function index() {
        // Obtém os dados do usuário e da carteira usando os métodos do BaseController
        $userData = $this->getUserData();
        $walletData = $this->getWalletData();

        // Inclui a view da carteira e passa os dados
        require_once __DIR__ . '/../../views/usuario/carteira.php';
    }
}
