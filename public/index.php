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

// Define as rotas da aplicação
$routes = [
    '/'         => 'HomeController@index',
    '/login'    => 'AuthController@login',
    '/cadastro' => 'AuthController@exibirCadastro',
    '/registrar'=> 'AuthController@cadastrarUsuario',
    // Outras rotas...
];

// Processamento das rotas
if (array_key_exists($requestUri, $routes)) {
    list($controllerName, $action) = explode('@', $routes[$requestUri]);
    $controllerPath = __DIR__ . '/../controllers/' . $controllerName . '.php';

    if (file_exists($controllerPath)) {
        require_once $controllerPath;
        if (class_exists($controllerName)) {
            $controller = new $controllerName();
            if (method_exists($controller, $action)) {
                call_user_func([$controller, $action]);
            } else {
                header("HTTP/1.0 404 Not Found");
                echo "Método '{$action}' não encontrado no controller '{$controllerName}'.";
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "Controller '{$controllerName}' não definido.";
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Arquivo do controller '{$controllerName}' não encontrado.";
    }
} else {
    header("HTTP/1.0 404 Not Found");
    require_once '../views/404.php';
}
