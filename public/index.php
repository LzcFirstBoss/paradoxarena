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

function groupRoutes($prefix, $routes) {
    $grouped = [];
    foreach ($routes as $route => $action) {
        // Garante que o prefixo comece com "/" e não termine com "/"
        $prefix = '/' . trim($prefix, '/');
        $route  = '/' . ltrim($route, '/');
        $grouped[$prefix . $route] = $action;
    }
    return $grouped;
}

// Define as rotas da aplicação
$adminRoutes = groupRoutes('/admin', [
    '/dashboard' => 'Admin/DashboardController@index',
    '/users'     => 'Admin/UserController@index',
]);

// Suponha que suas rotas padrão já estejam definidas:
$routes = [
    '/'         => 'HomeController@index',
    '/login'    => 'Auth/LoginController@login',
    '/cadastro' => 'Auth/CadastroController@exibirCadastro',
    '/registrar'=> 'Auth/CadastroController@cadastrarUsuario',
];

// Mescla as rotas:
$routes = array_merge($routes, $adminRoutes);

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
