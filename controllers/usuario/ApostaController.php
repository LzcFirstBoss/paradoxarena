<?php


require_once __DIR__ . '/BaseController.php';

class ApostaController extends BaseController {
    public function index() {

        $userData = $this->getUserData();
        $walletData = $this->getWalletData(); // Agora pega a carteira corretamente

        include_once __DIR__ . '/../../views/usuario/aposta.php';
    }
}
