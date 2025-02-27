<?php
// config/database.php

// Carrega as variáveis diretamente do arquivo .env
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignora linhas de comentário
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        // Divide a linha no primeiro '=' encontrado
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $name = trim($parts[0]);
            $value = trim($parts[1]);
            // Remove aspas, se houver
            $value = trim($value, "\"'");
            // Define a variável de ambiente
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
} else {
    die("Arquivo .env não encontrado em: $envFile");
}

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    // Construtor com parâmetros opcionais
    public function __construct($host = null, $port = null, $dbname = null, $username = null, $password = null) {
        $this->host     = $host     ?? getenv('DB_HOST');
        $this->port     = $port     ?? getenv('DB_PORT');
        $this->dbname   = $dbname   ?? getenv('DB_DATABASE');
        $this->username = $username ?? getenv('DB_USERNAME');
        $this->password = $password ?? getenv('DB_PASSWORD');
    }

    public function connect() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname}";
                $this->pdo = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}

// Instancia a classe Database usando os valores do .env (por padrão)
$db = new Database();
$pdo = $db->connect();
