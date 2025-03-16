<?php
// public/index.php

// Carrega o autoload do Composer e o dotenv
require_once __DIR__ . '/../config/env.php';


// Defina o base path se sua aplicação não estiver na raiz do servidor
$basePath = '/paradoxarena/public';

// Captura a URL requisitada (sem query string)
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove o base path da URL, se existir
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// Se a URL ficar vazia, atribua '/'
if ($requestUri === '') {
    $requestUri = '/';
}

function groupRoutes($urlPrefix, $controllerPrefix, $routes) {
    $grouped = [];
    // Garante que o prefixo da URL comece com "/" e não tenha barra final
    $urlPrefix = '/' . trim($urlPrefix, '/');
    if ($urlPrefix === '/') {
        $urlPrefix = ''; // Se for apenas "/", deixa vazio para não duplicar barras
    }
    
    foreach ($routes as $route => $action) {
        // Garante que a rota comece com "/"
        $route = '/' . ltrim($route, '/');
        // Monta a rota completa e adiciona o prefixo do controller à ação
        $grouped[$urlPrefix . $route] = $controllerPrefix . $action;
    }
    return $grouped;
}

// Rotas para o módulo Auth: URL sem prefixo, mas controllers com prefixo "Auth/"
$authRoutes = groupRoutes('', 'Auth/', [
    '/login'         => 'LoginController@login',
    '/cadastro'      => 'CadastroController@exibirCadastro',
    '/registrar'     => 'CadastroController@cadastrarUsuario',
    '/validar-email' => 'ValidarEmailController@validate',
    '/codigo'        => 'TwoFactorController@validate',
    '/logout'        => 'LoginController@logout',
]);

$usuario = groupRoutes('', 'usuario/',[
    '/dashboard' => 'DashBoardController@index',
    '/apostados' => 'ApostaController@index',
]
);

//rotas para páginas staticas
$staticRoutes = groupRoutes('', 'StaticPages/', [
    '/' => 'StaticPageController@home',
    '/home' => 'StaticPageController@home'
]);


// Mescla todas as rotas
$routes = array_merge($authRoutes, $staticRoutes, $usuario);


// Processamento das rotas
if (array_key_exists($requestUri, $routes)) {
    list($controllerRoute, $action) = explode('@', $routes[$requestUri]);
    // Constrói o caminho completo do arquivo do controller
    $controllerPath = __DIR__ . '/../controllers/' . $controllerRoute . '.php';
    
    // Extrai apenas o nome da classe (basename), removendo diretórios
    $className = basename($controllerRoute);
    
    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        if (class_exists($className)) {
            $controller = new $className();
            if (method_exists($controller, $action)) {
                call_user_func([$controller, $action]);
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "Método '{$action}' não encontrado no controller '{$className}'.";
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "Controller '{$className}' não definido.";
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Arquivo do controller '{$controllerPath}' não encontrado.";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    require_once '../views/error/404.php';
}
