<?php


require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../models/auth/user.php';

class ApostaController extends BaseController {
    public function index() {

        //extraindo as variaveis do array
        extract([
            'userData' => $this->userData,
            'walletBalance' => $this->walletBalance,
        ]);


        include_once __DIR__ . '/../../views/usuario/aposta.php';
    }
}
