<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está autenticado.
 * Se não estiver, redireciona para a página de login.
 */
function requireAuth() {
    if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
        $_SESSION['erros'] = ["Você precisa estar logado para acessar essa página."];
        header('Location: /paradoxarena/public/login');
        exit;
    }
}
