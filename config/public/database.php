<?php
require_once '../../config/env/env.php';

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    public function __construct() {
        // As variáveis de ambiente já foram carregadas pelo config/env.php
        $this->host = getenv('DB_HOST');
        $this->port = getenv('DB_PORT');
        $this->dbname = getenv('DB_DATABASE');
        $this->username = getenv('DB_USERNAME');
        $this->password = getenv('DB_PASSWORD');
    }

    public function connect() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname}";
                $this->pdo = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("Erro ao conectar ao banco de dados: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}

// Testando a conexão
$db = new Database();
$pdo = $db->connect();
