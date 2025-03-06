<?php
// controllers/StaticPageController.php

class StaticPageController {
    /**
     * Exibe uma página estática com base no nome fornecido.
     * Exemplo: para "about", carrega a view em ../views/static/about.php
     *
     * @param string $page Nome da página
     */
    public function view($page) {
        $path = __DIR__ . '/../views/static/' . $page . '.php';
        if (file_exists($path)) {
            require_once $path;
        } 
    }
    
    // Se preferir métodos específicos para cada página:
    
    public function home() {
        require_once __DIR__ . '/../../views/static/home.php';
    }
    
    public function contact() {
        require_once __DIR__ . '/../../views/static/contact.php';
    }
}
