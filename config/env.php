<?php
/**
 * Arquivo: env.php
 * Descrição: Carrega as variáveis definidas no arquivo .env.
 *
 * Localização recomendada: Fora do diretório público (ex.: /config/env.php)
 * O arquivo .env deve estar na raiz do projeto, fora do diretório público.
 *
 * Exemplo de uso em um script:
 *   require_once __DIR__ . '/../config/env.php';
 *   echo getenv('NOME_DA_VARIAVEL');
 */

function carregarEnv($caminhoEnv) {
    if (!file_exists($caminhoEnv)) {
        throw new Exception("Arquivo .env não encontrado: " . $caminhoEnv);
    }

    // Lê o arquivo .env ignorando linhas vazias e comentários
    $linhas = file($caminhoEnv, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        // Ignorar linhas que começam com '#' (comentários)
        if (strpos(trim($linha), '#') === 0) {
            continue;
        }

        // Divide a linha no primeiro '=' encontrado
        $parts = explode('=', $linha, 2);
        if (count($parts) === 2) {
            $nome = trim($parts[0]);
            $valor = trim($parts[1]);

            // Remove aspas simples ou duplas ao redor do valor, se houver
            $valor = trim($valor, "\"'");

            // Define a variável de ambiente se ela ainda não estiver definida
            if (!array_key_exists($nome, $_ENV)) {
                putenv("$nome=$valor");
                $_ENV[$nome] = $valor;
                $_SERVER[$nome] = $valor;
            }
        }
    }
}

// Defina o caminho para o arquivo .env
// Exemplo: se este arquivo estiver em /config/env.php e o .env na raiz do projeto
$envFilePath = __DIR__ . '/../.env';

carregarEnv($envFilePath);
?>
